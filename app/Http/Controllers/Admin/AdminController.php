<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commune;
use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\Document;
use App\Enums\DocumentStatus;
use App\Enums\DocumentType; // Assurez-vous que cette énumération contient tous vos types de documents
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;
use Exception;
use Log;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord Super Admin avec toutes les statistiques globales.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Définition des statuts des documents
        $approuvee = DocumentStatus::APPROUVEE->value;
        $rejetee = DocumentStatus::REJETEE->value;
        $en_attente = DocumentStatus::EN_ATTENTE->value;

        // Statistiques globales des utilisateurs
        $totalUsers = User::count();
        $totalAdmins = User::where('role', UserRole::ADMIN)->count();
        $totalAgents = User::where('role', UserRole::AGENT)->count();
        $totalCitoyens = User::where('role', UserRole::CITOYEN)->count();

        // Calcul des changements et progrès mensuels
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();

        // Agents (pour les cartes dynamiques)
        $agentsThisMonth = User::where('role', UserRole::AGENT)
            ->where('created_at', '>=', $currentMonth)
            ->count();
        $agentsLastMonth = User::where('role', UserRole::AGENT)
            ->whereBetween('created_at', [$previousMonth, $currentMonth])
            ->count();
        $agentChange = $agentsLastMonth > 0
            ? round((($agentsThisMonth - $agentsLastMonth) / $agentsLastMonth) * 100)
            : ($agentsThisMonth > 0 ? 100 : 0);

        $agentProgress = $totalAgents > 0
            ? round(($agentsThisMonth / $totalAgents) * 100)
            : 0;

        // Citoyens (pour les cartes dynamiques)
        $citoyensThisMonth = User::where('role', UserRole::CITOYEN)
            ->where('created_at', '>=', $currentMonth)
            ->count();
        $citoyenProgress = $totalCitoyens > 0
            ? round(($citoyensThisMonth / $totalCitoyens) * 100)
            : 0;

        // Documents (pour les cartes dynamiques)
        $totalRequests = Document::count();
        $requestsThisMonth = Document::where('created_at', '>=', $currentMonth)->count();
        $requestsLastMonth = Document::whereBetween('created_at', [$previousMonth, $currentMonth])->count();
        $requestChange = $requestsLastMonth > 0
            ? round((($requestsThisMonth - $requestsLastMonth) / $requestsLastMonth) * 100)
            : ($requestsThisMonth > 0 ? 100 : 0);
        $requestProgress = $totalRequests > 0
            ? round(($requestsThisMonth / $totalRequests) * 100)
            : 0;

        // Régions actives (pour les cartes dynamiques)
        $totalRegions = Commune::whereNotNull('region')->distinct('region')->count();
        $activeRegions = Commune::whereNotNull('region')
            ->whereHas('users') // Compte les régions qui ont au moins un utilisateur associé
            ->distinct('region')
            ->count();
        $regionCoverage = $totalRegions > 0
            ? round(($activeRegions / $totalRegions) * 100)
            : 0;

        // NOUVELLES STATISTIQUES : Paiements
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalPayments = Payment::count();
        $pendingPayments = Payment::where('status', 'en_attente')->count();
        $completedPayments = Payment::where('status', 'completed')->count();


        // NOUVELLES STATISTIQUES : Génération de Documents et Statuts
        $totalPdfsGenerated = Document::whereNotNull('pdf_path')->count();
        $totalPdfsSigned = Document::where('status', $approuvee)
                                   ->whereNotNull('pdf_path')
                                   ->count();


        // Statistiques de statut des documents pour les cartes
        $totalApprovedRequests = Document::where('status', $approuvee)->count();
        $totalRejectedRequests = Document::where('status', $rejetee)->count();
        $totalPendingRequests = Document::where('status', $en_attente)->count();


        // Données pour les cartes dynamiques en haut du tableau de bord
        $cards = [
            [
                'label' => 'Agents enregistrés',
                'value' => $totalAgents,
                'trend' => ($agentChange >= 0 ? '+' : '') . $agentChange . '% ce mois',
                'color' => 'primary',
            ],
            [
                'label' => 'Citoyens enregistrés',
                'value' => $totalCitoyens,
                'trend' => ($citoyenProgress >= 0 ? '+' : '') . $citoyenProgress . '% ce mois',
                'color' => 'success',
            ],
            [
                'label' => 'Documents déposés',
                'value' => $totalRequests,
                'trend' => ($requestChange >= 0 ? '+' : '') . $requestChange . '% ce mois',
                'color' => 'warning',
            ],
            [
                'label' => 'Régions actives',
                'value' => $activeRegions,
                'trend' => "Couverture à $regionCoverage%",
                'color' => 'secondary',
            ],
            // NOUVELLES CARTES DE STATISTIQUES DE PAIEMENT
            [
                'label' => 'Total Revenus Timbre',
                'value' => 'XOF ' . number_format($totalRevenue, 2, ',', ' '),
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'success',
            ],
            [
                'label' => 'Transactions Paiement',
                'value' => $totalPayments,
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'primary',
            ],
            [
                'label' => 'Paiements en Attente',
                'value' => $pendingPayments,
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'warning',
            ],
            // NOUVELLES CARTES DE STATISTIQUES DE DOCUMENTS GÉNÉRÉS
            [
                'label' => 'PDFs Générés',
                'value' => $totalPdfsGenerated,
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'info',
            ],
            [
                'label' => 'PDFs Signés',
                'value' => $totalPdfsSigned,
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'success',
            ],
            // NOUVELLES CARTES DE STATUT DES DEMANDES
            [
                'label' => 'Demandes Approuvées',
                'value' => $totalApprovedRequests,
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'success',
            ],
            [
                'label' => 'Demandes Rejetées',
                'value' => $totalRejectedRequests,
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'danger',
            ],
            [
                'label' => 'Demandes En Attente',
                'value' => $totalPendingRequests,
                'trend' => '', // Ajoutez une tendance si vous la calculez
                'color' => 'secondary',
            ],
        ];

        // Statistiques des documents par type (pour les graphiques du dashboard)
        // Cette requête est utilisée par le graphique 'documentTypeChart'
        $documentStats = Document::select(
            DB::raw('COUNT(*) as total'),
            'type'
        )
        ->groupBy('type')
        ->get();

        // Récupération des données de performance par région (pour le dashboard si affiché)
        $regionsPerformance = Commune::whereNotNull('region')
            ->select('region')
            ->distinct()
            ->get()
            ->map(function ($commune) use ($approuvee, $rejetee, $en_attente) {
                $regionName = $commune->region;
                // Assurez-vous que la relation 'commune' existe sur le modèle Document
                $totalDemandesRecues = Document::whereHas('commune', function ($q) use ($regionName) {
                    $q->where('region', $regionName);
                })->count();
                $demandesTraitees = Document::whereHas('commune', function ($q) use ($regionName) {
                    $q->where('region', $regionName);
                })
                ->whereIn('status', [$approuvee, $rejetee])
                ->count();
                $agentsCount = User::where('role', UserRole::AGENT)
                    ->whereHas('commune', function ($q) use ($regionName) {
                        $q->where('region', $regionName);
                    })
                    ->count();
                $rate = $totalDemandesRecues > 0
                    ? round(($demandesTraitees / $totalDemandesRecues) * 100)
                    : 0;

                return [
                    'name' => $regionName,
                    'demandes_recues' => $totalDemandesRecues,
                    'demandes_traitees' => $demandesTraitees,
                    'agents_count' => $agentsCount,
                    'rate' => $rate,
                ];
            });

        // Les 5 derniers agents enregistrés
        $latestAgents = User::with('commune')
            ->where('role', UserRole::AGENT)
            ->latest()
            ->take(5)
            ->get(['id', 'nom', 'prenom', 'commune_id']);


        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'cards',
            'regionsPerformance',
            'latestAgents',
            'totalAgents',
            'documentStats'
        ));
    }

    /**
     * Affiche les statistiques globales des utilisateurs et documents (plus détaillées que le dashboard).
     *
     * @return \Illuminate\View\View
     */
     public function showStatistics(Request $request)
    {
        // Filtres
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $region_id = $request->input('region_id');
        $commune_id = $request->input('commune_id');

        // Statistiques Utilisateurs
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalAgents = User::where('role', 'agent')->count();
        $totalCitoyens = User::where('role', 'citoyen')->count();

        // Requête de base pour les documents, excluant les duplicata
        $documentQuery = Document::query()->where('is_duplicata', false);

        // Application des filtres de date à la requête de base des documents
        if ($start_date) {
            $documentQuery->whereDate('created_at', '>=', $start_date);
        }
        if ($end_date) {
            $documentQuery->whereDate('created_at', '<=', $end_date); // Utilisation de 'created_at' pour la date de fin aussi
        }

        // Application des filtres de région/commune à la requête de base des documents
        if ($commune_id) {
            $documentQuery->where('commune_id', $commune_id);
        } elseif ($region_id) {
            $communesInRegion = Commune::where('region', $region_id)->pluck('id');
            $documentQuery->whereIn('commune_id', $communesInRegion);
        }

        // Utilisation des valeurs d'Enum directement
        $approuvee = DocumentStatus::APPROUVEE->value;
        $rejetee = DocumentStatus::REJETEE->value;
        $en_attente = DocumentStatus::EN_ATTENTE->value;

        // Statistiques de documents par type et statut (basées sur la requête filtrée)
        $documentStats = (clone $documentQuery)->select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = '$approuvee' THEN 1 ELSE 0 END) as approuvees"),
                DB::raw("SUM(CASE WHEN status = '$rejetee' THEN 1 ELSE 0 END) as rejetees"),
                DB::raw("SUM(CASE WHEN status = '$en_attente' THEN 1 ELSE 0 END) as en_attente_count"),
                'type'
            )
            ->groupBy('type')
            ->get();

        // Correction pour l'extraction des comptes spécifiques (Naissances, Mariages, Décès)
        $naissanceStat = $documentStats->firstWhere('type', DocumentType::NAISSANCE);
        $naissanceCount = $naissanceStat ? $naissanceStat->approuvees : 0;

        $mariageStat = $documentStats->firstWhere('type', DocumentType::MARIAGE);
        $mariageCount = $mariageStat ? $mariageStat->approuvees : 0;

        $decesStat = $documentStats->firstWhere('type', DocumentType::DECES);
        $decesCount = $decesStat ? $decesStat->approuvees : 0;

        // Calcul du taux de décès en pourcentage (Décès / Naissances)
        // Ajout d'une vérification pour éviter la division par zéro si naissanceCount est 0
        $decesRate = ($naissanceCount > 0) ? round(($decesCount / $naissanceCount) * 100, 2) : 0;


        // Statistiques Mensuelles (avec application des filtres)
        $monthlyStatsQuery = Document::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_year")
            ->selectRaw("SUM(CASE WHEN type = '" . DocumentType::NAISSANCE->value . "' THEN 1 ELSE 0 END) as naissance_count")
            ->selectRaw("SUM(CASE WHEN type = '" . DocumentType::MARIAGE->value . "' THEN 1 ELSE 0 END) as mariage_count")
            ->selectRaw("SUM(CASE WHEN type = '" . DocumentType::DECES->value . "' THEN 1 ELSE 0 END) as deces_count")
            ->where('is_duplicata', false); // Exclure les duplicata ici aussi

        // Appliquer les filtres de date aux statistiques mensuelles
        if ($start_date) {
            $monthlyStatsQuery->whereDate('created_at', '>=', $start_date);
        }
        if ($end_date) {
            $monthlyStatsQuery->whereDate('created_at', '<=', $end_date);
        }

        // Si aucun filtre de date n'est appliqué, afficher les données de l'année en cours
        if (!$start_date && !$end_date) {
            $monthlyStatsQuery->whereYear('created_at', now()->year);
        }

        // Appliquer les filtres de région/commune aux statistiques mensuelles
        if ($commune_id) {
            $monthlyStatsQuery->where('commune_id', $commune_id);
        } elseif ($region_id) {
            $communesInRegion = Commune::where('region', $region_id)->pluck('id');
            $monthlyStatsQuery->whereIn('commune_id', $communesInRegion);
        }

        $monthlyStats = $monthlyStatsQuery->groupBy('month_year')
            ->orderBy('month_year')
            ->get();

        // Statistiques par Région/Commune (basées sur la requête filtrée)
        $regionalCommuneStats = (clone $documentQuery)->select(
                'communes.region as region_name',
                'communes.name as commune_name',
                DB::raw("SUM(CASE WHEN documents.type = '" . DocumentType::NAISSANCE->value . "' THEN 1 ELSE 0 END) as naissance_count"),
                DB::raw("SUM(CASE WHEN documents.type = '" . DocumentType::MARIAGE->value . "' THEN 1 ELSE 0 END) as mariage_count"),
                DB::raw("SUM(CASE WHEN documents.type = '" . DocumentType::DECES->value . "' THEN 1 ELSE 0 END) as deces_count"),
                DB::raw("COUNT(documents.id) as total_acts")
            )
            ->join('communes', 'documents.commune_id', '=', 'communes.id')
            ->groupBy('communes.region', 'communes.name')
            ->orderBy('communes.region')
            ->orderBy('communes.name')
            ->get();

        // Charger toutes les régions et communes pour les filtres
        $allRegions = Commune::distinct()->pluck('region')->map(function ($item) {
            return ['id' => $item, 'name' => $item];
        })->toArray();

        $allCommunes = Commune::all();

        return view('admin.statistics', compact(
            'totalUsers',
            'totalAdmins',
            'totalAgents',
            'totalCitoyens',
            'documentStats',
            'en_attente',
            'naissanceCount',
            'mariageCount',
            'decesCount',
            'decesRate', // Ajout du taux de décès
            'monthlyStats',
            'regionalCommuneStats',
            'allRegions',
            'allCommunes'
        ));
    }
    /**
     * Affiche les performances de traitement des demandes.
     * Cette méthode centralise les données de performance.
     *
     * @return \Illuminate\View\View
     */
    public function showPerformances()
    {
        $approuvee = DocumentStatus::APPROUVEE->value;
        $rejetee = DocumentStatus::REJETEE->value;
        $en_attente = DocumentStatus::EN_ATTENTE->value;

        // 1. Performance Globale du Système
        $totalDemandes = Document::count();
        $demandesTraiteesGlobal = Document::whereIn('status', [$approuvee, $rejetee])->count();
        $tauxTraitementGlobal = $totalDemandes > 0 ? round(($demandesTraiteesGlobal / $totalDemandes) * 100, 2) : 0;
        $demandesEnAttenteGlobal = Document::where('status', $en_attente)->count();


        // 2. Performance par Type de Document
        $performanceByType = Document::select(
                'type',
                DB::raw('COUNT(*) as total_demandes'),
                DB::raw("SUM(CASE WHEN status = '$approuvee' THEN 1 ELSE 0 END) as approuvees"),
                DB::raw("SUM(CASE WHEN status = '$rejetee' THEN 1 ELSE 0 END) as rejetees")
            )
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                $item->traitees = $item->approuvees + $item->rejetees;
                $item->taux_traitement = $item->total_demandes > 0
                    ? round(($item->traitees / $item->total_demandes) * 100, 2)
                    : 0;
                return $item;
            });


        // 3. Performance par Région
        $performanceByRegion = Commune::whereNotNull('region')
            ->select('region')
            ->distinct()
            ->get()
            ->map(function ($commune) use ($approuvee, $rejetee, $en_attente) {
                $regionName = $commune->region;

                // Total des demandes pour cette région
                $totalDemandesRegion = Document::whereHas('user.commune', function ($q) use ($regionName) {
                    $q->where('region', $regionName);
                })->count();

                // Demandes traitées (approuvées ou rejetées) pour cette région
                $demandesTraiteesRegion = Document::whereHas('user.commune', function ($q) use ($regionName) {
                    $q->where('region', $regionName);
                })
                ->whereIn('status', [$approuvee, $rejetee])
                ->count();

                // Demandes en attente pour cette région
                $demandesEnAttenteRegion = Document::whereHas('user.commune', function ($q) use ($regionName) {
                    $q->where('region', $regionName);
                })
                ->where('status', $en_attente)
                ->count();

                // Nombre d'agents affectés à cette région
                $agentsCount = User::where('role', UserRole::AGENT)
                    ->whereHas('commune', function ($q) use ($regionName) {
                        $q->where('region', $regionName);
                    })
                    ->count();

                // Taux de traitement pour la région
                $tauxTraitementRegion = $totalDemandesRegion > 0
                    ? round(($demandesTraiteesRegion / $totalDemandesRegion) * 100, 2)
                    : 0;

                return [
                    'region' => $regionName,
                    'total_demandes' => $totalDemandesRegion,
                    'demandes_traitees' => $demandesTraiteesRegion,
                    'demandes_en_attente' => $demandesEnAttenteRegion,
                    'agents_count' => $agentsCount,
                    'taux_traitement' => $tauxTraitementRegion,
                ];
            });


        // 4. Performance des Agents (Top 5 des agents les plus performants)
        $topAgentsPerformance = User::where('role', UserRole::AGENT)
            ->withCount([
                'processedDocuments' => function ($query) use ($approuvee, $rejetee) {
                    $query->whereIn('status', [$approuvee, $rejetee]);
                }
            ])
            ->orderByDesc('processed_documents_count')
            ->take(5)
            ->get(['id', 'nom', 'prenom', 'email', 'processed_documents_count']);

        return view('admin.performances', compact(
            'totalDemandes',
            'demandesTraiteesGlobal',
            'tauxTraitementGlobal',
            'demandesEnAttenteGlobal',
            'performanceByType',
            'performanceByRegion',
            'topAgentsPerformance',
            'en_attente' // Passons aussi $en_attente à la vue pour une utilisation générique
        ));
    }


    /**
     * Affiche l'historique des activités.
     *
     * @return \Illuminate\View\View
     */
    public function viewHistory()
    {
        $activities = ActivityLog::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.history', compact('activities'));
    }

    /**
     * Affiche le formulaire de configuration des prix des documents (prix unitaires).
     *
     * @return \Illuminate\View\View
     */
    public function showSettings() // Renommée de settingsForm pour correspondre à la route
    {
        $unitPrices = [];
        foreach (DocumentType::cases() as $typeEnum) {
            $typeValue = $typeEnum->value;
            // Clé de réglage pour le prix unitaire
            $setting = DB::table('settings')->where('key', 'unit_price_' . $typeValue)->first();
            $unitPrices[$typeValue] = $setting ? (float)$setting->value : 0.00; // Par défaut à 0.00 si non trouvé
        }

        return view('admin.settings.prices', ['prices' => $unitPrices]); // Passer sous le nom 'prices'
    }

    /**
     * Enregistre les prix unitaires des documents configurés.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'prices.*' => 'required|numeric|min:0', // Valide que chaque prix est un nombre >= 0
        ]);

        try {
            foreach ($request->input('prices') as $type => $price) {
                // S'assurer que le type provient bien de l'énumération pour éviter des clés invalides
                if (in_array($type, array_column(DocumentType::cases(), 'value'))) {
                    DB::table('settings')->updateOrInsert(
                        ['key' => 'unit_price_' . $type], // Clé de réglage pour le prix unitaire
                        ['value' => $price]
                    );
                } else {
                    Log::warning("Tentative d'enregistrer un prix pour un type de document non valide: {$type}");
                }
            }

            return redirect()->back()->with('success', 'Les prix unitaires des documents ont été mis à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'enregistrement des prix unitaires des documents: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement des prix unitaires. Veuillez réessayer.');
        }
    }

    /**
     * Affiche la page de recherche.
     * @return \Illuminate\View\View
     */
    public function showSearch()
    {
        return view('admin.search');
    }

    /**
     * Affiche les notifications.
     * @return \Illuminate\View\View
     */
    public function showNotifications()
    {
        return view('admin.notifications');
    }

    /**
     * Exemple de méthode pour la gestion de la barre latérale (si gérée côté serveur).
     * @return \Illuminate\View\View
     */
    public function showToggleSidebar()
    {
        return view('admin.toggle_sidebar_view'); // Ou un simple redirect ou JSON response
    }
}
