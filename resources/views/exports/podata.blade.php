<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PODATA</title>
    <style>
    tr th {
        border: 1px solid #000000;
        word-wrap: normal;
    }
    tr th.noborder {
        border: none;
        word-wrap: normal;
    }
     tr th.noborder-last {
        border: none;
        word-wrap: normal;
    }
    tr th.noborderr {
        border: none;
        text-align: right;
        word-wrap: break-word;
    }
    tr th.noborderc {
        border: none;
        text-align: center;
        word-wrap: break-word;
        font: bolder;
    }
    tr td {
        border: 1px solid #000000;
        word-wrap: normal;
    }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>LOAD Date</th>
                <th>CSN</th>
                <th>Order Name</th>
                <th>Sale Order No.</th>
                <th>Inv No</th>
                <th>Bill No.</th>
                <th>เลขใบขน</th>
                <th>Ref Ship No</th>
                <th>C & F</th>
                <th>สถานะใบขน</th>
                <th>สถานะ C & F</th>
                <th>สถานะ Print</th>
                <th>รายละเอียด</th>
                <th>STATUS รวม</th>
                <th>ภาษีรวม</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                    <tr>
                        <td>{{ $item->loading_date }}</td>
                        <td>{{ $item->CSN }}</td>
                        <td>{{ $item->order_name }}</td>
                        <td>{{ $item->sale_order_name }}</td>
                        <td>{{ $item->inv_name }}</td>
                        <td>{{ $item->billing_name }}</td>
                        <td>{{ $item->trans_name }}</td>
                        <td>{{ $item->ref_ship_name }}</td>
                        <td>{{ $item->candf }}</td>
                        <td>{{ $item->status_trans }}</td>
                        <td>{{ $item->status_cnf }}</td>
                        <td>{{ $item->print_status }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->main_status }}</td>
                        <td>
                            @php
                                //  if($item->status == 'Match  Trans / C & F'){
                                    $totaltax = 0;

                                    //if($item->status != 'reject'){
                                        foreach ($item->podatadetails as $itemdetail) {
                                            if(isset($itemdetail->shipdata->BHT) && !empty($itemdetail->ship_data_id)){
                                                $totaltax += ($itemdetail->shipdata->BHT * $itemdetail->tax_rate)/100;
                                            }
                                        }
                                    //}
                                    echo number_format($totaltax,2,".",",");


                                //   }
                            @endphp

                        </td>

                        <td>{{ $item->note }}</td>
                    </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>