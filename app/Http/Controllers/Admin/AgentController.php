<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Commune;
use App\Enums\DocumentStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    // Affiche la liste des agents avec recherche et pagination
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::where('role', 'agent')
            ->with('commune');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('telephone', 'like', "%$search%");
            });
        }

        $agents = $query->paginate(10);

        return view('admin.agents.index', compact('agents'));
    }

    // Affiche le formulaire de création d'un agent
    public function create()
    {
        $this->authorize('create', User::class);

        $communes = Commune::orderBy('nom')->get();
        return view('admin.agents.create', compact('communes'));
    }

    // Enregistre un nouvel agent
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20|unique:users,telephone',
            'password' => 'required|string|min:8|confirmed',
            'commune_id' => 'required|exists:communes,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'agent';

        User::create($validated);

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Nouvel agent créé avec succès');
    }

    // Affiche les détails d'un agent
    public function show(User $agent)
    {
        $this->authorize('view', $agent);
        return view('admin.agents.show', compact('agent'));
    }

    // Affiche le formulaire d'édition d'un agent
    public function edit(User $agent)
    {
        $this->authorize('update', $agent);

        $communes = Commune::orderBy('nom')->get();
        return view('admin.agents.edit', compact('agent', 'communes'));
    }

    // Met à jour les informations d'un agent
    public function update(Request $request, User $agent)
    {
        $this->authorize('update', $agent);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$agent->id}",
            'telephone' => "required|string|max:20|unique:users,telephone,{$agent->id}",
            'commune_id' => 'required|exists:communes,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($agent->photo) {
                Storage::disk('public')->delete($agent->photo);
            }
            $validated['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $agent->update($validated);

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent mis à jour avec succès');
    }

    // Supprime un agent
    public function destroy(User $agent)
    {
        $this->authorize('delete', $agent);

        // Supprimer la photo si elle existe
        if ($agent->photo) {
            Storage::disk('public')->delete($agent->photo);
        }

        $agent->delete();

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent supprimé avec succès');
    }

    // Gestion des documents
    public function documents(Request $request)
    {
        $query = Document::where('commune_id', auth()->user()->commune_id)
            ->with(['user', 'processingAgent'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $documents = $query->paginate(15);

        return view('admin.agents.documents', [
            'documents' => $documents,
            'statuses' => DocumentStatus::cases(),
            'types' => ['naissance', 'mariage', 'deces'],
        ]);
    }

    // Affiche un document spécifique
    public function showDocument(Document $document)
    {
        Gate::authorize('view-document', $document);

        return view('admin.agents.documents.show', [
            'document' => $document->load(['user', 'processingAgent', 'piecesJointes']),
        ]);
    }

    // Approuve un document
    public function approveDocument(Document $document)
    {
        Gate::authorize('update-document', $document);

        $document->update([
            'status' => DocumentStatus::APPROUVEE,
            'processed_by' => auth()->id(),
            'decision_date' => now(),
        ]);

        // Générer le PDF
        $pdf = Pdf::loadView('admin.documents.pdf', [
            'document' => $document->load(['user', 'piecesJointes'])
        ]);

        $filename = "acte-{$document->registry_number}.pdf";
        $path = "documents/{$filename}";

        Storage::put("public/{$path}", $pdf->output());

        $document->update(['pdf_path' => $path]);

        return back()->with('success', 'Document approuvé et PDF généré');
    }

    // Rejette un document
    public function rejectDocument(Request $request, Document $document)
    {
        Gate::authorize('update-document', $document);

        $validated = $request->validate([
            'raison_rejet' => 'required|string|max:500',
        ]);

        $document->update([
            'status' => DocumentStatus::REJETEE,
            'raison_rejet' => $validated['raison_rejet'],
            'processed_by' => auth()->id(),
            'decision_date' => now(),
        ]);

        return back()->with('success', 'Document rejeté');
    }

    // Tableau de bord
    public function dashboard(Request $request)
    {
        // Statistiques de base
        $stats = [
            'total' => Document::count(),
            'en_attente' => Document::where('status', 'en_attente')->count(),
            'approuves' => Document::where('status', 'approuvee')->count(),
            'rejetes' => Document::where('status', 'rejetee')->count(),

            // Répartition par type
            'naissances' => Document::where('type', 'naissance')->count(),
            'mariages' => Document::where('type', 'mariage')->count(),
            'deces' => Document::where('type', 'deces')->count(),
        ];

        // Demandes récentes
        $recentDemandes = Document::with(['user', 'commune'])
            ->latest()
            ->take(10)
            ->get();

        // Demandes urgentes (en attente depuis plus de 3 jours)
        $urgentDemandes = Document::where('status', 'en_attente')
            ->where('created_at', '<', now()->subDays(3))
            ->with(['user', 'commune'])
            ->get();

        return view('admin.dashboard', compact('stats', 'recentDemandes', 'urgentDemandes'));
    }

    // Gestion des régions
    public function regions()
    {
        $regions = Commune::select('region')
            ->distinct()
            ->orderBy('region')
            ->get()
            ->pluck('region');

        return view('admin.regions.index', compact('regions'));
    }

    // API pour les communes par région
    public function communesByRegion($region)
    {
        $communes = Commune::where('region', $region)
            ->orderBy('nom')
            ->get(['id', 'nom']);

        return response()->json($communes);
    }
}
