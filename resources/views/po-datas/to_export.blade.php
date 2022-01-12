@extends('layouts.appnew')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>Export</h3></div>
                    <div class="card-body">
                        <a href="{{ route('po-datas.index') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/po-datas/export/range') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <table style="width: 70%">
                                <tr>
                                    <td style="width: 15%">
                                        ระหว่างวันที่
                                    </td>
                                    <td style="width: 25%">
                                        <input class="form-control" name="start_date"  id="start_date" type='date' required>
                                    </td>
                                    <td style="text-align:center; width: 15%;">
                                        ถึง
                                    </td>
                                    <td style="width: 25%">
                                        <input class="form-control" name="end_date"  id="end_date" type='date' required>
                                    </td>
                                    <td style="text-align:center; width: 20%">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fa fa-table" aria-hidden="true"></i>Export
                                        </button>
                                    </td>
                                </tr>   
                            </table>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
