@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Sales Report</h3>
                </div>
                <form action="{{ route('reports.sales.details') }}" method="get">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="branch">Branch</label>
                            <select name="branch" id="branch" onchange="getAccounts(this.value)" class="form-control">
                                <option value="all">All Branches</option>
                                @foreach (Auth()->user()->branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="branch">Customer(s)</label>
                            <select name="customer[]" id="customer" class="selectize" multiple>
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

        function getAccounts(branch_id) {
            var accountSelectize = $('#customer')[0].selectize; // Access the selectize instance
            if (branch_id) {
                // Make an AJAX call to fetch sectors for selected town
                $.ajax({
                    url: '/get_branch_customers/' + branch_id,
                    type: 'GET',
                    success: function(data) {
                        console.log(data);

                        // Clear previous options 
                        accountSelectize.clearOptions();

                        // Add new options
                        accountSelectize.addOption(data); // data should be an array of {value: '', text: ''}
                        accountSelectize.refreshOptions(false);
                    }
                });
            } else {
                accountSelectize.clearOptions();
            }



        }

        getAccounts('all');
    </script>
@endsection
