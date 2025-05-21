<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Commune;
use App\Enums\DocumentType;
use App\Enums\DocumentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Enums\UserRole;
use App\Events\DocumentStatusUpdated;

class DocumentController extends Controller
{
    /**
     * Affiche la liste des documents
     */

     public function dashboard()
     {
        return view('admin.dashboard');
     }


    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Document::with(['user', 'commune', 'agent'])
            ->latest();

        // Restriction par commune pour les agents
        if (auth()->user()->role === UserRole::AGENT) {
            $query->where('commune_id', $user->commune_id);
        }

        // Filtres de type et statut
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->paginate(20);

        return view('documents.index', [
            'documents' => $documents,
            'types' => DocumentType::cases(),
            'statuses' => DocumentStatus::cases(),
            'communes' => Commune::all()
        ]);
    }

    /**
     * Formulaire de création de document
     */
    public function create()
    {
        $user = auth()->user();

        return view('documents.create', [
            'types' => DocumentType::cases(),
            'communes' => $user->isAdmin()
                ? Commune::all()
                : [$user->commune]
        ]);
    }

    /**
     * Stockage d'un nouveau document
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'type' => ['required', 'in:' . implode(',', DocumentType::values())],
            'commune_id' => ['required', 'exists:communes,id'],
            'justificatif' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            'metadata.nom' => ['required_if:type,naissance,deces', 'string'],
            'metadata.prenom' => ['required_if:type,naissance,deces', 'string'],
            'metadata.date_acte' => ['required', 'date'],
        ]);

        // Générer un numéro unique
        $registryNumber = $this->generateRegistryNumber(
            $validated['type'],
            $validated['commune_id']
        );

        // Stocker le fichier
        $path = $request->file('justificatif')->store('justificatifs');

        // Créer le document
        $document = Document::create([
            'type' => $validated['type'],
            'registry_number' => $registryNumber,
            'metadata' => $validated['metadata'],
            'justificatif_path' => $path,
            'user_id' => $user->id,
            'commune_id' => $validated['commune_id'],
            'status' => $user->isCitoyen()
                ? DocumentStatus::EN_ATTENTE
                : DocumentStatus::APPROUVEE
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document enregistré avec succès');
    }

    /**
     * Affichage d’un document
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);

        return view('documents.show', [
            'document' => $document->load(['user', 'commune', 'agent'])
        ]);
    }

    /**
     * Mise à jour du statut
     */
    public function updateStatus(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'status' => ['required', 'in:approuvee,rejetee'],
            'comments' => ['required_if:status,rejetee', 'nullable', 'string']
        ]);

        $document->update([
            'status' => $validated['status'],
            'agent_id' => auth()->id(),
            'metadata' => array_merge(
                $document->metadata,
                ['comments' => $validated['comments'] ?? null]
            )
        ]);

        event(new DocumentStatusUpdated($document));

        return back()->with('success', 'Statut mis à jour');
    }

    /**
     * Téléchargement du justificatif
     */
    public function downloadJustificatif(Document $document)
    {
        $this->authorize('view', $document);

        if (!Storage::exists($document->justificatif_path)) {
            abort(404);
        }

        return Storage::download(
            $document->justificatif_path,
            "justificatif-{$document->registry_number}.pdf"
        );
    }

    /**
     * Génération d’un numéro de registre
     */
    protected function generateRegistryNumber(string $type, int $communeId): string
    {
        $commune = Commune::findOrFail($communeId);
        $year = now()->year;

        $sequence = Document::whereYear('created_at', $year)
            ->where('commune_id', $communeId)
            ->count() + 1;

        return sprintf('%s-%s-%04d', $year, Str::upper($commune->code), $sequence);
    }
}
