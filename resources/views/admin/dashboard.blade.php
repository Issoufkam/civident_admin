@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div id="content" class="content">
    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn">
                <i class="bi bi-list text-primary"></i>
            </button>
            <div class="d-flex">
                <div class="nav-search position-relative d-none d-md-block">
                    <input type="text" class="form-control" placeholder="Rechercher..." aria-label="Recherche">
                    <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3" aria-hidden="true"></i>
                </div>
                <div class="ms-3 d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="position-relative" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Notifications">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item notification-item" href="#">
                                    <div class="notification-icon bg-primary">
                                        <i class="bi bi-person-plus text-white"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text mb-0">Nouvel agent créé à Abidjan</p>
                                        <small class="notification-time text-muted">Il y a 10 minutes</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item notification-item" href="#">
                                    <div class="notification-icon bg-warning">
                                        <i class="bi bi-exclamation-triangle text-white"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text mb-0">Pic de demandes à Yamoussoukro</p>
                                        <small class="notification-time text-muted">Il y a 30 minutes</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item notification-item" href="#">
                                    <div class="notification-icon bg-info">
                                        <i class="bi bi-graph-up text-white"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-text mb-0">Rapport mensuel disponible</p>
                                        <small class="notification-time text-muted">Il y a 1 heure</small>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center view-all" href="#">Voir toutes les notifications</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Titre et description -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="page-title">Tableau de Bord</h1>
            <p class="text-muted">Vue d'ensemble de l'activité nationale</p>
        </div>
    </div>

    <!-- Statistiques Globales -->
    <div class="row stats-row mb-4">
        @foreach ($cards as $stat)
            <div class="col-md-3 mb-3">
                <div class="card border-left-{{ $stat['color'] }} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-{{ $stat['color'] }} text-uppercase mb-1">
                            {{ $stat['label'] }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stat['value'] }}
                        </div>
                        <div class="mt-2 text-muted small">
                            {{ $stat['trend'] }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tableau Performance & Derniers Agents -->
    <div class="row">
        <!-- Tableau Performance -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Performance par Région</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Région</th>
                                    <th>Agents</th>
                                    <th>Demandes</th>
                                    <th>Taux de Traitement</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($regionsPerformance as $region)
                                    @php
                                        $rate = $region['rate'];
                                        $badge = $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger');
                                        $label = $rate >= 80 ? 'Excellente' : ($rate >= 60 ? 'Moyenne' : 'Faible');
                                    @endphp
                                    <tr>
                                        <td><i class="bi bi-geo-alt text-primary me-2"></i> {{ $region['name'] }}</td>
                                        <td>{{ $region['agents'] }}</td>
                                        <td>{{ number_format($region['demandes'], 0, ',', ' ') }}</td>
                                        <td>
                                            <div class="progress" style="height: 5px; width: 100px;">
                                                <div class="progress-bar bg-{{ $badge }}" role="progressbar" style="width: {{ $rate }}%" aria-valuenow="{{ $rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="small">{{ $rate }}%</span>
                                        </td>
                                        <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.regions.index') }}">Voir toutes les régions</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Derniers Agents -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Derniers Agents</h5>
                    <a href="{{ route('admin.agents.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus"></i> Nouveau
                    </a>
                </div>
                <div class="card-body p-0 flex-grow-1 overflow-auto">
                    <div class="list-group list-group-flush">
                        @forelse ($agents as $agent)
                            <div class="list-group-item border-0 d-flex align-items-center">
                                <img src="{{ $agent->profile_photo_url ?? 'https://via.placeholder.com/40' }}" class="rounded-circle" alt="Photo de {{ $agent->nom }} {{ $agent->prenom }}" width="40" height="40">
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $agent->nom }} {{ $agent->prenom }}</h6>
                                    <small class="text-muted">{{ $agent->commune->nom ?? 'Localisation inconnue' }}</small>
                                </div>
                                <div class="ms-auto">
                                    @php
                                        $status = strtolower($agent->status ?? 'actif');
                                        $badgeStatus = $status === 'actif' ? 'success' : 'warning';
                                        $statusLabel = ucfirst($status);
                                    @endphp
                                    <span class="badge bg-{{ $badgeStatus }}">{{ $statusLabel }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item">Aucun agent trouvé.</div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3 px-3">
                        {{ $agents->links() }}
                    </div>
                </div>
                <div class="text-center p-3">
                    <a href="{{ route('admin.agents.index') }}">Voir tous les agents</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Types de Documents</h5>
                </div>
                <div class="card-body">
                    <canvas id="documentTypeChart" height="300" aria-label="Graphique en doughnut des types de documents" role="img"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Demandes par Région</h5>
                </div>
                <div class="card-body">
                    <canvas id="regionalDistributionChart" height="300" aria-label="Graphique en barres des demandes par région" role="img"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tu peux rendre ces données dynamiques en passant des variables PHP au JS
        const documentData = {
            labels: ['CNI', 'Passeport', 'Permis'],
            datasets: [{
                data: [5000, 3000, 1875],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107']
            }]
        };

        const regionalData = {
            labels: ['Abidjan', 'Bouaké', 'San-Pedro'],
            datasets: [{
                label: 'Demandes',
                data: [3000, 1800, 900],
                backgroundColor: '#4361ee'
            }]
        };

        new Chart(document.getElementById('documentTypeChart'), {
            type: 'doughnut',
            data: documentData,
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        new Chart(document.getElementById('regionalDistributionChart'), {
            type: 'bar',
            data: regionalData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
