@extends('dash')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@section('main-content')
    <form method="POST" action="{{ route('queries.manage.update', $singleQuery->id) }}">
        @csrf
        <div class="form-group">
            <label for="">Query Title :</label>
            <input type="text" name="f_query_title" class="form-control" value="{{ $singleQuery->query_title }}">
        </div>
        <div class="form-group">
            <label for="">Query Category :</label>
            <select class="form-select" aria-label="Default select example" name="f_report_category_id">
                @foreach (App\Models\ReportCategory::all() as $category)
                    <option value="{{ $category->id }}"
                        {{ $x = $singleQuery->report_category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="">Database Name :</label>
            <select class="form-select" aria-label="Default select example" name="db_name" id="d_name">
                @foreach (App\Models\Dbase::all() as $dbase)
                    <option value="{{ $dbase->db_name }}"
                        {{ $x = $singleQuery->db_name == $dbase->db_name ? 'selected' : '' }}>{{ $dbase->db_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="">Tags For the Query : </label>
            <select id="e1" class="form-control" name="tags[]" multiple>
                @foreach ($singleQuery->querytags as $tagObject)
                    <option value="{{ $tagObject->tag }}" selected>{{ $tagObject->tag }}</option>
                @endforeach

                @if (old('tags'))
                    @foreach (old('tags') as $tag)
                        <option value="{{ $tag }}" selected>{{ $tag }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group">
            <label for="exampleFormControlTextarea1">Enter SQL Query Here : </label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="f_sql_query_string">{{ $singleQuery->sql_query_string }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Updates</button>
    </form>
    <div class="row m-5">
        <div class="col-9"></div> <!-- Create 9 empty columns -->
        <div class="col-3">
            <a href="" class="btn btn-danger float-right" id="testingQuery">Test Query</a>
        </div>
    </div>
    <div class="container overflow-auto">
        <h1 id="loader" class="text-center">
            <div class="spinner-border visually-hidden" role="status" id="realLoader">
                <span class="visually-hidden">Loading...</span>
            </div>
        </h1>
        <table class="table  table-bordered data-table">
            <thead>
                <tr id="the-heading">

                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>

                </tr>
            </tfoot>
        </table>
    </div>
@endsection
<script>
    @section('extra-script')
        $(document).ready(function() {
            // WRAP BELOW CODE if a is Clicked !!!
            $('#testingQuery').click(function(e) {
                $("#loader").text("")
                $("#loader").html(`<div class="spinner-border visually-hidden" role="status" id="realLoader">
                <span class="visually-hidden">Loading...</span>
            </div>`)
                $("#realLoader").removeClass("visually-hidden")
                $('.filterClass').remove();
                console.log(e.target);
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var columns = [];
                $.ajax({
                    type: 'POST',
                    url: "{{ route('vvv') }}",
                    data: {
                        que: $("#exampleFormControlTextarea1").val(),
                        db_name: $("#d_name").val(),
                    },
                    success: function(data) {
                        console.log('Data Success From the API');
                        const x = data.data;
                        columnNames = data.keys;
                        for (var i of columnNames) {
                            columns.push({
                                data: i,
                                name: i
                            });
                            document.getElementById("the-heading").innerHTML = data.row;
                        }
                        $('.data-table').DataTable({
                            deferRender: true,
                            retrieve: false,
                            processing: true,
                            serverSide: false,
                            searchable: true,
                            searching: true,
                            "bDestroy": true,
                            data: x,

                            columns: columns,
                            initComplete: function() {

                                $("#loader").text("");

                                // alert(timeElapsed);
                            }, // initComplete END 
                        }); // End Of Making the New Data Table 
                    },
                    error: function(e) {
                        console.log(e);
                        $("#loader").text("Error Running the SQL query !!");
                    },
                })
            });

        }); // End OF Document Ready
    @endsection
</script>
