<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Super Admin - Gestion des Mairies')</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ asset('css/Admin.css') }}" rel="stylesheet" />
    @stack('styles')
</head>

<body>
    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar bg-light">
            <div class="sidebar-header p-3">
                <h3 class="text-primary">Administration</h3>
                <div class="sidebar-brand-icon mb-2">
                    <i class="bi bi-shield-lock fs-2 text-primary"></i>
                </div>
            </div>

            <div class="sidebar-user text-center px-3 mb-3">
                <img src="{{ Auth::user()->photo ?? 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg' }}"
                     alt="Photo profil"
                     class="user-avatar rounded-circle object-fit-cover mb-2"
                     style="width: 60px; height: 60px;" />
                <div class="user-info">
                    <h5 class="mb-0">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h5>
                    <span class="text-muted">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>

            <ul class="list-unstyled components px-3">
                <li class="mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i> Tableau de Bord
                    </a>
                </li>

                @if(Auth::user()->role === 'admin')
                    <li class="mb-2">
                        <a href="{{ route('admin.agents.index') }}" class="d-flex align-items-center">
                            <i class="bi bi-people me-2"></i> Agents
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.regions.index') }}" class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2"></i> Régions
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.communes.index') }}" class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2"></i> Communes
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer px-3 mt-auto mb-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div id="content" class="flex-grow-1">
            <!-- Topbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-primary">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="d-flex ms-auto align-items-center">
                        <div class="nav-search position-relative me-3">
                            <input type="text" class="form-control" placeholder="Rechercher..." />
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="position-relative" id="notificationDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                {{-- Badge de notifications si nécessaire --}}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                {{-- Notifications à afficher dynamiquement --}}
                                {{-- <li><a class="dropdown-item" href="#">Aucune notification</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="container-fluid px-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Toggle sidebar
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });

        // Enable tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

    @stack('scripts')
</body>

</html>
