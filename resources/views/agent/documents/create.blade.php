@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Créer un document</h1>

    <form action="{{ route('agent.documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Type :</label>
            <select name="type" id="type" onchange="toggleMetadataFields()" required>
                <option value="">-- Choisir un type --</option>
                <option value="naissance">Naissance</option>
                <option value="mariage">Mariage</option>
                <option value="deces">Décès</option>
            </select>
        </div>

        {{-- Champ supprimé car registry_number est généré automatiquement --}}
        {{-- <div>
            <label>N° registre :</label>
            <input type="text" name="registry_number" required>
        </div> --}}

        <div>
            <label>Page :</label>
            <input type="number" name="registry_page">
        </div>

        <div>
            <label>Volume :</label>
            <input type="text" name="registry_volume">
        </div>

        <!-- CHAMPS METADATA POUR NAISSANCE -->
        <div id="naissance-fields" style="display:none;">
            <h4>Détails Naissance</h4>
            <label>Nom de l'enfant :</label>
            <input type="text" name="nom_enfant">

            <label>Date de naissance :</label>
            <input type="date" name="date_naissance">

            <label>Lieu de naissance :</label>
            <input type="text" name="lieu_naissance">

            <label>Nom du père :</label>
            <input type="text" name="nom_pere">

            <label>Nom de la mère :</label>
            <input type="text" name="nom_mere">
        </div>

        <!-- CHAMPS METADATA POUR MARIAGE -->
        <div id="mariage-fields" style="display:none;">
            <h4>Détails Mariage</h4>
            <label>Nom époux :</label>
            <input type="text" name="nom_epoux">

            <label>Nom épouse :</label>
            <input type="text" name="nom_epouse">

            <label>Date du mariage :</label>
            <input type="date" name="date_mariage">

            <label>Lieu du mariage :</label>
            <input type="text" name="lieu_mariage">
        </div>

        <!-- CHAMPS METADATA POUR DECES -->
        <div id="deces-fields" style="display:none;">
            <h4>Détails Décès</h4>
            <label>Nom du défunt :</label>
            <input type="text" name="nom_defunt">

            <label>Date du décès :</label>
            <input type="date" name="date_deces">

            <label>Lieu du décès :</label>
            <input type="text" name="lieu_deces">
        </div>

        <div>
            <label>Justificatif :</label>
            <input type="file" name="justificatif" required>
        </div>

        {{-- Champ masqué pour l'ID de la commune --}}
        <input type="hidden" name="commune_id" value="{{ Auth::user()->commune_id }}">

        {{-- Champ masqué pour l'ID de l'utilisateur (agent) --}}
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        {{-- Affichage en lecture seule des infos de l’agent --}}
        <div class="mb-3">
            <label>Agent traitant :</label>
            <input type="text" class="form-control" value="{{ Auth::user()->nom }}" readonly>
        </div>

        <div class="mb-3">
            <label>Commune :</label>
            <input type="text" class="form-control" value="{{ Auth::user()->commune->name ?? 'Non défini' }}" readonly>
        </div>

            <label>Date de traitement :</label>
            <input type="date" name="traitement_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
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
        toggleMetadataFields();
    });
</script>
@endsection
