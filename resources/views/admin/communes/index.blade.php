@extends('layouts.app')

@section('content')
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

    {{-- Barre de recherche --}}
    <form method="GET" action="{{ route('admin.communes.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Rechercher une commune ou région..." value="{{ request('search') }}">
        <button class="btn btn-primary">Rechercher</button>
    </form>

    {{-- Bouton d'ajout --}}
    <a href="{{ route('admin.communes.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Ajouter une commune
    </a>

    {{-- Tableau des communes --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Région</th>
                    <th>Code de la commune</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($communes as $commune)
                    <tr>
                        <td>{{ $loop->iteration + ($communes->currentPage() - 1) * $communes->perPage() }}</td>
                        <td>{{ $commune->name }}</td>
                        <td>{{ $commune->code }}
                        <td>{{ $commune->region }}</td>
                        <td>
                            <a href="{{ route('admin.communes.edit', $commune) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </a>

                            <form action="{{ route('admin.communes.destroy', $commune) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Aucune commune trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $communes->withQueryString()->links() }}
    </div>
</div>
@endsection
