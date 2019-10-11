@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Shipdatas</div>
                    <div class="card-body">
                        <a href="{{ url('/ship-datas/create') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        <a href="{{ url('/imports/shipdata') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-plus" aria-hidden="true"></i> Import
                        </a>
                        <form method="GET" action="{{ url('/ship-datas') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>INV Date</th>
                                        <th>INV No</th>
                                        <th>ใบขน</th>
                                        <th>shipping_ref</th>
                                        <th>Product Name</th>
                                        <th>QTY</th>
                                        <th>FOB/BHT</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($shipdatas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->INV_DATE }}</td>
                                        <td>{{ $item->inv_no }}</td>
                                        <td>{{ $item->trans_no }}</td>
                                        <td>{{ $item->shipping_ref }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ round($item->FOB,2) }} / {{ round($item->BHT,2) }}</td>
                                        <td>
                                            <a href="{{ url('/ship-datas/' . $item->id) }}" title="View ShipData"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/ship-datas/' . $item->id . '/edit') }}" title="Edit ShipData"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/ship-datas' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete ShipData" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $shipdatas->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
