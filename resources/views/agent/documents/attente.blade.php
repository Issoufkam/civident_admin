@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 px-3 px-sm-4 px-lg-5" style="margin-top: 0;">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">Liste des Demandes en Attente</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <form id="filterForm" class="d-flex flex-wrap gap-2" action="{{ route('agent.documents.attente') }}" method="GET">
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
                            {{-- Le bouton de statut "En attente" est fixe pour cette page --}}
                            <input type="hidden" name="status" value="EN_ATTENTE">
                            <button type="submit" class="btn btn-primary d-none">Appliquer les filtres</button> {{-- Bouton caché pour soumettre avec JS --}}
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
                                <th scope="col">Date de Création</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requestsTableBody">
                            @forelse ($documentsEnAttente as $document)
                            <tr>
                                <td>{{ $document->registry_number }}</td>
                                <td>
                                    {{ $document->type }}
                                    @if($document->is_duplicata)
                                    <span class="badge bg-info">Duplicata</span>
                                    @endif
                                </td>
                                <td>{{ $document->user->nom ?? 'N/A' }}</td>
                                <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $document->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('agent.documents.show', $document->id) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('agent.documents.edit', $document->id) }}" class="btn btn-sm btn-outline-secondary" title="Modifier le document">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('agent.documents.approve', $document->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir approuver ce document ?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Approuver le document">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger" title="Rejeter le document"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal" data-document-id="{{ $document->id }}">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Aucune demande en attente trouvée pour votre commune.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2">
                    {{ $documentsEnAttente->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Rejeter le Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Raison du rejet</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logic for the Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const documentId = button.getAttribute('data-document-id');
            const rejectForm = document.getElementById('rejectForm');
            // Update the form action URL
            rejectForm.action = `/agent/documents/${documentId}/reject`; // Adjust this route as per your web.php
        });
    }

    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');

    function applyFilters() {
        filterForm.submit();
    }

    // Déclencher la soumission du formulaire lors de la saisie dans la recherche (avec un délai)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500); // Débounce de 500ms
    });

    // Déclencher la soumission du formulaire lors du changement de type
    typeFilter.addEventListener('change', function() {
        applyFilters();
    });
});
</script>
@endpush
@endsection
