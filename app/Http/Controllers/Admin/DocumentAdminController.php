<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Commune;
use App\Enums\DocumentStatus;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentAdminController extends Controller
{
    /**
     * Affiche la liste des documents avec filtres facultatifs.
     */
    public function index(Request $request)
    {
        $agent = auth()->user();

        // Vérifie que l'agent est bien associé à une commune
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

    /**
     * Approuve une demande de document.
     */
    public function approve(Document $document)
    {
        $document->update([
            'status' => DocumentStatus::APPROUVEE,
            'agent_id' => auth()->id(),
            'decision_date' => now(),
        ]);

        return back()->with('success', 'Demande approuvée !');
    }

    /**
     * Rejette une demande avec un commentaire obligatoire.
     */
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

    /**
     * Génère le PDF d’un document.
     */
    public function generatePdf(Document $document)
    {
        $pdf = Pdf::loadView('admin.documents.pdf', compact('document'));

        return $pdf->download("acte-{$document->registry_number}.pdf");
    }

    // previsualiser le document
    public function showDocument(Document $document)
    {
        return view('agent.documents.show', compact('document'));
    }

    // créer un document
    public function create()
    {
        return view('agent.documents.create');
    }

}
