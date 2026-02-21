@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                            <a href="javascript:window.print()" class="btn btn-success ml-4"><i
                                    class="ri-printer-line mr-4"></i> Print</a>
                        </div>
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h1>{{ projectName() }}</h1>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <h3>Sales Invoice</h3>
                                </div>
                            </div>
                        </div>
                        <!--end card-header-->
                    </div><!--end col-->
                    <div class="col-lg-12 ">

                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Bill #</p>
                                    <h5 class="fs-14 mb-0">{{ $sale->id }}</h5>
                                </div>
                                <!--end col-->
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($sale->date)) }}</h5>
                                </div>
                                <!--end col-->
                                <div class="col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Customer</p>
                                    <h5 class="fs-14 mb-0">{{ $sale->customer->title }}</h5>
                                </div>
                                <!--end col-->
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                    <h5 class="fs-14 mb-0"><span id="total-amount">{{ date('d M Y') }}</span></h5>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                            <thead>
                                                <tr class="table-active">
                                                    <th scope="col" style="width: 50px;">#</th>
                                                    <th scope="col" class="text-start">Product</th>
                                                    <th scope="col" class="text-start">Unit</th>
                                                    <th scope="col" class="text-end">Quantity</th>
                                                    <th scope="col" class="text-end">Price</th>
                                                    <th scope="col" class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="products-list">

                                                @foreach ($sale->details as $key => $product)
                                                    <tr>
                                                        <td class="p-1 m-1">{{ $key + 1 }}</td>
                                                        <td class="text-start p-1 m-1">{{ $product->product->name }}</td>
                                                        <td class="text-start m-1 p-1">{{ $product->unit->unit_name }}</td>
                                                        <td class="text-end m-1 p-1">
                                                            {{ number_format($product->qty) }}</td>
                                                        <td class="text-end p-1 m-1">
                                                            {{ number_format($product->price, 2) }}
                                                        </td>
                                                        <td class="text-end p-1 m-1">
                                                            {{ number_format($product->amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-end p-1 m-1">Total</th>
                                                    <th class="text-end p-1 m-1">
                                                        {{ number_format($sale->details->sum('qty')) }}</th>
                                                    <th></th>
                                                    <th class="text-end p-1 m-1">
                                                        {{ number_format($sale->details->sum('amount'), 2) }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5" class="text-end p-1 m-1">Discount</th>
                                                    <th class="text-end p-1 m-1">{{ number_format($sale->discount, 2) }}
                                                    </th>
                                                </tr>
                                                <tr class="table-active">
                                                    <th colspan="5" class="text-end p-1 m-1">Net Amount</th>
                                                    <th class="text-end p-1 m-1 text-primary">
                                                        {{ number_format($sale->total, 2) }}</th>
                                                </tr>
                                            </tfoot>
                                        </table><!--end table-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p><strong>Notes: </strong>{{ $sale->notes }}</p>
                        </div>
                        <!--end card-body-->
                    </div><!--end col-->

                </div><!--end row-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
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
