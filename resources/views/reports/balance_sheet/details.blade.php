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
                                    <h3>Balance Sheet Report</h3>
                                </div>
                            </div>
                        </div>
                        <!--end card-header-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Branch</p>
                                    <h5 class="fs-14 mb-0">{{ $branch_name }}</h5>
                                </div>
                               
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Account Type</p>
                                    <h5 class="fs-14 mb-0 text-uppercase">{{ $account_type }}</h5>
                                </div>
                                <!--end col-->
                                <!--end col-->
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                    <h5 class="fs-14 mb-0"><span id="total-amount">{{ date('d M Y') }}</span></h5>
                                    {{-- <h5 class="fs-14 mb-0"><span id="total-amount">{{ \Carbon\Carbon::now()->format('h:i A') }}</span></h5> --}}
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th scope="col" style="width: 50px;" class="p-1">#</th>
                                            <th scope="col" class="text-start p-1">Type</th>
                                            <th scope="col" class="text-start p-1">Account</th>
                                            <th scope="col" class="text-start p-1">Branch</th>
                                            <th scope="col" class="text-center p-1">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                           
                                            $total_balance = 0;
                                        @endphp
                                        @foreach ($accounts as $key => $account)
                                        @php
                                             $total_balance += $account->balance;
                                        @endphp
                                         
                                              <tr>
                                                <td class="p-1">{{$key+1}}</td>
                                                <td class="text-start p-1">{{$account->type}}</td>
                                                <td class="text-start p-1">{{$account->title}}</td>
                                                <td class="text-start p-1">{{$account->branch->name}}</td>
                                                <td class="text-end p-1">{{number_format($account->balance, 2)}}</td>
                                              </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="table-active p-1 fw-bold text-end">Net Balance</td>
                                            <td class="table-active p-1 fw-bold text-end">{{number_format($total_balance, 2)}}</td>
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
