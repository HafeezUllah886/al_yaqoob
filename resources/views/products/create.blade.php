@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Create Product</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('product.store') }}" method="post">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" required id="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="catID">Category</label>
                                    <select name="category_id" id="catID" class="selectize">
                                        @foreach ($cats as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-header d-flex justify-content-between">
                                            <h5>Units - Pack Sizes</h5>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 ">
                                                <select class="selectize" id="unit">
                                                    <option value="">Select Unit</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}" data-name="{{ $unit->name }}"
                                                            data-val="{{ $unit->value }}">{{ $unit->name }} -
                                                            {{ $unit->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <table class="w-100 table">
                                            <thead>
                                                <th>Unit</th>
                                                <th class="text-center">Pack Size</th>
                                                <th class="text-center">Price</th>
                                                <th></th>
                                            </thead>
                                            <tbody id="units">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-secondary w-100">Create</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Default Modals -->


@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection

@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $("#catID").selectize();
        var $unitSelect = $("#unit").selectize({
            onChange: function(value) {
                if (!value.length) return;
                var selectize = this;
                var data = selectize.options[value];
                addUnit(value, data.name, data.val);
                selectize.clear();
                selectize.focus();
            }
        });

        var unitCount = 1;

        function addUnit(unitId, name, packSize) {
            if (!unitId) return;

            var html = '<tr class="p-0" id="row_' + unitId + '">';
            html +=
                '<td width="60%" class="p-0"><input type="text" class="form-control form-control-sm" name="unit_names[]" value="' +
                name + '"></td>';
            html +=
                '<td class="p-0"><input type="number" step="any" class="form-control form-control-sm text-center" name="unit_values[]" value="' +
                packSize + '"></td>';
            html +=
                '<td class="p-0"><input type="number" step="any" class="form-control form-control-sm text-center" name="prices[]" value="0"></td>';
            html += '<td class="p-0"> <span class="btn btn-sm btn-danger" onclick="deleteRow(\'' +
                unitId + '\')">X</span></td>';
            html += '</tr>';

            $("#units").append(html);
        }

        function deleteRow(optionCount) {
            $('#row_' + optionCount).remove();
        }
    </script>
@endsection
