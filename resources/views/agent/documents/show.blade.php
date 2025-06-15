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

    /* Style pour le conteneur PDF */
    .pdf-container {
        width: 100%;
        height: 600px; /* Ajustez la hauteur selon vos besoins */
        border: 1px solid var(--gray-300);
        border-radius: 0.5rem;
        overflow: hidden; /* Important pour masquer les barres de défilement de l'iframe */
    }

    .pdf-container iframe {
        width: 100%;
        height: 100%;
        border: none;
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
                                        <p class="mb-1 text-muted">Date de traitement</p>
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
                                            <p class="mb-1 text-muted">Nationalité époux</p>
                                            <p class="fw-bold">{{ $document->metadata['nationalite_epoux'] ?? 'Non renseignée' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nom épouse</p>
                                            <p class="fw-bold">{{ $document->metadata['nom_epouse'] ?? 'Non renseignée' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Nationalité épouse</p>
                                            <p class="fw-bold">{{ $document->metadata['nationalite_epouse'] ?? 'Non renseignée' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <p class="mb-1 text-muted">Lieu de mariage</p>
                                            <p class="fw-bold">{{ $document->metadata['lieu_mariage'] ?? 'Non renseigné' }}</p>
                                        </div>
                                        <div class="col-sm-6 mb-2">
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
                            @if($document->justificatif_path)
                                @php
                                    $filePath = Storage::url($document->justificatif_path); // Utilisation de Storage::url()
                                    $extension = pathinfo($document->justificatif_path, PATHINFO_EXTENSION);
                                    $fileExists = Storage::disk('public')->exists($document->justificatif_path); // Vérifier l'existence
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <div class="card document-item h-100 border-{{ $fileExists ? 'success' : 'danger' }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                @if($fileExists)
                                                    @php
                                                        $icon = match(strtolower($extension)) {
                                                            'pdf' => 'file-earmark-pdf text-danger',
                                                            'jpg', 'jpeg', 'png' => 'file-earmark-image text-primary',
                                                            default => 'file-earmark-text text-secondary'
                                                        };
                                                    @endphp

                                                    <i class="bi bi-{{ $icon }} fs-3 me-3"></i>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title fw-bold">Justificatif principal</h6>
                                                        <p class="card-text text-muted small mb-2">
                                                            Ajouté le {{ $document->created_at->format('d/m/Y à H:i') }}
                                                        </p>

                                                        {{-- Aperçu de l'image ou lien pour PDF --}}
                                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                                            <div class="mt-2 mb-3">
                                                                <img src="{{ $filePath }}"
                                                                    class="img-thumbnail"
                                                                    style="max-height: 150px; cursor: pointer"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#imagePreviewModal"
                                                                    onclick="document.getElementById('previewImage').src = this.src; document.getElementById('downloadPreview').href = this.src;">
                                                            </div>
                                                        @elseif(strtolower($extension) === 'pdf')
                                                            <div class="mt-2 mb-3">
                                                                <a href="{{ $filePath }}" target="_blank" class="btn btn-sm btn-info">
                                                                    <i class="bi bi-eye"></i> Voir le PDF
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="btn-group align-self-start" role="group">
                                                        <a href="{{ route('agent.documents.download', ['document' => $document->id, 'type' => 'justificatif']) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           download>
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-secondary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#imagePreviewModal"
                                                                    onclick="document.getElementById('previewImage').src = '{{ $filePath }}'; document.getElementById('downloadPreview').href = '{{ $filePath }}';">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @else
                                                    <i class="bi bi-file-excel fs-3 me-3 text-danger"></i>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title fw-bold">Justificatif principal</h6>
                                                        <p class="card-text text-danger small">Fichier introuvable sur le serveur</p>
                                                        <p class="card-text text-muted small">Chemin: {{ $document->justificatif_path }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Section pour les pièces jointes supplémentaires (si vous les avez) --}}
                            @forelse($document->attachments as $attachment)
                                @php
                                    // Assurez-vous que votre modèle Attachment a une colonne 'path' et 'name'
                                    $attachmentPath = Storage::url($attachment->path);
                                    $attachmentExtension = pathinfo($attachment->path, PATHINFO_EXTENSION);
                                    $attachmentExists = Storage::disk('public')->exists($attachment->path);
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <div class="card document-item h-100 border-{{ $attachmentExists ? 'success' : 'danger' }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                @if($attachmentExists)
                                                    @php
                                                        $icon = match(strtolower($attachmentExtension)) {
                                                            'pdf' => 'file-earmark-pdf text-danger',
                                                            'jpg', 'jpeg', 'png' => 'file-earmark-image text-primary',
                                                            default => 'file-earmark-text text-secondary'
                                                        };
                                                    @endphp

                                                    <i class="bi bi-{{ $icon }} fs-3 me-3"></i>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title fw-bold">{{ $attachment->name }}</h6>
                                                        <p class="card-text text-muted small mb-2">
                                                            {{ $attachment->created_at->format('d/m/Y à H:i') }} •
                                                            {{ round(Storage::disk('public')->size($attachment->path) / 1024, 1) }} KB
                                                        </p>

                                                        @if(in_array(strtolower($attachmentExtension), ['jpg', 'jpeg', 'png']))
                                                            <div class="mt-2 mb-3">
                                                                <img src="{{ $attachmentPath }}"
                                                                    class="img-thumbnail"
                                                                    style="max-height: 150px; cursor: pointer"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#imagePreviewModal"
                                                                    onclick="document.getElementById('previewImage').src = this.src; document.getElementById('downloadPreview').href = this.src;">
                                                            </div>
                                                        @elseif(strtolower($attachmentExtension) === 'pdf')
                                                            <div class="mt-2 mb-3">
                                                                <a href="{{ $attachmentPath }}" target="_blank" class="btn btn-sm btn-info">
                                                                    <i class="bi bi-eye"></i> Voir le PDF
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="btn-group align-self-start" role="group">
                                                        <a href="{{ route('agent.documents.download', ['document' => $document->id, 'attachment' => $attachment->id]) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           download>
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        @if(in_array(strtolower($attachmentExtension), ['jpg', 'jpeg', 'png']))
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-secondary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#imagePreviewModal"
                                                                    onclick="document.getElementById('previewImage').src = '{{ $attachmentPath }}'; document.getElementById('downloadPreview').href = '{{ $attachmentPath }}';">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @else
                                                    <i class="bi bi-file-excel fs-3 me-3 text-danger"></i>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title fw-bold">{{ $attachment->name }}</h6>
                                                        <p class="card-text text-danger small">Fichier introuvable sur le serveur</p>
                                                        <p class="card-text text-muted small">Chemin: {{ $attachment->path }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">Aucun document supplémentaire joint.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    ---

                    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Aperçu du document</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="previewImage" src="" class="img-fluid" alt="Aperçu du document">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <a href="#" id="downloadPreview" class="btn btn-primary" download>
                                        <i class="bi bi-download me-1"></i>Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($document->status->value === 'APPROUVEE')
                        <div class="approved-document-template mt-5">
                            <h4 class="mb-4">Document Approuvé</h4>

                            {{-- Affichage direct du PDF s'il existe et est approuvé --}}
                            @if($document->pdf_path && Storage::disk('public')->exists($document->pdf_path))
                                <div class="pdf-container mb-4">
                                    <iframe src="{{ Storage::url($document->pdf_path) }}" frameborder="0"></iframe>
                                </div>
                                <a href="{{ Storage::url($document->pdf_path) }}"
                                   class="btn btn-primary"
                                   target="_blank" download>
                                    <i class="bi bi-download me-2"></i>Télécharger l'acte approuvé
                                </a>
                            @else
                                <div class="alert alert-warning">
                                    Le PDF de l'acte approuvé n'est pas encore disponible ou introuvable.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-secondary mt-3">
                            Statut actuel: {{ $document->status->value }} - La section d'approbation ne s'affiche que pour les documents approuvés
                        </div>
                    @endif
                    <div class="d-flex gap-1">
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
                </div>
            </div>
        </div>
    </div>
</div>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mise à jour du lien de téléchargement dans le modal
        $('#imagePreviewModal').on('show.bs.modal', function (event) {
            const imgSrc = document.getElementById('previewImage').src;
            document.getElementById('downloadPreview').href = imgSrc;
        });
    });
</script>
@endpush
@endsection
