<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Commune;
use App\Enums\DocumentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;

class AgentController extends Controller
{
    // Liste des agents de la commune de l'utilisateur connecté
    public function index(Request $request)
    {
        $communeId = auth()->user()->commune_id;

        $agents = User::where('commune_id', $communeId)
            ->where('role', 'agent')
            ->with('commune')
            ->when($request->has('search'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('nom', 'LIKE', '%' . $request->search . '%')
                      ->orWhere('prenom', 'LIKE', '%' . $request->search . '%');
                });
            })
            ->paginate(10);

        return view('admin.agents.index', compact('agents'));
    }

    public function createUser()
    {
        $this->authorize('create', User::class);

        return view('admin.agents.create', [
            'commune' => Commune::findOrFail(auth()->user()->commune_id)
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        User::create([
            ...$validated,
            'role' => 'agent',
            'commune_id' => auth()->user()->commune_id,
            'password' => bcrypt($validated['password'])
        ]);

        return redirect()->route('admin.agents.index')
             ->with('success', 'Nouvel agent créé avec succès');
    }

    public function showAgent($id)
    {
        $agent = User::findOrFail($id);

        return view('admin.agents.show', compact('agent'));
    }

    public function updateAgent($id)
    {
        $agent = User::findOrFail($id);
        $communes = Commune::all();

        return view('admin.agents.edit', compact('agent', 'communes'));
    }

    public function update(Request $request, $id)
    {
        $agent = User::findOrFail($id);

        $this->authorize('update', $agent);

        $validated = $request->validate([
            'nom' => 'required|string|max:25',
            'prenom' => 'required|string|max:50',
            'email' => "required|email|unique:users,email,$id",
            'telephone' => "required|string|max:15|unique:users,telephone,$id",
            'commune_id' => 'required|exists:communes,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:7048'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $agent->update($validated);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent mis à jour avec succès');
    }

    // Affiche tous les documents de la commune
    public function documents(Request $request)
    {
        $documents = Document::where('commune_id', auth()->user()->commune_id)
            ->with(['citoyen', 'typeDocument'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('agents.documents', [
            'documents' => $documents,
            'statuses' => DocumentStatus::cases()
        ]);
    }

    public function showDocument(Document $document)
    {
        Gate::authorize('view-document', $document);

        return view('agents.traiter-document', [
            'document' => $document->load('piecesJointes')
        ]);
    }

    public function approveDocument(Document $document)
    {
        Gate::authorize('update-document', $document);

        $document->update([
            'status' => DocumentStatus::APPROUVEE,
            'processed_by' => auth()->id(),
            'decision_date' => now()
        ]);

        $this->generateDocumentPdf($document);

        return back()->with('success', 'Document approuvé et PDF généré');
    }

    public function rejectDocument(Request $request, Document $document)
    {
        Gate::authorize('update-document', $document);

        $validated = $request->validate([
            'raison_rejet' => 'required|string|max:500'
        ]);

        $document->update([
            'status' => DocumentStatus::REJETEE,
            'raison_rejet' => $validated['raison_rejet'],
            'processed_by' => auth()->id()
        ]);

        return back()->with('success', 'Document rejeté');
    }

    public function generateDocumentPdf(Document $document)
    {
        Gate::authorize('view-document', $document);

        $pdf = Pdf::loadView('agents.document-pdf', [
            'document' => $document->load('citoyen', 'piecesJointes')
        ]);

        return $pdf->download("document-{$document->id}.pdf");
    }

    public function destroy(User $agent)
    {
        $this->authorize('delete', $agent);

        $agent->delete();

        return back()->with('success', 'Agent supprimé définitivement');
    }

    public function dashboard()
    {
        return view('agent.dashboard');
    }

    public function regions()
    {
        $regions = Commune::select('region')->distinct()->get();

        return view('agents.regions', compact('regions'));
    }

    public function communesByRegion($region)
    {
        $communes = Commune::where('region', $region)->get();

        return response()->json($communes);
    }
}
