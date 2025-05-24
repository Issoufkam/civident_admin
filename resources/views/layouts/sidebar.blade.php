@php
        $user = Auth::user();
    @endphp

    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3>{{ $user->isAdmin() ? 'Administration' : 'Mairie' }}</h3>
            <div class="sidebar-brand-icon">
                <i class="bi {{ $user->isAdmin() ? 'bi-shield-lock' : 'bi-building' }}"></i>
            </div>
        </div>

        <div class="sidebar-user">
            <img src="{{ $user->isAdmin() ? 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1' : 'https://images.pexels.com/photos/1036623/pexels-photo-1036623.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1' }}" alt="Photo" class="user-avatar">
            <div class="user-info">
                <h5>{{ $user->nom }}</h5>
                <span>{{ $user->isAdmin() ? 'Super Administrateur' : 'Agent Municipal' }}</span>
            </div>
        </div>

        <ul class="list-unstyled components">
            @if($user->isAdmin())
                <li class="">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        Tableau de Bord
                    </a>
                </li>
            @else
                <li class="">
                <a href="{{ route('agent.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Tableau de Bord
                </a>
            </li>
            @endif

            @if ($user->isAdmin())
                <li><a href="{{ route('admin.agents.index') }}"><i class="bi bi-people"></i> Agents</a></li>
                <li><a href="{{ route('admin.regions.index') }}"><i class="bi bi-geo-alt"></i> Régions</a></li>
                <li><a href="{{ route('admin.communes.index') }}"><i class="bi bi-geo-alt"></i> Communes</a></li>

            @else
                <li><a href="{{ route('agent.documents.index') }}"><i class="bi bi-file-earmark-text"></i>Demandes</a></li>
                <li><a href="#"><i class="bi bi-hourglass-split"></i> En Attente <span class="badge rounded-pill bg-warning ms-2">24</span></a></li>
                <li><a href="#"><i class="bi bi-check-circle"></i> Approuvés</a></li>
                <li><a href="#"><i class="bi bi-x-circle"></i> Rejetés</a></li>
            @endif


        </ul>

        <div class="sidebar-footer">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-left"></i> Déconnexion
            </a>
        </div>
    </nav>
