@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Liste des agents</h2>
        <a href="{{ route('admin.agents.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Ajouter un agent
        </a>
    </div>

    {{-- Barre de recherche --}}
    <form method="GET" action="{{ route('admin.agents.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher par nom ou prénom">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>

    {{-- Tableau des agents --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Commune</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                    <tr>
                        <td>{{ $agent->nom }}</td>
                        <td>{{ $agent->prenom }}</td>
                        <td>{{ $agent->email }}</td>
                        <td>{{ $agent->telephone }}</td>
                        <td>{{ $agent->commune->name ?? 'Non défini' }}</td>
                        <td>
                            <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-sm btn-info">Voir</a>
                            <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <form action="{{ route('admin.agents.destroy', $agent->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet agent ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun agent trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $agents->withQueryString()->links() }}
    </div>
</div>
@endsection
