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

                        <form method="POST" action="{{ url('/uploadtrans/addnewinvAction/'.$banktransm->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
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
                                    <input name='btnsave' id='btnsave' class="btn btn-primary" type="submit" value="เพิ่ม" >
                               
                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
