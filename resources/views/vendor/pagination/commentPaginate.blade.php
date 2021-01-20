@if ($paginator->hasPages())
    <ul class="pagination" style="border-top:1px solid #BBB; padding: 2px 0">
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true">{{ $element }}</li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li aria-current="page"><a href="javascript:void(0)" style="font-size:14px; padding: 0 5px; background-color: #555; color:#FFF" class="active">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}" style="font-size: 10px; color:#888888">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

    </ul>

    @if ($paginator->onFirstPage())
        <a href="{{ $paginator->previousPageUrl() }}" class="disabled prev" rel="prev" aria-label="&laquo;" onclick="return false;">
            <span aria-hidden="true" class="arrow_carrot-left_alt2"></span>
        </a>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="prev" rel="prev" aria-label="&laquo;">
            <span aria-hidden="true" class="arrow_carrot-left_alt2"></span>
        </a>
    @endif


    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="next">
            <span aria-hidden="true" class="arrow_carrot-right_alt2"></span>
        </a>
    @else
        <a href="{{ $paginator->nextPageUrl() }}" class="disabled next" onclick="return false;">
            <span aria-hidden="true" class="arrow_carrot-right_alt2"></span>
        </a>
    @endif
@endif
