@extends('dash')
@section('main-content')
    @if ($resultingQueryies)
        <h3>
            {{ 'Search Result:' }}
        </h3>
        @foreach ($resultingQueryies as $resQuer)
            <div class="card">
                <div class="card-header">
                    <h5>Title: {{ $resQuer->query_title }}</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Tags:</strong>
                        @foreach ($resQuer->queryTags as $T)
                            <span class="badge badge-secondary">{{ $T->tag }}</span>
                        @endforeach
                    </p>
                    <p>
                        <strong>Category:</strong>
                        {{ \App\Models\ReportCategory::where('id', $resQuer->report_category_id)->first()->category_name }}
                    </p>
                    <p>
                        <strong>Links:</strong>
                        <a href="{{ route('queries.manage.edit', $resQuer->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('queries.manage.view', $resQuer->id) }}" class="btn btn-success">View</a>
                    </p>
                </div>
            </div>
        @endforeach
    @else
        <h1>
            {{ 'There is No Result For this Searching' }}
        </h1>
    @endif
@endsection
