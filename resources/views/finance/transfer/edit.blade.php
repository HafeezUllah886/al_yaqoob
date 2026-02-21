@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Transfer</h3>
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
                    <form action="{{ route('transfers.update', $transfer->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="from_id">From</label>
                                    <select name="from_id" id="from_id" required class="selectize">
                                        <option value=""></option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}" @selected($transfer->from_id == $account->id)>
                                                {{ $account->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="to_id">To</label>
                                    <select name="to_id" id="to_id" required class="selectize">
                                        <option value=""></option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}" @selected($transfer->to_id == $account->id)>
                                                {{ $account->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="amount">Amount</label>
                                    <input type="number" step="any" name="amount" required id="amount"
                                        class="form-control" value="{{ $transfer->amount }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" required id="date"
                                        value="{{ $transfer->date }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-2">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" cols="30" class="form-control" rows="5">{{ $transfer->notes }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="submit" class="btn btn-primary">Update Transfer</button>
                                <a href="{{ route('transfers.index') }}" class="btn btn-light">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection

@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();
    </script>
@endsection
