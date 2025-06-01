@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface des Régions</title>
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
    <div class="container">
        <h1 class="mb-4">Interface des Régions</h1>

        {{-- Message de succès --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Formulaire d’ajout de région --}}
        <div class="card mb-4">
            <div class="card-header">Ajouter une nouvelle région</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.regions.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="region">Nom de la région</label>
                        <input type="text" name="region" id="region" class="form-control @error('region') is-invalid @enderror" required>
                        @error('region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>

        {{-- Conteneur du tableau avec barre de recherche --}}
        <div class="table-container">
            <div class="table-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-geo-alt-fill fs-4"></i>
                    <h2 class="h3 mb-0">Liste des Régions</h2>
                </div>
            </div>

            <div class="p-4">
                {{-- Barre de recherche dynamique --}}
                <div class="search-container">
                    <form action="{{ route('admin.regions.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="search" class="form-control border-start-0 ps-0"
                                placeholder="Rechercher une région..."
                                name="search"
                                value="{{ request('search') }}"
                                id="searchInput">
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Rechercher</button>
                        @if(request('search'))
                            <a href="{{ route('admin.regions.index') }}" class="btn btn-outline-secondary px-4">Effacer</a>
                        @endif
                    </form>
                </div>

                {{-- Tableau des régions --}}
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom de la région</th>
                                <th>Nombre de lieux d'état civil</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($regions as $region)
                                <tr>
                                    <td>{{ $loop->iteration + ($regions->currentPage() - 1) * $regions->perPage() }}</td>
                                    <td>{{ $region->region }}</td>
                                    <td>{{ $region->lieux_count }}</td>
                                    <td class="text-end text-nowrap">
                                        {{-- Formulaire de suppression --}}
                                        {{-- CORRECTION ICI: Passer le paramètre 'region' dans l'URL --}}
                                        <form action="{{ route('admin.regions.destroy', ['region' => $region->region]) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette région ? Cela supprimera toutes les communes associées.');">
                                            @csrf
                                            @method('DELETE')
                                            {{-- L'input hidden 'region' n'est plus nécessaire ici car le paramètre est dans l'URL --}}
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>

                                        {{-- Formulaire de modification --}}
                                        {{-- CORRECTION ICI: Passer le paramètre 'region' dans l'URL pour la mise à jour --}}
                                        <form action="{{ route('admin.regions.update', ['region' => $region->region]) }}" method="POST" class="d-inline ms-2" id="edit-region-form-{{ Str::slug($region->region) }}">
                                            @csrf
                                            @method('PUT')
                                            {{-- L'input hidden 'old_region' peut rester pour la validation si nécessaire, mais le paramètre principal est dans l'URL --}}
                                            <input type="hidden" name="old_region" value="{{ $region->region }}">

                                            <div class="input-group input-group-sm d-inline-flex align-items-center" style="width: auto;">
                                                <input type="text"
                                                    name="new_region"
                                                    value="{{ old('new_region', $region->region) }}"
                                                    class="form-control form-control-sm {{ $errors->has('new_region') ? 'is-invalid' : '' }}"
                                                    style="width: 150px"
                                                    required
                                                    minlength="2"
                                                    maxlength="100"
                                                    id="region-input-{{ Str::slug($region->region) }}"
                                                    onkeydown="return event.key !== 'Enter'">

                                                <button type="submit"
                                                        class="btn btn-sm btn-primary"
                                                        data-bs-toggle="tooltip"
                                                        title="Enregistrer les modifications">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        onclick="resetRegionForm('{{ $region->region }}', '{{ Str::slug($region->region) }}')"
                                                        data-bs-toggle="tooltip"
                                                        title="Annuler">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>

                                            @error('new_region')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <i class="bi bi-geo-alt-slash"></i>
                                        <p>Aucune région trouvée.</p>
                                        <p>Veuillez ajuster votre recherche ou <a href="{{ route('admin.regions.create') }}">ajouter une nouvelle région</a>.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center pt-4">
                    <div class="text-muted">
                        Affichage de {{ $regions->firstItem() }} à {{ $regions->lastItem() }} sur {{ $regions->total() }} régions
                    </div>
                    <nav aria-label="Navigation des pages">
                        {{ $regions->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonction pour réinitialiser le formulaire de modification de région
        function resetRegionForm(originalRegionName, slug) {
            const input = document.getElementById(`region-input-${slug}`);
            if (input) {
                input.value = originalRegionName;
                input.classList.remove('is-invalid');
                const feedback = input.nextElementSibling; // Get the next sibling, which might be the invalid-feedback div
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none'; // Hide the error message
                }
            }
        }

        // Initialiser les tooltips Bootstrap
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
</body>
</html>
@endsection
