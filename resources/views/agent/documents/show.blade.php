@extends('layouts.app')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

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
                                <h5 class="text-secondary">
                                    <i class="bi bi-info-circle me-2"></i>Informations générales
                                </h5>
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
                                <h5 class="text-secondary">
                                    <i class="bi bi-person me-2"></i>Agent traitant
                                </h5>
                                <div class="row mt-3">
                                    <div class="col-12 mb-2">
                                        <p class="mb-1 text-muted">Nom</p>
                                        <p class="fw-bold">{{ $document->agent->nom ?? '-' }} {{ $document->agent->prenom ?? '-' }}</p>
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
                                    <h5 class="text-secondary">
                                        <i class="bi bi-baby me-2"></i>Détails de la naissance
                                    </h5>
                                    <div class="row mt-3">
                                        @foreach(['nom_enfant' => "Nom de l'enfant", 'prenom_enfant' => "Prénom de l'enfant", 'date_naissance' => "Date de naissance", 'nom_pere' => "Nom du père", 'nom_mere' => "Nom de la mère"] as $key => $label)
                                            <div class="col-sm-6 mb-2">
                                                <p class="mb-1 text-muted">{{ $label }}</p>
                                                <p class="fw-bold">{{ $document->metadata[$key] ?? 'Non renseigné' }}</p>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($document->type->value === 'mariage')
                                    <h5 class="text-secondary">
                                        <i class="bi bi-heart me-2"></i>Détails du mariage
                                    </h5>
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
                                    <h5 class="text-secondary">
                                        <i class="bi bi-flag me-2"></i>Détails du décès
                                    </h5>
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
                                    <h5 class="text-secondary">
                                        <i class="bi bi-file-text me-2"></i>Détails supplémentaires
                                    </h5>
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
                        <h5 class="text-secondary">
                            <i class="bi bi-paperclip me-2"></i>Documents joints
                        </h5>
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
                                                Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted">Aucun document joint disponible.</p>
                                </div>
                            @endforelse
                            {{-- Actions d'approbation --}}
                            {{-- @if(in_array($document->status->value, ['en_attente', 'en attente'])) --}}
                            <div class="mt-4 d-flex justify-content-end gap-3">
                                {{-- Bouton de validation --}}
                                <form action="{{ route('agent.documents.approve', $document) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> Valider
                                    </button>
                                </form>

                                {{-- Bouton de rejet --}}
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="bi bi-x-circle me-1"></i> Rejeter
                                </button>
                                <a href="{{ route('agent.documents.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                                </a>
                            </div>
                            {{-- @endif --}}
                        </div>
                    </div>
                    @if($document->status->value === 'APPROUVEE')
                        <div class="approved-document-template mt-5">
                            <h4 class="mb-4">Document Approuvé</h4>

                            {{-- Debug: Afficher les informations --}}
                            <div class="alert alert-info">
                                <p>Type: {{ $document->type->value }}</p>
                                <p>PDF Path: {{ $document->pdf_path ?? 'Non généré' }}</p>
                            </div>

                            @if($document->type->value === 'naissance')
                                @if(View::exists('certificats.naissance'))
                                    @include('certificats.naissance', ['document' => $document])
                                @else
                                    <div class="alert alert-warning">Template naissance non trouvé</div>
                                @endif
                            @elseif($document->type->value === 'mariage')
                                @if(View::exists('certificats.mariage'))
                                    @include('certificats.mariage', ['document' => $document])
                                @else
                                    <div class="alert alert-warning">Template mariage non trouvé</div>
                                @endif
                            @elseif($document->type->value === 'deces')
                                @if(View::exists('certificats.deces'))
                                    @include('certificats.deces', ['document' => $document])
                                @else
                                    <div class="alert alert-warning">Template décès non trouvé</div>
                                @endif
                            @endif

                            @if($document->pdf_path && file_exists(public_path($document->pdf_path)))
                                <a href="{{ $document->pdf_path }}"
                                class="btn btn-primary mt-3"
                                target="_blank">
                                    <i class="bi bi-download me-2"></i>Télécharger le PDF
                                </a>
                            @else
                                <div class="alert alert-danger mt-3">Le PDF n'est pas disponible</div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-secondary mt-3">
                            Statut actuel: {{ $document->status->value }} - La section d'approbation ne s'affiche que pour les documents approuvés
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('agent.documents.reject', $document) }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Motif du rejet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="reason" class="form-label">Veuillez indiquer la raison du rejet :</label>
                    <textarea class="form-control" name="reason" id="reason" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection
