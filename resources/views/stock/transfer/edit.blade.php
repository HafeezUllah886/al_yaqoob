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
                                    <h3> Edit Stock Transfer </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse"><a href="{{ route('stockTransfer.index') }}"
                                        class="btn btn-danger">Close</a></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('stockTransfer.update', $stockTransfer->id) }}" enctype="multipart/form-data" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" value="{{ $stockTransfer->date }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="fromID">From (Branch)</label>
                                    <input type="text" value="{{ $branchFrom->name }}" readonly class="form-control">
                                    <input type="hidden" value="{{ $branchFrom->id }}" name="from">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="toID">To (Branch)</label>
                                    <input type="text" value="{{ $branchTo->name }}" readonly class="form-control">
                                    <input type="hidden" value="{{ $branchTo->id }}" name="to">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="attachement">Attachment</label>
                                    <input type="file" name="file" id="attachement" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <input type="text" value="{{ $product->name }}" readonly class="form-control">
                                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                                </div>
                            </div>
                            <input type="hidden" id="base_stock" value="{{ $stock }}">
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="qty">Unit</label>
                                    <select name="unit_id" class="form-control" onchange="changeUnit(this)">
                                        @foreach ($product->units as $unit)
                                            <option value="{{ $unit->id }}" data-value="{{ $unit->value }}" {{ $stockTransfer->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="qty">Available Stock</label>
                                    <input type="number" value="{{ number_format($stock / $stockTransfer->unit_value, 2) }}"
                                        id="available_stock" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="qty">Transfer Quantity</label>
                                    <input type="number" value="{{ $stockTransfer->pcs }}" required min="1" name="qty" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="form-group">
                                    <label for="transporter">Transporter</label>
                                    <select name="transporter" id="transporter" required class="form-control">
                                        <option value="">Select Transporter</option>
                                        @foreach ($transporters as $transporter)
                                            <option value="{{ $transporter->id }}" {{ $stockTransfer->transporter_id == $transporter->id ? 'selected' : '' }}>{{ $transporter->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="form-group">
                                    <label for="driver">Driver Name</label>
                                    <input type="text" name="driver" id="driver" value="{{ $stockTransfer->driver }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="form-group">
                                    <label for="vehicle">Vehicle Number</label>
                                    <input type="text" name="vehicle" id="vehicle" value="{{ $stockTransfer->vehicle }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="form-group">
                                    <label for="fare">Fare</label>
                                    <input type="number" name="fare" id="fare" value="{{ $stockTransfer->fare }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="form-group">
                                    <label for="payment_status">Payment Status</label>
                                    <select name="payment_status" id="payment_status" class="form-control">
                                        <option value="Unpaid" {{ $stockTransfer->payment_status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="Paid" {{ $stockTransfer->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="form-group">
                                    <label for="account_id">Payment Account</label>
                                    <select name="account_id" id="account_id" class="form-control">
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}" {{ $stockTransfer->account_id == $account->id ? 'selected' : '' }}>{{ $account->title }}</option>
                                        @endforeach
                                    </select>
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
                                        @foreach ($expenses as $key => $expense )
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
                                                        value="{{ $expense->notes && str_contains($expense->notes, ':') ? trim(last(explode(':', $expense->notes))) : $expense->notes }}">
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
                                    <label for="notes">Transfer Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{ $stockTransfer->notes }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Update Transfer</button>
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
        var expense_ser = {{ $expenses->count() }};

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
