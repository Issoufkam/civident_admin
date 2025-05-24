@extends('layouts.app')

@section('content')


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Agents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            padding: 2rem 0;
        }
        .table-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .table-header {
            background: linear-gradient(to right, #6366f1, #4f46e5);
            color: white;
            padding: 1.5rem;
        }
        .search-container {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-success {
            background-color: #10b981;
            border-color: #10b981;
        }
        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
        }
        .agent-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .actions-cell {
            white-space: nowrap;
        }
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6b7280;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .pagination {
            margin: 0;
        }
        .pagination .page-link {
            color: #6366f1;
        }
        .pagination .active .page-link {
            background-color: #6366f1;
            border-color: #6366f1;
        }
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 0;
            }
            .actions-cell .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="table-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-people-fill fs-4"></i>
                    <h1 class="h3 mb-0">Liste des agents</h1>
                </div>
                <a href="#" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i>
                    <span>Ajouter un agent</span>
                </a>
            </div>

            <div class="p-4">
                <div class="search-container">
                    <form class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Rechercher par nom ou prénom..." 
                                   id="searchInput">
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Rechercher</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Commune</th>
                                <th>Rôle</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="agentsTableBody">
                            <!-- Table content will be dynamically populated -->
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4" id="tableFooter" style="display: none !important;">
                    <div class="text-muted">
                        Affichage de <span id="startCount">1</span> à <span id="endCount">10</span> sur <span id="totalCount">100</span> agents
                    </div>
                    <nav aria-label="Navigation des pages">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Précédent</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Suivant</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mock data for demonstration
        const mockAgents = [
            {
                id: 1,
                nom: 'Dupont',
                prenom: 'Jean',
                email: 'jean.dupont@example.com',
                telephone: '01 23 45 67 89',
                commune: 'Paris',
                role: 'Agent',
                photo: 'https://i.pravatar.cc/150?img=1'
            },
            {
                id: 2,
                nom: 'Martin',
                prenom: 'Sophie',
                email: 'sophie.martin@example.com',
                telephone: '01 98 76 54 32',
                commune: 'Lyon',
                role: 'Administrateur',
                photo: 'https://i.pravatar.cc/150?img=2'
            },
            {
                id: 3,
                nom: 'Bernard',
                prenom: 'Michel',
                email: 'michel.bernard@example.com',
                telephone: '01 45 67 89 10',
                commune: 'Marseille',
                role: 'Super Admin',
                photo: 'https://i.pravatar.cc/150?img=3'
            }
        ];

        function displayAgents(agents) {
            const tableBody = document.getElementById('agentsTableBody');
            const tableFooter = document.getElementById('tableFooter');
            
            if (agents.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-inbox text-muted"></i>
                                <h4>Aucun agent trouvé</h4>
                                <p class="mb-0">Aucun agent ne correspond à votre recherche.</p>
                            </div>
                        </td>
                    </tr>
                `;
                tableFooter.style.display = 'none';
            } else {
                tableBody.innerHTML = agents.map(agent => `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="${agent.photo}" alt="${agent.prenom} ${agent.nom}" class="agent-avatar">
                                <div>
                                    <div class="fw-semibold">${agent.prenom} ${agent.nom}</div>
                                    <small class="text-muted">${agent.role}</small>
                                </div>
                            </div>
                        </td>
                        <td>${agent.email}</td>
                        <td>${agent.telephone}</td>
                        <td>${agent.commune}</td>
                        <td>
                            <span class="badge bg-${getRoleBadgeColor(agent.role)}">${agent.role}</span>
                        </td>
                        <td class="actions-cell text-end">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-danger" title="Supprimer" onclick="deleteAgent(${agent.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
                tableFooter.style.display = 'flex';
            }
        }

        function getRoleBadgeColor(role) {
            switch (role.toLowerCase()) {
                case 'super admin':
                    return 'danger';
                case 'administrateur':
                    return 'warning';
                default:
                    return 'success';
            }
        }

        function deleteAgent(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')) {
                // Simulate deletion
                const updatedAgents = mockAgents.filter(agent => agent.id !== id);
                displayAgents(updatedAgents);
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filteredAgents = mockAgents.filter(agent => 
                agent.nom.toLowerCase().includes(searchTerm) ||
                agent.prenom.toLowerCase().includes(searchTerm) ||
                agent.email.toLowerCase().includes(searchTerm)
            );
            displayAgents(filteredAgents);
        });

        // Initial display
        displayAgents(mockAgents);
    </script>
</body>
</html>
@endsection