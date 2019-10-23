@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>PO Datas</h3></div>
                    <div class="card-body">
                        <a href="{{ url('/imports/podata') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-upload" aria-hidden="true"></i> Import
                        </a>
                        <a href="{{ url('/file-uploads/create') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload ใบขน
                        </a>
                        <a href="{{ url('/imports/AllProcess') }}" class="btn btn-success btn-sm" title="Add New PoData">
                            <i class="fa fa-microchip" aria-hidden="true"></i> All ใบขน
                        </a>
                        <a href="{{ url('/imports/AllProcessCf') }}" class="btn btn-success btn-sm" title="Add New PoData">
                            <i class="fa fa-microchip" aria-hidden="true"></i> All C & F
                        </a>
                        <form method="GET" action="{{ url('/po-datas') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                                        <th>CSN</th>
                                        <th>Order Name</th>
                                        <th>Loading Date<br/>Follow up date</th>
                                        <th>Sale Order</th>
                                        <th>No. Items</th>
                                        <th>Tax Return</th>
                                        <th>Link</th>
                                        <th>OverAll</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($podatas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->CSN }}</td>
                                        <td>{{ $item->order_name }}</td>
                                        @if ( strtotime('+14 day', strtotime($item->loading_date)) <= strtotime(date('Y-m-d')))
                                          <td style="background-color:red;">{{ $item->loading_date }}<br/>{{ date('Y-m-d', strtotime('+14 day', strtotime($item->loading_date))) }}</td>
                                          
                                        @else
                                          <td>{{ $item->loading_date }}<br/>{{ date('Y-m-d', strtotime('+14 day', strtotime($item->loading_date))) }}</td>
                                          
                                        @endif
                                        
                                        <td>{{ $item->sale_order_name }}</td>
                                        <td>{{ $item->podatadetails->count() }}</td>
                                        <td>
                                            @php
                                                if($item->status == 'MAP Trans / C & F'){
                                                    $totaltax = 0;
                                                    foreach ($item->podatadetails as $itemdetail) {
                                                        if(!empty($itemdetail->ship_data_id)){
                                                            $totaltax += ($itemdetail->shipdata->BHT * $itemdetail->tax_rate)/100;
                                                        }
                                                    }
                                                    echo number_format($totaltax,2,".",",");
                                                }
                                            @endphp
                                        </td>
                                        
                                        <td><a href="{{ url('/po-datas/manualProcess/' . $item->id) }}" title="View PoData"><button class="btn btn-info btn-sm" style="font-size:9px;">{{ $item->status }}</button></a>
                                           </td>
                                           <td>
                                               @php
                                                   $flagcanclosed = true;
                                               @endphp
                                               @if ($item->fileupload->count() > 0)
                                                  <a href="{{ url($item->fileupload[0]->serverpath) }}" title="View PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-upload" aria-hidden="true"></i></button></a>
                                                
                                               @else
                                                   <a href="#" title="View PoData"><button class="btn btn-info btn-sm"><i class="fa fa-upload" aria-hidden="true"></i></button></a>
                                                    @php
                                                   $flagcanclosed = false;
                                               @endphp
                                               @endif
                                               @if ($item->print_status == '')
                                               @php
                                                   $flagcanclosed = false;
                                               @endphp
                                               <a href="{{ url('/po-datas/changestatus/' . $item->id) }}" title="View PoData"><button class="btn btn-info btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                                               @else
                                               <a href="{{ url('/po-datas/changestatus/' . $item->id) }}" title="View PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                                               @endif
                                        </td>
                                        <td>
                                            @if ($flagcanclosed && $item->main_status != 'Complete')
                                                <a href="{{ url('/po-datas/changemainstatus/' . $item->id .'/Complete') }}" title="View PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-check-circle" aria-hidden="true"></i></button></a>
                                            @else
                                               {{ $item->main_status }} 
                                            @endif
                                            
                                        </td>
                                        <td>
                                            
                                            <a href="{{ url('/po-datas/' . $item->id) }}" title="View PoData"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/po-datas/' . $item->id . '/edit') }}" title="Edit PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                            <form method="POST" action="{{ url('/po-datas' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete PoData" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $podatas->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
