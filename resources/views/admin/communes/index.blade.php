@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface des Communes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            padding: 2rem 0;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }
        .card-header {
            background-color: #0d6efd; /* Couleur primaire */
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .btn {
            border-radius: 0.5rem; /* Coins légèrement arrondis pour les boutons */
        }
        .table-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 2rem; /* Espace entre le formulaire d'ajout et le tableau */
        }
        .table-header {
            background: linear-gradient(to right, #6366f1, #4f46e5); /* Consistent with agent list */
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
        .pagination .page-link {
            color: #6366f1;
        }
        .pagination .active .page-link {
            background-color: #6366f1;
            border-color: #6366f1;
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
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">
            <i class="bi bi-geo-alt"></i> Interface des Communes
        </h1>

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Conteneur du tableau avec barre de recherche --}}
        <div class="table-container">
            <div class="table-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-building-fill-gear fs-4"></i> {{-- Changed icon for communes --}}
                    <h2 class="h3 mb-0">Liste des Communes</h2>
                </div>
                <a href="{{ route('admin.communes.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i>
                    <span>Ajouter une commune</span>
                </a>
            </div>

            <div class="p-4">
                {{-- Barre de recherche dynamique --}}
                <div class="search-container">
                    <form action="{{ route('admin.communes.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="search" class="form-control border-start-0 ps-0"
                                placeholder="Rechercher une commune ou région..."
                                name="search"
                                value="{{ request('search') }}"
                                id="searchInput">
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Rechercher</button>
                        @if(request('search'))
                            <a href="{{ route('admin.communes.index') }}" class="btn btn-outline-secondary px-4">Effacer</a>
                        @endif
                    </form>
                </div>

                {{-- Tableau des communes --}}
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Région</th>
                                <th>Code de la commune</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($communes as $commune)
                                <tr>
                                    <td>{{ $loop->iteration + ($communes->currentPage() - 1) * $communes->perPage() }}</td>
                                    <td>{{ $commune->name }}</td>
                                    <td>{{ $commune->region }}</td>
                                    <td>{{ $commune->code }}</td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('admin.communes.edit', $commune) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i> Modifier
                                        </a>

                                        <form action="{{ route('admin.communes.destroy', $commune) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Confirmer la suppression de la commune {{ $commune->name }} ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <i class="bi bi-building-slash"></i>
                                        <p>Aucune commune trouvée.</p>
                                        <p>Veuillez ajuster votre recherche ou <a href="{{ route('admin.communes.create') }}">ajouter une nouvelle commune</a>.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center pt-4">
                    <div class="text-muted">
                        Affichage de {{ $communes->firstItem() }} à {{ $communes->lastItem() }} sur {{ $communes->total() }} communes
                    </div>
                    <nav aria-label="Navigation des pages">
                        {{ $communes->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
