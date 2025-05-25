@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-3 px-sm-4 px-lg-5">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Liste des documents</h1>

    <div class="mb-4 text-end">
        <a href="{{ route('agent.documents.create') }}"
        class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Créer un document
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
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
                                
                                <a href="{{ route('agent.documents.show', $document) }}" class="btn btn-outline-dark" >
                                    Voir
                                </a>

                                @if($document->status === 'EN_ATTENTE')
                                    <form action="{{ route('agent.documents.approve', $document) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600"
                                                onclick="return confirm('Confirmer l’pprobation de ce document ?')">
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
