@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 px-3 px-sm-4 px-lg-5" style="margin-top: 0;">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">Liste des Demandes</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <form id="filterForm" class="d-flex flex-wrap gap-2">
                            <div class="search-container me-2 mb-2 mb-sm-0">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="form-control" id="searchInput" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                            </div>
                            <div class="status-filter">
                                {{-- Les classes des boutons sont mises à jour pour refléter l'état initial en fonction de la requête --}}
                                <button type="button" class="btn btn-sm {{ !request('status') || request('status') === 'all' ? 'btn-primary active' : 'btn-outline-primary' }}" data-status="all">Toutes</button>
                                {{-- Correction : Pour 'APPROUVEE', utilisez btn-outline-success pour l'inactif et btn-success (ou primary) pour l'actif, cohérent avec 'bg-success' du badge --}}
                                <button type="button" class="btn btn-sm {{ request('status') === 'APPROUVEE' ? 'btn-success active' : 'btn-outline-success' }}" data-status="APPROUVEE">Approuvées</button>
                                <button type="button" class="btn btn-sm {{ request('status') === 'EN_ATTENTE' ? 'btn-warning active' : 'btn-outline-warning' }}" data-status="EN_ATTENTE">En attente</button>
                                <button type="button" class="btn btn-sm {{ request('status') === 'REJETEE' ? 'btn-danger active' : 'btn-outline-danger' }}" data-status="REJETEE">Rejetées</button>
                                <button type="button" class="btn btn-sm {{ request('status') === 'duplicata' ? 'btn-info active' : 'btn-outline-info' }}" data-status="duplicata">Duplicatas</button>
                            </div>
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
                                <th scope="col">ID</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Commune</th>
                                <th scope="col">Type</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requestsTableBody">
                            @forelse ($documents as $document)
                            <tr>
                                <td>{{ $document->registry_number }}</td>
                                <td>{{ $document->agent->nom ?? '-' }}</td>
                                <td>{{ $document->commune->name ?? '-' }}</td>
                                <td>
                                    {{ $document->type }}
                                    @if($document->is_duplicata)
                                    <span class="badge bg-info">Duplicata</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $document->status === 'APPROUVEE' ? 'bg-success' :
                                        ($document->status === 'REJETEE' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $document->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('agent.documents.show', $document->id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                        <a href="{{ route('agent.documents.edit', $document->id) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                        @if(!$document->is_duplicata && $document->status === 'APPROUVEE')
                                        <a href="{{ route('agent.documents.create.duplicata', $document->id) }}"
                                            class="btn btn-sm btn-outline-info"
                                            title="Créer un duplicata">
                                            <i class="bi bi-copy"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Aucune demande trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2">
                    {{ $documents->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusButtons = document.querySelectorAll('.status-filter button');

    function updateFilters() {
        const currentParams = new URLSearchParams(window.location.search);

        // Mise à jour du terme de recherche
        if (searchInput.value) {
            currentParams.set('search', searchInput.value);
        } else {
            currentParams.delete('search');
        }

        // Mise à jour du filtre de statut
        let activeStatus = null;
        statusButtons.forEach(button => {
            if (button.classList.contains('active')) {
                activeStatus = button.dataset.status;
            }
        });

        if (activeStatus && activeStatus !== 'all') {
            currentParams.set('status', activeStatus);
        } else {
            currentParams.delete('status');
        }

        // Réinitialisation de la pagination à la première page lorsque les filtres changent
        currentParams.delete('page');

        // Construire la nouvelle URL et rediriger
        window.location.href = window.location.pathname + '?' + currentParams.toString();
    }

    // Écouteur d'événement pour la saisie de recherche
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateFilters, 500); // Débouncer la recherche pendant 500ms
    });

    // Écouteurs d'événements pour les boutons de statut
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Mise à jour immédiate de la classe active pour un retour visuel
            statusButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary', 'btn-success', 'btn-warning', 'btn-danger', 'btn-info');
                // Réappliquer les classes outline pour les boutons inactifs
                let originalClass;
                if (btn.dataset.status === 'APPROUVEE') originalClass = 'btn-outline-success';
                else if (btn.dataset.status === 'EN_ATTENTE') originalClass = 'btn-outline-warning';
                else if (btn.dataset.status === 'REJETEE') originalClass = 'btn-outline-danger';
                else if (btn.dataset.status === 'duplicata') originalClass = 'btn-outline-info';
                else originalClass = 'btn-outline-primary'; // 'all'
                btn.classList.add(originalClass);
            });
            // Appliquer la classe active correcte
            let activeClass;
            if (this.dataset.status === 'APPROUVEE') activeClass = 'btn-success';
            else if (this.dataset.status === 'EN_ATTENTE') activeClass = 'btn-warning';
            else if (this.dataset.status === 'REJETEE') activeClass = 'btn-danger';
            else if (this.dataset.status === 'duplicata') activeClass = 'btn-info';
            else activeClass = 'btn-primary'; // 'all'
            this.classList.add('active', activeClass);

            updateFilters(); // Déclencher la mise à jour du filtre immédiatement
        });
    });

    // Initialiser les états des boutons en fonction des paramètres d'URL actuels
    const initialStatus = new URLSearchParams(window.location.search).get('status');
    statusButtons.forEach(button => {
        button.classList.remove('active', 'btn-primary', 'btn-success', 'btn-warning', 'btn-danger', 'btn-info');
        let initialClass;
        if (button.dataset.status === 'APPROUVEE') initialClass = 'btn-outline-success';
        else if (button.dataset.status === 'EN_ATTENTE') initialClass = 'btn-outline-warning';
        else if (button.dataset.status === 'REJETEE') initialClass = 'btn-outline-danger';
        else if (button.dataset.status === 'duplicata') initialClass = 'btn-outline-info';
        else initialClass = 'btn-outline-primary'; // 'all'

        if (button.dataset.status === (initialStatus || 'all')) {
            button.classList.remove(initialClass); // Remove outline if it was present
            let activeClass;
            if (button.dataset.status === 'APPROUVEE') activeClass = 'btn-success';
            else if (button.dataset.status === 'EN_ATTENTE') activeClass = 'btn-warning';
            else if (button.dataset.status === 'REJETEE') activeClass = 'btn-danger';
            else if (button.dataset.status === 'duplicata') activeClass = 'btn-info';
            else activeClass = 'btn-primary'; // 'all'
            button.classList.add('active', activeClass);
        } else {
            button.classList.add(initialClass);
        }
    });

});
</script>
@endpush

@endsection
