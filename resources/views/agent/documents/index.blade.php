@extends('layouts.app')

@section('content')

<div class="container-fluid py-2 px-3 px-sm-4 px-lg-5" style="margin-top: 0;">
  <div class="col-12">
    <div class="card">
      <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="card-title mb-0">Liste des Demandes</h5>
                <div class="d-flex flex-wrap gap-2">
                <div class="search-container me-2 mb-2 mb-sm-0">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="form-control" id="searchInput" placeholder="Rechercher...">
                </div>
                <div class="status-filter">
                    <button class="btn btn-sm btn-primary active" data-status="all">Toutes</button>
                    <button class="btn btn-sm btn-outline-warning" data-status="APPROUVEE">Approuvées</button>
                    <button class="btn btn-sm btn-outline-success" data-status="EN_ATTENTE">En attente</button>
                    <button class="btn btn-sm btn-outline-danger" data-status="REJETEE">Rejetées</button>
                    <button class="btn btn-sm btn-outline-info" data-status="duplicata">Duplicatas</button>
                </div>
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
            @foreach ($documents as $document)
                <tr
                    data-registry="{{ strtolower($document->registry_number) }}"
                    data-status="{{ strtolower($document->status->value) }}"
                    data-duplicata="{{ $document->is_duplicata ? 'true' : 'false' }}"
                >
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
                        <span class="{{ $document->status === 'APPROUVEE' ? 'bg-green-100 text-green-800' :
                                    ($document->status === 'REJETEE' ? 'bg-red-100 text-red-800' :
                                    'bg-yellow-100 text-yellow-800') }}">
                            {{ $document->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('agent.documents.show', $document->id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                        <a href="{{ route('agent.documents.edit', $document->id) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                        @if(!$document->is_duplicata && $document->status === 'APPROUVEE')
                            <a href="{{ route('agent.documents.create.duplicata', $document->id) }}"
                            class="btn btn-sm btn-outline-info"
                            title="Créer un duplicata">
                                <i class="bi bi-copy"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>

          </table>
        </div>
        <div class="px-3 py-2">
          {{ $documents->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Gestion de la recherche
  const searchInput = document.getElementById('searchInput');
  searchInput.addEventListener('input', function() {
    filterRequests();
  });

  // Gestion des filtres par statut
  const statusButtons = document.querySelectorAll('.status-filter button');
  statusButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Mettre à jour l'état actif des boutons
      statusButtons.forEach(btn => {
        btn.classList.remove('active', 'btn-primary', 'btn-success', 'btn-warning', 'btn-danger');
        btn.classList.add('btn-outline-primary', 'btn-outline-success', 'btn-outline-warning', 'btn-outline-danger');
      });

      // Appliquer la classe active au bouton cliqué
      this.classList.remove('btn-outline-primary', 'btn-outline-success', 'btn-outline-warning', 'btn-outline-danger');

      if (this.dataset.status === 'APPROUVEE') {
        this.classList.add('btn-success', 'active');
      } else if (this.dataset.status === 'EN_ATTENTE') {
        this.classList.add('btn-warning', 'active');
      } else if (this.dataset.status === 'REJETEE') {
        this.classList.add('btn-danger', 'active');
      } else {
        this.classList.add('btn-primary', 'active');
      }

      filterRequests();
    });
  });

 function filterRequests() {
    const searchTerm = searchInput.value.toLowerCase();
    const activeStatus = document.querySelector('.status-filter button.active').dataset.status;

    document.querySelectorAll('#requestsTableBody tr').forEach(row => {
        const rowStatus = row.dataset.status;
        const rowSearch = row.dataset.registry;
        const isDuplicata = row.dataset.duplicata === 'true';

        // Vérifier le statut
        let statusMatch;
        if (activeStatus === 'duplicata') {
            statusMatch = isDuplicata;
        } else {
            statusMatch = activeStatus === 'all' || rowStatus === activeStatus.toLowerCase();
        }

        // Vérifier la recherche
        const searchMatch = rowSearch.includes(searchTerm);

        // Afficher ou masquer la ligne
        row.style.display = statusMatch && searchMatch ? '' : 'none';
    });
}

  // Initialisation du filtrage
  filterRequests();
});
</script>
@endpush
<script>
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const statusButtons = document.querySelectorAll(".status-filter button");
  const rows = document.querySelectorAll("#requestsTableBody tr");

  let currentStatus = "all";

  function filterTable() {
    const searchText = searchInput.value.toLowerCase();

    rows.forEach(row => {
      const registry = row.getAttribute("data-registry");
      const status = row.getAttribute("data-status");

      const matchesSearch = registry.includes(searchText);
      const matchesStatus = (currentStatus === "all") || (status === currentStatus);

      if (matchesSearch && matchesStatus) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  }

  searchInput.addEventListener("input", filterTable);

  statusButtons.forEach(button => {
    button.addEventListener("click", function () {
      // Changer le style actif
      statusButtons.forEach(btn => btn.classList.remove("active", "btn-primary"));
      statusButtons.forEach(btn => btn.classList.add("btn-outline-" + btn.dataset.status));

      this.classList.add("active", "btn-primary");
      this.classList.remove("btn-outline-" + this.dataset.status);

      currentStatus = this.dataset.status;
      filterTable();
    });
  });
});
</script>

@endsection
