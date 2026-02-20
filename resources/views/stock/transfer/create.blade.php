@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3> Create Stock Transfer </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()"
                                        class="btn btn-danger">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('stockTransfer.store') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="fromID">From (Branch)</label>
                                    <input type="text" value="{{ $branchFrom->name }}" readonly class="form-control">
                                    <input type="hidden" value="{{ $branchFrom->id }}" name="from">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="toID">To (Branch)</label>
                                    <input type="text" value="{{ $branchTo->name }}" readonly class="form-control">
                                    <input type="hidden" value="{{ $branchTo->id }}" name="to">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="attachement">Attachment</label>
                                    <input type="file" name="file" id="attachement" class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <input type="text" value="{{ $product->name }}" readonly class="form-control">
                                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                                </div>
                            </div>
                            <input type="hidden" id="base_stock" value="{{ $stock }}">
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="qty">Unit</label>
                                    <select name="unit_id" class="form-control" onchange="changeUnit(this)">
                                        @foreach ($product->units as $unit)
                                            <option value="{{ $unit->id }}" data-value="{{ $unit->value }}">
                                                {{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="qty">Available Stock</label>
                                    <input type="number" value="{{ $stock / $product->units()->first()->value }}"
                                        id="available_stock" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="qty">Transfer Quantity</label>
                                    <input type="number" value="0" name="qty" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3> Expenses </h3>
                                        </div>
                                        <div class="col-6 d-flex flex-row-reverse"><span onclick="addExpense()"
                                                class="btn btn-primary">Add</span></div>
                                    </div>
                                </div>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="expenses">
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Transfer Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Transfer</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();

        function changeUnit(select) {
            var unit_value = select.options[select.selectedIndex].getAttribute('data-value');
            var base_stock = document.getElementById('base_stock').value;
            document.getElementById('available_stock').value = (base_stock / unit_value).toFixed(2);
        }

        var categories = @json($expense_categories);
        var accounts = @json($accounts);
        var expense_ser = 0;

        function addExpense() {
            console.log("Function Called");
            expense_ser++;
            html = '<tr id="expense_' + expense_ser + '">';
            html += '<td>';
            html += '<select name="category[]" required class="form-control">';
            html += '<option value="">Select Category</option>';
            categories.forEach(function(category) {
                html += '<option value="' + category.id + '">' + category.name + '</option>';
            });
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<select name="expense_account[]" required class="form-control">';
            html += '<option value="">Select Account</option>';
            accounts.forEach(function(account) {
                html += '<option value="' + account.id + '">' + account.title + ' - ' + account.branch.name +
                    '</option>';
            });
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<input type="number" name="expense_amount[]" required class="form-control">';
            html += '</td>';
            html += '<td>';
            html += '<input type="text" name="expense_notes[]"  class="form-control">';
            html += '</td>';
            html += '<td>';
            html += '<span class="btn btn-sm btn-danger" onclick="deleteExpense(' + expense_ser + ')">X</span>';
            html += '</td>';
            html += '</tr>';

            $("#expenses").append(html);

        }

        function deleteExpense(id) {
            $('#expense_' + id).remove();
        }
    </script>
@endsection
