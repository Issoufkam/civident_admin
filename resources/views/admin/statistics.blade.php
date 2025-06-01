@extends('layouts.app')

@section('title', 'Statistiques Administrateur')

@section('content')
<div class="container-fluid px-4 py-4" id="main-content">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-1">Statistiques Générales</h1>
            <p class="text-muted">Analyse approfondie des données de la plateforme</p>
        </div>
    </div>

    {{-- Section des filtres --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Filtrer les Statistiques</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.statistics') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="region_id" class="form-label">Région</label>
                        <select class="form-select" id="region_id" name="region_id">
                            <option value="">Toutes les régions</option>
                            @foreach($allRegions as $region)
                                <option value="{{ $region['id'] }}" {{ request('region_id') == $region['id'] ? 'selected' : '' }}>
                                    {{ $region['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="commune_id" class="form-label">Commune</label>
                        <select class="form-select" id="commune_id" name="commune_id">
                            <option value="">Toutes les communes</option>
                            @foreach($allCommunes as $commune)
                                <option value="{{ $commune->id }}" {{ request('commune_id') == $commune->id ? 'selected' : '' }}>
                                    {{ $commune->name }} ({{ $commune->region }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                        <a href="{{ route('admin.statistics') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Section des statistiques utilisateurs --}}
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Utilisateurs</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <div class="stats-icon bg-primary-subtle">
                            <i class="bi bi-people fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Administrateurs</h6>
                            <h2 class="mb-0">{{ $totalAdmins }}</h2>
                        </div>
                        <div class="stats-icon bg-success-subtle">
                            <i class="bi bi-shield-lock fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Agents Municipaux</h6>
                            <h2 class="mb-0">{{ $totalAgents }}</h2>
                        </div>
                        <div class="stats-icon bg-warning-subtle">
                            <i class="bi bi-person-badge fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Citoyens Enregistrés</h6>
                            <h2 class="mb-0">{{ $totalCitoyens }}</h2>
                        </div>
                        <div class="stats-icon bg-info-subtle">
                            <i class="bi bi-person-circle fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section des statistiques par type d'acte civil --}}
    <div class="row mb-4">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Actes de Naissance</h6>
                            <h2 class="mb-0">{{ $naissanceCount }}</h2>
                        </div>
                        <div class="stats-icon bg-success-subtle">
                            <i class="bi bi-baby-fill fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Actes de Mariage</h6>
                            <h2 class="mb-0">{{ $mariageCount }}</h2>
                        </div>
                        <div class="stats-icon bg-info-subtle">
                            <i class="bi bi-heart-fill fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Actes de Décès</h6>
                            <h2 class="mb-0">{{ $decesCount }}</h2>
                        </div>
                        <div class="stats-icon bg-danger-subtle">
                            <i class="bi bi-cross fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Nouvelle carte pour le Taux de Décès --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Taux de Décès</h6>
                            <h2 class="mb-0">{{ $decesRate }}%</h2>
                        </div>
                        <div class="stats-icon bg-danger-subtle">
                            <i class="bi bi-percent fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Nouvelle Section des statistiques de Paiement --}}
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="h4 mb-3">Statistiques de Paiement</h3>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total des Revenus</h6>
                            <h2 class="mb-0">XOF 0.00</h2> {{-- Placeholder --}}
                        </div>
                        <div class="stats-icon bg-primary-subtle">
                            <i class="bi bi-currency-dollar fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Nombre de Paiements</h6>
                            <h2 class="mb-0">0</h2> {{-- Placeholder --}}
                        </div>
                        <div class="stats-icon bg-success-subtle">
                            <i class="bi bi-receipt fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Paiements en Attente</h6>
                            <h2 class="mb-0">0</h2> {{-- Placeholder --}}
                        </div>
                        <div class="stats-icon bg-warning-subtle">
                            <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Nouvelle Section des statistiques de Génération de Documents --}}
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="h4 mb-3">Statistiques de Génération de Documents</h3>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total PDFs Générés</h6>
                            <h2 class="mb-0">0</h2> {{-- Placeholder --}}
                        </div>
                        <div class="stats-icon bg-info-subtle">
                            <i class="bi bi-file-earmark-pdf fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">PDFs Signés Numériquement</h6>
                            <h2 class="mb-0">0</h2> {{-- Placeholder --}}
                        </div>
                        <div class="stats-icon bg-success-subtle">
                            <i class="bi bi-file-earmark-check fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section des statistiques de documents par type et statut --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Répartition des Demandes par Type et Statut</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Type de Document</th>
                                    <th>Total</th>
                                    <th>Approuvés</th>
                                    <th>Rejetés</th>
                                    <th>En Attente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documentStats as $stat)
                                    <tr>
                                        <td>{{ ucfirst($stat->type->value) }}</td>
                                        <td>{{ $stat->total ?? 0 }}</td>
                                        <td>{{ $stat->approuvees ?? 0 }}</td>
                                        <td>{{ $stat->rejetees ?? 0 }}</td>
                                        <td>{{ $stat->en_attente_count ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aucune donnée de document disponible.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Vous pouvez ajouter un <canvas> ici pour un graphique de répartition par statut --}}
                    {{-- <div class="chart-container" style="position: relative; height:40vh; width:100%">
                        <canvas id="documentStatusChart"></canvas>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Section des tendances mensuelles --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark">Courbe d'Évolution Mensuelle des Actes Civils</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:60vh; width:100%">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Configuration globale de Chart.js
    Chart.defaults.font.family = 'Nunito, -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    Chart.defaults.color = '#858796';
    Chart.defaults.aspectRatio = 2; // Ratio largeur/hauteur par défaut

    // Courbe d'évolution mensuelle
    const monthlyStats = @json($monthlyStats);
    console.log("Données mensuelles:", monthlyStats);

    if (monthlyStats && monthlyStats.length > 0) {
        const ctxMonthlyTrend = document.getElementById('monthlyTrendChart').getContext('2d');

        // Préparation des données pour la courbe
        const labels = monthlyStats.map(item => item.month_year);
        const naissanceData = monthlyStats.map(item => item.naissance_count);
        const mariageData = monthlyStats.map(item => item.mariage_count);
        const decesData = monthlyStats.map(item => item.deces_count);

        const chart = new Chart(ctxMonthlyTrend, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Naissances',
                        data: naissanceData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Mariages',
                        data: mariageData,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Décès',
                        data: decesData,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Désactive le ratio fixe
                layout: {
                    padding: {
                        top: 20,
                        right: 20,
                        bottom: 20,
                        left: 20
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            parser: 'YYYY-MM',
                            unit: 'month',
                            displayFormats: {
                                month: 'MMM'
                            },
                            tooltipFormat: 'MMMM'
                        },
                        title: {
                            display: true,
                            text: 'Période',
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: true
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 12
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: "Nombre d'actes",
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        },
                        ticks: {
                            precision: 0,
                            padding: 10
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: true
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            },
                            boxWidth: 12,
                            boxHeight: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 10,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                elements: {
                    line: {
                        cubicInterpolationMode: 'monotone'
                    },
                    point: {
                        hoverRadius: 6,
                        hoverBorderWidth: 2
                    }
                }
            }
        });

        // Fonction de redimensionnement responsive
        function resizeChart() {
            const container = document.querySelector('.chart-container');
            const canvas = document.getElementById('monthlyTrendChart');

            // Ajuste la hauteur en fonction de la largeur
            const aspectRatio = 0.6; // Ratio personnalisable
            const newHeight = container.offsetWidth * aspectRatio;
            container.style.height = `${newHeight}px`;

            chart.resize();
        }

        // Écouteur de redimensionnement
        window.addEventListener('resize', resizeChart);
        resizeChart(); // Initialisation

    } else {
        console.warn("Aucune donnée disponible pour la courbe d'évolution");
        document.querySelector('.chart-container').innerHTML =
            '<div class="alert alert-warning text-center">Aucune donnée disponible pour afficher la courbe d\'évolution</div>';
    }

    // ... (le reste de votre code JavaScript existant pour les autres graphiques) ...
});
</script>
@endpush

@endsection
