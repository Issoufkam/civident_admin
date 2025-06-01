@extends('layouts.app')

@section('title', 'Tableau de Bord Super Admin')

@section('content')

<div class="container-fluid px-4 py-4" id="main-content">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-1">Tableau de Bord Super Admin</h1>
            <p class="text-muted">Vue d'ensemble de l'activité nationale</p>
        </div>
    </div>

    <div class="row mb-4">
        @foreach($cards as $card)
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">{{ $card['label'] }}</h6>
                                <h2 class="mb-0">{{ $card['value'] }}</h2>
                            </div>
                            <div class="stats-icon bg-{{ $card['color'] }}-subtle">
                                @if($card['label'] == 'Agents enregistrés')
                                    <i class="bi bi-people fs-4 text-{{ $card['color'] }}"></i>
                                @elseif($card['label'] == 'Documents déposés')
                                    <i class="bi bi-file-text fs-4 text-{{ $card['color'] }}"></i>
                                @elseif($card['label'] == 'Régions actives')
                                    <i class="bi bi-geo-alt fs-4 text-{{ $card['color'] }}"></i>
                                @elseif($card['label'] == 'Citoyens enregistrés')
                                    <i class="bi bi-person-circle fs-4 text-{{ $card['color'] }}"></i>
                                @endif
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-{{ $card['color'] }}" style="width: {{ preg_replace('/[^0-9]/', '', $card['trend']) }}%"></div>
                        </div>
                        <p class="mb-0 mt-2 small">
                            @if(str_contains($card['trend'], '+'))
                                <i class="bi bi-arrow-up-short text-success"></i>
                            @elseif(str_contains($card['trend'], '-'))
                                <i class="bi bi-arrow-down-short text-danger"></i>
                            @else
                                <i class="bi bi-info-circle text-muted"></i>
                            @endif
                            {{ $card['trend'] }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Performance par Région</h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Ce mois
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item">Cette semaine</button></li>
                            <li><button class="dropdown-item">Ce mois</button></li>
                            <li><button class="dropdown-item">Ce trimestre</button></li>
                            <li><button class="dropdown-item">Cette année</button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Région</th>
                                    <th>Agents</th> {{-- Ajout de la colonne Agents --}}
                                    <th>Demandes Reçues</th>
                                    <th>Demandes Traitées</th>
                                    <th>Taux de Traitement</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($regionsPerformance as $region)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                                <span>{{ $region['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $region['agents_count'] }}</td> {{-- Affichage du nombre d'agents --}}
                                        <td>{{ $region['demandes_recues'] }}</td>
                                        <td>{{ $region['demandes_traitees'] }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress" style="width: 100px; height: 6px;">
                                                    @php
                                                        $progressBarColor = 'danger';
                                                        if ($region['rate'] >= 90) $progressBarColor = 'success';
                                                        else if ($region['rate'] >= 70) $progressBarColor = 'warning';
                                                    @endphp
                                                    <div class="progress-bar bg-{{ $progressBarColor }}" style="width: {{ $region['rate'] }}%"></div>
                                                </div>
                                                <span class="small">{{ $region['rate'] }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($region['rate'] >= 90)
                                                <span class="badge bg-success">Excellent</span>
                                            @elseif($region['rate'] >= 70)
                                                <span class="badge bg-warning text-dark">Bon</span>
                                            @else
                                                <span class="badge bg-danger">À améliorer</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.regions.index') }}" class="view-all">Voir toutes les régions</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Gestion des Agents</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAgentModal">
                        <i class="bi bi-person-plus"></i> Nouvel Agent
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($latestAgents as $agent)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($agent->nom . ' ' . $agent->prenom) }}&background=random&color=fff&size=40"
                                            alt="{{ $agent->prenom }} {{ $agent->nom }}" class="rounded-circle" width="40" height="40">
                                    <div class="ms-3">
                                        <h6 class="mb-0">{{ $agent->prenom }} {{ $agent->nom }}</h6>
                                        <small class="text-muted">{{ $agent->commune ? $agent->commune->name . ' - ' . $agent->commune->region : 'N/A' }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        {{-- Vous pourriez avoir un champ 'is_active' ou 'status' pour les agents --}}
                                        <span class="badge bg-success">Actif</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                Aucun agent n'a encore été enregistré.
                            </div>
                        @endforelse
                    </div>
                    <div class="text-center p-3">
                        <a href="{{ route('admin.agents.index') }}" class="view-all">Voir tous les agents</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Distribution par Type de Document</h5>
                </div>
                <div class="card-body">
                    <canvas id="documentTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Distribution Régionale des Demandes</h5>
                </div>
                <div class="card-body">
                    <canvas id="regionalDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- New Agent Modal (optionnel, si vous maintenez la fonctionnalité modale) --}}
<div class="modal fade" id="newAgentModal" tabindex="-1" aria-labelledby="newAgentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newAgentModalLabel">Ajouter un Nouvel Agent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Formulaire d'ajout d'agent ici.</p>
                {{-- Vous incluriez normalement un formulaire ici, par exemple @include('admin.users.partials.create_agent_form') --}}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Données passées depuis le contrôleur
        const documentTypeLabels = @json($documentStats->pluck('type'));
        const documentTypeData = @json($documentStats->pluck('total'));
        const regionalDistributionLabels = @json($regionsPerformance->pluck('name'));
        const regionalDistributionData = @json($regionsPerformance->pluck('demandes_recues'));

        const documentData = {
            labels: documentTypeLabels,
            datasets: [{
                data: documentTypeData,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997'] // Ajoutez d'autres couleurs si nécessaire
            }]
        };

        const regionalData = {
            labels: regionalDistributionLabels,
            datasets: [{
                label: 'Demandes',
                data: regionalDistributionData,
                backgroundColor: '#4361ee'
            }]
        };

        new Chart(document.getElementById('documentTypeChart'), {
            type: 'doughnut',
            data: documentData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString() + ' demandes';
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('regionalDistributionChart'), {
            type: 'bar',
            data: regionalData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre de demandes'
                        },
                        ticks: {
                            // Assurez-vous d'avoir des ticks entiers
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Région'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
