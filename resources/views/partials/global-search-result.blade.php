<div class="card search-result">
    <ul>
        <div class="card-header pb-15">
            <b>{{ $results->count() }} Results found for "{{ request('q') }}"</b>
        </div>
        <div class="card-body">
            @if($results->count())
            <div class="row">

                @foreach($results->groupByType() as $type => $items)

                @can('view-projects')
                <div class="col-md-4">
                    @else
                    <div class="col-md-8">
                        @endcan
                        <h4 class="search-result-card-heading">{{ ucfirst($type) }} ({{ $items->count() }})</h4>
                        <div class="list-group">
                            @foreach($items as $item)
                                <a href="{{ $item->url }}" class="list-group-item">
                                    <i class="fa fa-caret-right"></i> {{ Str::limit(strip_tags($item->title), 100, ' (...)') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>


    </ul>
</div>