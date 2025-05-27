@extends('layouts.app')

@section('content')
<style>
    :root {
      --primary: #0056b3;
      --primary-light: rgba(0, 86, 179, 0.1);
      --secondary: #6c757d;
      --success: #28a745;
      --success-light: rgba(40, 167, 69, 0.1);
      --warning: #ffc107;
      --danger: #dc3545;
      --light: #f8f9fa;
      --dark: #343a40;
      --gray-100: #f8f9fa;
      --gray-200: #e9ecef;
      --gray-300: #dee2e6;
      --gray-400: #ced4da;
      --gray-500: #adb5bd;
      --gray-600: #6c757d;
      --gray-700: #495057;
      --gray-800: #343a40;
      --gray-900: #212529;
      --transition-speed: 0.3s;
    }

    /* Votre CSS personnalisé ici... */
    .detail-view {
      background-color: white;
      border-radius: 0.75rem;
      box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
    }

    .timeline-item {
      position: relative;
      padding-left: 2rem;
      padding-bottom: 1.5rem;
    }

    .timeline-item::before {
      content: '';
      position: absolute;
      left: 0.5rem;
      top: 0;
      height: 100%;
      width: 2px;
      background-color: var(--gray-300);
    }

    .badge-pending {
      background-color: var(--warning);
      color: var(--dark);
    }

    .badge-approved {
      background-color: var(--success);
      color: white;
    }

    .badge-rejected {
      background-color: var(--danger);
      color: white;
    }
</style>

