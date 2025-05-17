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
    // Supervision des demandes
    public function index(Request $request)
    {
        $documents = Document::with(['user', 'commune', 'agent'])
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status)) // Remplacement de filled() par has()
            ->when($request->has('commune_id'), fn($q) => $q->where('commune_id', $request->commune_id))
            ->latest()
            ->paginate(25);

        $communes = Commune::all();
        return view('admin.documents.index', compact('documents', 'communes'));
    }

    // Validation des demandes
    public function approve(Document $document)
    {
        $document->update([
            'status' => DocumentStatus::APPROUVEE,
            'agent_id' => auth()->id(),
            'decision_date' => now()
        ]);

        return back()->with('success', 'Demande approuvée !');
    }

    public function reject(Request $request, Document $document)
    {
        $validated = $request->validate([ // Ajout de la validation
            'comments' => 'required|string|max:500'
        ]);

        $document->update([
            'status' => DocumentStatus::REJETEE,
            'comments' => $validated['comments'] // Utilisation des données validées
        ]);

        return back()->with('success', 'Demande rejetée !');
    }

    // Génération PDF
    public function generatePdf(Document $document)
    {
        $pdf = Pdf::loadView('admin.documents.pdf', compact('document')); // Utilisation correcte du facade
        return $pdf->download("acte-{$document->registry_number}.pdf");
    }
}
