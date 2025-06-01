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
                <a href="{{ route('admin.agents.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i>
                    <span>Ajouter un agent</span>
                </a>
            </div>

            <div class="p-4">
                <div class="search-container">
                    {{-- Formulaire de recherche dynamique --}}
                    <form action="{{ route('admin.agents.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="search" class="form-control border-start-0 ps-0"
                                placeholder="Rechercher par nom, prénom, email ou téléphone..."
                                name="search" {{-- IMPORTANT: Ajout de l'attribut name --}}
                                value="{{ request('search') }}" {{-- IMPORTANT: Pré-remplir avec la valeur de recherche actuelle --}}
                                id="searchInput">
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Rechercher</button>
                        {{-- Bouton pour effacer la recherche si une recherche est active --}}
                        @if(request('search'))
                            <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary px-4">Effacer</a>
                        @endif
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-circle"></i>
                                    Photo
                                </th>
                                <th>Nom</th> {{-- Changed from Agent to Nom for clarity --}}
                                <th>Prénom</th> {{-- Added Prénom for clarity --}}
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Commune</th>
                                <th>Rôle</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="agentsTableBody">
                            @forelse ($agents as $agent)
                            <tr>
                                <td class="d-flex align-items-center gap-2">
                                    {{-- Affichage de la photo avec fallback --}}
                                    @if (isset($agent->photo) && $agent->photo && file_exists(public_path('storage/' . $agent->photo)))
                                        <img src="{{ asset('storage/' . $agent->photo) }}" alt="{{ $agent->nom }} {{ $agent->prenom }}" class="agent-avatar">
                                    @else
                                        @php
                                            $nameForAvatar = (isset($agent) && $agent->prenom && $agent->nom) ? urlencode($agent->prenom . ' ' . $agent->nom) : 'Agent';
                                            $defaultAvatar = "https://ui-avatars.com/api/?name={$nameForAvatar}&background=0d6efd&color=fff&size=40";
                                        @endphp
                                        <img src="{{ $defaultAvatar }}" alt="Avatar par défaut" class="agent-avatar">
                                    @endif
                                </td>
                                <td>{{ $agent->nom }}</td>
                                <td>{{ $agent->prenom }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>{{ $agent->telephone }}</td>
                                <td>{{ $agent->commune->name ?? 'Non défini' }}</td> {{-- Changed from nom to name for commune --}}
                                <td>{{ ucfirst($agent->role) }}</td>
                                <td class="text-nowrap actions-cell">
                                    <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                    <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </a>
                                    <form action="{{ route('admin.agents.destroy', $agent->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <i class="bi bi-person-x"></i>
                                    <p>Aucun agent trouvé.</p>
                                    <p>Veuillez ajuster votre recherche ou <a href="{{ route('admin.agents.create') }}">ajouter un nouvel agent</a>.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center pt-4">
                    <div class="text-muted">
                        Affichage de {{ $agents->firstItem() }} à {{ $agents->lastItem() }} sur {{ $agents->total() }} agents
                    </div>
                    <nav aria-label="Navigation des pages">
                        {{ $agents->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Font Awesome pour les icônes fas fa-* (si vous l'utilisez) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" xintegrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
@endsection
