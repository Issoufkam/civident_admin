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

class DocumentAdminController extends Controller
{
    private const JUSTIFICATIF_DIR = 'justificatifs';
    private const DOCUMENTS_DIR = 'documents';
    private const SIGNATURES_DIR = 'signatures';
    private const TIMBRES_DIR = 'timbres';

    public function index(Request $request)
    {
        $agent = auth()->user();
        $communeId = $agent->commune_id;
        abort_unless($communeId, 403, "Aucune commune associée à votre profil.");

        $query = Document::with(['user', 'commune', 'agent'])
            ->where('commune_id', $communeId)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_duplicata')) {
            $query->where('is_duplicata', true);
        }

        $documents = $query->paginate(25);
        $communes = Commune::where('id', $communeId)->get();

        return view('agent.documents.index', compact('documents', 'communes'));
    }

    public function create()
    {
        return view('agent.documents.create');
    }

    public function showDocument(Document $document)
    {
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
        $validated = $this->validateDocumentRequest($request);
        $validated = $this->prepareDocumentData($validated, $request);

        try {
            Document::create($validated);
            return redirect()->route('agent.documents.index')->with('success', 'Document créé avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur création document: " . $e->getMessage());
            return back()->withInput()->with('error', 'Erreur lors de la création du document.');
        }
    }

    // public function approve(Document $document)
    // {
    //     $this->authorizeDocument($document);

    //     try {
    //         // Mettre à jour le statut
    //         $document->update([
    //             'status' => DocumentStatus::APPROUVEE,
    //             'agent_id' => auth()->id(),
    //             'decision_date' => now()
    //         ]);

    //         // Générer le PDF
    //         $pdf = Pdf::loadView($this->getDocumentView($document), [
    //             'document' => $document,
    //             'signature' => $this->getAgentSignaturePath(),
    //             'timbre' => $this->getTimbrePath(),
    //         ]);

    //         $filename = 'acte-' . $document->registry_number . '.pdf';
    //         $path = 'public/documents/' . $filename;

    //         Storage::put($path, $pdf->output());

    //         // Mettre à jour le chemin du PDF
    //         $document->update(['pdf_path' => 'storage/documents/' . $filename]);

    //         return redirect()->route('agent.documents.show', $document)
    //             ->with('success', 'Document approuvé avec succès');

    //     } catch (\Exception $e) {
    //         Log::error("Erreur approbation document: " . $e->getMessage());
    //         return back()->with('error', 'Erreur lors de l\'approbation du document.');
    //     }
    // }

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
        $duplicata->decision_date = null;
        $duplicata->agent_id = null;
        $duplicata->created_at = now();
        $duplicata->updated_at = now();
        $duplicata->save();

        return redirect()->route('agent.documents.show', $duplicata)->with('success', 'Duplicata créé avec succès.');
    }

    private function validateDocumentRequest(Request $request): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(['naissance', 'mariage', 'deces'])],
            'registry_page' => 'nullable|integer',
            'registry_volume' => 'nullable|string|max:255',
            'justificatif' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'traitement_date' => 'required|date',
            ...$this->getTypeSpecificValidationRules($request->type),
        ]);
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
                'nom_pere' => 'required|string|max:255',
                'nom_mere' => 'required|string|max:255',
            ],
            'mariage' => [
                'nom_epoux' => 'required|string|max:255',
                'nom_epouse' => 'required|string|max:255',
                'date_mariage' => 'required|date',
                'lieu_mariage' => 'required|string|max:255',
            ],
            'deces' => [
                'nom_defunt' => 'required|string|max:25',
                'prenom_defunt' => 'required|string|max:50',
                'date_deces' => 'required|date',
                'lieu_deces' => 'required|string|max:255',
            ],
        };
    }

    private function prepareDocumentData(array $data, Request $request): array
    {
        $data['user_id'] = Auth::id();
        $data['commune_id'] = Auth::user()->commune_id;
        $data['registry_number'] = $this->generateRegistryNumber($data['type']);
        $data['justificatif_path'] = $request->file('justificatif')->store(self::JUSTIFICATIF_DIR, 'public');
        $data['metadata'] = $this->extractMetadata($data['type'], $request->all());
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
                'nom_epoux', 'nom_epouse', 'date_mariage', 'lieu_mariage'
            ])),
            'deces' => array_intersect_key($requestData, array_flip([
                'nom_defunt', 'prenom_defunt', 'date_deces', 'lieu_deces'
            ])),
        };
    }

    private function updateDocumentStatus(Document $document, DocumentStatus $status, ?string $comments = null): void
    {
        $document->update([
            'status' => $status,
            'agent_id' => auth()->id(),
            'decision_date' => now(),
            'comments' => $comments, // Cela peut être null, ce qui est acceptable si la colonne l'autorise
        ]);
    }


    private function generateDocumentPdf(Document $document): string
    {
        $pdf = Pdf::loadView($this->getDocumentView($document), [
            'document' => $document,
            'signature' => $this->getAgentSignaturePath(),
            'timbre' => $this->getTimbrePath(),
        ]);

        $filename = 'acte-' . $document->registry_number . '.pdf';
        Storage::put('public/' . self::DOCUMENTS_DIR . '/' . $filename, $pdf->output());

        return 'storage/' . self::DOCUMENTS_DIR . '/' . $filename;
    }

    private function getDocumentView(Document $document): string
    {
        return match ($document->type->value) {
            'naissance' => 'certificats.naissance',
            'mariage' => 'certificats.mariage',
            'deces' => 'certificats.deces',
            default => throw new \InvalidArgumentException('Type de document inconnu'),
        };
    }


    private function getAgentSignaturePath(): ?string
    {
        $path = 'public/' . self::SIGNATURES_DIR . '/' . auth()->id() . '.png';
        return Storage::exists($path) ? Storage::url($path) : null;
    }

    private function getTimbrePath(): ?string
    {
        $path = 'public/' . self::TIMBRES_DIR . '/timbre.png';
        return Storage::exists($path) ? Storage::url($path) : null;
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

        $validated = $this->validateDocumentRequest($request);

        try {
            $validated = $this->prepareDocumentData($validated, $request);

            // Ne pas régénérer certains champs
            unset($validated['user_id'], $validated['commune_id'], $validated['registry_number']);

            // Supprimer l'ancien fichier justificatif si un nouveau est fourni
            if ($request->hasFile('justificatif')) {
                if ($document->justificatif_path && Storage::disk('public')->exists($document->justificatif_path)) {
                    Storage::disk('public')->delete($document->justificatif_path);
                }
                $validated['justificatif_path'] = $request->file('justificatif')
                    ->store(self::JUSTIFICATIF_DIR, 'public');
            }

            // Mise à jour
            $document->update($validated);

            return redirect()->route('agent.documents.index', $document)
                ->with('success', 'Document mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur mise à jour document: " . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour du document.');
        }
    }


    public function editDoc($id)
    {
        $document = Document::findOrFail($id);
        return view('agent.documents.edit', compact('document'));
    }

    public function printNaissance($id)
    {
        $document = Document::findOrFail($id);
        return view('certificats.naissance', compact('document'));
    }

    public function printMariage($id)
    {
        $document = Document::findOrFail($id);
        return view('certificats.mariage', compact('document'));
    }

    public function printDeces($id)
    {
        $document = Document::findOrFail($id);
        return view('certificats.deces', compact('document'));
    }

    // public function printGenerique($id)
    // {
    //     $document = Document::findOrFail($id);
    //     return view('certificats.generique', compact('document'));
    // }

    public function approve(Request $request, $id)
{
    $document = Document::findOrFail($id);

    // Traitement de la validation
    $document->status = 'approuvee';
    $document->agent_id = auth()->id();
    $document->save();

    // Redirige vers la bonne vue imprimable
    switch ($document->type->value) {
        case 'naissance':
            return redirect()->route('agent.documents.certificats.naissance', $document->id);
        case 'mariage':
            return redirect()->route('agent.documents.certificats.mariage', $document->id);
        case 'deces':
            return redirect()->route('agent.documents.certificats.deces', $document->id);
        // default:
        //     return redirect()->route('document.print.generique', $document->id);
    }
}

}
