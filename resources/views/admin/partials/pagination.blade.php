@if ($paginator->hasPages())
  <div class="pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
      <span class="disabled">&laquo;</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="dots">{{ $element }}</span>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="current">{{ $page }}</span>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
    @else
      <span class="disabled">&raquo;</span>
    @endif
  </div>
@endif
