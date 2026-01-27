@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Branches</h3>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new">Create
                            New</button>
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
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Users</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($branches as $key => $branch)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ $branch->address }}</td>
                                    <td>{{ $branch->phone }}</td>
                                    <td>
                                        @foreach ($branch->users as $user)
                                            <span class="badge bg-primary">{{ $user->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @can('Edit Branches')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#edit_{{ $branch->id }}">Edit</button>
                                            <div id="edit_{{ $branch->id }}" class="modal fade" tabindex="-1"
                                                aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Edit Branch</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"> </button>
                                                        </div>
                                                        <form action="{{ route('branches.update', $branch->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group mt-2">
                                                                    <label for="name">Name</label>
                                                                    <input type="text" name="name" required id="name"
                                                                        class="form-control" value="{{ $branch->name }}">
                                                                </div>
                                                                <div class="form-group mt-2">
                                                                    <label for="address">Address</label>
                                                                    <input type="text" name="address" required id="address"
                                                                        class="form-control" value="{{ $branch->address }}">
                                                                </div>
                                                                <div class="form-group mt-2">
                                                                    <label for="phone">Phone</label>
                                                                    <input type="text" name="phone" required id="phone"
                                                                        class="form-control" value="{{ $branch->phone }}">
                                                                </div>
                                                                <div class="form-group mt-2">
                                                                    <label for="users">Users</label>
                                                                    @foreach ($users as $user)
                                                                        <div class="form-check d-block me-3">
                                                                            <input
                                                                                class="form-check-input permission-checkbox group-{{ Str::slug($user->name) }}"
                                                                                type="checkbox" name="users[]"
                                                                                value="{{ $user->id }}"
                                                                                @if ($branch->users->contains($user->id)) checked @endif
                                                                                id="user-{{ $user->id }}">
                                                                            <label class="form-check-label"
                                                                                for="user-{{ $user->id }}">
                                                                                {{ $user->name }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="new" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Create Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('branches.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="name">Name</label>
                            <input type="text" name="name" required id="name" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="address">Address</label>
                            <input type="text" name="address" required id="address" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" required id="phone" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="users">Users</label>
                            @foreach ($users as $user)
                                <div class="form-check d-block me-3">
                                    <input class="form-check-input permission-checkbox group-{{ Str::slug($user->name) }}"
                                        type="checkbox" name="users[]" value="{{ $user->id }}"
                                        id="user-{{ $user->id }}">
                                    <label class="form-check-label" for="user-{{ $user->id }}">
                                        {{ $user->name }}
                                    </label>
                                </div>
                            @endforeach

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Default Modals -->
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
    </script>
@endsection
