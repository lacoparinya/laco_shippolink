@extends('layouts.apptrans')

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
                        <form method="POST" action="{{ url('/uploadtrans/editAction/'.$banktransm->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <strong>File : </strong>{{ $banktransm->filename }}
                            </div>
                            <div class="col-md-3">
                                <strong>วันที่โอน : </strong>
                                <input class="form-control" type="date" name="trans_date"  id="trans_date" value="{{ $banktransm->trans_date }}">                                
                            </div>
                            <div class="col-md-3">
                                <strong>ยอด USD : </strong>
                                <input class="form-control" name="total_usd"  id="total_usd" value="{{ $banktransm->total_usd }}">                                
                            </div>
                            <div class="col-md-3">
                                <strong>Note : </strong>
                                <input class="form-control" name="note"  id="note" value="{{ $banktransm->note }}">                                
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            
                                <input type="hidden"  name="totaltran"  id="totaltran" value="{{ $banktransm->total_usd }}">
                                          
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Inv No.</th>
                                        <th>ยอดรวม C&F USD</th>
                                        <th>ยอดจ่ายมา USD</th>
                                        <th>ยอดจ่ายมา (%)</th>
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
                                                {{ round($item2->podata->candf,2)}}
                                            @else
                                                0
                                            @endif
                                            
                                        
                                        </td>
                                        <td>
                                        <input class="form-control incusd" name="income_usd-{{$item2->id}}"  id="income_usd-{{$item2->id}}" value="{{ round($item2->income_usd,2) }}" data-index="{{$item2->id}}">
                                        </td>
                                        <td>
                                            @if (isset($item2->podata->candf))
                                                <input class="form-control perusd" name="percent_usd-{{$item2->id}}"  id="percent_usd-{{$item2->id}}" value="{{ round($item2->income_usd*100/$item2->podata->candf,2) }}"  data-index="{{$item2->id}}">
                                                <input type="hidden"  name="cnf-{{$item2->id}}"  id="cnf-{{$item2->id}}" value="{{$item2->podata->candf}}">
                                            @else
                                                
                                            @endif
                                           
                                             </td>
                                    </tr> 
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td>Total</td>
                                        <td><span id="usdtotal">{{  $usd_total }}</span></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>ค้าง</td>
                                        <td><span id="difftotal">{{  $banktransm->total_usd - $usd_total }}</span></td>
                                        <td><input name='btnsave' id='btnsave' class="btn btn-primary" type="submit" value="Update" >
                               </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
