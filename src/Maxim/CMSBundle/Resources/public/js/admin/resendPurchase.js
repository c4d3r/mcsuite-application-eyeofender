$(function(){
    $('.admin-shop-resend').click(function(e){

        e.preventDefault();

        var _button = $(this);
        var purchaseid = _button.val();

        var _status = $('#admin-shop-purchase-' + purchaseid + '-status');
        var _delivery = $('#admin-shop-purchase-' + purchaseid + '-delivery');

        var _buttonOriginal = _button.clone();

        _button.text('Resending...').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: path_admin_shop_resend,
            data: { _purchaseid: purchaseid },
            dataType: "json",
            timeout: 15000,
            success: function(data) {

                console.log(data);

                if(data.hasOwnProperty('message')) {
                    _button.addClass("btn-danger");
                    _button.text(data.message);
                }  else {
                    _button.text('Delivered!').addClass('btn-success');
                }

                if(data.status != "COMPLETED") {
                    _button.addClass("btn-danger");
                    _status.addClass('bg-danger');
                    _button.text('Failed!');
                } else {
                    _button.addClass("btn-success");
                    _status.addClass('bg-success');
                }
                if(data.store_item_delivery != "DELIVERED") {
                    _delivery.addClass('bg-danger');
                    _button.text('Could not deliver item!');
                } else {
                    _delivery.addClass('bg-success');
                }

                _status.text(data.status);
                _delivery.text(data.store_item_delivery);

                setTimeout(function(){
                    _button.replaceWith(_buttonOriginal);
                }, 5000);
            },
            error: function(request, status, err) {
                console.log("error: " + err);
            }
        }, function(data){
            //to get the error
            console.log("error");
            console.log(data.responseText);
        });
    });
});