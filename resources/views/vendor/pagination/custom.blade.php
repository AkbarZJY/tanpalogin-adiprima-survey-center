@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="custom-pagination-nav">
        {{-- Results Counter / Info --}}
        <div class="custom-pagination-info">
            Menampilkan
            <span class="pagination-highlight">{{ $paginator->firstItem() ?? 0 }}</span>
            -
            <span class="pagination-highlight">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="pagination-highlight">{{ $paginator->total() }}</span>
            data
        </div>

        {{-- Pagination Buttons --}}
        <ul class="custom-pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="custom-page-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                    <span class="custom-page-link" aria-hidden="true">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="custom-page-item">
                    <a class="custom-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="custom-page-item disabled" aria-disabled="true">
                        <span class="custom-page-link dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="custom-page-item active" aria-current="page">
                                <span class="custom-page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="custom-page-item">
                                <a class="custom-page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="custom-page-item">
                    <a class="custom-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="custom-page-item disabled" aria-disabled="true" aria-label="Berikutnya">
                    <span class="custom-page-link" aria-hidden="true">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
