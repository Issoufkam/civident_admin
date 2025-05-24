@extends('layouts.app')

@section('content')

<div class="wrapper">
  <!-- Main Content -->
  <div class="container-fluid px-4 py-4" id="main-content">

    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-12">
        <h1 class="page-title">Tableau de Bord Super Admin</h1>
        <p class="text-muted">Vue d'ensemble de l'activité nationale</p>
      </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row stats-row mb-4">

      <!-- Agents Card -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card stats-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-muted">Total Agents</h6>
                <h2 class="stats-number">45</h2>
              </div>
              <div class="stats-icon bg-primary-subtle">
                <i class="bi bi-people text-primary"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="stats-text mt-2"><i class="bi bi-arrow-up-short text-success"></i> 12% depuis le mois dernier</p>
          </div>
        </div>
      </div>

      <!-- Demandes Card -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card stats-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-muted">Demandes Totales</h6>
                <h2 class="stats-number">2,547</h2>
              </div>
              <div class="stats-icon bg-success-subtle">
                <i class="bi bi-file-text text-success"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="stats-text mt-2"><i class="bi bi-arrow-up-short text-success"></i> 8% depuis le mois dernier</p>
          </div>
        </div>
      </div>

      <!-- Régions Card -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card stats-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-muted">Régions Actives</h6>
                <h2 class="stats-number">31</h2>
              </div>
              <div class="stats-icon bg-info-subtle">
                <i class="bi bi-geo-alt text-info"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-info" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="stats-text mt-2"><i class="bi bi-check-circle text-success"></i> Couverture nationale</p>
          </div>
        </div>
      </div>

      <!-- Taux Card -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card stats-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-muted">Taux de Traitement</h6>
                <h2 class="stats-number">92%</h2>
              </div>
              <div class="stats-icon bg-warning-subtle">
                <i class="bi bi-lightning-charge text-warning"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-warning" role="progressbar" style="width: 92%" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="stats-text mt-2"><i class="bi bi-arrow-up-short text-success"></i> 3% depuis la semaine dernière</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">

      <!-- Performance Table Column -->
      <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Performance par Région</h5>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Ce mois
              </button>
              <ul class="dropdown-menu" aria-labelledby="periodDropdown">
                <li><a class="dropdown-item" href="#">Cette semaine</a></li>
                <li><a class="dropdown-item" href="#">Ce mois</a></li>
                <li><a class="dropdown-item" href="#">Ce trimestre</a></li>
                <li><a class="dropdown-item" href="#">Cette année</a></li>
              </ul>
            </div>
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
                  <!-- Abidjan Row -->
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
                      <div class="progress" style="height: 5px; width: 100px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="small">95%</span>
                    </td>
                    <td><span class="badge bg-success">Excellent</span></td>
                  </tr>

                  <!-- Yamoussoukro Row -->
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
                      <div class="progress" style="height: 5px; width: 100px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 88%" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="small">88%</span>
                    </td>
                    <td><span class="badge bg-success">Très Bon</span></td>
                  </tr>

                  <!-- Bouaké Row -->
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
                      <div class="progress" style="height: 5px; width: 100px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="small">75%</span>
                    </td>
                    <td><span class="badge bg-warning text-dark">Bon</span></td>
                  </tr>

                  <!-- San-Pédro Row -->
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
                      <div class="progress" style="height: 5px; width: 100px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="small">70%</span>
                    </td>
                    <td><span class="badge bg-warning text-dark">Bon</span></td>
                  </tr>

                  <!-- Korhogo Row -->
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
                      <div class="progress" style="height: 5px; width: 100px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="small">60%</span>
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

      <!-- Agents List Column -->
      <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Gestion des Agents</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAgentModal">
              <i class="bi bi-person-plus"></i> Nouvel Agent
            </button>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">

              <!-- Agent 1 -->
              <div class="list-group-item border-0">
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

              <!-- Agent 2 -->
              <div class="list-group-item border-0">
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

              <!-- Agent 3 -->
              <div class="list-group-item border-0">
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

              <!-- Agent 4 -->
              <div class="list-group-item border-0">
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

    <!-- Charts Row -->
    <div class="row">

      <!-- Document Types Chart -->
      <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">Distribution par Type de Document</h5>
          </div>
          <div class="card-body">
            <canvas id="documentTypeChart" height="300"></canvas>
          </div>
        </div>
      </div>

      <!-- Regional Distribution Chart -->
      <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">Distribution Régionale des Demandes</h5>
          </div>
          <div class="card-body">
            <canvas id="regionalDistributionChart" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- New Agent Modal -->
<div class="modal fade" id="newAgentModal" tabindex="-1" aria-labelledby="newAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newAgentModalLabel">Créer un Nouvel Agent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="newAgentForm">
          <div class="mb-3">
            <label for="agentName" class="form-label">Nom complet</label>
            <input type="text" class="form-control" id="agentName" required>
          </div>
          <div class="mb-3">
            <label for="agentEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="agentEmail" required>
          </div>
          <div class="mb-3">
            <label for="agentPassword" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="agentPassword" required>
          </div>
          <div class="mb-3">
            <label for="agentRegion" class="form-label">Région</label>
            <select class="form-select" id="agentRegion" required>
              <option value="">Sélectionner une région</option>
              <option value="abidjan">Abidjan</option>
              <option value="yamoussoukro">Yamoussoukro</option>
              <option value="bouake">Bouaké</option>
              <option value="san_pedro">San-Pédro</option>
            </select>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
