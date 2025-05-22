@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div id="main-content">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="page-title">Tableau de Bord</h1>
            <p class="text-muted">Vue d'ensemble de l'activité nationale</p>
        </div>
    </div>

    <!-- Statistiques Globales -->
<div class="row stats-row mb-4">
    @foreach ($cards as $stat)
        <div class="col-md-3">
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


    <!-- Tableau + Agents -->
    <div class="row">
        <!-- Tableau Performance -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Performance par Région</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
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
                                        $badge = $region['rate'] >= 80 ? 'success' : ($region['rate'] >= 60 ? 'warning' : 'danger');
                                        $label = $region['rate'] >= 80 ? 'Excellente' : ($region['rate'] >= 60 ? 'Moyenne' : 'Faible');
                                    @endphp
                                    <tr>
                                        <td><i class="bi bi-geo-alt text-primary me-2"></i> {{ $region['name'] }}</td>
                                        <td>{{ $region['agents'] }}</td>
                                        <td>{{ number_format($region['demandes'], 0, ',', ' ') }}</td>
                                        <td>
                                            <div class="progress" style="height: 5px; width: 100px;">
                                                <div class="progress-bar bg-{{ $badge }}" style="width: {{ $region['rate'] }}%"></div>
                                            </div>
                                            <span class="small">{{ $region['rate'] }}%</span>
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
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Derniers Agents</h5>
                    <a href="{{ route('admin.agents.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus"></i> Nouveau
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                            $agents = [
                                ['name' => 'Issouf Kambiré', 'location' => 'Yopougon', 'status' => 'Actif'],
                                ['name' => 'Fatou Koné', 'location' => 'Abobo', 'status' => 'Inactif'],
                            ];
                        @endphp
                        @foreach ($agents as $agent)
                        <div class="list-group-item border-0 d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="{{ $agent['name'] }}">
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $agent['name'] }}</h6>
                                <small class="text-muted">{{ $agent['location'] }}</small>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-{{ $agent['status'] === 'Actif' ? 'success' : 'warning' }}">{{ $agent['status'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center p-3">
                        <a href="{{ route('admin.agents.index') }}">Voir tous les agents</a>
                    </div>
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
                    <canvas id="documentTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Demandes par Région</h5>
                </div>
                <div class="card-body">
                    <canvas id="regionalDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Chart(document.getElementById('documentTypeChart'), {
            type: 'doughnut',
            data: {
                labels: ['CNI', 'Passeport', 'Permis'],
                datasets: [{
                    data: [5000, 3000, 1875],
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107']
                }]
            },
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
            data: {
                labels: ['Abidjan', 'Bouaké', 'San-Pedro'],
                datasets: [{
                    label: 'Demandes',
                    data: [3000, 1800, 900],
                    backgroundColor: '#4361ee'
                }]
            },
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
