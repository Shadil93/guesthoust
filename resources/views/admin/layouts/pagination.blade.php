<nav aria-label="...">
    <ul class="pagination justify-content-end">
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1"><</a>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1"><</a>
        </li>
        @endif

        @foreach ($elements as $element)
        <!-- "Three Dots" Separator -->
        @if (is_string($element))
        <li class="page-item"><a class="page-link" href="#">{{ $element }}</a></li>
        @endif

        <!-- Array Of Links -->
        @if (is_array($element))
        @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
                <li class="page-item ">
                    <a class="page-link" href="#">{{ $page }}<span class="sr-only">(current)</span></a>
                </li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endif
            @endforeach
        @endif
        @endforeach
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">></a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link" href="#">></a>
        </li>
        @endif
    </ul><!--end pagination-->
</nav><!--end nav--> 




