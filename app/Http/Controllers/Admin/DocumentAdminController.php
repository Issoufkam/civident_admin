<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Commune;
use App\Enums\DocumentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DocumentAdminController extends Controller
{
    // Constantes des répertoires, tous sont des sous-dossiers de 'storage/app/public'
    private const JUSTIFICATIF_DIR = 'justificatifs';
    private const DOCUMENTS_DIR = 'documents';
    private const SIGNATURES_DIR = 'signatures';
    private const TIMBRES_DIR = 'timbres';
    private const ATTACHMENTS_DIR = 'attachments'; // Nouvelle constante pour le répertoire des pièces jointes

    /**
     * Get document counts by status for the authenticated agent's commune.
     *
     * @return array
     */
    private function getDocumentCounts(): array
    {
        $agent = auth()->user();
        $communeId = $agent->commune_id;

        if (!$communeId) {
            return [
                'attente' => 0,
                'approuve' => 0,
                'rejete' => 0,
            ];
        }

        return [
            'attente' => Document::where('commune_id', $communeId)
                               ->where('status', DocumentStatus::EN_ATTENTE->value)
                               ->count(),
            'approuve' => Document::where('commune_id', $communeId)
                                ->where('status', DocumentStatus::APPROUVEE->value)
                                ->count(),
            'rejete' => Document::where('commune_id', $communeId)
                               ->where('status', DocumentStatus::REJETEE->value)
                               ->count(),
        ];
    }

    public function index(Request $request)
    {
        $agent = auth()->user();
        $communeId = $agent->commune_id;
        abort_unless($communeId, 403, "Aucune commune associée à votre profil.");

        $query = Document::with(['user', 'commune', 'agent'])
            ->where('commune_id', $communeId)
            ->latest();

        // Filtre par statut
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'duplicata') {
                $query->where('is_duplicata', true);
            } else {
                $statusValue = match(strtolower($request->status)) {
                    'approuvee' => DocumentStatus::APPROUVEE->value,
                    'rejete' => DocumentStatus::REJETEE->value,
                    'en_attente' => DocumentStatus::EN_ATTENTE->value,
                    default => null,
                };
                if ($statusValue) {
                    $query->where('status', $statusValue);
                }
            }
        }

        // Recherche par numéro de registre
        if ($request->filled('search')) {
            $query->where('registry_number', 'like', '%' . $request->search . '%');
        }

        $documents = $query->paginate(25);
        $communes = Commune::where('id', $communeId)->get();

        // Get document counts
        $documentCounts = $this->getDocumentCounts();

        return view('agent.documents.index', compact('documents', 'communes', 'documentCounts'));
    }

    public function create()
    {
        return view('agent.documents.create');
    }

    public function showDocument(Document $document)
    {
        // Charge la relation 'attachments' pour qu'elle soit disponible dans la vue
        $document->load('attachments');
        $this->authorizeDocument($document);
        return view('agent.documents.show', compact('document'));
    }

    public function generatePdf(Document $document)
    {
        $this->authorizeDocument($document);

        try {
            $pdf = Pdf::loadView($this->getDocumentView($document), [
                'document' => $document,
                'signature' => $this->getAgentSignaturePath(),
                'timbre' => $this->getTimbrePath(),
            ]);

            return $pdf->download("acte-{$document->registry_number}.pdf");
        } catch (\Exception $e) {
            Log::error("Erreur génération PDF: " . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération du PDF.');
        }
    }

    public function store(Request $request)
    {
        // La validation doit inclure les pièces jointes si elles sont envoyées avec le formulaire
        $validated = $this->validateDocumentRequest($request);

        try {
            // Préparation des données principales du document (y compris justificatif_path)
            $documentData = $this->prepareDocumentData($validated, $request);
            $document = Document::create($documentData); // Crée le document principal

            // --- NOUVELLE LOGIQUE POUR LES ATTACHMENTS SUPPLÉMENTAIRES ---
            // Vérifie s'il y a des fichiers dans le champ 'attachments_files'
            if ($request->hasFile('attachments_files')) {
                foreach ($request->file('attachments_files') as $file) {
                    // Vérifie si chaque fichier est valide avant de le stocker
                    if ($file->isValid()) {
                        // Stocke le fichier dans storage/app/public/attachments
                        $attachmentPath = $file->store(self::ATTACHMENTS_DIR, 'public');

                        // Crée une entrée dans la table 'attachments' liée à ce document
                        $document->attachments()->create([
                            'name' => $file->getClientOriginalName(),
                            'path' => $attachmentPath,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(), // Enregistre la taille en octets
                        ]);
                    } else {
                        Log::warning("Fichier non valide tenté d'être uploadé pour document " . $document->id);
                    }
                }
            }
            // --- FIN NOUVELLE LOGIQUE ---

            return redirect()->route('agent.documents.index')->with('success', 'Document et pièces jointes créés avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur création document/attachments: " . $e->getMessage());
            return back()->withInput()->with('error', 'Erreur lors de la création du document et des pièces jointes.');
        }
    }

    public function rejectDocument(Request $request, Document $document)
    {
        $this->authorizeDocument($document);

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->updateDocumentStatus($document, DocumentStatus::REJETEE, $validated['reason']);

        return redirect()->route('agent.documents.index')->with('success', 'Demande rejetée avec succès.');
    }

    public function createDuplicata($id)
    {
        $original = Document::findOrFail($id);

        if ($original->is_duplicata) {
            return back()->with('error', 'Impossible de faire un duplicata d’un duplicata.');
        }

        $duplicata = $original->replicate();
        $duplicata->is_duplicata = true;
        $duplicata->original_document_id = $original->id;
        $duplicata->registry_number = $original->registry_number . '-DUP-' . Str::random(3);
        $duplicata->status = DocumentStatus::EN_ATTENTE;
        $duplicata->pdf_path = null;
        $duplicata->traitement_date = null;
        $duplicata->agent_id = null;
        $duplicata->created_at = now();
        $duplicata->updated_at = now();
        $duplicata->save();

        return redirect()->route('agent.documents.show', $duplicata)->with('success', 'Duplicata créé avec succès.');
    }

    private function validateDocumentRequest(Request $request): array
    {
        $rules = [
            'type' => ['required', Rule::in(['naissance', 'mariage', 'deces'])],
            'registry_page' => 'nullable|integer',
            'registry_volume' => 'nullable|string|max:255',
            'justificatif' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'traitement_date' => 'required|date',
            // Règle pour les pièces jointes supplémentaires (facultatif, car pas toujours présent)
            'attachments_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120', // Max 5MB par fichier
        ];

        return $request->validate(array_merge($rules, $this->getTypeSpecificValidationRules($request->type)));
    }

    private function getTypeSpecificValidationRules(string $type): array
    {
        return match ($type) {
            'naissance' => [
                'nom_enfant' => 'required|string|max:25',
                'prenom_enfant' => 'required|string|max:50',
                'date_naissance' => 'required|date',
                'sexe' => 'required|string|max:8',
                'lieu_naissance' => 'required|string|max:50',
                'nationalite_pere' => 'required|string|max:50',
                'nationalite_mere' => 'required|string|max:50',
                'nom_pere' => 'required|string|max:255',
                'nom_mere' => 'required|string|max:255',
            ],
            'mariage' => [
                'nom_epoux' => 'required|string|max:255',
                'nom_epouse' => 'required|string|max:255',
                'nationalite_epoux' => 'required|string|max:50',
                'nationalite_epouse' => 'required|string|max:50',
                'date_mariage' => 'required|date',
                'lieu_mariage' => 'required|string|max:255',
            ],
            'deces' => [
                'nom_defunt' => 'required|string|max:25',
                'prenom_defunt' => 'required|string|max:50',
                'date_deces' => 'required|date',
                'lieu_deces' => 'required|string|max:255',
            ],
            default => [],
        };
    }

    private function prepareDocumentData(array $data, Request $request): array
    {
        $data['user_id'] = Auth::id();
        $data['commune_id'] = Auth::user()->commune_id;
        $data['registry_number'] = $this->generateRegistryNumber($data['type']);
        // Stocke le justificatif principal dans storage/app/public/justificatifs
        $data['justificatif_path'] = $request->file('justificatif')->store(self::JUSTIFICATIF_DIR, 'public');
        $data['metadata'] = $this->extractMetadata($data['type'], $request->all());
        $data['status'] = DocumentStatus::EN_ATTENTE;
        return $data;
    }

    private function generateRegistryNumber(string $type): string
    {
        return strtoupper(substr($type, 0, 3)) . '-' . now()->format('Ymd-His') . '-' . Str::random(4);
    }

    private function extractMetadata(string $type, array $requestData): array
    {
        return match ($type) {
            'naissance' => array_intersect_key($requestData, array_flip([
                'nom_enfant', 'prenom_enfant', 'date_naissance', 'sexe', 'lieu_naissance', 'nom_pere', 'nom_mere'
            ])),
            'mariage' => array_intersect_key($requestData, array_flip([
                'nom_epoux', 'nom_epouse', 'date_mariage', 'lieu_mariage', 'nationalite_epoux', 'nationalite_epouse'
            ])),
            'deces' => array_intersect_key($requestData, array_flip([
                'nom_defunt', 'prenom_defunt', 'date_deces', 'lieu_deces'
            ])),
            default => [],
        };
    }

    private function updateDocumentStatus(Document $document, DocumentStatus $status, ?string $comments = null): void
    {
        $document->update([
            'status' => $status,
            'agent_id' => auth()->id(),
            'traitement_date' => now(),
            'comments' => $comments,
        ]);
        // Si le document est approuvé, on enregistre la date de traitement
        if ($status === DocumentStatus::APPROUVEE) {
            $document->update(['traitement_date' => Carbon::now()->toDateString()]); // Définit la date actuelle au format 'AAAA-MM-JJ'
        }
    }

    private function generateDocumentPdf(Document $document): string
    {
        $pdf = Pdf::loadView($this->getDocumentView($document), [
            'document' => $document,
            'signature' => $this->getAgentSignaturePath(),
            'timbre' => $this->getTimbrePath(),
        ]);

        $filename = 'acte-' . $document->registry_number . '.pdf';
        // Stocke le PDF généré dans storage/app/public/documents
        Storage::disk('public')->put(self::DOCUMENTS_DIR . '/' . $filename, $pdf->output());

        // Retourne le chemin relatif au disque 'public' (ex: 'documents/acte-xyz.pdf')
        return self::DOCUMENTS_DIR . '/' . $filename;
    }

    private function getDocumentView(Document $document): string
    {
        return match ($document->type->value ?? $document->type) {
            'naissance' => 'certificats.naissance',
            'mariage' => 'certificats.mariage',
            'deces' => 'certificats.deces',
            default => throw new \InvalidArgumentException('Type de document inconnu'),
        };
    }

    private function getAgentSignaturePath(): ?string
    {
        // Cherche la signature dans storage/app/public/signatures
        $path = self::SIGNATURES_DIR . '/' . auth()->id() . '.png';
        return Storage::disk('public')->exists($path) ? Storage::disk('public')->url($path) : null;
    }

    private function getTimbrePath(): ?string
    {
        // Cherche le timbre dans storage/app/public/timbres
        $path = self::TIMBRES_DIR . '/timbre.png';
        return Storage::disk('public')->exists($path) ? Storage::disk('public')->url($path) : null;
    }

    private function authorizeDocument(Document $document): void
    {
        if ($document->commune_id !== auth()->user()->commune_id) {
            abort(403, 'Vous n\'avez pas accès à ce document.');
        }
    }

    public function update(Request $request, Document $document)
    {
        $this->authorizeDocument($document);

        // La validation doit inclure les pièces jointes si elles sont envoyées avec le formulaire
        $validated = $this->validateDocumentRequest($request);

        try {
            $validated['metadata'] = $this->extractMetadata($validated['type'], $request->all());

            // Gère l'update du justificatif principal
            if ($request->hasFile('justificatif')) {
                // Supprime l'ancien justificatif de storage/app/public/justificatifs si existant
                if ($document->justificatif_path && Storage::disk('public')->exists($document->justificatif_path)) {
                    Storage::disk('public')->delete($document->justificatif_path);
                }
                // Stocke le nouveau justificatif dans storage/app/public/justificatifs
                $validated['justificatif_path'] = $request->file('justificatif')
                    ->store(self::JUSTIFICATIF_DIR, 'public');
            } else {
                // Si aucun nouveau justificatif n'est uploadé, on ne met pas à jour le champ
                // Il est important de ne pas unset 'justificatif' si il est absent dans la request
                // car cela pourrait écraser le champ existant avec null si la validation le permettait.
                // Par contre, si votre formulaire permet de vider le justificatif, il faudrait une logique dédiée.
                // Ici, on part du principe qu'il doit toujours y avoir un justificatif.
                unset($validated['justificatif']);
            }

            // Mise à jour du document principal
            unset($validated['user_id'], $validated['commune_id'], $validated['registry_number']);
            $document->update($validated);

            // --- NOUVELLE LOGIQUE POUR LES ATTACHMENTS SUPPLÉMENTAIRES LORS DE L'UPDATE ---
            // Supprimer les anciens attachments si nécessaire (dépend de votre UX)
            // Pour l'exemple, nous allons juste ajouter les nouveaux et ne pas gérer la suppression ici
            // Une approche plus robuste inclurait des IDs pour les attachments existants ou une suppression/recréation totale.
            if ($request->hasFile('attachments_files')) {
                foreach ($request->file('attachments_files') as $file) {
                    if ($file->isValid()) {
                        $attachmentPath = $file->store(self::ATTACHMENTS_DIR, 'public');
                        $document->attachments()->create([
                            'name' => $file->getClientOriginalName(),
                            'path' => $attachmentPath,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                        ]);
                    } else {
                        Log::warning("Fichier non valide tenté d'être uploadé lors de la mise à jour pour document " . $document->id);
                    }
                }
            }
            // --- FIN NOUVELLE LOGIQUE D'UPDATE ---

            return redirect()->route('agent.documents.show', $document)
                ->with('success', 'Document mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur mise à jour document/attachments: " . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour du document et des pièces jointes.');
        }
    }

    public function editDoc($id)
    {
        $document = Document::findOrFail($id);
        $this->authorizeDocument($document);
        return view('agent.documents.edit', compact('document'));
    }

    public function printNaissance($id)
    {
        $document = Document::findOrFail($id);
        $this->authorizeDocument($document);
        return view('certificats.naissance', compact('document'));
    }

    public function printMariage($id)
    {
        $document = Document::findOrFail($id);
        $this->authorizeDocument($document);
        return view('certificats.mariage', compact('document'));
    }

    public function printDeces($id)
    {
        $document = Document::findOrFail($id);
        $this->authorizeDocument($document);
        return view('certificats.deces', compact('document'));
    }

    public function approve(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $this->authorizeDocument($document);

        try {
            $this->updateDocumentStatus($document, DocumentStatus::APPROUVEE);

            $pdfPath = $this->generateDocumentPdf($document);
            $document->update(['pdf_path' => $pdfPath]);

            switch ($document->type->value ?? $document->type) {
                case 'naissance':
                    return redirect()->route('agent.documents.certificats.naissance', $document->id)
                        ->with('success', 'Document approuvé et acte prêt à être imprimé.');
                case 'mariage':
                    return redirect()->route('agent.documents.certificats.mariage', $document->id)
                        ->with('success', 'Document approuvé et acte prêt à être imprimé.');
                case 'deces':
                    return redirect()->route('agent.documents.certificats.deces', $document->id)
                        ->with('success', 'Document approuvé et acte prêt à être imprimé.');
                default:
                    return redirect()->route('agent.documents.show', $document)
                        ->with('success', 'Document approuvé.');
            }
        } catch (\Exception $e) {
            Log::error("Erreur d'approbation ou de génération de PDF pour document " . $document->id . ": " . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'approbation ou de la génération du document.');
        }
    }

    public function approuve(Request $request)
    {
        $agent = Auth::user();

        if (!$agent) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $communeId = $agent->commune_id;

        if (empty($communeId)) {
            return back()->with('error', 'Votre compte agent n\'est pas associé à une commune.');
        }

        $query = Document::where('status', DocumentStatus::APPROUVEE->value)
            ->where('commune_id', $communeId);

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('registry_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('registry_number', 'like', '%' . $search . '%');
                    })
                    ->orWhereJsonContains('metadata->nom_enfant', $search)
                    ->orWhereJsonContains('metadata->nom_epoux', $search)
                    ->orWhereJsonContains('metadata->nom_defunt', $search);
            });
        }

        $documentsApprouves = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Get document counts
        $documentCounts = $this->getDocumentCounts();

        return view('agent.documents.approuve', compact('documentsApprouves', 'documentCounts'));
    }

    public function rejete(Request $request)
    {
        $agent = Auth::user();

        if (!$agent) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $communeId = $agent->commune_id;

        if (empty($communeId)) {
            return back()->with('error', 'Votre compte agent n\'est pas associé à une commune.');
        }

        $query = Document::where('status', DocumentStatus::REJETEE->value)
            ->where('commune_id', $communeId);

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('registry_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereJsonContains('metadata->nom_enfant', $search)
                    ->orWhereJsonContains('metadata->nom_epoux', $search)
                    ->orWhereJsonContains('metadata->nom_defunt', $search);
            });
        }

        $documentsRejetes = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Get document counts
        $documentCounts = $this->getDocumentCounts();

        return view('agent.documents.rejete', compact('documentsRejetes', 'documentCounts'));
    }

    public function attente(Request $request)
    {
        $agent = Auth::user();

        if (!$agent) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $communeId = $agent->commune_id;

        if (empty($communeId)) {
            return back()->with('error', 'Votre compte agent n\'est pas associé à une commune.');
        }

        $query = Document::where('status', DocumentStatus::EN_ATTENTE->value)
            ->where('commune_id', $communeId);

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('registry_number', 'like', '%' . $search . '%')
                ->orWhereJsonContains('metadata->nom_enfant', $search)
                ->orWhereJsonContains('metadata->nom_epoux', $search)
                ->orWhereJsonContains('metadata->nom_defunt', $search);
            });
        }

        $documentsEnAttente = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Get document counts
        $documentCounts = $this->getDocumentCounts();

        return view('agent.documents.attente', compact('documentsEnAttente', 'documentCounts'));
    }
}
