@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12"><h2>Upload Excel | รายการPO</h2>
            </div>

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/imports/processAction') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                            <div class="form-group col-md-6">
                            <input type='hidden' id='import_type' name = 'import_type' value='podata' />
                            <input  type="file" accept="excel/*" name="uploadfile" id="uploadfile"> 
                        </div>
                        <div class="form-group col-md-6">
                                <input name='btnsave' id='btnsave' class="btn btn-primary" type="submit" value="Upload" >
                            </div>
                        </form>
            </div>
        </div>
    </div>
@endsection