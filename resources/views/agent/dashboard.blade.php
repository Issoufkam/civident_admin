@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Tableau de Bord</h1>
            <p class="text-muted">Aperçu des demandes de documents</p>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row stats-row">
        <!-- Carte Demandes Totales -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Demandes Totales</h6>
                            <h2 class="stats-number">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="stats-icon bg-primary-subtle">
                            <i class="bi bi-file-earmark-text text-primary"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-primary" role="progressbar"
                             style="width: {{ min(100, ($stats['total'] / ($stats['total_last_month'] ?: 1)) * 100) }}%"
                             aria-valuenow="{{ $stats['total'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total_last_month'] * 2 }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['total_change'] > 0)
                            <i class="bi bi-arrow-up-short text-success"></i>
                        @elseif($stats['total_change'] < 0)
                            <i class="bi bi-arrow-down-short text-danger"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['total_change']) }}% depuis le mois dernier
                    </p>
                </div>
            </div>
        </div>

        <!-- Carte Demandes En Attente -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">En Attente</h6>
                            <h2 class="stats-number">{{ $stats['en_attente'] }}</h2>
                        </div>
                        <div class="stats-icon bg-warning-subtle">
                            <i class="bi bi-hourglass-split text-warning"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                             style="width: {{ $stats['total'] ? ($stats['en_attente'] / $stats['total']) * 100 : 0 }}%"
                             aria-valuenow="{{ $stats['en_attente'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total'] }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['pending_change'] > 0)
                            <i class="bi bi-arrow-up-short text-danger"></i>
                        @elseif($stats['pending_change'] < 0)
                            <i class="bi bi-arrow-down-short text-success"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['pending_change']) }}% depuis la semaine dernière
                    </p>
                </div>
            </div>
        </div>

        <!-- Carte Demandes Approuvées -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Approuvés</h6>
                            <h2 class="stats-number">{{ $stats['approuves'] }}</h2>
                        </div>
                        <div class="stats-icon bg-success-subtle">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $stats['total'] ? ($stats['approuves'] / $stats['total']) * 100 : 0 }}%"
                             aria-valuenow="{{ $stats['approuves'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total'] }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['approved_change'] > 0)
                            <i class="bi bi-arrow-up-short text-success"></i>
                        @elseif($stats['approved_change'] < 0)
                            <i class="bi bi-arrow-down-short text-danger"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['approved_change']) }}% depuis le mois dernier
                    </p>
                </div>
            </div>
        </div>

        <!-- Carte Demandes Rejetées -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Rejetés</h6>
                            <h2 class="stats-number">{{ $stats['rejetes'] }}</h2>
                        </div>
                        <div class="stats-icon bg-danger-subtle">
                            <i class="bi bi-x-circle text-danger"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-danger" role="progressbar"
                             style="width: {{ $stats['total'] ? ($stats['rejetes'] / $stats['total']) * 100 : 0 }}%"
                             aria-valuenow="{{ $stats['rejetes'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total'] }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['rejected_change'] < 0)
                            <i class="bi bi-arrow-down-short text-success"></i>
                        @elseif($stats['rejected_change'] > 0)
                            <i class="bi bi-arrow-up-short text-danger"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['rejected_change']) }}% depuis le mois dernier
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section principale -->
    <div class="row">
        <!-- Tableau des demandes récentes -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Demandes Récentes</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                type="button"
                                id="requestsFilterDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                aria-haspopup="true">
                            {{ $currentFilter === 'all' ? 'Tous les types' : ($documentTypes[$currentFilter] ?? ucfirst($currentFilter)) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="requestsFilterDropdown"
                            data-bs-popper="static">
                            <li>
                                <a class="dropdown-item filter-item {{ $currentFilter === 'all' ? 'active fw-bold' : '' }}"
                                href="{{ route('agent.documents.index', array_merge(request()->query(), ['filter' => 'all'])) }}"
                                data-filter="all">
                                    <i class="bi bi-list-ul me-2"></i> Tous les types
                                    @if($currentFilter === 'all')
                                        <i class="bi bi-check2 ms-2"></i>
                                    @endif
                                </a>
                            </li>
                            @foreach($documentTypes as $type => $label)
                            <li>
                                <a class="dropdown-item filter-item {{ $currentFilter === $type ? 'active fw-bold' : '' }}"
                                href="{{ route('agent.documents.index', array_merge(request()->query(), ['filter' => $type])) }}"
                                data-filter="{{ $type }}">
                                    <i class="bi bi-{{ $type === 'naissance' ? 'person-vcard' : ($type === 'mariage' ? 'heart-fill' : 'flower1') }} me-2"></i>
                                    {{ $label }}
                                    @if($currentFilter === $type)
                                        <i class="bi bi-check2 ms-2"></i>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="requests-table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Référence</th>
                                    <th scope="col">Citoyen</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDemandes as $demande)
                                @if($demande->commune_id == auth()->user()->commune_id)
                                <tr>
                                    <td>{{ $demande->registry_number }}</td>
                                    <td>{{ $demande->user->nom }} {{ $demande->user->prenom }}</td>
                                    <td>
                                        <span class="badge
                                            @if($demande->type == 'naissance') bg-primary
                                            @elseif($demande->type == 'mariage') bg-success
                                            @else bg-secondary @endif">
                                            {{ ucfirst($demande->type->value) }}
                                        </span>
                                    </td>
                                    <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge
                                            @if($demande->status == 'en_attente') bg-warning
                                            @elseif($demande->status == 'approuvee') bg-success
                                            @else bg-danger @endif">
                                            {{ ucfirst($demande->status->value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('agent.documents.show', $demande) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-0 bg-white text-center">
                    <a href="{{ route('agent.documents.index') }}" class="view-all">Voir toutes les demandes</a>
                </div>
            </div>
        </div>

        <!-- Graphique de distribution -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Distribution par Type</h5>
                </div>
                <div class="card-body">
                    <canvas id="documentTypeChart" height="250"></canvas>
                    <div class="mt-3 text-center small">
                        @php
                            $colors = ['text-primary', 'text-success', 'text-secondary', 'text-danger', 'text-warning'];
                            $i = 0;
                        @endphp

                        @foreach ($documentTypes as $key => $label)
                            <span class="me-2">
                                <i class="bi bi-circle-fill {{ $colors[$i % count($colors)] }}"></i>
                                {{ ucfirst($label) }} ({{ $stats[$key . 's'] ?? 0 }})
                            </span>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section inférieure -->
    <div class="row">
        <!-- Graphique d'activité mensuelle -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Activité Mensuelle</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyActivityChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Demandes urgentes -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Demandes Urgentes</h5>
                    <span class="badge bg-danger rounded-pill">{{ count($urgentDemandes) }} en attente</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush urgent-requests">
                        @foreach($urgentDemandes as $demande)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#{{ $demande->registry_number }}</strong>
                                <div class="text-muted small">{{ $demande->user->nom }} - {{ ucfirst($demande->type) }}</div>
                                <small class="text-danger">En attente depuis {{ $demande->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('admin.documents.show', $demande->id) }}" class="btn btn-sm btn-outline-primary">
                                Traiter
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique de distribution par type
    const typeCtx = document.getElementById('documentTypeChart').getContext('2d');
    const typeChart = new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach ($documentTypes as $key => $label)
                    "{{ $label }}",
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach ($documentTypes as $key => $label)
                        {{ $stats[$key . 's'] ?? 0 }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',   // Naissance
                    'rgba(75, 192, 192, 0.7)',   // Mariage
                    'rgba(153, 102, 255, 0.7)'   // Décès
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

    // Graphique d'activité mensuelle
    const monthlyCtx = document.getElementById('monthlyActivityChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Demandes',
                data: @json($monthlyData),
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: '#fff',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Filtrage des demandes
    document.querySelectorAll('.filter-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            window.location.href = "{{ route('agent.dashboard') }}?filter=" + filter;
        });
    });
});
</script>
@endpush
