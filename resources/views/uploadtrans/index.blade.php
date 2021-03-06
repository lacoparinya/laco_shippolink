@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>ใบโอนเงิน</h3></div>
                    <div class="card-body">
                        <a href="{{ url('/uploadtrans/create') }}" class="btn btn-success btn-sm" title="Add New SapDataCf">
                            <i class="fa fa-plus" aria-hidden="true"></i> Upload ใบโอน
                        </a>
                        <br/>
                        <form method="GET" action="{{ url('/uploadtrans/index') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th>วันที่โอน</th>
                                        <th>USD</th>
                                        <th>Invoice</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($banktransms as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}
                                         </td>
                                        <td>{{ $item->filename }}
                                            @if (!empty($item->processpath))
                                                <br/><a href="{{ url('/uploadtrans/genpdf/' . $item->id) }}" title="View SapDataCf" target="_blank"><button class="btn btn-success btn-sm">Process</button></a>    
                                            @else
                                                <br/><a href="{{ url('/uploadtrans/updateformat/' . $item->id) }}" title="View SapDataCf" target="_blank"><button class="btn btn-danger btn-sm">Update</button></a>   
                                            @endif
                                        </td>
                                        <td>{{ $item->trans_date }}</td>
                                        <td>{{ number_format($item->total_usd,2,".",",") }}</td>
                                        <td>
                                            @foreach ($item->banktransd()->get() as $item2)
                                                {{  $item2->podata->inv_name or  $item2->other_case}}<br/>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ url('/uploadtrans/addnewinv/' . $item->id) }}" title="View SapDataCf"><button class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add Inv</button></a>
                                            <a href="{{ url('/uploadtrans/view/' . $item->id) }}" title="View SapDataCf"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/uploadtrans/edit/' . $item->id) }}" title="Edit SapDataCf"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            <form method="POST" action="{{ url('/uploadtrans/delete' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete SapDataCf" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $banktransms->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection