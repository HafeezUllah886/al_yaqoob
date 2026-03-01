@extends('layout.popups')
@section('content')
    <style>
        .table-stock-in { background-color: #1bc5bd !important; color: white !important; }
        .table-stock-out { background-color: #f64e60 !important; color: white !important; }
        .text-white { color: white !important; }
        .fw-bold { font-weight: bold !important; }
        .p-1 { padding: 0.25rem !important; }
        .fs-14 { font-size: 14px !important; }
        @media print {
            .d-print-none { display: none !important; }
            .table-stock-in { background-color: #1bc5bd !important; -webkit-print-color-adjust: exact; }
            .table-stock-out { background-color: #f64e60 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                            <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                        </div>
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="fw-bold">{{ $branch_name }}</h2>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3 text-end">
                                    <h3 class="fw-bold">Stock Movement Report</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">From</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($start_date)) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">To (Closing Date)</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($end_date)) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">Branch</p>
                                    <h5 class="fs-14 mb-0 text-uppercase">{{ $branch_name }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-12">Printed On</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="p-1 align-middle">#</th>
                                            <th rowspan="2" class="p-1 align-middle text-start">Product</th>
                                            <th rowspan="2" class="p-1 align-middle text-center">Unit</th>
                                            <th rowspan="2" class="p-1 align-middle text-center">Pack Size</th>
                                            <th rowspan="2" class="p-1 align-middle text-end">Opening</th>
                                            <th colspan="4" class="p-1 table-stock-in text-white fw-bold text-center">Stock - In</th>
                                            <th colspan="4" class="p-1 table-stock-out text-white fw-bold text-center">Stock - Out</th>
                                            <th rowspan="2" class="p-1 align-middle text-end">Closing</th>
                                            <th rowspan="2" class="p-1 align-middle text-end">Current</th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 table-stock-in text-white fw-bold fs-12">Purchase</th>
                                            <th class="p-1 table-stock-in text-white fw-bold fs-12">Transfer In</th>
                                            <th class="p-1 table-stock-in text-white fw-bold fs-12">Adj.</th>
                                            <th class="p-1 table-stock-in text-white fw-bold fs-12">Total In</th>
                                            <th class="p-1 table-stock-out text-white fw-bold fs-12">Sales</th>
                                            <th class="p-1 table-stock-out text-white fw-bold fs-12">Transfer Out</th>
                                            <th class="p-1 table-stock-out text-white fw-bold fs-12">Adj.</th>
                                            <th class="p-1 table-stock-out text-white fw-bold fs-12">Total Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($report_data as $key => $row)
                                            <tr>
                                                <td class="p-1">{{ $key + 1 }}</td>
                                                <td class="p-1 text-start">{{ $row['product'] }}</td>
                                                <td class="p-1 text-center">{{ $row['unit'] }}</td>
                                                <td class="p-1 text-center">{{ $row['pack_size'] }}</td>
                                                <td class="p-1 text-end">{{ number_format($row['opening'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end">{{ number_format($row['purchase'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end">{{ number_format($row['stock_transfers_in'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end">{{ number_format($row['adj_in'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end fw-bold">{{ number_format($row['total_in'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end">{{ number_format($row['sales'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end">{{ number_format($row['stock_transfers_out'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end">{{ number_format($row['adj_out'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end fw-bold">{{ number_format($row['total_out'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end fw-bold">{{ number_format($row['closing'] / $row['pack_size'], 1) }}</td>
                                                <td class="p-1 text-end fw-bold">{{ number_format($row['current_stock'] / $row['pack_size'], 1) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
