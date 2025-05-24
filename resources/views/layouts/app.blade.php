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
    @php
        $user = Auth::user();
    @endphp

    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3>{{ $user->isAdmin() ? 'Administration' : 'Mairie' }}</h3>
            <div class="sidebar-brand-icon">
                <i class="bi {{ $user->isAdmin() ? 'bi-shield-lock' : 'bi-building' }}"></i>
            </div>
        </div>

        <div class="sidebar-user">
            <img src="{{ $user->isAdmin() ? 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1' : 'https://images.pexels.com/photos/1036623/pexels-photo-1036623.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1' }}" alt="Photo" class="user-avatar">
            <div class="user-info">
                <h5>{{ $user->nom }}</h5>
                <span>{{ $user->isAdmin() ? 'Super Administrateur' : 'Agent Municipal' }}</span>
            </div>
        </div>

        <ul class="list-unstyled components">
            @if($user->isAdmin())
                <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        Tableau de Bord
                    </a>
                </li>
            @else
                <li class="active">
                <a href="{{ route('agent.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Tableau de Bord
                </a>
            </li>
            @endif

            @if ($user->isAdmin())
                <li><a href="{{ route('admin.agents.index') }}"><i class="bi bi-people"></i> Agents</a></li>
                <li><a href="{{ route('admin.regions.index') }}"><i class="bi bi-geo-alt"></i> Régions</a></li>
                <li><a href="{{ route('admin.communes.index') }}"><i class="bi bi-geo-alt"></i> Communes</a></li>
                <li><a href="#"><i class="bi bi-graph-up"></i> Statistiques</a></li>
            @else
                <li><a href="{{ route('agent.demandes.index') }}"><i class="bi bi-file-earmark-text"></i>Demandes</a></li>
                <li><a href="#"><i class="bi bi-hourglass-split"></i> En Attente <span class="badge rounded-pill bg-warning ms-2">24</span></a></li>
                <li><a href="#"><i class="bi bi-check-circle"></i> Approuvés</a></li>
                <li><a href="#"><i class="bi bi-x-circle"></i> Rejetés</a></li>
            @endif

            <li><a href="#"><i class="bi bi-gear"></i> Paramètres</a></li>
        </ul>

        <div class="sidebar-footer">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-left"></i> Déconnexion
            </a>
        </div>
    </nav>


    @yield('content')
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
<!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/data.js"></script>
  <script src="js/charts.js"></script>

</body>
</html>
