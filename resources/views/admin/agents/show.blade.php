@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Agent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa; /* fond gris très clair */
            font-family: 'Inter', sans-serif; /* Using Inter for consistency */
        }
        .card {
            border-radius: 12px;
        }
        .card-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            background-color: #0d6efd; /* Primary color for header */
            color: white;
        }
        .btn {
            border-radius: 30px;
        }
        .img-fluid {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow border-0">
                    <div class="card-header text-center">
                        <h4 class="mb-0"><i class="bi bi-person-vcard-fill me-2"></i> Détails de l'agent</h4>
                    </div>

                    <div class="card-body">

                        {{-- Display agent photo or a placeholder avatar --}}
                        <div class="text-center mb-4">
                            @if (isset($agent) && $agent->photo)
                                <img src="{{ asset('storage/' . $agent->photo) }}" alt="Photo de {{ $agent->prenom }} {{ $agent->nom }}"
                                     class="rounded-circle img-fluid shadow-sm border border-2"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                {{-- Fallback to UI Avatars if no photo is available or $agent is not set --}}
                                @php
                                    $nameForAvatar = (isset($agent) && $agent->prenom && $agent->nom) ? urlencode($agent->prenom . ' ' . $agent->nom) : 'Agent';
                                @endphp
                                <img src="https://ui-avatars.com/api/?name={{ $nameForAvatar }}&background=0d6efd&color=fff&size=150"
                                     alt="Avatar de {{ $nameForAvatar }}"
                                     class="rounded-circle img-fluid shadow-sm border border-2"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                        </div>

                        {{-- Display agent details, with checks for $agent existence --}}
                        @if (isset($agent))
                            <p><strong>Nom :</strong> {{ $agent->nom }}</p>
                            <p><strong>Prénom :</strong> {{ $agent->prenom }}</p>
                            <p><strong>Email :</strong> {{ $agent->email }}</p>
                            <p><strong>Téléphone :</strong> {{ $agent->telephone ?? 'N/A' }}</p>
                            <p><strong>Commune :</strong> {{ $agent->commune?->name ?? 'N/A' }}</p>
                            <p><strong>Rôle :</strong> {{ ucfirst($agent->role) }}</p>

                            {{-- Dynamically generate the edit link using the agent's ID --}}
                            <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-primary mt-3">
                                <i class="bi bi-pencil-square me-1"></i> Modifier
                            </a>
                        @else
                            <p class="text-muted text-center">Les détails de l'agent ne sont pas disponibles.</p>
                        @endif

                        {{-- Dynamically generate the back to list link --}}
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary mt-3 ms-2">
                            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
@endsection
