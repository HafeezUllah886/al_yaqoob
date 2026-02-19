@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6"><h3> Create Stock Transfer </h3></div>
                                <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()" class="btn btn-danger">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('stockTransfers.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <select name="product" class="selectize">
                                        @foreach ($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="qty">Quantity</label>
                                    <input type="number" value="0" name="qty" class="form-control">
                                </div>
                            </div>
                           
                            <div class="col-3 mt-2">
                                <div class="form-group">
                                    <label for="fromID">From (Branch)</label>
                                    <input type="text" value="{{$branchFrom->name}}" disabled class="form-control">
                                    <input type="hidden" value="{{$branchFrom->id}}" name="from">
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="toID">To (Branch)</label>
                                    <input type="text" value="{{$branchTo->name}}" disabled class="form-control">
                                    <input type="hidden" value="{{$branchTo->id}}" name="to">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Transfer</button>
                            </div>
                </div>
            </form>
            </div>

        </div>
        <!--end card-->
    </div>
    <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize1").selectize();
        $(".selectize").selectize();
       
      


    </script>
@endsection
