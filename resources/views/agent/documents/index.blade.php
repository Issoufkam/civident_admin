@extends('layouts.app')

@section('styles')
    <!-- Bootstrap CSS -->
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
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Liste des documents</h1>

    <div class="mb-4 text-end">
        <a href="{{ route('agent.documents.create') }}"
        class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Créer un document
        </a>
    </div>

    <div class="bg-primary shadow-md rounded-lg p-6">
        <!-- Filtres -->
        <form method="GET" action="{{ route('agent.documents.index') }}" class="flex flex-wrap gap-4 items-center mb-6">
            <div>
                <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">-- Filtrer par statut --</option>
                    <option value="EN_ATTENTE" {{ request('status') == 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                    <option value="APPROUVEE" {{ request('status') == 'APPROUVEE' ? 'selected' : '' }}>Approuvée</option>
                    <option value="REJETEE" {{ request('status') == 'REJETEE' ? 'selected' : '' }}>Rejetée</option>
                </select>
            </div>
            <noscript>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filtrer</button>
            </noscript>
        </form>

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Type</th>
                        <th>Commune</th>
                        <th>Statut</th>
                        <th>Utilisateur</th>
                        <th>Agent</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs">
                    @forelse ($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td>{{ $document->registry_number }}</td>
                            <td>{{ $document->type }}</td>
                            <td class="hidden sm:table-cell">{{ $document->commune->name ?? '-' }}</td>
                            <td>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $document->status === 'APPROUVEE' ? 'bg-green-100 text-green-800' :
                                    ($document->status === 'REJETEE' ? 'bg-red-100 text-red-800' :
                                    'bg-yellow-100 text-yellow-800') }}">
                                    {{ $document->status }}
                                </span>
                            </td>
                            <td class="">{{ $document->user->nom ?? '-' }}</td>
                            <td class="hidden md:table-cell">{{ $document->agent->nom ?? '-' }}</td>
                            <td>{{ $document->created_at->format('d/m/Y') }}</td>
                            <td >
                                <a href="{{ route('agent.documents.show', $document) }}">
                                    Voir
                                </a>

                                @if($document->status === 'EN_ATTENTE')
                                    <form action="{{ route('agent.documents.approve', $document) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600"
                                                onclick="return confirm('Confirmer l’approbation de ce document ?')">
                                            ✓
                                        </button>
                                    </form>

                                    <form action="{{ route('agent.documents.reject', $document) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600"
                                                onclick="return confirm('Confirmer le rejet de ce document ?')">
                                            ✗
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Aucun document trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $documents->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
@endsection
