@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Tableau de Bord</h1>
            <p class="text-muted">Aperçu des demandes de documents</p>
        </div>
    </div>

    <div class="row stats-row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Demandes Totales</h6>
                            <h2 class="stats-number">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="stats-icon bg-primary-subtle">
                            <i class="bi bi-file-earmark-text text-primary"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-primary" role="progressbar"
                             style="width: {{ min(100, ($stats['total'] / ($stats['total_last_month'] ?: 1)) * 100) }}%"
                             aria-valuenow="{{ $stats['total'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total_last_month'] * 2 }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['total_change'] > 0)
                            <i class="bi bi-arrow-up-short text-success"></i>
                        @elseif($stats['total_change'] < 0)
                            <i class="bi bi-arrow-down-short text-danger"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['total_change']) }}% depuis le mois dernier
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">En Attente</h6>
                            <h2 class="stats-number">{{ $stats['en_attente'] }}</h2>
                        </div>
                        <div class="stats-icon bg-warning-subtle">
                            <i class="bi bi-hourglass-split text-warning"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                             style="width: {{ $stats['total'] ? ($stats['en_attente'] / $stats['total']) * 100 : 0 }}%"
                             aria-valuenow="{{ $stats['en_attente'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total'] }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['pending_change'] > 0)
                            <i class="bi bi-arrow-up-short text-danger"></i>
                        @elseif($stats['pending_change'] < 0)
                            <i class="bi bi-arrow-down-short text-success"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['pending_change']) }}% depuis la semaine dernière
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Approuvés</h6>
                            <h2 class="stats-number">{{ $stats['approuves'] }}</h2>
                        </div>
                        <div class="stats-icon bg-success-subtle">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $stats['total'] ? ($stats['approuves'] / $stats['total']) * 100 : 0 }}%"
                             aria-valuenow="{{ $stats['approuves'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total'] }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['approved_change'] > 0)
                            <i class="bi bi-arrow-up-short text-success"></i>
                        @elseif($stats['approved_change'] < 0)
                            <i class="bi bi-arrow-down-short text-danger"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['approved_change']) }}% depuis le mois dernier
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Rejetés</h6>
                            <h2 class="stats-number">{{ $stats['rejetes'] }}</h2>
                        </div>
                        <div class="stats-icon bg-danger-subtle">
                            <i class="bi bi-x-circle text-danger"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-danger" role="progressbar"
                             style="width: {{ $stats['total'] ? ($stats['rejetes'] / $stats['total']) * 100 : 0 }}%"
                             aria-valuenow="{{ $stats['rejetes'] }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $stats['total'] }}"></div>
                    </div>
                    <p class="stats-text mt-2">
                        @if($stats['rejected_change'] < 0)
                            <i class="bi bi-arrow-down-short text-success"></i>
                        @elseif($stats['rejected_change'] > 0)
                            <i class="bi bi-arrow-up-short text-danger"></i>
                        @else
                            <i class="bi bi-dash text-secondary"></i>
                        @endif
                        {{ abs($stats['rejected_change']) }}% depuis le mois dernier
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Demandes Récentes prend toute la largeur --}}
        <div class="col-lg-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Demandes Récentes</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                type="button"
                                id="requestsFilterDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                aria-haspopup="true">
                            {{ $currentFilter === 'all' ? 'Tous les types' : ($documentTypes[$currentFilter] ?? ucfirst($currentFilter)) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="requestsFilterDropdown"
                            data-bs-popper="static">
                            <li>
                                <a class="dropdown-item filter-item {{ $currentFilter === 'all' ? 'active fw-bold' : '' }}"
                                href="{{ route('agent.documents.index', array_merge(request()->query(), ['filter' => 'all'])) }}"
                                data-filter="all">
                                    <i class="bi bi-list-ul me-2"></i> Tous les types
                                    @if($currentFilter === 'all')
                                        <i class="bi bi-check2 ms-2"></i>
                                    @endif
                                </a>
                            </li>
                            @foreach($documentTypes as $type => $label)
                            <li>
                                <a class="dropdown-item filter-item {{ $currentFilter === $type ? 'active fw-bold' : '' }}"
                                href="{{ route('agent.documents.index', array_merge(request()->query(), ['filter' => $type])) }}"
                                data-filter="{{ $type }}">
                                    <i class="bi bi-{{ $type === 'naissance' ? 'person-vcard' : ($type === 'mariage' ? 'heart-fill' : 'flower1') }} me-2"></i>
                                    {{ $label }}
                                    @if($currentFilter === $type)
                                        <i class="bi bi-check2 ms-2"></i>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="requests-table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Référence</th>
                                    <th scope="col">Citoyen</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDemandes as $demande)
                                @if($demande->commune_id == auth()->user()->commune_id)
                                <tr>
                                    <td>{{ $demande->registry_number }}</td>
                                    <td>{{ $demande->user->nom }} {{ $demande->user->prenom }}</td>
                                    <td>
                                        <span class="badge
                                            @if($demande->type == 'naissance') bg-primary
                                            @elseif($demande->type == 'mariage') bg-success
                                            @else bg-secondary @endif">
                                            {{ ucfirst($demande->type->value) }}
                                        </span>
                                    </td>
                                    <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge
                                            @if($demande->status == 'en_attente') bg-warning
                                            @elseif($demande->status == 'approuvee') bg-success
                                            @else bg-danger @endif">
                                            {{ ucfirst($demande->status->value) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('agent.documents.show', $demande) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-0 bg-white text-center">
                    <a href="{{ route('agent.documents.index') }}" class="view-all">Voir toutes les demandes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Graphique de distribution --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Distribution par Type</h5>
                </div>
                {{-- Conteneur pour maintenir l'aspect ratio du graphique --}}
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="chart-aspect-ratio-container" style="width: 100%;">
                        <canvas id="documentTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphique d'activité mensuelle --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Activité Mensuelle</h5>
                </div>
                {{-- Ajout d'une min-height pour le conteneur du graphique --}}
                <div class="card-body" style="min-height: 300px;">
                    <canvas id="monthlyActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Styles CSS pour le conteneur d'aspect ratio
    const style = document.createElement('style');
    style.innerHTML = `
        .chart-aspect-ratio-container {
            position: relative;
            width: 100%; /* Revert à 100% pour remplir l'espace horizontal */
            /* Ajustement de la hauteur relative à la largeur pour un histogramme plus visuel */
            padding-bottom: 75%; /* Augmenté pour un histogramme plus grand */
            overflow: hidden;
        }
        .chart-aspect-ratio-container canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
        }
        /* Style pour l'overlay de chargement */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            border-radius: 0.5rem;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    `;
    document.head.appendChild(style);

    // Graphique de distribution par type (Histogramme / Bar Chart)
    const typeCtx = document.getElementById('documentTypeChart').getContext('2d');
    const typeChart = new Chart(typeCtx, {
        type: 'bar', // CHANGEMENT CLÉ : type 'bar' pour l'histogramme
        data: {
            labels: [
                @foreach ($documentTypes as $key => $label)
                    "{{ $label }}",
                @endforeach
            ],
            datasets: [{
                label: 'Nombre de Demandes', // Ajout d'un label pour l'axe Y
                data: [
                    @foreach ($documentTypes as $key => $label)
                        {{ $stats[$key . 's'] ?? 0 }}, // Assurez-vous que les clés correspondent (ex: 'naissances', 'mariages', 'deces')
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',   // Naissance (bleu)
                    'rgba(75, 192, 192, 0.7)',   // Mariage (vert-bleu)
                    'rgba(153, 102, 255, 0.7)'   // Décès (violet)
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Généralement pas de légende pour un histogramme simple
                }
            },
            scales: { // Configuration des axes pour un histogramme
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Type de Document'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0 // Assure que les ticks sont des nombres entiers
                    },
                    title: {
                        display: true,
                        text: 'Nombre de Demandes'
                    }
                }
            }
        }
    });

    // Graphique d'activité mensuelle (inchangé)
    const monthlyCtx = document.getElementById('monthlyActivityChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Demandes',
                data: @json($monthlyData),
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: '#fff',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Filtrage des demandes (rendu dynamique via AJAX) - Inchangé
    const requestsTableBody = document.querySelector('#requests-table tbody');
    const requestsTableCard = document.querySelector('.col-lg-12 .card');
    const requestsFilterButton = document.getElementById('requestsFilterDropdown');
    const requestsFilterItems = document.querySelectorAll('.filter-item');

    requestsFilterItems.forEach(item => {
        item.addEventListener('click', async function(e) {
            e.preventDefault();
            const filter = this.getAttribute('data-filter');
            const url = this.href;

            const loadingOverlay = document.createElement('div');
            loadingOverlay.classList.add('loading-overlay');
            loadingOverlay.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            `;
            requestsTableCard.style.position = 'relative';
            requestsTableCard.appendChild(loadingOverlay);

            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await response.text();

                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                const newTableBody = tempDiv.querySelector('#requests-table tbody');
                if (newTableBody) {
                    requestsTableBody.innerHTML = newTableBody.innerHTML;
                }

                // Extrait et remplace la pagination si elle existe
                const oldPaginationContainer = document.querySelector('.d-flex.justify-content-between.align-items-center.pt-4');
                const newPaginationContainer = tempDiv.querySelector('.d-flex.justify-content-between.align-items-center.pt-4');
                if (oldPaginationContainer && newPaginationContainer) {
                    oldPaginationContainer.innerHTML = newPaginationContainer.innerHTML;
                }


                const selectedLabel = this.textContent.trim().replace(/[\u2713\u2714\u2715\u2705\u2708-\u270D\u2712\u2716-\u2719\u271C-\u271E\u2721-\u2724\u2726-\u2728\u272A-\u272B\u272D-\u272E\u2730-\u2732\u2734-\u2735\u2737-\u273A\u273C-\u273E\u2740-\u2743\u2745-\u2747\u2749-\u274C\u274E-\u274F\u2751-\u2752\u2756-\u2757\u2759-\u275B\u275D-\u275E\u2761-\u2767\u2769-\u276B\u276D-\u276E\u2770-\u2771\u2774-\u2775\u2795-\u2797\u2799-\u279B\u27A1\u27A2-\u27A7\u27A9-\u27AE\u27B0-\u27B1\u27B3-\u27B6\u27B8-\u27BE\u27C0-\u27C4\u27C6-\u27C7\u27C9-\u27CC\u27CE-\u27CF\u27D1-\u27D2\u27D4-\u27D5\u27D7-\u27D8\u27DA-\u27DB\u27DD-\u27DE\u27E0-\u27E3\u27E4-\u27E5\u27F0-\u27F7\u27F9-\u27FF\u2800-\u28FF\u2900-\u2903\u2904-\u2905\u2906-\u2907\u2909-\u290B\u290C-\u290F\u2911-\u2912\u2914-\u2915\u2917-\u2919\u291B-\u291C\u291E-\u291F\u2921-\u2923\u2925-\u2927\u2929-\u292B\u292D-\u292F\u2931-\u2932\u2934-\u2935\u2936-\u2937\u2938-\u293B\u293D-\u293E\u2940-\u2941\u2943-\u2944\u2946-\u2947\u2949-\u294B\u294D-\u294E\u2950-\u2951\u2953-\u2955\u2957-\u2958\u295A-\u295B\u295D-\u295E\u2960-\u2961\u2963-\u2966\u2968-\u296B\u296D-\u296E\u2970-\u2971\u2973-\u2975\u2977-\u297B\u297D-\u297E\u2980-\u2982\u2983-\u298B\u298D-\u298F\u2991-\u2994\u2996-\u2998\u299A-\u299B\u299D-\u299E\u29A0-\u29A1\u29A3-\u29A4\u29A6-\u29A7\u29A9-\u29AA\u29AC-\u29AD\u29AF-\u29B0\u29B2-\u29B5\u29B7-\u29B8\u29BA-\u29BB\u29BD-\u29BE\u29C0-\u29C1\u29C3-\u29C5\u29C7-\u29C8\u29CA-\u29CB\u29CD-\u29CE\u29D0-\u29D1\u29D3-\u29D4\u29D6-\u29D7\u29D9-\u29DA\u29DC-\u29DD\u29DF-\u29E0\u29E2-\u29E3\u29E5-\u29E6\u29E8-\u29E9\u29EB-\u29EC\u29EE-\u29EF\u29F1-\u29F4\u29F6-\u29F7\u29F9-\u29FB\u29FD-\u29FE\u2A00-\u2A03\u2A04-\u2A06\u2A07-\u2A0A\u2A0C-\u2A0D\u2A0F-\u2A11\u2A13-\u2A16\u2A18-\u2A1B\u2A1D-\u2A1E\u2A20-\u2A25\u2A27-\u2A29\u2A2B-\u2A2E\u2A30-\u2A31\u2A33-\u2A36\u2A38-\u2A39\u2A3B-\u2A3C\u2A3E-\u2A3F\u2A41-\u2A42\u2A44-\u2A47\u2A49-\u2A4C\u2A4E-\u2A4F\u2A51-\u2A54\u2A56-\u2A59\u2A5B-\u2A5C\u2A5E-\u2A5F\u2A61-\u2A62\u2A64-\u2A67\u2A69-\u2A6C\u2A6E-\u2A6F\u2A71-\u2A73\u2A75-\u2A76\u2A78-\u2A79\u2A7B-\u2A7C\u2A7E-\u2A7F\u2A81-\u2A82\u2A84-\u2A85\u2A87-\u2A89\u2A8B-\u2A8C\u2A8E-\u2A8F\u2A91-\u2A92\u2A94-\u2A95\u2A97-\u2A99\u2A9B-\u2A9C\u2A9E-\u2A9F\u2AA1-\u2AA2\u2AA4-\u2AA6\u2AA8-\u2AAB\u2AAD-\u2AAE\u2AB0-\u2AB2\u2AB4-\u2AB5\u2AB7-\u2AB8\u2ABA-\u2ABB\u2ABD-\u2ABF\u2AC1-\u2AC2\u2AC4-\u2AC5\u2AC7-\u2AC8\u2ACA-\u2ACB\u2ACD-\u2ACE\u2AD0-\u2AD1\u2AD3-\u2AD4\u2AD6-\u2AD7\u2AD9-\u2ADB\u2ADD-\u2ADE\u2AE0-\u2AE1\u2AE3-\u2AE4\u2AE6-\u2AE7\u2AE9-\u2AEC\u2AEE-\u2AFA\u2AFC-\u2AFF\u2B00-\u2B03\u2B05-\u2B06\u2B08-\u2B0B\u2B0D-\u2B0E\u2B10-\u2B11\u2B13-\u2B16\u2B18-\u2B1B\u2B1D-\u2B1E\u2B20-\u2B21\u2B23-\u2B24\u2B26-\u2B27\u2B29-\u2B2A\u2B2C-\u2B2D\u2B2F-\u2B30\u2B32-\u2B33\u2B35-\u2B36\u2B38-\u2B39\u2B3B-\u2B3C\u2B3E-\u2B3F\u2B41-\u2B42\u2B44-\u2B45\u2B47-\u2B48\u2B4A-\u2B4B\u2B4D-\u2B4F\u2B51-\u2B52\u2B54-\u2B55\u2B57-\u2B58\u2B5A-\u2B5B\u2B5D-\u2B5E\u2B60-\u2B61\u2B63-\u2B64\u2B66-\u2B67\u2B69-\u2B6A\u2B6C-\u2B6D\u2B6F-\u2B70\u2B72-\u2B73\u2B75-\u2B76\u2B78-\u2B79\u2B7B-\u2B7C\u2B7E-\u2B7F\u2B81-\u2B82\u2B84-\u2B85\u2B87-\u2B88\u2B8A-\u2B8B\u2B8D-\u2B8E\u2B90-\u2B91\u2B93-\u2B94\u2B96-\u2B97\u2B99-\u2B9A\u2B9C-\u2B9D\u2B9F-\u2BA0\u2BA2-\u2BA3\u2BA5-\u2BA6\u2BA8-\u2BA9\u2BAB-\u2BAC\u2BAE-\u2BAF\u2BB1-\u2BB2\u2BB4-\u2BB5\u2BB7-\u2BB8\u2BBA-\u2BBB\u2BBD-\u2BBE\u2BC0-\u2BC1\u2BC3-\u2BC4\u2BC6-\u2BC7\u2BC9-\u2BCA\u2BCC-\u2BCD\u2BCF-\u2BD0\u2BD2-\u2BD3\u2BD5-\u2BD6\u2BD8-\u2BD9\u2BDB-\u2BDC\u2BDE-\u2BDF\u2BE1-\u2BE2\u2BE4-\u2BE5\u2BE7-\u2BE8\u2BEA-\u2BEB\u2BED-\u2BEE\u2BF0-\u2BF1\u2BF3-\u2BF4\u2BF6-\u2BF7\u2BF9-\u2BFA\u2BFC-\u2BFF\u2C00-\u2C2F\u2C30-\u2C5F\u2C60-\u2CEF\u2CF0-\u2CFF\u2D00-\u2D2F\u2D30-\u2D7F\u2D80-\u2DDF\u2DE0-\u2DFF\u2E00-\u2E7F\u2E80-\u2EFF\u2F00-\u2FDF\u2FDF-\u2FFF\u3000-\u303F\u3040-\u309F\u30A0-\u30FF\u3100-\u312F\u3130-\u318F\u3190-\u319F\u31A0-\u31BF\u31C0-\u31EF\u31F0-\u32FF\u3300-\u33FF\u3400-\u4DBF\u4DC0-\u4DFF\u4E00-\u9FFF\uA000-\uA48F\uA490-\uA4CF\uA4D0-\uA4FF\uA500-\uA63F\uA640-\uA69F\uA6A0-\uA6FF\uA700-\uA71F\uA720-\uA7FF\uA800-\uA82F\uA830-\uA83F\uA840-\uA87F\uA880-\uA8FF\uA900-\uA92F\uA930-\uA95F\uA960-\uA97F\uA980-\uA9DF\uA9E0-\uA9FF\uAA00-\uAA5F\uAA60-\uAA7F\uAA80-\uAADF\uAAE0-\uAAFF\uAB00-\uAB2F\uAB30-\uAB6F\uAB70-\uABBF\uABC0-\uABFF\uAC00-\uD7AF\uD7B0-\uD7FF\uD800-\uDB7F\uDB80-\uDBFF\uDC00-\uDFFF\uE000-\uF8FF\uF900-\uFAFF\uFB00-\uFB4F\uFB50-\uFDFF\uFE00-\uFE0F\uFE10-\uFE1F\uFE20-\uFE2F\uFE30-\uFE4F\uFE50-\uFE6F\uFE70-\uFEFF\uFF00-\uFFEF\uFFF0-\uFFFF\u10000-\u1007F\u10080-\u100FF\u10100-\u1013F\u10140-\u1018F\u10190-\u101CF\u101D0-\u101FF\u10200-\u1027F\u10280-\u1029F\u102A0-\u102DF\u102E0-\u102FF\u10300-\u1032F\u10330-\u1034F\u10350-\u1037F\u10380-\u1039F\u103A0-\u103DF\u103E0-\u103FF\u10400-\u1044F\u10450-\u1047F\u10480-\u104AF\u104B0-\u104FF\u10500-\u1052F\u10530-\u1056F\u10570-\u105BF\u105C0-\u105FF\u10600-\u107BF\u107C0-\u107FF\u10800-\u1083F\u10840-\u1085F\u10860-\u1087F\u10880-\u108AF\u108B0-\u108FF\u10900-\u1091F\u10920-\u1093F\u10980-\u1099F\u109A0-\u109FF\u10A00-\u10A5F\u10A60-\u10A7F\u10A80-\u10A9F\u10AC0-\u10AFF\u10B00-\u10B3F\u10B40-\u10B5F\u10B60-\u10B7F\u10B80-\u10BFF\u10C00-\u10C4F\u10C50-\u10C7F\u10C80-\u10CFF\u10D00-\u10D3F\u10D40-\u10DFF\u10E00-\u10E5F\u10E60-\u10E7F\u10E80-\u10EBF\u10EC0-\u10EFF\u10F00-\u10F2F\u10F30-\u10F4F\u10F50-\u10F7F\u10F80-\u10FFF\u11000-\u1107F\u11080-\u110CF\u110D0-\u110FF\u11100-\u1114F\u11150-\u1117F\u11180-\u111DF\u111E0-\u111FF\u11200-\u1124F\u11280-\u112AF\u112B0-\u112FF\u11300-\u1135F\u11400-\u1147F\u11480-\u114DF\u11580-\u115FF\u11600-\u1165F\u11660-\u1167F\u11680-\u116CF\u11700-\u1173F\u11800-\u1184F\u118A0-\u118FF\u11900-\u1195F\u119A0-\u119FF\u11A00-\u11A4F\u11A50-\u11AAF\u11AB0-\u11ABF\u11AC0-\u11AFF\u11B00-\u11B5F\u11B60-\u11BFF\u11C00-\u11C5F\u11C60-\u11C6F\u11C70-\u11C9F\u11CA0-\u11CFF\u11D00-\u11D5F\u11D60-\u11DAF\u11DB0-\u11DFF\u11EE0-\u11EFF\u11F00-\u11F5F\u11FB0-\u11FBF\u11FC0-\u11FFF\u12000-\u123FF\u12400-\u1247F\u12480-\u1254F\u12F90-\u12FFF\u13000-\u1342F\u13430-\u1343F\u14400-\u1467F\u16A40-\u16A6F\u16AD0-\u16AFF\u16B00-\u16BFF\u16F00-\u16F9F\u16FE0-\u16FFF\u17000-\u187FF\u18800-\u18AFF\u1B000-\u1B0FF\u1B100-\u1B12F\u1B170-\u1B2FF\u1BC00-\u1BC9F\u1BCA0-\u1BCAF\u1D000-\u1D0FF\u1D100-\u1D1FF\u1D200-\u1D24F\u1D300-\u1D35F\u1D360-\u1D37F\u1D400-\u1D7FF\u1D800-\u1DAAF\u1E000-\u1E02F\u1E100-\u1E14F\u1E290-\u1E2FF\u1E800-\u1E8DF\u1E900-\u1E95F\u1EE00-\u1EEFF\u1F000-\u1F02F\u1F030-\u1F09F\u1F0A0-\u1F0FF\u1F100-\u1F1FF\u1F200-\u1F2FF\u1F300-\u1F5FF\u1F600-\u1F64F\u1F650-\u1F67F\u1F680-\u1F6FF\u1F700-\u1F77F\u1F780-\u1F7FF\u1F800-\u1F8FF\u1F900-\u1F9FF\u1FA00-\u1FA6F\u1FA70-\u1FAFF\u1FB00-\u1FBFF\u2FC00-\u2FFFD\u30000-\u3FFFD\uE0000-\uE007F]/g, ''); // Supprime les icônes (flèches, coches, etc.)
                requestsFilterButton.textContent = selectedLabel;

                requestsFilterItems.forEach(item => {
                    item.classList.remove('active', 'fw-bold');
                });
                this.classList.add('active', 'fw-bold');

            } catch (error) {
                console.error('Erreur lors du chargement des demandes:', error);
            } finally {
                if (loadingOverlay.parentNode) {
                    requestsTableCard.removeChild(loadingOverlay);
                }
            }
        });
    });
});
</script>
@endpush
