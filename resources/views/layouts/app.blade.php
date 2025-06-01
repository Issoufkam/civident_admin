<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Mairie</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        /* Vos styles CSS personnalisés restent ici */
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
    @include('layouts.sidebar')
    <div id="content">
        @include('layouts.nav')
        {{-- Le contenu de la page spécifique sera affiché ici grâce à @yield('content') --}}
        @yield('content')

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/data.js"></script>
<script src="/js/charts.js"></script>
<script src="/js/dashboard.js"></script>
<script src="/js/request-details.js"></script>
<script src="/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- C'EST LA LIGNE CLÉ À AJOUTER ! --}}
@stack('scripts')

<script>
    // Exemple de gestion de la recherche et affichage dynamique (mock data)
    const mockAgents = [
        {
            id: 1,
            matricule: "M006",
            nom: "Jacques",
            prenom: "Dupont",
            dateNaissance: "1990-01-15",
            lieuNaissance: "Paris",
            genre: "Homme",
            telephone: "0123456789",
            email: "jacques@example.com",
            adresse: "1 rue de Paris",
            niveauEtude: "Licence",
            situationMatrimoniale: "Célibataire",
            dateEmbauche: "2020-03-15",
            dateDepart: null,
            dateCreation: "2020-03-15",
            dateModification: "2024-05-24",
        },
        {
            id: 2,
            matricule: "M007",
            nom: "Marie",
            prenom: "Curie",
            dateNaissance: "1985-11-07",
            lieuNaissance: "Varsovie",
            genre: "Femme",
            telephone: "0987654321",
            email: "marie@example.com",
            adresse: "2 avenue de la Science",
            niveauEtude: "Doctorat",
            situationMatrimoniale: "Mariée",
            dateEmbauche: "2015-06-10",
            dateDepart: null,
            dateCreation: "2015-06-10",
            dateModification: "2023-12-01",
        }
        // ... autres agents
    ];

    const searchInput = document.getElementById('searchAgentInput');
    const tableBody = document.querySelector('tbody');

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            // Filtrer les agents en fonction de la recherche
            const filteredAgents = mockAgents.filter(agent => {
                return (
                    agent.matricule.toLowerCase().includes(query) ||
                    agent.nom.toLowerCase().includes(query) ||
                    agent.prenom.toLowerCase().includes(query) ||
                    agent.telephone.includes(query) ||
                    agent.email.toLowerCase().includes(query)
                );
            });

            // Construire le HTML des lignes filtrées
            let rowsHtml = '';
            if(filteredAgents.length === 0) {
                rowsHtml = `<tr><td colspan="8" class="text-center text-gray-500 py-4">Aucun agent trouvé.</td></tr>`;
            } else {
                filteredAgents.forEach((agent, index) => {
                    rowsHtml += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2">${index + 1}</td>
                            <td class="px-2 py-2">${agent.matricule}</td>
                            <td class="px-2 py-2">${agent.nom}</td>
                            <td class="px-2 py-2">${agent.prenom}</td>
                            <td class="px-2 py-2">${agent.dateNaissance}</td>
                            <td class="px-2 py-2">${agent.telephone}</td>
                            <td class="px-2 py-2">${agent.email}</td>
                            <td class="px-2 py-2">
                                <button class="btn btn-primary btn-sm" onclick="alert('Modifier agent ID ${agent.id}')">Modifier</button>
                            </td>
                        </tr>
                    `;
                });
            }

            tableBody.innerHTML = rowsHtml;
        });
    }
</script>

</body>
</html>
