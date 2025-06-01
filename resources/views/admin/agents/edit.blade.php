@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-container">
        <div class="form-header">
            <i class="bi bi-person-gear"></i>
            <h1 class="h3 mb-0">Modifier l'agent</h1>
        </div>

        <div class="form-content">
            @if ($errors->any())
                <div class="error-container" id="errorContainer" style="display:block;">
                    <h5 class="text-danger mb-2">Veuillez corriger les erreurs suivantes:</h5>
                    <ul class="mb-0" id="errorList">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data" id="agentForm" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Photo actuelle</label>
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $agent->photo) }}" alt="Photo actuelle" class="img-thumbnail" width="150">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Changer la photo</label>
                            <div class="photo-upload" id="photoUpload">
                                <i class="bi bi-cloud-upload fs-3 text-primary"></i>
                                <p class="mb-0 mt-2">Cliquez pour sélectionner une nouvelle photo</p>
                                <small class="text-muted d-block">JPG, PNG, GIF (max 2MB)</small>
                                <img id="photoPreview" src="{{ asset('storage/img/images.png') }}" class="photo-preview" alt="Preview">
                                <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                            </div>
                            <small class="text-muted">Laissez vide si vous ne souhaitez pas modifier la photo.</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @foreach (['nom', 'prenom', 'email', 'telephone'] as $field)
                            <div class="mb-3">
                                <label for="{{ $field }}" class="form-label">{{ ucfirst($field) }} <span class="text-danger">*</span></label>
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}"
                                       name="{{ $field }}"
                                       id="{{ $field }}"
                                       class="form-control @error($field) is-invalid @enderror"
                                       value="{{ old($field, $agent->$field) }}"
                                       required>
                                @error($field)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="commune_id" class="form-label">Commune <span class="text-danger">*</span></label>
                            <select class="form-select @error('commune_id') is-invalid @enderror" id="commune_id" name="commune_id" required>
                                <option value="">-- Sélectionnez une commune --</option>
                                @foreach ($communes as $commune)
                                    <option value="{{ $commune->id }}" {{ old('commune_id', $agent->commune_id) == $commune->id ? 'selected' : '' }}>
                                        {{ ucfirst($commune->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('commune_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            <small class="text-muted">Laissez vide si vous ne souhaitez pas modifier le mot de passe.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ old('adresse', $agent->adresse) }}">
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="loading d-none" id="loadingSpinner"></span>
                        <span id="submitText">Enregistrer les modifications</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
@endsection
