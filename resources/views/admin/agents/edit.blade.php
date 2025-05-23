@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier agent</h1>

    {{-- Affichage des erreurs de validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire de modification --}}
    <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $agent->nom) }}" required>
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom', $agent->prenom) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $agent->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone', $agent->telephone) }}">
        </div>

        <div class="mb-3">
            <label for="commune_id" class="form-label">Commune</label>
            <select name="commune_id" id="commune_id" class="form-select">
                <option value="">-- Sélectionnez une commune --</option>
                @foreach ($communes as $commune)
                    <option value="{{ $commune->id }}" {{ $agent->commune_id == $commune->id ? 'selected' : '' }}>
                        {{ $commune->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rôle</label>
            <select name="role" id="role" class="form-select" required>
                <option value="agent" {{ $agent->role == 'agent' ? 'selected' : '' }}>Agent</option>
                <option value="admin" {{ $agent->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                <option value="superadmin" {{ $agent->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
