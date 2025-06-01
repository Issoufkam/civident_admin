{{-- resources/views/vendor/pagination/bootstrap-5.blade.php --}}

@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        {{-- Ajout de pagination-sm pour des boutons plus petits, et justify-content-center pour centrer. my-0 pour réduire les marges verticales. --}}
        <ul class="pagination pagination-sm justify-content-center my-0">
            {{-- Lien de la page précédente --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" tabindex="-1" aria-hidden="true">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
            @endif

            {{-- Éléments de pagination (numéros de page) --}}
            @foreach ($elements as $element)
                {{-- Séparateur "Trois points" (...) --}}
                @if (is_string($element))
                    {{-- Masqué sur les très petits écrans, visible à partir de 'sm' --}}
                    <li class="page-item disabled d-none d-sm-block" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Tableau de liens (numéros de page) --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            {{-- Page active - Masquée sur les très petits écrans, visible à partir de 'sm' --}}
                            <li class="page-item active d-none d-sm-block" aria-current="page">
                                <a class="page-link" href="{{ $url }}">{{ $page }} <span class="sr-only">(current)</span></a>
                            </li>
                        @else
                            {{-- Autres pages - Masquées sur les très petits écrans, visible à partir de 'sm' --}}
                            <li class="page-item d-none d-sm-block"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Lien de la page suivante --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" tabindex="-1" aria-hidden="true">Next</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
