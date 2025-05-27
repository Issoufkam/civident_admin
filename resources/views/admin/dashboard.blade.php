@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')

<div class="container-fluid px-4 py-4" id="main-content">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2 mb-1">Tableau de Bord Super Admin</h1>
                    <p class="text-muted">Vue d'ensemble de l'activité nationale</p>
                </div>
            </div>

            <!-- Statistiques Globales -->
            <div class="row mb-4">
                <!-- Agents Card -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card stats-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Total Agents</h6>
                                    <h2 class="mb-0">45</h2>
                                </div>
                                <div class="stats-icon bg-primary-subtle">
                                    <i class="bi bi-people fs-4 text-primary"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 75%"></div>
                            </div>
                            <p class="mb-0 mt-2 small">
                                <i class="bi bi-arrow-up-short text-success"></i>
                                12% depuis le mois dernier
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Demandes Card -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card stats-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Demandes Totales</h6>
                                    <h2 class="mb-0">2,547</h2>
                                </div>
                                <div class="stats-icon bg-success-subtle">
                                    <i class="bi bi-file-text fs-4 text-success"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 85%"></div>
                            </div>
                            <p class="mb-0 mt-2 small">
                                <i class="bi bi-arrow-up-short text-success"></i>
                                8% depuis le mois dernier
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Régions Card -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card stats-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Régions Actives</h6>
                                    <h2 class="mb-0">31</h2>
                                </div>
                                <div class="stats-icon bg-info-subtle">
                                    <i class="bi bi-geo-alt fs-4 text-info"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: 95%"></div>
                            </div>
                            <p class="mb-0 mt-2 small">
                                <i class="bi bi-check-circle text-success"></i>
                                Couverture nationale
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Taux Card -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card stats-card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Taux de Traitement</h6>
                                    <h2 class="mb-0">92%</h2>
                                </div>
                                <div class="stats-icon bg-warning-subtle">
                                    <i class="bi bi-lightning-charge fs-4 text-warning"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 92%"></div>
                            </div>
                            <p class="mb-0 mt-2 small">
                                <i class="bi bi-arrow-up-short text-success"></i>
                                3% depuis la semaine dernière
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau Performance & Derniers Agents -->
            <div class="row">
                <!-- Tableau Performance -->
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
                                            <th>Agents</th>
                                            <th>Demandes</th>
                                            <th>Taux de Traitement</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <span>Abidjan</span>
                                                </div>
                                            </td>
                                            <td>12</td>
                                            <td>856</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress" style="width: 100px; height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: 95%"></div>
                                                    </div>
                                                    <span class="small">95%</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-success">Excellent</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <span>Yamoussoukro</span>
                                                </div>
                                            </td>
                                            <td>8</td>
                                            <td>425</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress" style="width: 100px; height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: 88%"></div>
                                                    </div>
                                                    <span class="small">88%</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-success">Très Bon</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <span>Bouaké</span>
                                                </div>
                                            </td>
                                            <td>6</td>
                                            <td>312</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress" style="width: 100px; height: 6px;">
                                                        <div class="progress-bar bg-warning" style="width: 75%"></div>
                                                    </div>
                                                    <span class="small">75%</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning text-dark">Bon</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <span>San-Pédro</span>
                                                </div>
                                            </td>
                                            <td>5</td>
                                            <td>245</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress" style="width: 100px; height: 6px;">
                                                        <div class="progress-bar bg-warning" style="width: 70%"></div>
                                                    </div>
                                                    <span class="small">70%</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning text-dark">Bon</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                                    <span>Korhogo</span>
                                                </div>
                                            </td>
                                            <td>4</td>
                                            <td>198</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress" style="width: 100px; height: 6px;">
                                                        <div class="progress-bar bg-danger" style="width: 60%"></div>
                                                    </div>
                                                    <span class="small">60%</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-danger">À améliorer</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="#" class="view-all">Voir toutes les régions</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Derniers Agents -->
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
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100"
                                             alt="Agent" class="rounded-circle" width="40" height="40">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Kouamé Aya</h6>
                                            <small class="text-muted">Abidjan - Plateau</small>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-success">Actif</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100"
                                             alt="Agent" class="rounded-circle" width="40" height="40">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Traoré Ibrahim</h6>
                                            <small class="text-muted">Yamoussoukro - Centre</small>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-success">Actif</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100"
                                             alt="Agent" class="rounded-circle" width="40" height="40">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Koffi Marc</h6>
                                            <small class="text-muted">Bouaké - Nord</small>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-warning text-dark">En pause</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100"
                                             alt="Agent" class="rounded-circle" width="40" height="40">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Bamba Fatou</h6>
                                            <small class="text-muted">San-Pédro - Ouest</small>
                                        </div>
                                        <div class="ms-auto">
                                            <span class="badge bg-success">Actif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center p-3">
                                <a href="#" class="view-all">Voir tous les agents</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
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
