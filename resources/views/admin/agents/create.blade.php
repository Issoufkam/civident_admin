@extends('layouts.app')

@section('content')

@php
    $isEdit = isset($agent);
@endphp

<head>
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; padding: 2rem 0; }
        .form-container { background: white; border-radius: 1rem; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1); overflow: hidden; }
        .form-header { background: linear-gradient(to right, #0d6efd, #0a58ca); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; }
        .form-header i { font-size: 1.5rem; }
        .form-content { padding: 2rem; }
        .form-control, .form-select { padding: 0.75rem 1rem; border-radius: 0.5rem; transition: all 0.2s; }
        .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
        .photo-upload { border: 2px dashed #dee2e6; border-radius: 0.5rem; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.2s; }
        .photo-upload:hover { border-color: #0d6efd; background-color: #f8f9fa; }
        .photo-preview { max-width: 150px; max-height: 150px; margin: 1rem auto; display: none; }
        .error-container { background-color: #fff5f5; border-left: 4px solid #dc3545; padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.5rem; display: none; }
        .btn { padding: 0.75rem 1.5rem; font-weight: 500; }
        .btn-primary { background: #0d6efd; border: none; }
        .btn-primary:hover { background: #0a58ca; }
        .loading { display: none; }
        .loading.active { display: inline-block; width: 1rem; height: 1rem; margin-right: 0.5rem; border: 2px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (max-width: 768px) { .form-content { padding: 1.5rem; } }
    </style>
</head>

<body>
<div class="container">
    <div class="form-container">
        <div class="form-header">
            <i class="bi bi-person-plus-fill"></i>
            <h1 class="h3 mb-0">{{ $isEdit ? 'Modifier l’agent' : 'Ajouter un nouvel agent' }}</h1>
        </div>

        <div class="form-content">
            <div class="error-container" id="errorContainer">
                <h5 class="text-danger mb-2">Veuillez corriger les erreurs suivantes:</h5>
                <ul class="mb-0" id="errorList"></ul>
            </div>

            <form id="agentForm" novalidate action="{{ $isEdit ? route('admin.agents.update', $agent) : route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        @foreach (['nom' => 'Nom', 'prenom' => 'Prénom', 'telephone' => 'Téléphone', 'email' => 'Email'] as $field => $label)
                            <div class="mb-3">
                                <label for="{{ $field }}" class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                                <input
                                    type="{{ $field === 'email' ? 'email' : 'text' }}"
                                    class="form-control @error($field) is-invalid @enderror"
                                    id="{{ $field }}"
                                    name="{{ $field }}"
                                    value="{{ old($field, $agent->$field ?? '') }}"
                                    required
                                >
                                @error($field)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Photo de profil</label>
                            <div class="photo-upload" id="photoUpload">
                                <i class="bi bi-cloud-upload fs-3 text-primary"></i>
                                <p class="mb-0 mt-2">Cliquez pour sélectionner une photo</p>
                                <small class="text-muted d-block">JPG, PNG, GIF (max 2MB)</small>
                                @if ($isEdit && $agent->photo)
                                    <img src="{{ asset('storage/' . $agent->photo) }}" class="photo-preview img-fluid" style="display: block;" alt="Photo actuelle">
                                @else
                                    <img id="photoPreview" class="photo-preview img-fluid" alt="Preview">
                                @endif
                                <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="commune_id" class="form-label">Commune <span class="text-danger">*</span></label>
                            <select class="form-select @error('commune_id') is-invalid @enderror" id="commune_id" name="commune_id" required>
                                <option value="">-- Choisir une commune --</option>
                                @foreach($communes as $commune)
                                    <option value="{{ $commune->id }}" {{ old('commune_id', $agent->commune_id ?? '') == $commune->id ? 'selected' : '' }}>
                                        {{ $commune->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('commune_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">-- Choisir un rôle --</option>
                                @foreach(['admin' => 'Administrateur', 'agent' => 'Agent'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('role', $agent->role ?? '') == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe {{ $isEdit ? '' : ' *' }}</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse', $agent->adresse ?? '') }}">
                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="loading" id="loadingSpinner"></span>
                        <span id="submitText">{{ $isEdit ? 'Mettre à jour' : 'Enregistrer' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const photoUpload = document.getElementById('photoUpload');
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');

        photoUpload.addEventListener('click', () => photoInput.click());
        photoInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
</script>

</body>
@endsection
