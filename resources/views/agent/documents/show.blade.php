@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        Demande #{{ $document->registry_number }}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Informations</h5>
                <p><strong>Type :</strong> {{ ucfirst($document->type->value) }}</p>
                <p><strong>Statut :</strong>
                    <span class="badge bg-{{ $document->status === 'approuvee' ? 'success' : 'warning' }}">
                        {{ $document->status }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                @if($document->type->value === 'naissance')
                    <h5>Détails naissance</h5>
                    <p><strong>Nom enfant :</strong> {{ $document->metadata['nom_enfant'] }}</p>
                    <p><strong>Date :</strong> {{ $document->metadata['date_naissance'] }}</p>
                @endif
            </div>
        </div>

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
