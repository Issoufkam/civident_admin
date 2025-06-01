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

        $communes = Commune::orderBy('name')->get();
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
            'password' => 'required|string|min:8',
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

   public function show($id) // Changez 'User $agent' en '$id'
{
    // Tente de trouver l'utilisateur par l'ID.
    // Si l'utilisateur n'est pas trouvé, Laravel lancera automatiquement une erreur 404 (NotFoundHttpException).
    $agent = User::findOrFail($id);

    // Cette ligne sera exécutée seulement si l'agent est trouvé.
    $this->authorize('view', $agent);

    return view('admin.agents.show', compact('agent'));
}

    // Affiche le formulaire d'édition d'un agent
    public function edit(User $agent)
    {
        $this->authorize('update', $agent);

        $communes = Commune::orderBy('name')->get();
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
            'password' => 'nullable|string|min:8',
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

  public function dashboard(Request $request)
{
    $communeId = auth()->user()->commune_id;
    $now = now();
    $lastMonth = $now->copy()->subMonth();
    $lastWeek = $now->copy()->subWeek();

    // Configuration des types de documents
    $documentTypes = [
        'naissance' => 'Extrait de naissance',
        'mariage' => 'Acte de mariage',
        'deces' => 'Acte de décès'
    ];

    // Filtrage des documents
    $currentFilter = $request->get('filter', 'all');
    $query = Document::where('commune_id', $communeId);

    if ($currentFilter !== 'all') {
        $query->where('type', $currentFilter);
    }

    $documents = $query->paginate(15);

    // Récupération des statistiques
    $statsQuery = Document::where('commune_id', $communeId)
        ->selectRaw('COUNT(*) as total')
        ->selectRaw('SUM(CASE WHEN status = "en_attente" THEN 1 ELSE 0 END) as en_attente')
        ->selectRaw('SUM(CASE WHEN status = "approuvee" THEN 1 ELSE 0 END) as approuves')
        ->selectRaw('SUM(CASE WHEN status = "rejetee" THEN 1 ELSE 0 END) as rejetes')
        ->selectRaw('SUM(CASE WHEN type = "naissance" THEN 1 ELSE 0 END) as naissances')
        ->selectRaw('SUM(CASE WHEN type = "mariage" THEN 1 ELSE 0 END) as mariages')
        ->selectRaw('SUM(CASE WHEN type = "deces" THEN 1 ELSE 0 END) as deces')
        ->first();

    $stats = [
        'total' => $statsQuery->total ?? 0,
        'en_attente' => $statsQuery->en_attente ?? 0,
        'approuves' => $statsQuery->approuves ?? 0,
        'rejetes' => $statsQuery->rejetes ?? 0,
        'naissances' => $statsQuery->naissances ?? 0,
        'mariages' => $statsQuery->mariages ?? 0,
        'deces' => $statsQuery->deces ?? 0,

        'total_last_month' => Document::where('commune_id', $communeId)
                                ->where('created_at', '>=', $lastMonth)
                                ->count(),
        'en_attente_last_week' => Document::where('commune_id', $communeId)
                                    ->where('status', 'en_attente')
                                    ->where('created_at', '>=', $lastWeek)
                                    ->count(),
        'approuves_last_month' => Document::where('commune_id', $communeId)
                                    ->where('status', 'approuvee')
                                    ->where('created_at', '>=', $lastMonth)
                                    ->count(),
        'rejetes_last_month' => Document::where('commune_id', $communeId)
                                    ->where('status', 'rejetee')
                                    ->where('created_at', '>=', $lastMonth)
                                    ->count(),
    ];

    // Calcul des pourcentages
    $stats['total_change'] = $this->calculatePercentageChange(
        $stats['total'] - $stats['total_last_month'],
        $stats['total_last_month']
    );

    $stats['pending_change'] = $this->calculatePercentageChange(
        $stats['en_attente'] - $stats['en_attente_last_week'],
        $stats['en_attente_last_week']
    );

    $stats['approved_change'] = $this->calculatePercentageChange(
        $stats['approuves'] - $stats['approuves_last_month'],
        $stats['approuves_last_month']
    );

    $stats['rejected_change'] = $this->calculatePercentageChange(
        $stats['rejetes'] - $stats['rejetes_last_month'],
        $stats['rejetes_last_month']
    );

    // Préparation des données pour les graphiques
    $monthlyLabels = [];
    $monthlyData = [];

    for ($i = 5; $i >= 0; $i--) {
        $date = $now->copy()->subMonths($i);
        $monthlyLabels[] = $date->translatedFormat('M Y');
        $monthlyData[] = Document::where('commune_id', $communeId)
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
    }

    $chartData = [
        'typeChart' => [
            'labels' => array_values($documentTypes),
            'data' => [
                $stats['naissances'] ?? 0,
                $stats['mariages'] ?? 0,
                $stats['deces'] ?? 0
            ],
            'colors' => ['#4e73df', '#1cc88a', '#36b9cc']
        ],
        'activityChart' => [
            'labels' => $monthlyLabels,
            'data' => $monthlyData
        ]
    ];

    // Récupération des demandes
    $recentDemandes = Document::with(['user', 'commune'])
        ->where('commune_id', $communeId)
        ->latest()
        ->take(5)
        ->get();

    $urgentDemandes = Document::where('commune_id', $communeId)
        ->where('status', 'en_attente')
        ->where('created_at', '<', $now->subDays(3))
        ->with(['user', 'commune'])
        ->get();

    return view('agent.dashboard', [
        'recentDemandes' => $recentDemandes,
        'urgentDemandes' => $urgentDemandes,
        'documentTypes' => $documentTypes,
        'currentFilter' => $currentFilter,
        'documents' => $documents,
        'stats' => $stats,
        'chartData' => $chartData,
        'monthlyLabels' => $monthlyLabels,
        'monthlyData' => $monthlyData,
        'chartData' => $chartData
    ]);
}

    private function calculatePercentageChange($difference, $original)
    {
        if ($original == 0) {
            return 0; // Éviter la division par zéro
        }
        return round(($difference / $original) * 100);
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
