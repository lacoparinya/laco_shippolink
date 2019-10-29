$(document).ready(function () {
    $('.incusd').change(function(){
        var usdtotal = 0;

        $('.incusd').each(function (i, obj) {
            id = $(obj).data('index');
            myval = parseFloat($(obj).val());
            console.log(id );
            usdtotal += parseFloat($(obj).val());

            cnf = parseFloat($('#cnf-'+id).val());
            perc = myval * 100 / cnf;
            $('#percent_usd-' + id).val(parseFloat(perc).toFixed(2));

        });

        $('#usdtotal').html(parseFloat(usdtotal).toFixed(2));

        diff = parseFloat($('#totaltran').val()) - usdtotal;

        $('#difftotal').html(parseFloat(diff).toFixed(2));
    });
    
    $('.perusd').change(function () {
        var usdtotal = 0;

        $('.perusd').each(function (i, obj) {
            id = $(obj).data('index');
            perc = parseFloat($(obj).val());
            cnf = parseFloat($('#cnf-' + id).val());

            myval = cnf * perc/100;
            usdtotal += myval;

            
           // perc = myval * 100 / cnf;
            $('#income_usd-' + id).val(parseFloat(myval).toFixed(2));

        });

        $('#usdtotal').html(parseFloat(usdtotal).toFixed(2));
        
        diff = parseFloat($('#totaltran').val()) - usdtotal;

        $('#difftotal').html(parseFloat(diff).toFixed(2));
    });
});