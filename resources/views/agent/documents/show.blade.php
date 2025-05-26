@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        Demande #{{ $document->registry_number }}
    </div>

    <div class="card-body">
        <div class="row">
            {{-- Colonne Informations --}}
            <div class="col-md-6">
                <h5>Informations générales</h5>
                <p><strong>Type :</strong> {{ ucfirst($document->type->value) }}</p>
                <p><strong>Statut :</strong>
                    @php
                        $statusColor = match($document->status->value) {
                            'approuvee' => 'success',
                            'rejettee' => 'danger',
                            default => 'warning',
                        };
                    @endphp
                    <span class="badge bg-{{ $statusColor }}">
                        {{ ucfirst($document->status->value) }}
                    </span>
                </p>
            </div>

            {{-- Colonne Détails spécifiques selon le type --}}
            <div class="col-md-6">
                @if($document->type->value === 'naissance')
                    <h5>Détails de la naissance</h5>
                    <p><strong>Nom de l’enfant :</strong> {{ $document->metadata['nom_enfant'] ?? 'Non renseigné' }}</p>
                    <p><strong>Prénom de l’enfant :</strong> {{ $document->metadata['prenom_enfant'] ?? 'Non renseigné' }}</p>
                    <p><strong>Nom du père :</strong> {{ $document->metadata['nom_pere'] ?? 'Non renseigné' }}</p>
                    <p><strong>Nom de la mère :</strong> {{ $document->metadata['nom_mere'] ?? 'Non renseigné' }}</p>
                    <p><strong>Date de naissance :</strong> {{ $document->metadata['date_naissance'] ?? 'Non renseignée' }}</p>

                @elseif($document->type->value === 'mariage')
                    <h5>Détails du mariage</h5>
                    <p><strong>Nom époux :</strong> {{ $document->metadata['nom_epoux'] ?? 'Non renseigné' }}</p>
                    <p><strong>Nom épouse :</strong> {{ $document->metadata['nom_epouse'] ?? 'Non renseigné' }}</p>
                    <p><strong>Date du mariage :</strong> {{ $document->metadata['date_mariage'] ?? 'Non renseignée' }}</p>

                @elseif($document->type->value === 'deces')
                    <h5>Détails du décès</h5>
                    <p><strong>Nom défunt :</strong> {{ $document->metadata['nom_defunt'] ?? 'Non renseigné' }}</p>
                    <p><strong>Date du décès :</strong> {{ $document->metadata['date_deces'] ?? 'Non renseignée' }}</p>

                @else
                    <h5>Détails supplémentaires</h5>
                    @foreach($document->metadata as $key => $value)
                        <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }} :</strong> {{ $value }}</p>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Boutons d’action --}}
        <div class="mt-4">
            <form action="{{ route('agent.documents.approve', $document) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Valider
                </button>
            </form>

            <form action="{{ route('agent.documents.reject', $document) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times-circle"></i> Rejeter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
