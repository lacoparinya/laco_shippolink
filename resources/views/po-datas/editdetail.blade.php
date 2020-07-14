@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit PoDataDetail #{{ $podatadetail->id }}</div>
                    <div class="card-body">
                        <a href="{{ url('/po-datas/'.$podatadetail->id) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/po-datas/editDetailAction/' . $podatadetail->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-4" >
                            <strong>Product Name : </strong>{{ $podatadetail->product_name }}</div>
                            <div class="col-md-4" >
                            <strong>Product Code : </strong>{{ $podatadetail->product_code }}</div>
                            <div class="col-md-4" >
                            <strong>Weight : </strong>{{number_format($podatadetail->weight,2,".",",")}}</div>                             
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" >
                                    <strong>Qty : </strong>
                                    <input class="form-control" type=number name="qty"  id="qty" value="{{ $podatadetail->qty }}">   
                                </div>
                                <div class="col-md-4" >
                                    <strong>Unit : </strong>
                                    <input class="form-control" name="unit_name"  id="unit_name" value="{{ $podatadetail->unit_name }}">   
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Update">
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
