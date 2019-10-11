@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">PoData {{ $podata->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/po-datas') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/po-datas/' . $podata->id . '/edit') }}" title="Edit PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('podatas' . '/' . $podata->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete PoData" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <div class="row">
                            <div class="col-md-3"><b>CSN</b> : {{ $podata->CSN }}</div>
                            <div class="col-md-3"><b>Order</b> : {{ $podata->order_name }}</div>
                            <div class="col-md-3"><b>Loading Date</b> : {{ $podata->loading_date}}</div>
                            <div class="col-md-3"><b>Sale Order</b> : {{ $podata->sale_order_name}}</div>
                            <div class="col-md-3"><b>Invoice</b> : {{ $podata->inv_name}}</div>
                            <div class="col-md-3"><b>Billing</b> : {{ $podata->billing_name}}</div>
                            <div class="col-md-3"><b>ใบขน</b> : {{ $podata->trans_name}}</div>
                            <div class="col-md-3"><b>Ref Shippment.</b> : {{ $podata->ref_ship_name}}</div>
                            <div class="col-md-3"><b>C & F</b> : {{ $podata->candf}}</div>
                            <div class="col-md-3"><b>Status</b> : {{ $podata->status}}</div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>Weight</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($podata->podatadetails as $item)
                                    <tr>
                                        <td>{{$item->product_name}}</td>
                                        <td>{{$item->product_code}}</td>
                                        <td>{{$item->weight}}</td>
                                        <td>{{$item->qty}}</td>
                                        <td>{{$item->unit_name}}</td>
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
@endsection
