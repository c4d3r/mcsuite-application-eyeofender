AJAX = {
    post : function(url, parameters, status, button) {
        $(status).addClass('alert-block').html('Validating....').fadeIn(1000);
        $.post(url, parameters, function(data) {
            $(button).attr("disabled", "disabled");
            console.log(data);
            //data = jQuery.parseJSON(data);
            if(typeof data !== 'object'){ data = jQuery.parseJSON(data); }
            if (data.success == true) {
                $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                {
                    if(data.redirect != null){
                        document.location = data.redirect;
                    }
                    else{
                        //add message and change the class of the box and start fading
                        $(status).removeClass('alert-error').addClass('alert-success').html(data.message).fadeTo(900, 1, function() {
                            //redirect to secure page
                            location.reload();
                        });
                        setTimeout(function() { $(status).slideUp(1000) }, 3000);
                    }
                });
            } else {
                $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                {
                    $(button).removeAttr("disabled").css("display", "block");
                    $(status).removeClass('alert-success').addClass('alert-error').fadeTo(900, 1).html(data.message);
                });
            }
        });
        return false;
    },
	
	post_noButton : function(url, parameters, status) {
        $(status).removeClass().addClass('alert-block').css('display', 'block').html('Validating....').fadeIn(1000);
        $.post(url, parameters, function(data) {

            data = jQuery.parseJSON(data);
            console.log(data);

            if (data.success == true) {
                $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                {
                    //add message and change the class of the box and start fading
                    $(status).html(data.message).addClass('alert-success').fadeTo(900, 1, function() {
                        //redirect to secure page
                        location.reload();
                    });
                    setTimeout(function() { $(status).slideUp(1000) }, 3000);
                });
            } else {
                $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                {
                    $(status).html(data.message).addClass('alert-error').fadeTo(900, 1);
                });
            }

        });
        return false;
    },

    post_url : function(url) {

        var data = {
            "success": false,
            "message": "Could not execute action, please try again later."
        }

        $.post(url, function(data) {
            var received = jQuery.parseJSON(data);
            console.log(received);
            data.success = received.success;
            data.message = received.message;

            var success = received["success"].toString();
            success = success.toUpperCase();
            if(success == 'true') {
                return true;
            } else {
                alert(data.message);
            }
        });
    },

    post_url_datatable: function(url, nRow, oTable) {

        $.post(url, function(data) {
            var received = jQuery.parseJSON(data);

            var success = received["success"].toString();
            success = success.toUpperCase();
            if(success == 'TRUE') {
                oTable.fnDeleteRow( nRow );
            } else {
                alert(data.message);
            }
        });
    },
	
	load : function(url, contentBox)
	{
		$(contentBox).load(url);
	},
	
	show : function(id)
	{
		if(id.is(":visible"))
		{
			id.slideUp(300);
		}
		else
		{
			id.slideDown(300);
		}
	},
	
	show : function(id, show)
	{
		if(id.is(":visible"))
		{
			id.slideUp(300);
			show.fadeIn("slow");
		}
		else
		{
			id.fadeIn("slow");
			show.slideUp(300);
		}
	}, 
	
	loadImage : function(url, id)
	{
		
		// set up the node / element
		_im = $("<img>");

		// hide and bind to the load event
		_im.hide();
		_im.bind("load", function() {
			$(this).fadeIn("slow");
		});

		// append to target node / element
		$('#imagePortret').html(_im);

		// set the src attribute now, after insertion to the DOM
		_im.attr('src', url); 
	}
}


