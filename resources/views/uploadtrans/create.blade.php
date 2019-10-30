@extends('layouts.appnew')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>Upload ใบโอนเงิน</h3></div>
                    <div class="card-body">
                        <a href="{{ url('/uploadtrans/index') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/uploadtrans/createAction') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class='row'>
                                <div class="col-md-6">
                                    <label for="custom-headers" class="control-label">เลข Invoice</label>
                                    <select id='custom-headers[]' name="custom-headers[]"  multiple='multiple' class="searchable">
                                    @foreach ($polistlist as $poobj)
                                        @if ($poobj->status_transclose <> 'Yes')
                                            <option value='{{ $poobj->id }}'>{{ $poobj->inv_name }}</option>
                                        @endif
                                        
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="uploadfile" class="control-label">PDF ใบโอนเงิน</label>
                                    <input type="file" accept="application/pdf" name="uploadfile" id="uploadfile">  
                                    <label for="trans_date" class="control-label">วันที่โอน</label>
                                    <input class="form-control" name="trans_date"  id="trans_date" type='date' >
                                    <label for="total_usd" class="control-label">ยอด USD</label>
                                    <input class="form-control" name="total_usd"  id="total_usd" value="0" >
                                    <label for="note" class="control-label">Note</label>
                                    <textarea class="form-control" name="note"  id="note" ></textarea>
 </div>
                                <div class="form-group col-md-12">
                                    <input name='btnsave' id='btnsave' class="btn btn-primary" type="submit" value="Upload" >
                               
                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
