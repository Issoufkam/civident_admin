@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier le document #{{ $document->registry_number }}</h2>

    <form action="{{ route('agent.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="type" class="form-label">Type de document</label>
            <select name="type" id="type" class="form-control" onchange="toggleMetadataFields()" required>
                <option value="">-- Choisir un type --</option>
                <option value="naissance" {{ $document->type == 'naissance' ? 'selected' : '' }}>Naissance</option>
                <option value="mariage" {{ $document->type == 'mariage' ? 'selected' : '' }}>Mariage</option>
                <option value="deces" {{ $document->type == 'deces' ? 'selected' : '' }}>Décès</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Page :</label>
            <input type="number" name="registry_page" class="form-control" value="{{ old('registry_page', $document->registry_page) }}">
        </div>

        <div class="mb-3">
            <label>Volume :</label>
            <input type="text" name="registry_volume" class="form-control" value="{{ old('registry_volume', $document->registry_volume) }}">
        </div>

        {{-- Champs Naissance --}}
        <div id="naissance-fields" style="display:none;">
            <h4>Détails Naissance</h4>
            <label>Nom de l'enfant :</label>
            <input type="text" name="nom_enfant" class="form-control" value="{{ old('nom_enfant', $document->nom_enfant) }}">

            <label>Prénom de l'enfant :</label>
            <input type="text" name="prenom_enfant" class="form-control" value="{{ old('prenom_enfant', $document->prenom_enfant) }}">

            <label>Sexe :</label>
            <input type="text" name="sexe" class="form-control" value="{{ old('sexe', $document->sexe) }}">

            <label>Date de naissance :</label>
            <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $document->date_naissance) }}">

            <label>Lieu de naissance :</label>
            <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $document->lieu_naissance) }}">

            <label>Nom du père :</label>
            <input type="text" name="nom_pere" class="form-control" value="{{ old('nom_pere', $document->nom_pere) }}">

            <label>Nom de la mère :</label>
            <input type="text" name="nom_mere" class="form-control" value="{{ old('nom_mere', $document->nom_mere) }}">
        </div>

        {{-- Champs Mariage --}}
        <div id="mariage-fields" style="display:none;">
            <h4>Détails Mariage</h4>
            <label>Nom époux :</label>
            <input type="text" name="nom_epoux" class="form-control" value="{{ old('nom_epoux', $document->nom_epoux) }}">

            <label>Nom épouse :</label>
            <input type="text" name="nom_epouse" class="form-control" value="{{ old('nom_epouse', $document->nom_epouse) }}">

            <label>Date du mariage :</label>
            <input type="date" name="date_mariage" class="form-control" value="{{ old('date_mariage', $document->date_mariage) }}">

            <label>Lieu du mariage :</label>
            <input type="text" name="lieu_mariage" class="form-control" value="{{ old('lieu_mariage', $document->lieu_mariage) }}">
        </div>

        {{-- Champs Décès --}}
        <div id="deces-fields" style="display:none;">
            <h4>Détails Décès</h4>
            <label>Nom du défunt :</label>
            <input type="text" name="nom_defunt" class="form-control" value="{{ old('nom_defunt', $document->nom_defunt) }}">

            <label>Date du décès :</label>
            <input type="date" name="date_deces" class="form-control" value="{{ old('date_deces', $document->date_deces) }}">

            <label>Lieu du décès :</label>
            <input type="text" name="lieu_deces" class="form-control" value="{{ old('lieu_deces', $document->lieu_deces) }}">
        </div>

        <div class="mb-3">
            <label>Justificatif (optionnel – laisser vide pour ne pas changer):</label>
            <input type="file" name="justificatif" class="form-control">
        </div>

        <div class="mb-3">
            <label>Statut :</label>
            <select name="status" class="form-control">
                @foreach (\App\Enums\DocumentStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected($document->status === $status->value)>
                        {{ $status->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Commune :</label>
            <input type="text" class="form-control" value="{{ $document->commune->name ?? 'Non défini' }}" readonly>
        </div>

        <div class="mb-3">
            <label>Date de traitement :</label>
            <input type="date" name="traitement_date" class="form-control" value="{{ old('traitement_date', $document->traitement_date ?? now()->format('Y-m-d')) }}" required>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>

<script>
    function toggleMetadataFields() {
        const type = document.getElementById('type').value;
        document.getElementById('naissance-fields').style.display = (type === 'naissance') ? 'block' : 'none';
        document.getElementById('mariage-fields').style.display = (type === 'mariage') ? 'block' : 'none';
        document.getElementById('deces-fields').style.display = (type === 'deces') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleMetadataFields(); // pour pré-remplir les champs affichés selon la valeur actuelle
    });
</script>
@endsection
