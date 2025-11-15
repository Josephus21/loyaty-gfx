@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center small">

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&lt;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&lt;</a></li>
            @endif

            {{-- Page Numbers (Only 3 visible) --}}
            @php
                $start = max(1, $paginator->currentPage() - 1);
                $end = min($paginator->lastPage(), $start + 2);
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $paginator->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor

            {{-- Dots --}}
            @if ($end < $paginator->lastPage())
                <li class="page-item disabled">
                    <span class="page-link">…</span>
                </li>
            @endif

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&gt;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&gt;</span></li>
            @endif

        </ul>
    </nav>
@endif
