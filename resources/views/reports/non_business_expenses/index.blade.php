@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Non-Business Expense Report</h3>
                </div>
                <form action="{{ route('reports.non_business_expenses.details') }}" method="get">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="branch">Branch</label>
                            <select name="branch" id="branch" class="form-control">
                                <option value="all">All Assigned Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="categories">Expense Category(ies)</label>
                            <select name="categories[]" id="categories" class="selectize" multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="subcategories">Sub-category(ies)</label>
                            <select name="subcategories[]" id="subcategories" class="selectize" multiple>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }} ({{ $subcategory->parent->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ firstDayOfMonth() }}" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="end_date">End Date</label>
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
        $(".selectize").selectize({
            plugins: ['remove_button'],
            maxItems: null,
            create: false,
            placeholder: 'Select Option...'
        });
    </script>
@endsection
