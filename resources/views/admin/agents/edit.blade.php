@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Agent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            padding: 2rem 0;
        }
        .form-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .form-header {
            background: linear-gradient(to right, #6366f1, #4f46e5);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .form-header i {
            font-size: 1.5rem;
        }
        .form-content {
            padding: 2rem;
        }
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }
        .photo-upload {
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .photo-upload:hover {
            border-color: #6366f1;
            background-color: #f8f9fa;
        }
        .current-photo {
            max-width: 150px;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .photo-preview {
            max-width: 150px;
            max-height: 150px;
            margin: 1rem auto;
            display: none;
            border-radius: 0.5rem;
        }
        .error-container {
            background-color: #fff5f5;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            display: none;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }
        .btn-primary {
            background: #6366f1;
            border: none;
        }
        .btn-primary:hover {
            background: #4f46e5;
        }
        .loading {
            display: none;
        }
        .loading.active {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
            border: 2px solid #fff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @media (max-width: 768px) {
            .form-content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <i class="bi bi-person-gear"></i>
                <h1 class="h3 mb-0">Modifier l'agent</h1>
            </div>

            <div class="form-content">
                <div class="error-container" id="errorContainer">
                    <h5 class="text-danger mb-2">Veuillez corriger les erreurs suivantes:</h5>
                    <ul class="mb-0" id="errorList"></ul>
                </div>

                <form id="agentForm" novalidate>
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
                                    <img id="photoPreview" class="photo-preview" alt="Preview">
                                    <input type="file" class="d-none" id="photo" name="photo" accept="image/*">
                                </div>
                                <small class="text-muted">Laissez vide si vous ne souhaitez pas modifier la photo.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $agent->nom) }}" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                 <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom', $agent->prenom) }}" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $agent->email) }}" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                 <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone', $agent->telephone) }}">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="commune_id" class="form-label">Commune <span class="text-danger">*</span></label>
                                <select class="form-select" id="commune_id" name="commune_id" required>
                                    <option value="">-- Sélectionnez une commune --</option>
                                    <option value="1">marcory</option>
                                    <option value="2">treichvile</option>
                                    <option value="3">port-bouet</option>
                                    <option value="4">plateau</option>
                                    <option value="5">abobo</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                               <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                <small class="text-muted">Laissez vide si vous ne souhaitez pas modifier le mot de passe.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" id="cancelBtn">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="loading" id="loadingSpinner"></span>
                            <span id="submitText">Enregistrer les modifications</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate existing agent data
            const mockAgent = {
                nom: 'Dupont',
                prenom: 'Jean',
                email: 'jean.dupont@example.com',
                telephone: '0123456789',
                commune_id: '1',
                role: 'admin',
                adresse: '123 rue de Paris'
            };

            // Fill form with mock data
            Object.keys(mockAgent).forEach(key => {
                const field = document.getElementById(key);
                if (field) {
                    field.value = mockAgent[key];
                }
            });

            const form = document.getElementById('agentForm');
            const photoUpload = document.getElementById('photoUpload');
            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('photoPreview');
            const errorContainer = document.getElementById('errorContainer');
            const errorList = document.getElementById('errorList');
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const submitText = document.getElementById('submitText');

            photoUpload.addEventListener('click', () => photoInput.click());

            photoInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        photoPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            function validateForm() {
                const errors = [];
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    field.classList.remove('is-invalid');
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        field.nextElementSibling.textContent = 'Ce champ est requis';
                        errors.push(`Le champ ${field.previousElementSibling.textContent.replace('*', '')} est requis`);
                    }
                });

                const email = form.querySelector('#email');
                if (email.value && !/\S+@\S+\.\S+/.test(email.value)) {
                    email.classList.add('is-invalid');
                    email.nextElementSibling.textContent = 'Email invalide';
                    errors.push('Format d\'email invalide');
                }

                const telephone = form.querySelector('#telephone');
                if (telephone.value && !/^\d{10}$/.test(telephone.value.replace(/\s/g, ''))) {
                    telephone.classList.add('is-invalid');
                    telephone.nextElementSibling.textContent = 'Numéro de téléphone invalide';
                    errors.push('Format de téléphone invalide');
                }

                const password = form.querySelector('#password');
                if (password.value && password.value.length < 8) {
                    password.classList.add('is-invalid');
                    password.nextElementSibling.textContent = 'Le mot de passe doit contenir au moins 8 caractères';
                    errors.push('Le mot de passe doit contenir au moins 8 caractères');
                }

                if (errors.length > 0) {
                    errorList.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
                    errorContainer.style.display = 'block';
                    return false;
                }

                errorContainer.style.display = 'none';
                return true;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateForm()) {
                    loadingSpinner.classList.add('active');
                    submitBtn.disabled = true;
                    submitText.textContent = 'Enregistrement...';

                    // Simulate form submission
                    setTimeout(() => {
                        alert('Agent modifié avec succès!');
                        loadingSpinner.classList.remove('active');
                        submitBtn.disabled = false;
                        submitText.textContent = 'Enregistrer les modifications';
                    }, 1500);
                }
            });

            document.getElementById('cancelBtn').addEventListener('click', () => {
                if (confirm('Voulez-vous vraiment annuler ? Les modifications non enregistrées seront perdues.')) {
                    window.location.href = '#'; // In a real app, this would redirect to the agents list
                }
            });
        });
    </script>
</body>
</html>
@endsection
