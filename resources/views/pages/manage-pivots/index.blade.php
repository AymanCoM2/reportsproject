@extends('dash')

@section('main-content')
    @foreach ($allRelatedPivots as $pivot)
        <div class="card d-flex justify-content-center mx-4">
            <div class="card-body">
                <p>{{ $pivot->pivot_name }}</p>
                <form action="{{ route('manage-pivots-post') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <select class="form-select" aria-label="Default select example" name="secondary">
                            <option selected value="0">Select Another Pivot</option>
                            @foreach ($allRelatedPivots as $pi)
                                @if ($pi->id != $pivot->id)
                                    {{-- <option  value="{{ $pi->id }}">{{ $pi->pivot_name }}</option> --}}
                                    <option selected value="{{ $pi->id }}">{{ $pi->pivot_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">Merge With</button>
                        <input type="hidden" name="primary" value="{{ $pivot->id }}">
                    </div>
                </form>

                <form action="{{route('delete-pivot')}}" class="form-inline float-right m-2" method="POST">
                    @csrf
                    <input type="submit" name="" id="" value="Delete : {{ $pivot->pivot_name }}"
                        class="float-right m-2  button-solid btn btn-link border-0">
                        <input type="hidden" name="deletedPivodId" id="" value="{{ $pivot->id }}">
                </form>
            </div>
        </div>
    @endforeach
@endsection

@section('creation-link')
@endsection

@section('extra-script')
@endsection
