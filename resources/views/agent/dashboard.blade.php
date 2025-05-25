@extends('layouts.app')
@endsection


  <style>
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
  min-width: 250px;
  max-width: 250px;
  background-color: white;
  transition: all var(--transition-speed);
  z-index: 999;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
}

.sidebar.collapsed {
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

.status-pending {
  background-color: var(--warning-light);
  color: var(--warning);
}

.status-approved {
  background-color: var(--success-light);
  color: var(--success);
}

.status-rejected {
  background-color: var(--danger-light);
  color: var(--danger);
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

.btn-success {
  background-color: var(--success);
  border-color: var(--success);
}

.btn-danger {
  background-color: var(--danger);
  border-color: var(--danger);
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
}

@media (max-width: 768px) {
  .stats-row {
    flex-direction: column;
  }
  
  .nav-search {
    width: 100%;
  }
}

/* Request Details Page */
.request-info {
  margin-bottom: 1.5rem;
}

.info-label {
  font-weight: 600;
  font-size: 0.9rem;
  color: var(--gray-600);
  margin-bottom: 0.25rem;
}

.info-value {
  font-size: 1rem;
  color: var(--gray-800);
}

.documents-list .document-card {
  border: 1px solid var(--gray-300);
  border-radius: var(--border-radius);
  padding: 1rem;
  margin-bottom: 1rem;
  transition: all 0.2s;
}

.documents-list .document-card:hover {
  border-color: var(--primary);
  box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
}

.document-icon {
  width: 45px;
  height: 45px;
  border-radius: 0.375rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--primary-light);
  color: var(--primary);
  font-size: 1.25rem;
  margin-right: 1rem;
}

.document-name {
  font-weight: 500;
  color: var(--gray-800);
  margin-bottom: 0.25rem;
}

.document-meta {
  font-size: 0.8rem;
  color: var(--gray-600);
}

.document-actions a {
  color: var(--gray-700);
  margin-left: 0.5rem;
  font-size: 1.1rem;
  transition: color 0.2s;
}

.document-actions a:hover {
  color: var(--primary);
}

/* Urgent Requests */
.urgent-requests .list-group-item {
  padding: 1rem;
  border: none;
  border-bottom: 1px solid var(--gray-200);
}

.urgent-requests .list-group-item:last-child {
  border-bottom: none;
}

.urgent-request-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.urgent-request-name {
  font-weight: 500;
  color: var(--gray-800);
  margin-bottom: 0.25rem;
}

.urgent-request-type {
  font-size: 0.8rem;
  color: var(--gray-600);
}

.urgent-request-date {
  font-size: 0.8rem;
  color: var(--gray-600);
}

.urgent-badge {
  margin-left: 0.5rem;
}

/* Form Elements */
.form-control, .form-select {
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
}

.form-control:focus, .form-select:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.25);
}

/* Tooltips */
.tooltip {
  font-size: 0.8rem;
}

/* Breadcrumb */
.breadcrumb {
  background-color: transparent;
  padding: 0;
  margin-bottom: 1rem;
}

.breadcrumb-item a {
  color: var(--primary);
  text-decoration: none;
}

.breadcrumb-item.active {
  color: var(--gray-600);
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
        <h3>Mairie</h3>
        <div class="sidebar-brand-icon">
          <i class="bi bi-building"></i>
        </div>
      </div>
      <div class="sidebar-user">
        <img src="https://images.pexels.com/photos/1036623/pexels-photo-1036623.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Agent Photo" class="user-avatar">
        <div class="user-info">
          <h5>Sophie Martin</h5>
          <span>Agent Municipal</span>
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
          <a href="#" id="pending-link">
            <i class="bi bi-hourglass-split"></i>
            En Attente
            <span class="badge rounded-pill bg-warning ms-2">24</span>
          </a>
        </li>
        <li>
          <a href="#" id="approved-link">
            <i class="bi bi-check-circle"></i>
            Approuvés
          </a>
        </li>
        <li>
          <a href="#" id="rejected-link">
            <i class="bi bi-x-circle"></i>
            Rejetés
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
              <input type="text" class="form-control" id="search-input" placeholder="Rechercher une demande...">
              <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </div>
            <div class="ms-3 d-flex align-items-center">
              <div class="dropdown">
                <a href="#" class="position-relative" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-bell fs-5"></i>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    5
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                  <li><h6 class="dropdown-header">Notifications</h6></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-primary">
                      <i class="bi bi-file-earmark-text text-white"></i>
                    </div>
                    <div class="notification-content">
                      <p class="notification-text">Nouvelle demande d'extrait de nais..</p>
                      <p class="notification-time">Il y a 10 minutes</p>
                    </div>
                  </a></li>
                  <li><a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-success">
                      <i class="bi bi-check-circle text-white"></i>
                    </div>
                    <div class="notification-content">
                      <p class="notification-text">Demande traitée avec succès</p>
                      <p class="notification-time">Il y a 30 minutes</p>
                    </div>
                  </a></li>
                  <li><a class="dropdown-item notification-item" href="#">
                    <div class="notification-icon bg-warning">
                      <i class="bi bi-exclamation-triangle text-white"></i>
                    </div>
                    <div class="notification-content">
                      <p class="notification-text">Demande urgente en attente</p>
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
        <!-- Dashboard Content - will be loaded from dashboard.js -->
      </div>
    </div>
  </div>
  <!-- Templates for different views -->
  <template id="dashboard-template">
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
