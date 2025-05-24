@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Détails de l'agent</h1>

    <div class="card">
        <div class="card-body">
            @if ($agent->photo)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $agent->photo) }}" alt="Photo de {{ $agent->prenom }} {{ $agent->nom }}" class="img-thumbnail" width="150">
                </div>
            @endif

            <p><strong>Nom :</strong> {{ $agent->nom }}</p>
            <p><strong>Prénom :</strong> {{ $agent->prenom }}</p>
            <p><strong>Email :</strong> {{ $agent->email }}</p>
            <p><strong>Téléphone :</strong> {{ $agent->telephone }}</p>
            <p><strong>Commune :</strong> {{ $agent->commune?->name ?? 'N/A' }}</p>
            <p><strong>Rôle :</strong> {{ ucfirst($agent->role) }}</p>

            <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-primary mt-3">Modifier</a>
            <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary mt-3">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection
