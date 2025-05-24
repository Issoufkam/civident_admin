@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des demandes</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N° Registre</th>
                <th>Type</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($demandes as $document)
                <tr>
                    <td>{{ $document->registry_number }}</td>
                    <td>{{ ucfirst($document->type) }}</td>
                    <td>{{ $document->metadata['nom'] ?? '-' }}</td>
                    <td>{{ $document->metadata['prenom'] ?? '-' }}</td>
                    <td>{{ ucfirst($document->status) }}</td>
                    <td>{{ $document->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('documents.show', $document) }}" class="btn btn-primary btn-sm">Voir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucune demande trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $demandes->links() }}
</div>
@endsection
