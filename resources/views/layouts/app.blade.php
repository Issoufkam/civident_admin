<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin - Gestion des Mairies')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/Admin.css') }}">
    @stack('styles')
<style>
    /* Main Styles for the Super Admin Dashboard */
:root {
  --primary: #0056b3;
  --primary-light: #e6f0fa;
  --secondary: #2a9d8f;
  --secondary-light: #e8f5f3;
  --accent: #e9c46a;
  --accent-light: #faf5e6;
  --success: #28a745;
  --success-light: #e9f7ed;
  --warning: #ffc107;
  --warning-light: #fff9e6;
  --danger: #dc3545;
  --danger-light: #fbecee;
  --info: #17a2b8;
  --info-light: #e8f6f8;
  --gray-100: #f8f9fa;
  --gray-200: #e9ecef;
  --gray-300: #dee2e6;
  --gray-400: #ced4da;
  --gray-500: #adb5bd;
  --gray-600: #6c757d;
  --gray-700: #495057;
  --gray-800: #343a40;
  --gray-900: #212529;
  --transition-speed: 0.3s;
  --border-radius: 0.5rem;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f7fa;
  color: var(--gray-700);
  overflow-x: hidden;
}

/* Wrapper */
.wrapper {
  display: flex;
  align-items: stretch;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  min-width:  250px;
  max-width: 250px;
  background-color: white;
  transition: all var(--transition-speed);
  z-index: 999;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
}

.sidebar.active {
  margin-left: -250px;
}

.sidebar-header {
  padding: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid var(--gray-200);
}

.sidebar-header h3 {
  margin: 0;
  color: var(--primary);
  font-weight: 600;
}

.sidebar-brand-icon {
  background-color: var(--primary-light);
  color: var(--primary);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar-user {
  padding: 1.5rem;
  display: flex;
  align-items: center;
  border-bottom: 1px solid var(--gray-200);
}

.user-avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 10px;
  border: 2px solid var(--primary-light);
}

.user-info h5 {
  margin: 0;
  font-size: 0.95rem;
  font-weight: 600;
}

.user-info span {
  font-size: 0.8rem;
  color: var(--gray-600);
}

.sidebar .components {
  padding: 1rem 0;
  flex-grow: 1;
}

.sidebar ul li a {
  padding: 0.8rem 1.5rem;
  display: flex;
  align-items: center;
  color: var(--gray-700);
  text-decoration: none;
  transition: all var(--transition-speed);
  font-size: 0.95rem;
}

.sidebar ul li a i {
  margin-right: 10px;
  font-size: 1.1rem;
}

.sidebar ul li a:hover {
  color: var(--primary);
  background-color: var(--primary-light);
}

.sidebar ul li.active > a {
  color: var(--primary);
  background-color: var(--primary-light);
  font-weight: 500;
}

.sidebar-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--gray-200);
}

.sidebar-footer a {
  color: var(--gray-700);
  text-decoration: none;
  display: flex;
  align-items: center;
  transition: color var(--transition-speed);
  font-size: 0.95rem;
}

.sidebar-footer a i {
  margin-right: 10px;
}

.sidebar-footer a:hover {
  color: var(--danger);
}

/* Content */
#content {
  width: 100%;
  min-height: 100vh;
  transition: all var(--transition-speed);
  display: flex;
  flex-direction: column;
}

/* Navbar */
.navbar {
  padding: 1rem 1.5rem;
}

#sidebarCollapse {
  background: transparent;
  border: none;
}

.nav-search {
  width: 300px;
}

.nav-search .form-control {
  padding-right: 40px;
  border-radius: 20px;
  border: 1px solid var(--gray-300);
  background-color: var(--gray-100);
}

.notification-dropdown {
  width: 300px;
  padding: 0;
  border: none;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.notification-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid var(--gray-200);
}

.notification-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.75rem;
  flex-shrink: 0;
}

.notification-content {
  flex-grow: 1;
}

