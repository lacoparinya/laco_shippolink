@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header"><h3>Blue Conner Datas | Status : {{ $status or 'ALL' }}</h3></div>
                    <div class="card-body">
                        <a href="{{ url('/imports/podata') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload Blue Conner Datas
                        </a>
                        <a href="{{ url('/file-uploads/create') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload ใบขน
                        </a>
                        <a href="{{ url('/imports/shipdata') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload รายการพร้อมเลขใบขน 
                        </a>
                        <a href="{{ url('/uploadtrans/create') }}" class="btn btn-success btn-sm" title="Add New ShipData">
                            <i class="fa fa-upload" aria-hidden="true"></i> Upload ใบโอนเงิน
                        </a>


                        <a href="{{ url('/imports/AllProcess') }}" class="btn btn-success btn-sm" title="Add New PoData">
                            <i class="fa fa-microchip" aria-hidden="true"></i> Match  ใบขน
                        </a>
                        <a href="{{ url('/imports/AllProcessCf') }}" class="btn btn-success btn-sm" title="Add New PoData">
                            <i class="fa fa-microchip" aria-hidden="true"></i> Match  C & F
                        </a>
                        <a href="{{ url('/po-datas/export/all') }}" class="btn btn-success btn-sm" title="Export">
                            <i class="fa fa-table" aria-hidden="true"></i> Export
                        </a>
                        <form method="GET" action="{{ url('/po-datas') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="INV No..." value="{{ request('search') }}">
                                <input type="text" class="form-control" name="search2" placeholder="SO No..." value="{{ request('search2') }}">
                                
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <a href="{{ url('/po-datas') }}" class="btn btn-primary btn-sm" title="ALL">
                            <i class="fa fa-globe" aria-hidden="true"></i> ALL
                        </a>
                        <a href="{{ url('/po-datas?status=Process') }}" class="btn btn-primary btn-sm" title="Status Process">
                            <i class="fa fa-play" aria-hidden="true"></i> Process
                        </a>
                        <a href="{{ url('/po-datas?status=Complete') }}" class="btn btn-primary btn-sm" title="Status Complete">
                            <i class="fa fa-check-square" aria-hidden="true"></i> Complete
                        </a>
                        <a href="{{ url('/po-datas?status=reject') }}" class="btn btn-primary btn-sm" title="Status Reject">
                            <i class="fa fa-chain-broken" aria-hidden="true"></i> Reject
                        </a>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CSN / Order Name / Sale Order / Invoice</th>
                                        <th>Loading Date<br/>Follow up date</th>
                                        <th>จำนวนสินค้า</th>
                                        <th>Tax Return</th>
                                        <th>Link</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($podatas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><a href="{{ url('/po-datas/' . $item->id) }}" title="View PoData">{{ $item->CSN }} / {{ $item->order_name }} <br/>/ {{ $item->sale_order_name }} / {{ $item->inv_name }}</a></td>
                                        @if ( strtotime('+14 day', strtotime($item->loading_date)) <= strtotime(date('Y-m-d')))
                                          <td style="background-color:red;color:white;text-align:center;">{{ $item->loading_date }}<br/>{{ date('Y-m-d', strtotime('+14 day', strtotime($item->loading_date))) }}</td>
                                          
                                        @else
                                          <td style="text-align:center;">{{ $item->loading_date }}<br/>{{ date('Y-m-d', strtotime('+14 day', strtotime($item->loading_date))) }}</td>
                                          
                                        @endif
                                        <td style="text-align:center;">{{ $item->podatadetails->count() }}</td>
                                        <td>
                                            @php
                                              //  if($item->status == 'Match  Trans / C & F'){
                                                    $totaltax = 0;
                                                    foreach ($item->podatadetails as $itemdetail) {
                                                        if(isset($itemdetail->shipdata->BHT) && !empty($itemdetail->ship_data_id)){
                                                            $totaltax += ($itemdetail->shipdata->BHT * $itemdetail->tax_rate)/100;
                                                        }
                                                    }
                                                    echo number_format($totaltax,2,".",",");
                                             //   }
                                            @endphp
                                        </td>
                                        
                                        <td>
                                            @if ($item->status_trans == 'Yes')
                                                <button class="btn btn-success btn-sm" title="Match  ใบขนแล้ว" ><i class="fa fa-truck" aria-hidden="true"></i></button>
                                            @else
                                                <button class="btn btn-light btn-sm" title="ยังไม่ได้ Match  ใบขน"><i class="fa fa-truck" aria-hidden="true"></i></button>
                                            @endif
                                            @if ($item->status_cnf == 'Yes')
                                                <button class="btn btn-success btn-sm" title="Match  C & F แล้ว" ><i class="fa fa-usd" aria-hidden="true"></i></button>
                                            @else
                                                <button class="btn btn-light btn-sm" title="ยังไม่ได้ Match  C & F" ><i class="fa fa-usd" aria-hidden="true"></i></button>
                                            @endif
                                               @php
                                                   $flagcanclosed = true;
                                               @endphp
                                               @if ($item->fileupload->count() > 0)
                                                  <a href="{{ url($item->fileupload[0]->serverpath) }}" title="Upload ใบขนแล้ว"><button class="btn btn-success btn-sm"><i class="fa fa-upload" aria-hidden="true"></i></button></a>
                                               @else
                                                   <a href="{{ url('/file-uploads/create') }}" title="ยังไม่ได้ Upload ใบขน"><button class="btn btn-light btn-sm" ><i class="fa fa-upload" aria-hidden="true"></i></button></a>
                                                    @php
                                                   $flagcanclosed = false;
                                               @endphp
                                               @endif
                                               @if ($item->print_status == '')
                                               @php
                                                   $flagcanclosed = false;
                                               @endphp
                                               <a href="{{ url('/po-datas/changestatus/' . $item->id) }}" title="ยังไม่ได้ Print เอกสาร"><button class="btn btn-light btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                                               @else
                                               <a href="{{ url('/po-datas/changestatus/' . $item->id) }}" title="Print เอกสารแล้ว"><button class="btn btn-success btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button></a>
                                               @endif

                                               @if ($item->banktransd->count() > 0)
                                                   @php
                                                       $totalusd = 0;
                                                   @endphp
                                                    @foreach ($item->banktransd()->get() as $item2)
                                                    @php
                                                        $totalusd += $item2->income_usd;
                                                    @endphp
                                                   @endforeach
                                                   @foreach ($item->banktransd()->get() as $item2)
                                                        @if (($item->candf - $totalusd) > 0 )
                                                            <a href="{{ url('/uploadtrans/view/' . $item2->bank_trans_m_id) }}" title="โอนเงิน ยังไม่ครบ"><button class="btn btn-light btn-sm"><i class="fa fa-money" aria-hidden="true"></i></button></a>
                                                        @else
                                                            <a href="{{ url('/uploadtrans/view/' . $item2->bank_trans_m_id) }}" title="โอนเงิน ครบแล้ว"><button class="btn btn-success btn-sm"><i class="fa fa-money" aria-hidden="true"></i></button></a>
                                                        @endif
                                                        @if (!empty($item2->banktransm->processpath))
                                                            <a href="{{ url('/uploadtrans/genpdf/'.$item2->bank_trans_m_id) }}" title="พิมพ์ใบโอนเงิน"><button class="btn btn-success btn-sm"><i class="fa fa-credit-card" aria-hidden="true"></i></button></a>
                                                        @else
                                                            @if (!empty($item2->banktransm->serverpath))
                                                                <a href="{{ url('/'.$item2->banktransm->serverpath) }}" title="พิมพ์ใบโอนเงิน"><button class="btn btn-success btn-sm"><i class="fa fa-credit-card" aria-hidden="true"></i></button></a>
                                                            @endif
                                                        @endif
                                                   
                                                   @endforeach
                                               @else
                                                   
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
                                            
                                            <a href="{{ url('/po-datas/' . $item->id . '/edit') }}" title="Edit PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                            <form method="POST" action="{{ url('/po-datas' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete PoData" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form>
                                            @if ($item->main_status == 'reject')
                                                <a href="{{ url('/po-datas/changemainstatus/' . $item->id .'/Process') }}" title="View PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-link" aria-hidden="true"></i></button></a>
                                            @else
                                                <a href="{{ url('/po-datas/changemainstatus/' . $item->id .'/reject') }}" title="View PoData"><button class="btn btn-primary btn-sm"><i class="fa fa-chain-broken" aria-hidden="true"></i></button></a>
                                            @endif

                                             
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                             <div class="pagination-wrapper"> {!! $podatas->appends(['search' => Request::get('search'),'search2' => Request::get('search2'),'status' => Request::get('status')])->render() !!} </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
