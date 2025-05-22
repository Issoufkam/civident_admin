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
--border-radius: 0.5rem; }
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
</style>
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
          <a href="#" id="agents-link active">
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
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm mb-4">
            <div class="container-fluid">
                <button class="btn btn-link">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div class="d-flex align-items-center">
                    <div class="search-bar me-3">
                        <input type="text" class="form-control" placeholder="Rechercher...">
                        <i class="bi bi-search"></i>
                    </div>

                    <div class="position-relative me-3">
                        <a href="#" class="btn btn-link position-relative">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Gestion des Agents</h1>
                <p class="text-muted">Gérez les agents municipaux et leurs accès</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAgentModal">
                <i class="bi bi-person-plus"></i> Nouvel Agent
            </button>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Région</label>
                        <select class="form-select">
                            <option value="">Toutes les régions</option>
                            <option>Abidjan</option>
                            <option>Yamoussoukro</option>
                            <option>Bouaké</option>
                            <option>San-Pédro</option>
                            <option>Korhogo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select">
                            <option value="">Tous les statuts</option>
                            <option>Actif</option>
                            <option>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rechercher</label>
                        <input type="text" class="form-control" placeholder="Nom, email...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Agents List -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Agent</th>
                                <th>Région</th>
                                <th>Statut</th>
                                <th>Dernière Connexion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg" 
                                             alt="Kouamé Aya">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Kouamé Aya</h6>
                                            <small class="text-muted">kouame.aya@example.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Abidjan - Plateau</td>
                                <td><span class="badge bg-success">Actif</span></td>
                                <td>Il y a 5 minutes</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg" 
                                             alt="Traoré Ibrahim">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Traoré Ibrahim</h6>
                                            <small class="text-muted">traore.ibrahim@example.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Yamoussoukro - Centre</td>
                                <td><span class="badge bg-success">Actif</span></td>
                                <td>Il y a 15 minutes</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg" 
                                             alt="Koffi Marc">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Koffi Marc</h6>
                                            <small class="text-muted">koffi.marc@example.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Bouaké - Nord</td>
                                <td><span class="badge bg-warning text-dark">En pause</span></td>
                                <td>Il y a 1 heure</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg" 
                                             alt="Bamba Fatou">
                                        <div class="ms-3">
                                            <h6 class="mb-0">Bamba Fatou</h6>
                                            <small class="text-muted">bamba.fatou@example.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td>San-Pédro - Ouest</td>
                                <td><span class="badge bg-success">Actif</span></td>
                                <td>Il y a 2 heures</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Précédent</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            


</body>   
</html>    