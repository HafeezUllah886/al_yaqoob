@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                            <a href="javascript:window.print()" class="btn btn-success ml-4"><i
                                    class="ri-printer-line mr-4"></i> Print</a>
                        </div>
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1">
                                    <h1 class="fw-bold text-uppercase">{{ projectNameMedium() }}</h1>
                                </div>
                                <div class="flex-shrink-0 text-end">
                                    <h2 class="fw-bold">Profit Report</h2>
                                    <h5 class="text-muted">{{ $branch_name }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">From</p>
                                    <h5 class="fs-14 mb-0">{{ date('d-m-Y', strtotime($from)) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">To</p>
                                    <h5 class="fs-14 mb-0">{{ date('d-m-Y', strtotime($to)) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr class="table-light">
                                            <th class="p-1">#</th>
                                            <th class="p-1 text-start">Product</th>
                                            <th class="p-1 text-start">Unit</th>
                                            <th class="p-1 text-start">Pack Size</th>
                                            <th class="p-1 text-end">Purchase Price</th>
                                            <th class="p-1 text-end">Sale Price</th>
                                            <th class="p-1 text-end">Unit Profit</th>
                                            <th class="p-1 text-end">Total Sold</th>
                                            <th class="p-1 text-end">Net Profit</th>
                                            <th class="p-1 text-end">Stock</th>
                                            <th class="p-1 text-end">Stock Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $key => $product)
                                            @if($product->sold > 0 || $product->stock > 0)
                                            @php
                                                $unit = $product->units->first();
                                            @endphp
                                            <tr>
                                                <td class="p-1">{{ $key + 1 }}</td>
                                                <td class="p-1 text-start">{{ $product->name }}</td>
                                                <td class="p-1 text-start">{{ $unit->unit_name }}</td>
                                                <td class="p-1 text-start">{{ $unit->value }}</td>
                                                <td class="p-1 text-end">{{ number_format($product->purchase_price , 2) }}</td>
                                                <td class="p-1 text-end">{{ number_format($product->sale_price , 2) }}</td>
                                                <td class="p-1 text-end">{{ number_format($product->profit , 2) }}</td>
                                                <td class="p-1 text-end">{{ number_format($product->sold , 0) }}</td>
                                                <td class="p-1 text-end">{{ number_format($product->net_profit , 2) }}</td>
                                                <td class="p-1 text-end">{{ number_format($product->stock , 0) }}</td>
                                                <td class="p-1 text-end">{{ number_format($product->stock_value, 2) }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8" class="text-end p-1">Total:</th>
                                            <th class="text-end p-1">{{ number_format($product_profit, 2) }}</th>
                                            <th class="text-end p-1">{{ number_format($products->sum('stock'), 0) }}</th>
                                            <th class="text-end p-1">{{ number_format($stock_value, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="8" class="text-end p-1 border-0">Expenses (-):</th>
                                            <th class="text-end p-1 border-top">{{ number_format($expenses, 2) }}</th>
                                            <th colspan="2" class="border-0"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="8" class="text-end p-1 border-0">Non-Business Expenses (-):</th>
                                            <th class="text-end p-1 border-top">{{ number_format($non_business_expenses, 2) }}</th>
                                            <th colspan="2" class="border-0"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="8" class="text-end p-1 border-0">Net Profit:</th>
                                            <th class="text-end p-1 border-top border-bottom fw-bold fs-16">
                                                {{ number_format($product_profit - $expenses - $non_business_expenses, 2) }}
                                            </th>
                                            <th colspan="2" class="border-0"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
