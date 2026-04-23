@if ($paginator->hasPages())
<nav>
    <ul class="custom-pagination">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">&#8249;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&#8249;</a>
            </li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&#8250;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">&#8250;</span>
            </li>
        @endif

    </ul>
</nav>

<style>
    .custom-pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        align-items: center;
    }

    .custom-pagination .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background: #f0f0f0;
        color: #333;
        border: 1.5px solid #ddd;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.18s, color 0.18s;
        cursor: pointer;
    }

    .custom-pagination .page-item .page-link:hover {
        background: #e0e0e0;
        color: #111;
    }

    .custom-pagination .page-item.active .page-link {
        background: #F4845F;
        color: #fff;
        border-color: #F4845F;
    }

    .custom-pagination .page-item.disabled .page-link {
        background: #f5f5f5;
        color: #bbb;
        border-color: #e8e8e8;
        cursor: default;
        pointer-events: none;
    }
</style>
@endif