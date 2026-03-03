@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Daily Cash Flow Report</h3>
                </div>
                <form action="{{ route('reports.cash_flow.details') }}" method="get">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="branch">Branch</label>
                            <select name="branch" id="branch" onchange="getAccounts(this.value)" class="form-control">
                                <option value="All">All Branches</option>
                                @foreach (Auth()->user()->branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="branch">Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="All">All Categories</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
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
  
    </script>
@endsection
