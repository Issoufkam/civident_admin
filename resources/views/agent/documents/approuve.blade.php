@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 px-3 px-sm-4 px-lg-5" style="margin-top: 0;">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">Liste des Demandes Approuvées</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <form id="filterForm" class="d-flex flex-wrap gap-2" action="{{ route('agent.documents.approuve') }}" method="GET">
                            <div class="search-container me-2 mb-2 mb-sm-0">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="form-control" id="searchInput" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                            </div>
                            <div class="type-filter me-2 mb-2 mb-sm-0">
                                <select class="form-select" name="type" id="typeFilter">
                                    <option value="all">Tous types</option>
                                    <option value="naissance" {{ request('type') == 'naissance' ? 'selected' : '' }}>Naissance</option>
                                    <option value="mariage" {{ request('type') == 'mariage' ? 'selected' : '' }}>Mariage</option>
                                    <option value="deces" {{ request('type') == 'deces' ? 'selected' : '' }}>Décès</option>
                                </select>
                            </div>
                            <input type="hidden" name="status" value="APPROUVEE"> {{-- Statut fixe pour cette page --}}
                            <button type="submit" class="btn btn-primary d-none">Appliquer les filtres</button>
                        </form>
                        <a href="{{ route('agent.documents.create') }}" class="btn btn-sm btn-primary ms-auto mb-2 mb-sm-0">
                            <i class="bi bi-plus"></i> Ajouter un Document
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-requests mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Numéro de Registre</th>
                                <th scope="col">Type</th>
                                <th scope="col">Demandeur</th>
                                <th scope="col">Date d'Approbation</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requestsTableBody">
                            @forelse ($documentsApprouves as $document)
                            <tr>
                                <td>{{ $document->registry_number }}</td>
                                <td>
                                    {{ $document->type }}
                                    @if($document->is_duplicata)
                                    <span class="badge bg-info">Duplicata</span>
                                    @endif
                                </td>
                                <td>{{ $document->user->nom ?? 'N/A' }}</td>
                                <td>{{ $document->updated_at ? $document->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $document->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('agent.documents.show', $document->id) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($document->pdf_path)
                                            <a href="{{ Storage::url($document->pdf_path) }}" class="btn btn-sm btn-secondary" title="Télécharger le PDF" download>
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif
                                        @if($document->status === 'APPROUVEE') {{-- Changed 'approuvee' to 'APPROUVEE' to match Enum --}}
                                            @if($document->type->value === 'naissance')
                                                <a href="{{ route('agent.documents.certificats.naissance', $document->id) }}" class="btn btn-sm btn-info" title="Imprimer acte de naissance" target="_blank">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            @elseif($document->type->value === 'mariage')
                                                <a href="{{ route('agent.documents.certificats.mariage', $document->id) }}" class="btn btn-sm btn-info" title="Imprimer acte de mariage" target="_blank">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            @elseif($document->type->value === 'deces')
                                                <a href="{{ route('agent.documents.certificats.deces', $document->id) }}" class="btn btn-sm btn-info" title="Imprimer acte de décès" target="_blank">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            @endif
                                        @endif
                                        @if(!$document->is_duplicata && $document->status === 'APPROUVEE') {{-- Changed 'approuvee' to 'APPROUVEE' to match Enum --}}
                                            <form action="{{ route('agent.documents.createDuplicata', $document->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir créer un duplicata de ce document ?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" title="Créer un duplicata">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Aucune demande approuvée trouvée pour votre commune.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2">
                    {{ $documentsApprouves->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection {{-- This is the correct closing for @section('content') --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');

    function applyFilters() {
        filterForm.submit();
    }

    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });

    typeFilter.addEventListener('change', function() {
        applyFilters();
    });
});
</script>
@endpush