.notification-text {
  margin-bottom: 0.25rem;
  font-size: 0.9rem;
}

.notification-time {
  margin: 0;
  font-size: 0.8rem;
  color: var(--gray-600);
}

/* Main Content */
#main-content {
  padding: 1.5rem;
  flex-grow: 1;
}

.page-title {
  font-weight: 600;
  color: var(--gray-800);
  margin-bottom: 0.5rem;
}

/* Stats Cards */
.stats-card {
  border-radius: var(--border-radius);
  transition: transform 0.3s;
}

.stats-card:hover {
  transform: translateY(-5px);
}

.stats-number {
  font-weight: 600;
  margin-bottom: 0;
  color: var(--gray-800);
}

.stats-text {
  font-size: 0.8rem;
  color: var(--gray-600);
  margin-bottom: 0;
}

.stats-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}

/* Cards */
.card {
  border-radius: var(--border-radius);
  margin-bottom: 1.5rem;
}

.card-header {
  padding: 1rem 1.25rem;
}

.card-title {
  font-weight: 600;
  color: var(--gray-800);
}

/* Tables */
.table {
  margin-bottom: 0;
}

.table th {
  font-weight: 600;
  color: var(--gray-700);
  font-size: 0.9rem;
}

.table td {
  vertical-align: middle;
}

/* Status Badges */
.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.8rem;
}

/* Buttons */
.btn {
  border-radius: 0.375rem;
  padding: 0.5rem 1rem;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-primary {
  background-color: var(--primary);
  border-color: var(--primary);
}

.btn-primary:hover {
  background-color: #004494;
  border-color: #004494;
}

/* Links */
.view-all {
  color: var(--primary);
  font-weight: 500;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.2s;
}

.view-all:hover {
  color: #004494;
  text-decoration: underline;
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

/* Responsive */
@media (max-width: 992px) {
  .sidebar {
    margin-left: -250px;
    position: fixed;
    height: 100%;
  }
  
  .sidebar.active {
    margin-left: 0;
  }
  
  #content {
    width: 100%;
  }
  
  #sidebarCollapse {
    display: block;
  }
  
  .stats-row {
    flex-direction: column;
  }
  
  .nav-search {
    width: 100%;
  }
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: var(--gray-400);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--gray-500);
}
</style>

