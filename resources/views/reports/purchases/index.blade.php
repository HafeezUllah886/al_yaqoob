@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Purchases Report</h3>
                </div>
                <form action="{{ route('reports.purchases.details') }}" method="get">
                    <div class="card-body">
                          <div class="form-group">
                            <label for="branch">Branch</label>
                            <select name="branch" id="branch" class="form-control">
                                <option value="all">All Branches</option>
                                @foreach (Auth()->user()->branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="vendor">Vendor(s)</label>
                            <select name="vendor[]" id="vendor" class="selectize" multiple>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2 ">
                            <label for="area">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ firstDayOfMonth() }}"
                                class="form-control">
                        </div>
                        <div class="form-group mt-2 ">
                            <label for="area">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-d') }}"
                                class="form-control">
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
        $(".selectize").selectize({
            plugins: ['remove_button'],
            maxItems: null,
            create: false,
            placeholder: 'Select Option...'
        });

       
    </script>
@endsection
