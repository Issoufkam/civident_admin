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
            'registry_volume' => 'nullable|string',
            'justificatif' => 'required|file',
            'traitement_date' => 'required|date',
        ]);

        // Ajout automatique de l'utilisateur connecté et de sa commune
        $validated['user_id'] = Auth::id();
        $validated['commune_id'] = Auth::user()->commune_id;

        // Génération du numéro d’enregistrement
        $registryNumber = strtoupper(substr($validated['type'], 0, 3)) . '-' . now()->format('Ymd-His') . '-' . Str::random(4);

        // Upload du fichier justificatif
        $justificatifPath = $request->file('justificatif')->store('justificatifs', 'public');

        // Metadata selon le type
        $metadata = match ($validated['type']) {
            'naissance' => [
                'nom_enfant' => $request->input('nom_enfant'),
                'date_naissance' => $request->input('date_naissance'),
                'lieu_naissance' => $request->input('lieu_naissance'),
                'nom_pere' => $request->input('nom_pere'),
                'nom_mere' => $request->input('nom_mere'),
            ],
            'mariage' => [
                'nom_epoux' => $request->input('nom_epoux'),
                'nom_epouse' => $request->input('nom_epouse'),
                'date_mariage' => $request->input('date_mariage'),
                'lieu_mariage' => $request->input('lieu_mariage'),
            ],
            'deces' => [
                'nom_defunt' => $request->input('nom_defunt'),
                'date_deces' => $request->input('date_deces'),
                'lieu_deces' => $request->input('lieu_deces'),
            ],
        };

        Document::create([
            ...$validated,
            'registry_number' => $registryNumber,
            'justificatif_path' => $justificatifPath,
            'metadata' => $metadata,
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Document créé avec succès');
    }

    public function showDocument(Document $document)
    {
        return view('agent.documents.show', compact('document'));
    }

    public function approve(Document $document)
    {
        $document->update([
            'status' => DocumentStatus::APPROUVEE,
            'agent_id' => auth()->id(),
            'decision_date' => now(),
        ]);

        $pdf = Pdf::loadView('agent.documents.pdf', compact('document'));
        $filename = 'acte-' . $document->registry_number . '.pdf';

        Storage::put('public/documents/' . $filename, $pdf->output());

        $document->update([
            'pdf_path' => 'storage/documents/' . $filename,
        ]);

        return back()->with('success', 'Demande approuvée et PDF généré avec succès !');
    }

    public function reject(Request $request, Document $document)
    {
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
        $pdf = Pdf::loadView('admin.documents.pdf', compact('document'));
        return $pdf->download("acte-{$document->registry_number}.pdf");
    }

    public function rejectDocument(Document $document)
    {
        $document->status = DocumentStatus::REJETEE->value;
        $document->save();

        return redirect()->route('agent.documents.index')
            ->with('success', 'Le document a été rejeté.');
    }
}
