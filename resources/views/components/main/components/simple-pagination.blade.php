@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Page navigation">
        <div class="row justify-content-between align-items-center g-2">
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-grid">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <button type="button" class="btn btn-outline-secondary" disabled>{!! __('pagination.previous') !!}</button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-primary" rel="prev">{!! __('pagination.previous') !!}</a>
                @endif

            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-grid">

                {{-- Next Page Link ---}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-primary" rel="next">{!! __('pagination.next') !!}</a>
                @else
                    <button type="button" class="btn btn-outline-secondary" disabled>{!! __('pagination.next') !!}</button>
                @endif

            </div>
        </div>
    </nav>
@endif