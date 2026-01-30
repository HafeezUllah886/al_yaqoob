@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Products</h3>
                    <div>
                        @can('Create Products')
                            <button data-bs-toggle="modal" data-bs-target="#new" class="btn btn-primary">Create New</button>
                        @endcan
                    </div>
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
                    <table class="table" id="buttons-datatables">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Sale Price</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->code }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $product->unit->name ?? 'N/A' }}
                                        @if ($product->units->count() > 0)
                                            <br>
                                            <small class="text-muted">
                                                @foreach ($product->units as $u)
                                                    {{ $u->unit->name }}
                                                    ({{ (float) $u->conversion_factor }}){{ !$loop->last ? ',' : '' }}
                                                @endforeach
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @can('Edit Products')
                                            <button data-bs-toggle="modal" data-bs-target="#edit_{{ $product->id }}"
                                                class="btn btn-primary">Edit</button>
                                        @endcan
                                        {{--  @can('Delete Products')
                                            <button data-bs-toggle="modal" data-bs-target="#delete_{{ $product->id }}"
                                                class="btn btn-danger">Delete</button>
                                        @endcan --}}
                                    </td>
                                </tr>
                                <!-- Edit Modal -->
                                <div id="edit_{{ $product->id }}" class="modal fade" tabindex="-1"
                                    aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"> </button>
                                            </div>
                                            <form action="{{ route('product.update', $product->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group mt-2">
                                                        <label for="name">Name</label>
                                                        <input type="text" name="name" required class="form-control"
                                                            value="{{ $product->name }}">
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="code">Code</label>
                                                        <div class="input-group">
                                                            <input type="text" name="code" required
                                                                class="form-control" value="{{ $product->code }}" readonly>
                                                            <button type="button" class="btn btn-secondary"
                                                                onclick="generateCode(this)">Generate</button>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="category_id">Category</label>
                                                        <select name="category_id" class="form-control selectize">
                                                            <option value="">Select Category</option>
                                                            @foreach ($categories as $cat)
                                                                <option value="{{ $cat->id }}"
                                                                    {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                                    {{ $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="unit_id">Unit</label>
                                                        <select name="unit_id" required class="form-control selectize">
                                                            <option value="">Select Unit</option>
                                                            @foreach ($units as $unit)
                                                                <option value="{{ $unit->id }}"
                                                                    {{ $product->unit_id == $unit->id ? 'selected' : '' }}>
                                                                    {{ $unit->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="price">Base Unit Price</label>
                                                        <input type="number" step="any" name="price" required
                                                            class="form-control" value="{{ $product->price }}">
                                                    </div>

                                                    <hr>
                                                    <h5>Other Units</h5>
                                                    <table class="table table-bordered"
                                                        id="edit_units_table_{{ $product->id }}">
                                                        <thead>
                                                            <tr>
                                                                <th>Unit</th>
                                                                <th>Factor</th>
                                                                <th>Price</th>
                                                                <th><button type="button" class="btn btn-sm btn-success"
                                                                        onclick="addUnitRow('edit_units_table_{{ $product->id }}')">+</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($product->units as $p_unit)
                                                                <tr>
                                                                    <td>
                                                                        <select name="units[{{ $loop->index }}][unit_id]"
                                                                            class="form-control">
                                                                            @foreach ($units as $unit)
                                                                                <option value="{{ $unit->id }}"
                                                                                    {{ $p_unit->unit_id == $unit->id ? 'selected' : '' }}>
                                                                                    {{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" step="any"
                                                                            name="units[{{ $loop->index }}][conversion_factor]"
                                                                            class="form-control"
                                                                            value="{{ $p_unit->conversion_factor }}"></td>
                                                                    <td><input type="number" step="any"
                                                                            name="units[{{ $loop->index }}][price]"
                                                                            class="form-control"
                                                                            value="{{ $p_unit->price }}"></td>
                                                                    <td><button type="button" class="btn btn-sm btn-danger"
                                                                            onclick="removeUnitRow(this)">-</button></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal -->
                                {{--   <div id="delete_{{ $product->id }}" class="modal fade" tabindex="-1"
                                    aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel">Delete Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"> </button>
                                            </div>
                                            <form action="{{ route('product.destroy', $product->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this product?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Modal -->
    <div id="new" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Create Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('product.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="name">Name</label>
                            <input type="text" name="name" required id="name" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="code">Code</label>
                            <div class="input-group">
                                <input type="text" name="code" required id="code" class="form-control"
                                    readonly>
                                <button type="button" class="btn btn-secondary"
                                    onclick="generateCode(this)">Generate</button>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control selectize">
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="unit_id">Unit</label>
                            <select name="unit_id" required id="unit_id" class="form-control selectize">
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="price">Base Unit Price</label>
                            <input type="number" step="any" name="price" required id="price"
                                class="form-control" value="0">
                        </div>

                        <hr>
                        <h5>Other Units</h5>
                        <table class="table table-bordered" id="new_units_table">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Factor</th>
                                    <th>Price</th>
                                    <th><button type="button" class="btn btn-sm btn-success"
                                            onclick="addUnitRow('new_units_table')">+</button></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();

        function generateCode(btn) {
            $.ajax({
                url: "{{ route('product.generatecode') }}",
                success: function(res) {
                    $(btn).closest('.input-group').find('input').val(res);
                }
            });
        }

        function addUnitRow(tableId) {
            var table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);

            cell1.innerHTML = `<select name="units[${Date.now()}][unit_id]" class="form-control">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>`;
            cell2.innerHTML =
                `<input type="number" step="any" name="units[${Date.now()}][conversion_factor]" class="form-control" value="1">`;
            cell3.innerHTML =
                `<input type="number" step="any" name="units[${Date.now()}][price]" class="form-control" value="0">`;
            cell4.innerHTML =
                `<button type="button" class="btn btn-sm btn-danger" onclick="removeUnitRow(this)">-</button>`;
        }

        function removeUnitRow(btn) {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
@endsection
