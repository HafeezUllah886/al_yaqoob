@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-10">
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
                                    <h3>Non-Business Expense Report</h3>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <h5 class="fs-14 mb-0 text-uppercase">{{ $branch == 'all' ? 'All Assigned Branches' : \App\Models\Branches::find($branch)->name }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th scope="col" style="width: 50px;" class="p-1">#</th>
                                            <th scope="col" class="text-start p-1">Date</th>
                                            <th scope="col" class="text-start p-1">Category</th>
                                            <th scope="col" class="text-start p-1">Account</th>
                                            <th scope="col" class="text-start p-1">Branch</th>
                                            <th scope="col" class="text-end p-1">Amount</th>
                                            <th scope="col" class="text-start p-1">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenses as $key => $expense)
                                            <tr>
                                                <td class="p-1">{{ $key + 1 }}</td>
                                                <td class="text-start p-1">{{ date('d M Y', strtotime($expense->date)) }}</td>
                                                <td class="text-start p-1">
                                                    {{ $expense->category->name }}
                                                    @if($expense->category->parent)
                                                        <small class="text-muted">({{ $expense->category->parent->name }})</small>
                                                    @endif
                                                </td>
                                                <td class="text-start p-1">{{ $expense->account->title }}</td>
                                                <td class="text-start p-1">{{ $expense->branch->name }}</td>
                                                <td class="text-end p-1">{{ number_format($expense->amount, 2) }}</td>
                                                <td class="text-start p-1"><small>{{ $expense->notes }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <th colspan="5" class="text-end p-1">Grand Total:</th>
                                            <th class="text-end p-1">{{ number_format($total_amount, 2) }}</th>
                                            <th></th>
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