</head>
<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
      <div class="sidebar-header">
        <h3>Administration</h3>
        <div class="sidebar-brand-icon">
          <i class="bi bi-shield-lock"></i>
        </div>
      </div>
      <div class="sidebar-user">
        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Admin Photo" class="user-avatar">
        <div class="user-info">
          <h5>Jean Kouassi</h5>
          <span>Super Administrateur</span>
        </div>
      </div>
      <ul class="list-unstyled components">
        <li class="active">
          <a href="#" id="dashboard-link">
            <i class="bi bi-speedometer2"></i>
            Tableau de Bord
          </a>
        </li>
        <li>
          <a href="{{ route('admin.agents.index') }}" id="agents-link">
            <i class="bi bi-people"></i>
            Agents
            <span class="badge rounded-pill bg-primary ms-2">45</span>
          </a>
        </li>
        <li>
          <a href="#" id="regions-link">
            <i class="bi bi-geo-alt"></i>
            Régions
          </a>
        </li>
        <li>
          <a href="#" id="statistics-link">
            <i class="bi bi-graph-up"></i>
            Statistiques
          </a>
        </li>
        <li>
          <a href="#" id="settings-link">
            <i class="bi bi-gear"></i>
            Paramètres
          </a>
        </li>
      </ul>
      <div class="sidebar-footer">
        <a href="#" id="logout-link">
          <i class="bi bi-box-arrow-left"></i> Déconnexion
        </a>
      </div>
    </nav>

    <!-- Page Content -->
    <div id="content">
      <!-- Topbar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
          <button type="button" id="sidebarCollapse" class="btn">
            <i class="bi bi-list text-primary"></i>
          </button>
          <div class="d-flex">
            <div class="nav-search position-relative d-none d-md-block">
              <input type="text" class="form-control" placeholder="Rechercher...">
              <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </div>
            <div class="ms-3 d-flex align-items-center">
              <div class="dropdown">
                <a href="#" class="position-relative" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-bell fs-5"></i>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                  <li><h6 class="dropdown-header">Notifications</h6></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-primary">
                      <i class="bi bi-person-plus text-white"></i>
                    </div>
                    <div class="notification-content">
                      <p class="notification-text">Nouvel agent créé à Abidjan</p>
                      <p class="notification-time">Il y a 10 minutes</p>
                    </div>
                  </a></li>
                  <li><a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-warning">
                      <i class="bi bi-exclamation-triangle text-white"></i>
                    </div>
                    <div class="notification-content">
                      <p class="notification-text">Pic de demandes à Yamoussoukro</p>
                      <p class="notification-time">Il y a 30 minutes</p>
                    </div>
                  </a></li>
                  <li><a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-info">
                      <i class="bi bi-graph-up text-white"></i>
                    </div>
                    <div class="notification-content">
                      <p class="notification-text">Rapport mensuel disponible</p>
                      <p class="notification-time">Il y a 1 heure</p>
                    </div>
                  </a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-center view-all" href="#">Voir toutes les notifications</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <div class="container-fluid px-4 py-4" id="main-content">
        <!-- Dashboard Content -->
        <div class="row">
          <div class="col-12">
            <h1 class="page-title">Tableau de Bord Super Admin</h1>
            <p class="text-muted">Vue d'ensemble de l'activité nationale</p>
          </div>
        </div>

        <!-- National Statistics -->
        <div class="row stats-row">
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

        <!-- Regional Performance and Agent Management -->
        <div class="row">
          <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
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
                        <td>
                          <span class="badge bg-success">Excellent</span>
                        </td>
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
                          <div class="progress" style="height: 5px; width: 100px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 88%" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <span class="small">88%</span>
                        </td>
                        <td>
                          <span class="badge bg-success">Très Bon</span>
                        </td>
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
                          <div class="progress" style="height: 5px; width: 100px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <span class="small">75%</span>
                        </td>
                        <td>
                          <span class="badge bg-warning text-dark">Bon</span>
                        </td>
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
                          <div class="progress" style="height: 5px; width: 100px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <span class="small">70%</span>
                        </td>
                        <td>
                          <span class="badge bg-warning text-dark">Bon</span>
                        </td>
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
                          <div class="progress" style="height: 5px; width: 100px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <span class="small">60%</span>
                        </td>
                        <td>
                          <span class="badge bg-danger">À améliorer</span>
                        </td>
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

          <div class="col-lg-4 mb-4" id="agent">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Gestion des Agents</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAgentModal">
                  <i class="bi bi-person-plus"></i> Nouvel Agent
                </button>
              </div>
              <div class="card-body p-0">
                <div class="list-group list-group-flush">
                  <div class="list-group-item border-0">
                    <div class="d-flex align-items-center">
                      <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100" alt="Agent" class="rounded-circle" width="40" height="40">
                      <div class="ms-3">
                        <h6 class="mb-0">Kouamé Aya</h6>
                        <small class="text-muted">Abidjan - Plateau</small>
                      </div>
                      <div class="ms-auto">
                        <span class="badge bg-success">Actif</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item border-0">
                    <div class="d-flex align-items-center">
                      <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100" alt="Agent" class="rounded-circle" width="40" height="40">
                      <div class="ms-3">
                        <h6 class="mb-0">Traoré Ibrahim</h6>
                        <small class="text-muted">Yamoussoukro - Centre</small>
                      </div>
                      <div class="ms-auto">
                        <span class="badge bg-success">Actif</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item border-0">
                    <div class="d-flex align-items-center">
                      <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100" alt="Agent" class="rounded-circle" width="40" height="40">
                      <div class="ms-3">
                        <h6 class="mb-0">Koffi Marc</h6>
                        <small class="text-muted">Bouaké - Nord</small>
                      </div>
                      <div class="ms-auto">
                        <span class="badge bg-warning text-dark">En pause</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item border-0">
                    <div class="d-flex align-items-center">
                      <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=100" alt="Agent" class="rounded-circle" width="40" height="40">
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

        <!-- Document Types and Regional Distribution -->
        <div class="row">
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
              <input type="text" class="form-control" id="agentName" placeholder=" entrer nom et prenom" required>
            </div>
            <div class="mb-3">
              <label for="agentEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="agentEmail" placeholder="exemple@email.com" required>
            </div>
            <div class="mb-3">
              <label for="agentPassword" class="form-label">Mot de passe</label>
              <input type="password" class="form-control" id="agentPassword" placeholder="******" required>
            </div>
            <div class="mb-3">
              <label for="agentRegion" class="form-label">Région</label>
              <select class="form-select" id="agentRegion" required>
                <option value="">Sélectionner une région</option>
                <option value="abidjan">Abidjan</option>
                <option value="yamoussoukro">Yamoussoukro</option>
                <option value="bouake">Bouaké</option>
                <option value="san-pedro">San-Pédro</option>
                <option value="korhogo">Korhogo</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="agentRole" class="form-label">Rôle</label>
              <select class="form-select" id="agentRole" required>
                <option value="agent">Agent Municipal</option>
                <option value="supervisor">Superviseur</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-primary" id="createAgentBtn">Créer l'agent</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Initialize charts
    document.addEventListener('DOMContentLoaded', function() {
      // Document Type Distribution Chart
      const documentTypeCtx = document.getElementById('documentTypeChart');
      new Chart(documentTypeCtx, {
        type: 'doughnut',
        data: {
          labels: ['Extraits de naissance', 'Actes de mariage', 'Actes de décès', 'Certificats de vie', 'Certificats de non revenu', 'Certificats d\'entretien'],
          datasets: [{
            data: [35, 25, 15, 10, 8, 7],
            backgroundColor: [
              '#0056b3',
              '#2a9d8f',
              '#e9c46a',
              '#f4a261',
              '#e76f51',
              '#8338ec'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 20,
                boxWidth: 12
              }
            }
          },
          cutout: '70%'
        }
      });

      // Regional Distribution Chart
      const regionalDistributionCtx = document.getElementById('regionalDistributionChart');
      new Chart(regionalDistributionCtx, {
        type: 'bar',
        data: {
          labels: ['Abidjan', 'Yamoussoukro', 'Bouaké', 'San-Pédro', 'Korhogo'],
          datasets: [{
            label: 'Nombre de demandes',
            data: [856, 425, 312, 245, 198],
            backgroundColor: '#0056b3',
            borderRadius: 5
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                display: false
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });

      // Initialize sidebar toggle
      const sidebarCollapse = document.getElementById('sidebarCollapse');
      const sidebar = document.getElementById('sidebar');
      
      if (sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener('click', function() {
          sidebar.classList.toggle('active');
        });
      }

      // Initialize create agent functionality
      const createAgentBtn = document.getElementById('createAgentBtn');
      if (createAgentBtn) {
        createAgentBtn.addEventListener('click', function() {
          const form = document.getElementById('newAgentForm');
          if (form.checkValidity()) {
            // Simulate agent creation
            const notification = document.createElement('div');
            notification.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
            notification.innerHTML = `
              Agent créé avec succès!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.body.appendChild(notification);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('newAgentModal'));
            modal.hide();
            
            // Remove notification after 3 seconds
            setTimeout(() => {
              notification.remove();
            }, 3000);
          } else {
            form.reportValidity();
          }
        });
      }
    });
  </script>
</body>
</html>