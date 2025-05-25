<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Commune;
use App\Enums\DocumentStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentAdminController extends Controller
{
    public function index(Request $request)
    {
        $agent = auth()->user();

        if (!$agent->commune_id) {
            abort(403, "Aucune commune associée à votre profil.");
        }

        $documents = Document::with(['user', 'commune', 'agent'])
            ->where('commune_id', $agent->commune_id)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(25);

        $communes = Commune::where('id', $agent->commune_id)->get();

        return view('agent.documents.index', compact('documents', 'communes'));
    }

    public function create()
    {
        return view('agent.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:naissance,mariage,deces',
            'registry_page' => 'nullable|integer',
            'registry_volume' => 'nullable|string|max:255',
            'justificatif' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'traitement_date' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['commune_id'] = Auth::user()->commune_id;

        $validated['registry_number'] = strtoupper(substr($validated['type'], 0, 3)) . '-' . now()->format('Ymd-His') . '-' . Str::random(4);
        $validated['justificatif_path'] = $request->file('justificatif')->store('justificatifs', 'public');

        // Construction des métadonnées selon le type de document
        $metadata = match ($validated['type']) {
            'naissance' => $request->only(['nom_enfant', 'date_naissance', 'lieu_naissance', 'nom_pere', 'nom_mere']),
            'mariage' => $request->only(['nom_epoux', 'nom_epouse', 'date_mariage', 'lieu_mariage']),
            'deces' => $request->only(['nom_defunt', 'date_deces', 'lieu_deces']),
        };

        $validated['metadata'] = $metadata;

        Document::create($validated);

        return redirect()->route('agent.documents.index')->with('success', 'Document créé avec succès.');
    }

    public function showDocument(Document $document)
    {
    
        $this->authorizeDocument($document);

        return view('agent.documents.show', compact('document'));
    }

    public function approve(Document $document)
    {
        $this->authorizeDocument($document);

        $document->update([
            'status' => DocumentStatus::APPROUVEE,
            'agent_id' => auth()->id(),
            'decision_date' => now(),
        ]);

        $view = match ($document->type->value) { // Utilisez ->value pour les enums
            'naissance' => 'certificats.naissance',
            'mariage'   => 'certificats.mariage',
            'deces'     => 'certificats.deces',
            default     => throw new \InvalidArgumentException('Type de document inconnu: '.$document->type->value),
        };

        $pdf = Pdf::loadView($view, ['document' => $document]);
        $filename = 'acte-' . $document->registry_number . '.pdf';

        Storage::put('public/documents/' . $filename, $pdf->output());

        $document->update([
            'pdf_path' => 'storage/documents/' . $filename,
        ]);

        return back()->with('success', 'Demande approuvée et PDF généré avec succès !');
    }

    public function reject(Request $request, Document $document)
    {
        $this->authorizeDocument($document);

        $validated = $request->validate([
            'comments' => 'required|string|max:500',
        ]);

        $document->update([
            'status' => DocumentStatus::REJETEE,
            'comments' => $validated['comments'],
        ]);

        return back()->with('success', 'Demande rejetée !');
    }

    public function generatePdf(Document $document)
    {
        $this->authorizeDocument($document);

        $document->decision_date_formatted = $document->decision_date
            ? $document->decision_date->format('d/m/Y')
            : 'Non spécifiée';

        $pdf = Pdf::loadView('documents.official', [
            'document' => $document
        ]);

        return $pdf->download("acte-{$document->registry_number}.pdf");
    }

    public function rejectDocument(Document $document)
    {
        $this->authorizeDocument($document);

        $document->update([
            'status' => DocumentStatus::REJETEE,
        ]);

        return redirect()->route('agent.documents.index')
            ->with('success', 'Le document a été rejeté.');
    }

    private function authorizeDocument(Document $document)
    {
        if ($document->commune_id !== auth()->user()->commune_id) {
            abort(403, 'Vous n’êtes pas autorisé à accéder à ce document.');
        }
    }
}
