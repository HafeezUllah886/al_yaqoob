@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Edit Role</h3>
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
                    <form action="{{ route('roles.update', $role->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $role->name }}" required>
                        </div>
                        <div class="mb-3">
                            <input type="checkbox" id="all_permissions">
                            <label for="all_permissions">All Permissions</label>
                            <div id="permissions">
                                <div class="row">
                                    @foreach ($permissions as $group => $perms)
                                        <div class="col-md-3">
                                            <fieldset class="mb-2">
                                                <legend class="h6">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input group-select" type="checkbox"
                                                            id="group-{{ Str::slug($group) }}"
                                                            data-group="{{ Str::slug($group) }}">
                                                        <label class="form-check-label" for="group-{{ Str::slug($group) }}">
                                                            {{ $group }}
                                                        </label>
                                                    </div>
                                                </legend>
                                                @foreach ($perms as $permission)
                                                    <div class="form-check d-block me-3">
                                                        <input
                                                            class="form-check-input permission-checkbox group-{{ Str::slug($group) }}"
                                                            type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            id="permission-{{ $permission->name }}"
                                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="permission-{{ $permission->name }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </fieldset>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

        // Select All Permissions
        $("#all_permissions").click(function() {
            $('.group-select, .permission-checkbox').prop('checked', $(this).is(':checked'));
        });

        // Select Group Permissions
        $(".group-select").click(function() {
            var groupId = $(this).data('group');
            $('.group-' + groupId).prop('checked', $(this).is(':checked'));
            checkAllPermissionsStatus();
        });

        // Individual Permission Click
        $(".permission-checkbox").click(function() {
            var groupId = $(this).attr('class').split(' ').find(c => c.startsWith('group-')).replace('group-', '');
            checkGroupStatus(groupId);
            checkAllPermissionsStatus();
        });

        function checkGroupStatus(groupId) {
            var total = $('.group-' + groupId).length;
            var checked = $('.group-' + groupId + ':checked').length;

            if (checked === 0) {
                $('#group-' + groupId).prop('checked', false).prop('indeterminate', false);
            } else if (checked === total) {
                $('#group-' + groupId).prop('checked', true).prop('indeterminate', false);
            } else {
                $('#group-' + groupId).prop('checked', false).prop('indeterminate', true);
            }
        }


        function checkAllPermissionsStatus() {
            var total = $('.permission-checkbox').length;
            var checked = $('.permission-checkbox:checked').length;

            if (checked === 0) {
                $('#all_permissions').prop('checked', false).prop('indeterminate', false);
            } else if (checked === total) {
                $('#all_permissions').prop('checked', true).prop('indeterminate', false);
            } else {
                $('#all_permissions').prop('checked', false).prop('indeterminate', true);
            }
        }
        checkAllPermissionsStatus();
    </script>
@endsection
