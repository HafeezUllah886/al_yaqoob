@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Stock Movement Report</h3>
                </div>
                <form action="{{ route('reports.stock.details') }}" method="get">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="branches">Branch</label>
                            <select name="branches" id="branches" class="selectize">
                                <option value="all">All Assigned Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ firstDayOfMonth() }}" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="end_date">End Date (Closing Date)</label>
                            <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-d') }}" class="form-control">
                        </div>

                        <div class="form-group mt-2">
                            <button class="btn btn-success w-100" id="viewBtn">View Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();
    </script>
@endsection
