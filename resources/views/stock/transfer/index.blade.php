@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>{!! lang("Stock_Transfer") !!}</h3>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTransfer">{!! lang("Create") !!}</button>
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
                            <th>{!! lang("Product") !!}</th>
                            <th>{!! lang("Qty") !!}</th>
                            <th>{!! lang("From") !!}</th>
                            <th>{!! lang("To") !!}</th>
                            <th>{!! lang("Date") !!}</th>
                            <th>{!! lang("Action") !!}</th>
                        </thead>
                        <tbody>
                            @foreach ($stockTransfers as $key => $transfer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transfer->product->name }}</td>
                                    <td>{{ $transfer->qty }}</td>
                                    <td>{{ $transfer->fromBranch->name }}</td>
                                    <td>{{ $transfer->toBranch->name }}</td>
                                    <td>{{ date('d M Y', strtotime($transfer->date)) }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                               {{--  <li>
                                                    <button class="dropdown-item" onclick="newWindow('{{route('stockTransfers.show', $transfer->id)}}')"
                                                        onclick=""><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                        {!! lang("View") !!}
                                                    </button>
                                                </li> --}}
                                                <li>
                                                    <a class="dropdown-item text-danger" href="{{route('stockTransfers.delete', $transfer->refID)}}">
                                                        <i class="ri-delete-bin-2-fill align-bottom me-2 text-danger"></i>
                                                        {!! lang("Delete") !!}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
     <div id="createTransfer" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{!! lang("Create") !!} {!! lang("Stock_Transfer") !!}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form method="get" action="{{route('stockTransfers.create')}}" id="form">
                         <div class="modal-body">
                           <div class="form-group">
                            <label for="">{!! lang("From") !!} {!! lang("Branch") !!}</label>
                            <select name="fromBranch" id="fromBranch" class="form-control">
                                @foreach ($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                           </div>
                           <div class="form-group mt-2">
                            <label for="">{!! lang("To") !!} {!! lang("Branch") !!}</label>
                            <select name="toBranch" id="toBranch" class="form-control">
                                @foreach ($toBranches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                           </div>
                         </div>
                         <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{!! lang("Close") !!}</button>
                                <button type="submit" id="viewBtn" class="btn btn-primary">{!! lang("Create") !!}</button>
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

   
@endsection


