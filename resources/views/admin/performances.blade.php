
@extends('layouts.app')

@section('title', 'Performances du Système')

@section('content')

<div class="container-fluid px-4 py-4" id="main-content">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-1">Performances du Système</h1>
            <p class="text-muted">Suivi détaillé du traitement des demandes</p>
        </div>
    </div>

    {{-- Section des indicateurs de performance globaux --}}
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Demandes</h6>
                            <h2 class="mb-0">{{ $totalDemandes }}</h2>
                        </div>
                        <div class="stats-icon bg-primary-subtle">
                            <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
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
                            <h6 class="text-muted">Demandes Traitées</h6>
                            <h2 class="mb-0">{{ $demandesTraiteesGlobal }}</h2>
                        </div>
                        <div class="stats-icon bg-success-subtle">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
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
                            <h6 class="text-muted">Taux de Traitement Global</h6>
                            <h2 class="mb-0">{{ $tauxTraitementGlobal }}%</h2>
                        </div>
                        <div class="stats-icon bg-info-subtle">
                            <i class="bi bi-percent fs-4 text-info"></i>
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
                            <h6 class="text-muted">Demandes En Attente</h6>
                            <h2 class="mb-0">{{ $demandesEnAttenteGlobal }}</h2>
                        </div>
                        <div class="stats-icon bg-warning-subtle">
                            <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Performance par Type de Document --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Performance par Type de Document</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Type de Document</th>
                                    <th>Total Demandes</th>
                                    <th>Approuvées</th>
                                    <th>Rejetées</th>
                                    <th>Taux de Traitement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($performanceByType as $item)
                                    <tr>
                                        {{-- Correction ici: Accédez à la valeur de l'Enum avec ->value --}}
                                        <td>{{ ucfirst($item->type->value) }}</td>
                                        <td>{{ $item->total_demandes }}</td>
                                        <td>{{ $item->approuvees }}</td>
                                        <td>{{ $item->rejetees }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress" style="width: 100px; height: 6px;">
                                                    @php
                                                        $progressBarColor = 'danger';
                                                        if ($item->taux_traitement >= 90) $progressBarColor = 'success';
                                                        else if ($item->taux_traitement >= 70) $progressBarColor = 'warning';
                                                    @endphp
                                                    <div class="progress-bar bg-{{ $progressBarColor }}" style="width: {{ $item->taux_traitement }}%"></div>
                                                </div>
                                                <span class="small">{{ $item->taux_traitement }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aucune donnée de performance par type de document.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <canvas id="performanceByTypeChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Performance par Région --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Performance par Région</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Région</th>
                                    <th>Agents</th>
                                    <th>Total Demandes</th>
                                    <th>Traitées</th>
                                    <th>En Attente</th>
                                    <th>Taux Traitement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($performanceByRegion as $region)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                                <span>{{ $region['region'] }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $region['agents_count'] }}</td>
                                        <td>{{ $region['total_demandes'] }}</td>
                                        <td>{{ $region['demandes_traitees'] }}</td>
                                        <td>{{ $region['demandes_en_attente'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress" style="width: 100px; height: 6px;">
                                                    @php
                                                        $progressBarColor = 'danger';
                                                        if ($region['taux_traitement'] >= 90) $progressBarColor = 'success';
                                                        else if ($region['taux_traitement'] >= 70) $progressBarColor = 'warning';
                                                    @endphp
                                                    <div class="progress-bar bg-{{ $progressBarColor }}" style="width: {{ $region['taux_traitement'] }}%"></div>
                                                </div>
                                                <span class="small">{{ $region['taux_traitement'] }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Aucune donnée de performance par région.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <canvas id="performanceByRegionChart" height="150"></canvas>
                </div>
            </div>
        </div>

        {{-- Top 5 Agents Performants --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Top 5 Agents Performants</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topAgentsPerformance as $agent)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($agent->prenom . ' ' . $agent->nom) }}&background=random&color=fff&size=40"
                                            alt="{{ $agent->prenom }} {{ $agent->nom }}" class="rounded-circle" width="40" height="40">
                                    <div class="ms-3">
                                        <h6 class="mb-0">{{ $agent->prenom }} {{ $agent->nom }}</h6>
                                        <small class="text-muted">{{ $agent->email }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-primary">{{ $agent->processed_documents_count }} traités</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                Aucun agent n'a encore traité de documents.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data for "Performance by Type of Document" Chart
        const performanceByType = @json($performanceByType);
        // Correction ici: Accédez à la valeur de l'Enum avec .value
        const typeLabels = performanceByType.map(item => item.type.value.charAt(0).toUpperCase() + item.type.value.slice(1));
        const typeTotalData = performanceByType.map(item => item.total_demandes);
        const typeApprovedData = performanceByType.map(item => item.approuvees);
        const typeRejectedData = performanceByType.map(item => item.rejetees);

        new Chart(document.getElementById('performanceByTypeChart'), {
            type: 'bar', // Or 'doughnut' if you prefer for overall distribution
            data: {
                labels: typeLabels,
                datasets: [
                    {
                        label: 'Total Demandes',
                        data: typeTotalData,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)', // Primary
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Approuvées',
                        data: typeApprovedData,
                        backgroundColor: 'rgba(25, 135, 84, 0.7)', // Success
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Rejetées',
                        data: typeRejectedData,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)', // Danger
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: false,
                        title: { display: true, text: 'Type de Document' }
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true,
                        title: { display: true, text: 'Nombre de Demandes' },
                        ticks: { callback: function(value) { if (Number.isInteger(value)) { return value; } } }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                }
            }
        });

        // Data for "Performance by Region" Chart
        const performanceByRegion = @json($performanceByRegion);
        const regionLabels = performanceByRegion.map(item => item.region);
        const regionTotalDemandes = performanceByRegion.map(item => item.total_demandes);
        const regionTauxTraitement = performanceByRegion.map(item => item.taux_traitement);

        new Chart(document.getElementById('performanceByRegionChart'), {
            type: 'bar',
            data: {
                labels: regionLabels,
                datasets: [
                    {
                        label: 'Total Demandes',
                        data: regionTotalDemandes,
                        backgroundColor: 'rgba(108, 117, 125, 0.7)', // Secondary
                        borderColor: 'rgba(108, 117, 125, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Taux de Traitement (%)',
                        data: regionTauxTraitement,
                        backgroundColor: 'rgba(23, 162, 184, 0.7)', // Info
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1,
                        yAxisID: 'y1' // Assign to a second Y-axis
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: { display: true, text: 'Région' }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Nombre de Demandes' },
                        ticks: { callback: function(value) { if (Number.isInteger(value)) { return value; } } }
                    },
                    y1: { // Second Y-axis for percentage
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        max: 100,
                        grid: { drawOnChartArea: false }, // Only draw grid lines for the first Y axis
                        title: { display: true, text: 'Taux (%)' },
                        ticks: { callback: function(value) { return value + '%'; } }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                }
            }
        });
    });
</script>
@endpush

@endsection
