@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">ใบโอนเงิน {{ $banktransm->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/uploadtrans/index') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/uploadtrans/edit/' . $banktransm->id) }}" title="Edit SapDataCf"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('sapdatacfs' . '/' . $banktransm->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete SapDataCf" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/><br/>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>File : </strong>{{ $banktransm->filename }}
                            </div>
                            <div class="col-md-3">
                                <strong>ยอด USD : </strong>{{ $banktransm->total_usd }}
                            </div>
                            <div class="col-md-3">
                                <strong>ยอด BHT : </strong>{{ $banktransm->total_bht }}
                            </div>
                            <div class="col-md-3">
                                <strong>Rate : </strong>{{ $banktransm->exchange_rate }}
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Inv No.</th>
                                        <th>ยอดรวม C&F USD</th>
                                        <th>ยอดจ่ายมา USD (%)</th>
                                        <th>ยอดค้าง USD (%)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $usd_total = 0;
                                        
                                    @endphp
                                    @foreach ($banktransm->banktransd()->get() as $item2)
                                    @php
                                        $usd_total += $item2->income_usd;
                                        
                                    @endphp
                                        <tr>
                                        <td>{{  $item2->podata->inv_name or $item2->other_case }}</td>
                                        <td>
                                            @if (isset($item2->podata->candf))
                                                {{  round($item2->podata->candf,2) }}
                                            @else
                                                -
                                            @endif
                                            </td>
                                        <td>
                                            @if (isset($item2->podata->candf)) 
                                                {{  round($item2->income_usd,2) }} ({{  round($item2->income_usd*100/$item2->podata->candf,2) }} )
                                            @else
                                                                                                {{  $item2->income_usd }} 
                                            @endif
                                            
                                             
                                        
                                        </td>
                                        <td>
                                             @if (isset($item2->podata->candf)) 
                                             {{  round($item2->podata->candf - $item2->income_usd,2) }} ({{  round(($item2->podata->candf - $item2->income_usd)*100/$item2->podata->candf,2) }} )
                                             @else
                                              0
                                            @endif
                                             </td>
                                             <td>
                                                  <a href="{{ url('/uploadtrans/removeinv/' . $item2->id) }}" title="Edit SapDataCf"><button class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                             </td>
                                    </tr> 
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td>Total</td>
                                        <td>{{  $usd_total }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>ค้าง</td>
                                        <td>{{  $banktransm->total_usd - $usd_total }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
