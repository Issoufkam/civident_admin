@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <title>Document</title>

    <style>
    body {
        background-color: #f8f9fa; /* fond gris très clair */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
        border-radius: 12px;
    }
    .card-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .btn {
        border-radius: 30px;
    }
</style>

</head>
<body>

    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-person-vcard-fill me-2"></i> Détails de l'agent</h4>
                </div>

                <div class="card-body">

                    @if ($agent->photo)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $agent->photo) }}" alt="Photo de {{ $agent->prenom }} {{ $agent->nom }}"
                                 class="rounded-circle img-fluid shadow-sm border border-2"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Nom</p>
                            <h6 class="fw-bold">{{ $agent->nom }}</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Prénom</p>
                            <h6 class="fw-bold">{{ $agent->prenom }}</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Email</p>
                            <h6 class="fw-bold">{{ $agent->email }}</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Téléphone</p>
                            <h6 class="fw-bold">{{ $agent->telephone }}</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Commune</p>
                            <h6 class="fw-bold">{{ $agent->commune?->nom ?? 'N/A' }}</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Rôle</p>
                            <h6 class="fw-bold">{{ ucfirst($agent->role) }}</h6>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square me-1"></i> Modifier
                        </a>
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
    
<!-- Bootstrap 5 JS (optionnel si tu veux des composants interactifs comme dropdowns, modals) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


@endsection
