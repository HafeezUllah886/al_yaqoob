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
                                    <h3>Sales Report</h3>
                                </div>
                            </div>
                        </div>
                        <!--end card-header-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Start Date</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($start_date)) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">End Date</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($end_date)) }}</h5>
                                </div>
                               
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Branch</p>
                                    <h5 class="fs-14 mb-0 text-uppercase">{{ $branch == 'all' ? 'All Branches' : \App\Models\Branches::find($branch)->name }}</h5>
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
                                            <th scope="col" class="text-start p-1">Product</th>
                                            <th scope="col" class="text-center p-1">Unit</th>
                                            <th scope="col" class="text-center p-1">Pack Size</th>
                                            <th scope="col" class="text-center p-1">Quantity</th>
                                            <th scope="col" class="text-center p-1">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                           
                                            $netAmount = 0;
                                            $netDiscount = 0;
                                        @endphp
                                        @foreach ($data as $key => $customer)
                                        @php
                                             $totalAmount = 0;
                                            $totalDiscount = 0;
                                        @endphp
                                          <tr>
                                            <td colspan="6" class="text-start table-active p-1 fw-bold">{{ $customer['details']->title }} @if($branch == 'all')| {{ $customer['details']->branch->name }}@endif</td>
                                          </tr>
                                          @foreach ($customer['products'] as $key => $product)
                                          @php
                                              $product_details = \App\Models\Products::find($product->product_id);
                                              $product_unit = \App\Models\Product_units::where('product_id', $product->product_id)->first();
                                              $totalAmount += $product->total_amount;
                                          @endphp
                                              <tr>
                                                <td class="p-1">{{$key+1}}</td>
                                                <td class="text-start p-1">{{$product_details->name}}</td>
                                                <td class="text-center p-1">{{$product_unit->unit_name}}</td>
                                                <td class="text-end p-1">{{$product_unit->value}}</td>
                                                <td class="text-end p-1">{{number_format($product->total_qty / $product_unit->value, 2)}}</td>
                                                <td class="text-end p-1">{{number_format($product->total_amount, 2)}}</td>
                                              </tr>
                                          @endforeach
                                          @php
                                              $netAmount += $totalAmount;
                                              $netDiscount += $customer['discounts']; 
                                          @endphp
                                          <tr>
                                            <td colspan="2" class="table-active p-1 text-end">Total = {{number_format($totalAmount, 2)}}  </td>
                                            <td colspan="2" class="table-active p-1 text-end">Discount = {{number_format($customer['discounts'], 2)}} </td>
                                            <td colspan="2" class="table-active p-1 text-end">Sale Amount = {{number_format($totalAmount - $totalDiscount, 2)}} </td>
                                           
                                          </tr>
                                        @endforeach
                                        @if (count($data) > 1)
                                            <tr>
                                          <td colspan="2" class="table-active p-1 fw-bold text-end">Grant Total = {{number_format($netAmount, 2)}} </td>
                                          <td colspan="2" class="table-active p-1 fw-bold text-end">Total Discount = {{number_format($netDiscount, 2)}} </td>
                                          <td colspan="2" class="table-active p-1 fw-bold text-end">Net = {{number_format($netAmount - $netDiscount, 2)}} </td>
                                         
                                        </tr>
                                        @endif
                                        
                                      
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
