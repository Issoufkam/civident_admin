@php
    // Assurez-vous que l'utilisateur est authentifié pour éviter des erreurs si non connecté
    $user = Auth::user();
    // Dans un vrai scénario, cette vue ne serait accessible qu'après connexion.
    // Pour la robustesse, on peut ajouter une vérification si $user est null.
    // if (!$user) {
    //     return redirect()->route('login');
    // }
@endphp

<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>{{ $user->isAdmin() ? 'Administration' : 'Mairie' }}</h3>
        <div class="sidebar-brand-icon">
            <i class="bi {{ $user->isAdmin() ? 'bi-shield-lock' : 'bi-building' }}"></i>
        </div>
    </div>

    <div class="sidebar-user">
        <img src="
            @if (isset($user) && $user->photo && file_exists(public_path('storage/' . $user->photo)))
                {{ asset('storage/' . $user->photo) }}
            @else
                {{-- Fallback to UI Avatars if no photo is available or file doesn't exist --}}
                @php
                    $nameForAvatar = (isset($user) && $user->nom && $user->prenom) ? urlencode($user->prenom . ' ' . $user->nom) : 'Utilisateur';
                @endphp
                https://ui-avatars.com/api/?name={{ $nameForAvatar }}&background=0d6efd&color=fff&size=40
            @endif
        " alt="Photo de l'utilisateur" class="user-avatar">
        <div class="user-info">
            <h5>{{ $user->nom ?? 'Nom Inconnu' }}</h5>
            <span>{{ $user->isAdmin() ? 'Super Administrateur' : 'Agent Municipal' }}</span>
        </div>
    </div>

    <ul class="list-unstyled components">
        {{-- Liens du tableau de bord (Admin ou Agent) --}}
        @if($user->isAdmin())
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Tableau de Bord
                </a>
            </li>
        @else
            <li class="{{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                <a href="{{ route('agent.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Tableau de Bord
                </a>
            </li>
        @endif

        {{-- Liens spécifiques à l'administrateur --}}
        @if ($user->isAdmin())
            <li class="{{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
                <a href="{{ route('admin.agents.index') }}"><i class="bi bi-people"></i> Agents</a>
            </li>
            <li class="{{ request()->routeIs('admin.regions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.regions.index') }}"><i class="bi bi-geo-alt"></i> Régions</a>
            </li>
            <li class="{{ request()->routeIs('admin.communes.*') ? 'active' : '' }}">
                <a href="{{ route('admin.communes.index') }}"><i class="bi bi-building"></i> Communes</a>
            </li>
            {{-- Lien pour les statistiques (visible uniquement par les admins) --}}
            {{-- Route corrigée vers 'admin.statistics' --}}
            <li class="{{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                <a href="{{ route('admin.statistics') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
            </li>
            {{-- Lien pour les performances (assumant route admin.lieux.index pour la performance globale) --}}
            {{-- Route corrigée vers 'admin.lieux.*' --}}
            <li class="{{ request()->routeIs('admin.performances') ? 'active' : '' }}">
                <a href="{{ route('admin.performances') }}"><i class="bi bi-graph-up"></i> Performances</a>
            </li>
            {{-- NOUVEAU LIEN: Configuration --}}
            <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.settings') }}"><i class="bi bi-gear"></i> Configuration</a>
            </li>
        @else
            {{-- Liens spécifiques à l'agent --}}
            <li class="{{ request()->routeIs('agent.documents.*') ? 'active' : '' }}">
                <a href="{{ route('agent.documents.index') }}"><i class="bi bi-file-earmark-text"></i> Demandes</a>
            </li>
            {{-- These links will now dynamically display counts --}}
            <li>
                <a href="{{ route('agent.documents.attente') }}"><i class="bi bi-hourglass-split"></i> En Attente
                    <span class="badge rounded-pill bg-warning ms-2">{{ $documentCounts['attente'] ?? 0 }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('agent.documents.approuve') }}"><i class="bi bi-check-circle"></i> Approuvés
                    <span class="badge rounded-pill bg-success ms-2">{{ $documentCounts['approuve'] ?? 0 }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('agent.documents.rejete') }}"><i class="bi bi-x-circle"></i> Rejetés
                    <span class="badge rounded-pill bg-danger ms-2">{{ $documentCounts['rejete'] ?? 0 }}</span>
                </a>
            </li>
        @endif
    </ul>

    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-left"></i> Déconnexion
        </a>
    </div>
</nav>
