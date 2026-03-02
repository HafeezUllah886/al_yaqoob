@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Balance Sheet Report</h3>
                </div>
                <form action="{{ route('reports.balance_sheet.details') }}" method="get">
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
                            <label for="branch">Account Type</label>
                            <select name="account_type" id="account_type" class="form-control">
                                <option value="All">All Account Types</option>
                                <option value="Business">Business</option>
                                <option value="Customer">Customer</option>
                                <option value="Vendor">Vendor</option>
                                <option value="Transporter">Transporter</option>
                               
                            </select>
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
