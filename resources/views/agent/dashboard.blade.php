@extends('layouts.app')

@section('content')

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
    <div class="row stats-row">
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card stats-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-muted">Demandes Totales</h6>
                <h2 class="stats-number">324</h2>
              </div>
              <div class="stats-icon bg-primary-subtle">
                <i class="bi bi-file-earmark-text text-primary"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
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
                <h6 class="text-muted">En Attente</h6>
                <h2 class="stats-number">24</h2>
              </div>
              <div class="stats-icon bg-warning-subtle">
                <i class="bi bi-hourglass-split text-warning"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="stats-text mt-2"><i class="bi bi-arrow-down-short text-danger"></i> 3% depuis la semaine dernière</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card stats-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-muted">Approuvés</h6>
                <h2 class="stats-number">276</h2>
              </div>
              <div class="stats-icon bg-success-subtle">
                <i class="bi bi-check-circle text-success"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
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
                <h6 class="text-muted">Rejetés</h6>
                <h2 class="stats-number">24</h2>
              </div>
              <div class="stats-icon bg-danger-subtle">
                <i class="bi bi-x-circle text-danger"></i>
              </div>
            </div>
            <div class="progress mt-3" style="height: 5px;">
              <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="stats-text mt-2"><i class="bi bi-arrow-down-short text-success"></i> 5% depuis le mois dernier</p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Demandes Récentes</h5>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="requestsFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Tous les types
              </button>
              <ul class="dropdown-menu" aria-labelledby="requestsFilterDropdown">
                <li><a class="dropdown-item" href="#">Tous les types</a></li>
                <li><a class="dropdown-item" href="#">Extrait de naissance</a></li>
                <li><a class="dropdown-item" href="#">Acte de mariage</a></li>
                <li><a class="dropdown-item" href="#">Acte de décès</a></li>
                <li><a class="dropdown-item" href="#">Certificat de vie</a></li>
                <li><a class="dropdown-item" href="#">Certificat de non revenu</a></li>
                <li><a class="dropdown-item" href="#">Certificat d'entretien</a></li>
              </ul>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle" id="requests-table">
                <thead class="table-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Citoyen</th>
                    <th scope="col">Type de document</th>
                    <th scope="col">Date de demande</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table content will be dynamically loaded from data.js -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer border-0 bg-white text-center">
            <a href="#" class="view-all">Voir toutes les demandes</a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">Distribution par Type</h5>
          </div>
          <div class="card-body">
            <canvas id="documentTypeChart" height="250"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
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
      <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Demandes Urgentes</h5>
            <span class="badge bg-danger rounded-pill">5 en attente</span>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush urgent-requests">
              <!-- Urgent requests will be loaded from data.js -->
            </ul>
          </div>
        </div>
      </div>
    </div>
  </template>

  <template id="request-details-template">
    <div class="row">
      <div class="col-12 mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" id="back-to-dashboard">Tableau de Bord</a></li>
            <li class="breadcrumb-item active" aria-current="page">Détails de la Demande</li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Informations de la Demande</h5>
            <span class="badge status-badge">En Attente</span>
          </div>
          <div class="card-body">
            <div class="row request-info">
              <!-- Request info will be injected here -->
            </div>
          </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">Documents Fournis</h5>
          </div>
          <div class="card-body">
            <div class="row documents-list">
              <!-- Documents will be injected here -->
            </div>
          </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">Historique de la Demande</h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table">
                <thead class="table-light">
                  <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Agent</th>
                    <th>Commentaire</th>
                  </tr>
                </thead>
                <tbody class="request-history">
                  <!-- History will be injected here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
          <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">Actions</h5>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2 mb-4">
              <button type="button" class="btn btn-success btn-approve" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="bi bi-check-circle"></i> Approuver la demande
              </button>
              <button type="button" class="btn btn-outline-danger btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-circle"></i> Rejeter la demande
              </button>
            </div>
            <div class="mb-3">
              <label for="requestNotes" class="form-label">Notes internes</label>
              <textarea class="form-control" id="requestNotes" rows="4" placeholder="Ajouter des notes concernant cette demande..."></textarea>
            </div>
            <div class="d-grid">
              <button type="button" class="btn btn-primary btn-save-notes">
                <i class="bi bi-save"></i> Enregistrer les notes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="approveModalLabel">Approuver la Demande</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="approveComment" class="form-label">Commentaire (optionnel)</label>
              <textarea class="form-control" id="approveComment" rows="3" placeholder="Ajouter un commentaire pour le citoyen..."></textarea>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="sendNotificationApprove" checked>
              <label class="form-check-label" for="sendNotificationApprove">
                Notifier le citoyen par email
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-success btn-confirm-approve" data-bs-dismiss="modal">Confirmer l'approbation</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="rejectModalLabel">Rejeter la Demande</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="rejectReason" class="form-label">Motif du rejet <span class="text-danger">*</span></label>
              <select class="form-select mb-3" id="rejectReason" required>
                <option value="" selected disabled>Sélectionnez un motif</option>
                <option value="documents_incomplets">Documents incomplets</option>
                <option value="informations_incorrectes">Informations incorrectes</option>
                <option value="documents_illisibles">Documents illisibles</option>
                <option value="demande_doublon">Demande en doublon</option>
                <option value="autre">Autre</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="rejectComment" class="form-label">Commentaire détaillé <span class="text-danger">*</span></label>
              <textarea class="form-control" id="rejectComment" rows="3" placeholder="Expliquez la raison du rejet..." required></textarea>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="sendNotificationReject" checked>
              <label class="form-check-label" for="sendNotificationReject">
                Notifier le citoyen par email
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-danger btn-confirm-reject" data-bs-dismiss="modal">Confirmer le rejet</button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="/js/data.js"></script>
  <script src="/js/charts.js"></script>
  <script src="/js/dashboard.js"></script>
  <script src="/js/request-details.js"></script>
  <script src="/js/main.js"></script>
</body>
</html>

@endsection
