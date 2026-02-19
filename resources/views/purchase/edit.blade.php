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
                                    <h3> Edit Purchase </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse"><a href="{{ route('purchase.index') }}"
                                        class="btn btn-danger">Close</a></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('purchase.update', $purchase->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <select name="product" class="selectize" id="product">
                                        <option value="0"></option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th width="30%">Item</th>
                                        <th width="10%" class="text-center">Unit</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Amount</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="products_list">
                                        @foreach ($purchase->details as $detail)
                                            <tr id="row_{{ $detail->product_id }}">
                                                <td class="no-padding">{{ $detail->product->name }}</td>
                                                <td class="no-padding">
                                                    <select name="unit[]" class="form-control text-center no-padding"
                                                        onchange="changeUnit({{ $detail->product_id }})"
                                                        id="unit_{{ $detail->product_id }}">
                                                        @foreach ($detail->product->units as $unit)
                                                            <option data-unit="{{ $unit->value }}"
                                                                data-price="{{ $unit->price }}"
                                                                value="{{ $unit->id }}" @selected($unit->id == $detail->unit_id)>
                                                                {{ $unit->unit_name }} ({{ $unit->value }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="no-padding"><input type="number" name="qty[]"
                                                        oninput="updateChanges({{ $detail->product_id }})" min="0"
                                                        required step="any" value="{{ $detail->qty }}"
                                                        class="form-control text-center no-padding"
                                                        id="qty_{{ $detail->product_id }}"></td>
                                                <td class="no-padding"><input type="number" name="price[]"
                                                        oninput="updateChanges({{ $detail->product_id }})" required
                                                        step="any" value="{{ $detail->price }}" min="1"
                                                        class="form-control text-center no-padding"
                                                        id="price_{{ $detail->product_id }}"></td>
                                                <td class="no-padding"><input type="number" name="amount[]" min="0.1"
                                                        readonly required step="any" value="{{ $detail->amount }}"
                                                        class="form-control text-center no-padding"
                                                        id="amount_{{ $detail->product_id }}"></td>
                                                <td class="no-padding"> <span class="btn btn-sm btn-danger"
                                                        onclick="deleteRow({{ $detail->product_id }})">X</span> </td>
                                                <input type="hidden" name="id[]" id="id_{{ $detail->product_id }}"
                                                    value="{{ $detail->product_id }}">
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Total</th>
                                            <th class="text-end" id="totalQty">0.00</th>
                                            <th></th>
                                            <th class="text-end" id="totalAmount">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="comp">Purchase Inv No.</label>
                                    <input type="text" name="inv" id="inv" value="{{ $purchase->inv }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" value="{{ $purchase->date }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="warehouse">Branch</label>
                                    <input type="text" name="branch" id="branch" value="{{ $branch->name }}"
                                        class="form-control" readonly>
                                    <input type="hidden" name="branch_id" id="branch_id" value="{{ $branch->id }}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="vendor">Vendor</label>
                                    <select name="vendorID" id="vendor" class="selectize1">
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" @selected($vendor->id == $purchase->vendor_id)>
                                                {{ $vendor->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="attachement">Attachment</label>
                                    <input type="file" name="file" id="attachement" class="form-control">
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
                                        @foreach ($purchase_expenses as $key => $expense)
                                            @php $ser = $key + 1; @endphp
                                            <tr id="expense_{{ $ser }}">
                                                <td>
                                                    <select name="category[]" required class="form-control">
                                                        <option value="">Select Category</option>
                                                        @foreach ($expense_categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                @selected($category->id == $expense->category_id)>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="expense_account[]" required class="form-control">
                                                        <option value="">Select Account</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                @selected($account->id == $expense->account_id)>{{ $account->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="expense_amount[]" required
                                                        class="form-control" value="{{ $expense->amount }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="expense_notes[]" class="form-control"
                                                        value="{{ $expense->notes }}">
                                                </td>
                                                <td>
                                                    <span class="btn btn-sm btn-danger"
                                                        onclick="deleteExpense({{ $ser }})">X</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{ $purchase->notes }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Update Purchase</button>
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
        $(document).ready(function() {
            updateTotal();
        });
        $(".selectize1").selectize();
        $(".selectize").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != 0) {
                    getSingleProduct(value);
                    this.clear();
                    this.focus();
                }
            },
        });
        var existingProducts = @json($purchase->details->pluck('product_id'));

        function getSingleProduct(id) {
            $.ajax({
                url: "{{ url('purchases/getproduct/') }}/" + id,
                method: "GET",
                success: function(product) {
                    let found = $.grep(existingProducts, function(element) {
                        return element == product.id;
                    });
                    if (found.length > 0) {} else {
                        var id = product.id;
                        var units = product.units;
                        var price = units[0].price;
                        var html = '<tr id="row_' + id + '">';
                        html +=
                            '<td class="no-padding">' + product.name + '</td>';
                        html +=
                            '<td class="no-padding"><select name="unit[]" class="form-control text-center no-padding" onchange="changeUnit(' +
                            id + ')" id="unit_' + id + '">';
                        units.forEach(function(unit) {
                            html += '<option data-unit="' + unit.value + '" data-price="' + unit.price +
                                '" value="' + unit.id + '">' +
                                unit.unit_name + ' (' + unit.value + ')</option>';
                        });
                        html += '</select></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="qty[]" oninput="updateChanges(' +
                            id +
                            ')" min="0" required step="any" value="1" class="form-control text-center no-padding" id="qty_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="price[]" oninput="updateChanges(' +
                            id + ')" required step="any" value="' + price +
                            '" min="1" class="form-control text-center no-padding" id="price_' + id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="amount[]" min="0.1" readonly required step="any" value="1" class="form-control text-center no-padding" id="amount_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRow(' +
                            id + ')">X</span> </td>';
                        html += '<input type="hidden" name="id[]" id="id_' + id + '" value="' + id + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        existingProducts.push(id);
                        updateChanges(id);
                    }
                }
            });
        }

        function updateChanges(id) {
            var qty = parseFloat($('#qty_' + id).val());
            var price = parseFloat($('#price_' + id).val());
            var amount = qty * price;
            $("#amount_" + id).val(amount.toFixed(2));
            updateTotal();
        }

        function changeUnit(id) {
            var unit = $('#unit_' + id).find('option:selected');
            unit = unit.data('price');
            $("#price_" + id).val(unit);
            updateChanges(id);
        }

        function updateTotal() {
            var total = 0;
            $("input[id^='amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                total += parseFloat(inputValue);
            });

            $("#totalAmount").html(total.toFixed(2));

            var totalQty = 0;
            $("input[id^='qty_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalQty += parseFloat(inputValue);
            });

            $("#totalQty").html(totalQty.toFixed(2));
        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value != id;
            });
            $('#row_' + id).remove();
            updateTotal();
        }

        var categories = @json($expense_categories);
        var accounts = @json($accounts);
        var expense_ser = {{ $purchase_expenses->count() }};

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
                html += '<option value="' + account.id + '">' + account.title + '</option>';
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
