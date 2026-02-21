@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h1>{{ projectNameMedium() }}</h1>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <h3>Payment Receipt</h3>
                                    <p> <span class="text-muted text-uppercase fw-semibold mt-0 m-0 p-0">Payment Ref #
                                        </span><span class="fs-14 m-0 p-0">{{ $tran->refID }}</span></p>
                                    <p> <span class="text-muted text-uppercase fw-semibold mt-0 m-0 p-0">Date : </span><span
                                            class="fs-14 m-0 p-0">{{ date('d M Y', strtotime($tran->date)) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:30%;" class="p-4 pb-1"><strong>Paid to</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">
                                        {{ $tran->toAccount->title }}</td>
                                </tr>
                                <tr>
                                    <td style="width:30%;" class="p-4 pb-1"><strong>Payment Amount</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">
                                        {{ number_format($tran->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="width:30%;" class="p-4 pb-1"><strong>Amount in Words</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">
                                        Rupees {{ numberToWords($tran->amount, 2) }} Only</td>
                                </tr>
                                <tr>
                                    <td style="width:30%;" class="p-4 pb-1"><strong>Paid from</strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">
                                        {{ $tran->account->title }}</td>
                                </tr>
                            </table>
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:80%;" class="p-4 pb-1 text-end" colspan="3"><strong>Previous
                                            Balance: </strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">
                                        {{ number_format(spotBalanceBefore($tran->to_account_id, $tran->refID), 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-4 pb-1"><strong>Paid By: _________________</strong></td>
                                    <td class="p-4 pb-1"><strong>Received By: _________________</strong></td>
                                    <td class="p-4 pb-1 text-end"><strong>Current Balance: </strong></td>
                                    <td class="border-2 border-top-0 border-start-0 border-end-0 text-center p-4 pb-1">
                                        {{ number_format(spotBalance($tran->to_account_id, $tran->refID), 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            <p><strong>Notes: </strong> {{ $tran->notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
