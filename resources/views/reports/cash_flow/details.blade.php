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
                                    <h1>{{projectNameMedium()}}</h1>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <h3>Daily Cash Flow Report</h3>
                                </div>
                            </div>
                        </div>
                        <!--end card-header-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($date)) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Branch</p>
                                    <h5 class="fs-14 mb-0">{{ $branch_name }}</h5>
                                </div>
                               
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Category</p>
                                    <h5 class="fs-14 mb-0 text-uppercase">{{ $category }}</h5>
                                </div>
                                <!--end col-->
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                    <h5 class="fs-14 mb-0"><span id="total-amount">{{ date('d M Y') }}</span></h5>
                                </div>
                                <!--end col-->
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Opening Balance</p>
                                    <h5 class="fs-14 mb-0">{{ number_format($opening_balance, 2) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total In</p>
                                    <h5 class="fs-14 mb-0">{{ number_format($cash_in->sum('cr'), 2) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total Out</p>
                                    <h5 class="fs-14 mb-0">{{ number_format($cash_out->sum('db'), 2) }}</h5>
                                </div>
                               
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Closing Balance</p>
                                    <h5 class="fs-14 mb-0">{{ number_format($closing_balance, 2) }}</h5>
                                </div>
                               
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body">
                            <div class="card-header border-bottom-dashed">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h3>In Flow</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th scope="col" style="width: 50px;" class="p-1">#</th>
                                            <th scope="col" class="text-start p-1">Ref #</th>
                                            <th scope="col" class="text-start p-1">Account</th>
                                            <th scope="col" class="text-start p-1">Notes</th>
                                            <th scope="col" class="text-end p-1">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cash_in as $item)
                                            <tr class="p-1">
                                                <td class="p-1">{{ $loop->iteration }}</td>
                                                <td class="p-1">{{ $item->refID }}</td>
                                                <td class="text-start p-1">{{ $item->account->title }}</td>
                                                <td class="text-start p-1">{{ $item->notes }}</td>
                                                <td class="text-end p-1">{{ number_format($item->cr, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="text-end p-1 fw-bold">Total In</td>
                                            <td class="text-end p-1 fw-bold">{{ number_format($cash_in->sum('cr'), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table><!--end table-->
                            </div>

                        </div>
                        <!--end card-body-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body">
                            <div class="card-header border-bottom-dashed">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h3>Out Flow</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th scope="col" style="width: 50px;" class="p-1">#</th>
                                            <th scope="col" class="text-start p-1">Ref #</th>
                                            <th scope="col" class="text-start p-1">Account</th>
                                            <th scope="col" class="text-start p-1">Notes</th>
                                            <th scope="col" class="text-end p-1">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cash_out as $item)
                                            <tr class="p-1">
                                                <td class="p-1">{{ $loop->iteration }}</td>
                                                <td class="p-1">{{ $item->refID }}</td>
                                                <td class="text-start p-1">{{ $item->account->title }}</td>
                                                <td class="text-start p-1">{{ $item->notes }}</td>
                                                <td class="text-end p-1">{{ number_format($item->db, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="text-end p-1 fw-bold">Total Out</td>
                                            <td class="text-end p-1 fw-bold">{{ number_format($cash_out->sum('db'), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table><!--end table-->
                            </div>

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