<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow rounded-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Demande #{{ $document->registry_number }}</h4>
                </div>

                <div class="card-body detail-view">
                    <div class="row">
                        {{-- Colonne Informations --}}
                        <div class="col-md-6 mb-4">
                            <div class="detail-section">
                                <h5 class="text-secondary"><i class="bi bi-info-circle me-2"></i>Informations générales</h5>
                                <div class="row mt-3">
                                    <div class="col-sm-6 mb-2">
                                        <p class="mb-1 text-muted">Type</p>
                                        <p class="fw-bold">{{ ucfirst($document->type->value) }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <p class="mb-1 text-muted">Statut</p>
                                        <span class="badge
                                            {{ $document->status === 'approuvee' ? 'badge-approved' :
                                               ($document->status === 'rejettee' ? 'badge-rejected' : 'badge-pending') }}">
                                            {{ ucfirst($document->status->value) }}
                                        </span>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <p class="mb-1 text-muted">Date de création</p>
                                        <p class="fw-bold">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <p class="mb-1 text-muted">Dernière mise à jour</p>
                                        <p class="fw-bold">{{ $document->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-section mt-4">
                                <h5 class="text-secondary"><i class="bi bi-person me-2"></i>Agent traitant</h5>
                                <div class="row mt-3">
                                    <div class="col-12 mb-2">
                                        <p class="mb-1 text-muted">Nom</p>
                                        <p class="fw-bold">{{ $document->agent->nom }}  {{ $document->agent->prenom }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <p class="mb-1 text-muted">Email</p>
                                        <p class="fw-bold">{{ $document->agent->email ?? '-' }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <p class="mb-1 text-muted">Commune</p>
                                        <p class="fw-bold">{{ $document->commune->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Colonne Détails spécifiques --}}
                        <div class="col-md-6 mb-4">
                            <div class="detail-section">
                                @if($document->type->value === 'naissance')
                                    <h5 class="text-secondary"><i class="bi bi-baby me-2"></i>Détails de la naissance</h5>
                                    <div class="row mt-3">
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nom de l'enfant</p>
                                            <p class="fw-bold">{{ $document->metadata['nom_enfant'] ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Prénom de l'enfant</p>
                                            <p class="fw-bold">{{ $document->metadata['prenom_enfant'] ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Date de naissance</p>
                                            <p class="fw-bold">{{ $document->metadata['date_naissance'] ?? 'Non renseignée' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nom du père</p>
                                            <p class="fw-bold">{{ $document->metadata['nom_pere'] ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nom de la mère</p>
                                            <p class="fw-bold">{{ $document->metadata['nom_mere'] ?? 'Non renseigné' }}</p>
                                        </div>
                                    </div>

                                @elseif($document->type->value === 'mariage')
                                    <h5 class="text-secondary"><i class="bi bi-heart me-2"></i>Détails du mariage</h5>
                                    <div class="row mt-3">
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nom époux</p>
                                            <p class="fw-bold">{{ $document->metadata['nom_epoux'] ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nom épouse</p>
                                            <p class="fw-bold">{{ $document->metadata['nom_epouse'] ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <p class="mb-1 text-muted">Date du mariage</p>
                                            <p class="fw-bold">{{ $document->metadata['date_mariage'] ?? 'Non renseignée' }}</p>
                                        </div>
                                    </div>

                                @elseif($document->type->value === 'deces')
                                    <h5 class="text-secondary"><i class="bi bi-flag me-2"></i>Détails du décès</h5>
                                    <div class="row mt-3">
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nom défunt</p>
                                            <p class="fw-bold">{{ $document->metadata['nom_defunt'] ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Date du décès</p>
                                            <p class="fw-bold">{{ $document->metadata['date_deces'] ?? 'Non renseignée' }}</p>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <p class="mb-1 text-muted">Lieu du décès</p>
                                            <p class="fw-bold">{{ $document->metadata['lieu_deces'] ?? 'Non renseigné' }}</p>
                                        </div>
                                    </div>

                                @else
                                    <h5 class="text-secondary"><i class="bi bi-file-text me-2"></i>Détails supplémentaires</h5>
                                    <div class="row mt-3">
                                        @foreach($document->metadata as $key => $value)
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                                            <p class="fw-bold">{{ $value ?? 'Non renseigné' }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Documents joints --}}
                    <div class="detail-section mt-4">
                        <h5 class="text-secondary"><i class="bi bi-paperclip me-2"></i>Documents joints</h5>
                        <div class="row mt-3" id="documentsList">
                            @forelse($document->attachments as $doc)
                            <div class="col-md-4 mb-3">
                                <div class="document-item p-3 rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf fs-3 me-3 text-danger"></i>
                                        <div>
                                            <div class="fw-bold">{{ $doc->nom }}</div>
                                            <div class="text-muted small">
                                                {{ $doc->created_at->format('d/m/Y H:i') }} •
                                                {{ round($doc->size / 1024, 1) }} KB
                                            </div>
                                        </div>
                                        <a href="{{ route('documents.download', $doc) }}"
                                        class="btn btn-sm btn-outline-primary ms-auto">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">Aucun document joint</p>
                            </div>
                        @endforelse

                        </div>
                    </div>

                    {{-- Historique
                    <div class="detail-section mt-4">
                        <h5 class="text-secondary"><i class="bi bi-clock-history me-2"></i>Historique</h5>
                        <div class="timeline mt-3" id="requestTimeline">
                            @foreach($document->histories as $history)
                            <div class="timeline-item">
                                <div class="timeline-badge"></div>
                                <div class="mb-1">
                                    <span class="fw-bold">{{ $history->action }}</span>
                                    <span class="text-muted small ms-2">
                                        {{ $history->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <p class="mb-1">Agent: {{ $history->user->name ?? 'Système' }}</p>
                                <p class="text-muted small mb-0">{{ $history->comment }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div> --}}

                    {{-- Boutons d'action --}}
                    <div class="detail-section mt-4">
                        <h5 class="text-secondary"><i class="bi bi-gear me-2"></i>Actions</h5>
                        <div class="d-flex flex-wrap gap-3 mt-3">
                            @if($document->status === 'en_attente')
                            <form action="{{ route('agent.documents.approve', $document) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Valider
                                </button>
                            </form>

                            <form action="{{ route('agent.documents.reject', $document) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle me-1"></i> Rejeter
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('agent.documents.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <!-- Modal pour ajouter une note -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('agent.documents.add-note', $document) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="noteContent" class="form-label">Contenu de la note</label>
                        <textarea class="form-control" id="noteContent" name="note" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le modal de note
        const noteModal = new bootstrap.Modal(document.getElementById('noteModal'));

        // Gérer le clic sur le bouton "Ajouter une note"
        document.getElementById('addNoteBtn').addEventListener('click', function() {
            noteModal.show();
        });

        // Animation pour les éléments de la timeline
        const timelineItems = document.querySelectorAll('.timeline-item');
        timelineItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            item.style.transition = 'all 0.5s ease ' + (index * 0.1) + 's';

            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 100);
        });
    });
</script>
@endpush

@endsection
