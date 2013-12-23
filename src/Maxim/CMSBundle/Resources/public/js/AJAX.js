AJAX = {
    post : function(url, parameters, divStatus, button) {

        button.prop('disabled', true);
        $(divStatus).removeClass().addClass('alert alert-block').html('Validating....').fadeIn(1000);
        $.ajax({
            type: "POST",
            url: url,
            data: parameters,
            dataType: "json",
            timeout: 15000,
            success: function(data) {
                if(typeof data !== 'object'){ data = jQuery.parseJSON(data); }
                if (data.success == true) {
                    $(divStatus).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        if(data.redirect != null){
                            document.location = data.redirect;
                        }
                        else{
                            //add message and change the class of the box and start fading
                            $(divStatus).removeClass().addClass('alert alert-success').html(data.message).fadeTo(900, 1, function() {
                                //redirect to secure page
                                document.location = document.location.href;
                            });
                        }
                    });
                } else {
                    $(divStatus).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        $(button).removeAttr("disabled").css("display", "block");
                        $(divStatus).removeClass().addClass('alert alert-error').fadeTo(900, 1).html(data.message);
                    });
                }
            },
            error: function(request, status, err) {
                if(status == "timeout") {
                    $(divStatus).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        $(button).removeAttr("disabled").css("display", "block");
                        $(divStatus).removeClass().addClass('alert alert-error').fadeTo(900, 1).html("Timeout while executing, please try again later.");
                    });
                } else {
                    $(divStatus).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        console.log("error");
                        console.log(request.responseText);
                        console.log(status.responseText);
                        console.log(err.responseText);
                        $(button).removeAttr("disabled").css("display", "block");
                        $(divStatus).removeClass().addClass('alert alert-error').fadeTo(900, 1).html("This function is unavailable right now, please try again later.");
                    });
                }
            }
        }, function(data){
            //to get the error
            console.log("error");
            console.log(data.responseText);
        });
        button.removeAttr('disabled');
        return false;
    },
    post_text : function(url, element, text, parameters) {
        var originalText = element.text();

        element.text(text[0]);

        $.ajax({
            type: "POST",
            url: url,
            data: parameters,
            dataType: "json",
            timeout: 15000,
            success: function(data) {
                if(typeof data !== 'object'){ data = jQuery.parseJSON(data); }
                if (data.success == true) {
                    element.text(text[1]);
                    if(data.redirect != null){
                        if(data.redirect == true) {
                            //redirect to secure page
                            document.location = document.location.href;
                        }
                        document.location = data.redirect;
                    }
                } else {
                    element.text(text[2]);
                    console.log(data.message);
                }
            },
            error: function(request, status, err) {
                element.text(text[2]);
                if(status == "timeout") {
                    console.log("Timeout while executing, please try again later.");

                } else {
                    console.log("error");
                    console.log(request.responseText);
                    console.log(status.responseText);
                    console.log(err.responseText);
                }
            }
        }, function(data){
            //to get the error
            element.text(text[2]);
            console.log("error");
            console.log(data.responseText);
        });
        return false;
    },
    post_regular : function(url, parameters, callback) {

        var callbackData;

        $.ajax({
            type: "POST",
            url: url,
            data: parameters,
            dataType: "json",
            timeout: 15000,
            success: function(data) {
                if(typeof data !== 'object'){ data = jQuery.parseJSON(data); }
                if (data.success == true) {
                    if(data.redirect != null){
                        if(data.redirect == true) {
                            //redirect to secure page
                            document.location = document.location.href;
                        }
                        document.location = data.redirect;
                    }
                } else {
                    console.log(data.message);
                }
                callbackData = data;
                if(typeof callback !== 'undefined') {
                    callback(callbackData);
                }
                return data;
            },
            error: function(request, status, err) {
                if(status == "timeout") {
                   console.log("Timeout while executing, please try again later.");

                } else {
                    console.log("error");
                    console.log(request.responseText);
                    console.log(status.responseText);
                    console.log(err.responseText);
                }
                callbackData = { success: false, message: "Timeout while executing, please try again later."};
                if(typeof callback !== 'undefined') {
                    callback(callbackData);
                }
                return false;
            }
        }, function(data){
            //to get the error
            console.log("error");
            console.log(data.responseText);
            callbackData = { success: false, message: "An error occured, please try again later."};
            if(typeof callback !== 'undefined') {
                callback(callbackData);
            }
            return false;
        });


    },
	post_noButton : function(url, parameters, status) {
        $(status).removeClass().addClass('alert alert-block').html('Validating....').fadeIn(1000);
        $.ajax({
            type: "POST",
            url: url,
            data: parameters,
            dataType: "json",
            timeout: 15000,
            success: function(data) {
                if(typeof data !== 'object'){ data = jQuery.parseJSON(data); }
                if (data.success == true) {
                    $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        if(data.redirect != null){
                            document.location = data.redirect;
                        }
                        else{
                            //add message and change the class of the box and start fading
                            $(status).removeClass().addClass('alert alert-success').html(data.message).fadeTo(900, 1, function() {
                                //redirect to secure page
                                document.location = document.location.href;
                            });
                        }
                    });
                } else {
                    $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        $(status).removeClass().addClass('alert alert-error').fadeTo(900, 1).html(data.message);
                        return false;
                    });
                }
            },
            error: function(request, status, err) {
                if(status == "timeout") {
                    $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        $(status).removeClass().addClass('alert alert-error').fadeTo(900, 1).html("Timeout while executing, please try again later.");
                    });
                } else {
                    $(status).fadeTo(200, 0.1, function()//start fading the messagebox
                    {
                        console.log("error");
                        console.log(request.responseText);
                        console.log(status.responseText);
                        console.log(err.responseText);
                        $(status).removeClass().addClass('alert alert-error').fadeTo(900, 1).html("This function is unavailable right now, please try again later.");
                    });
                }
            }
        }, function(data){
            //to get the error
            console.log("error");
            console.log(data.responseText);
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
                return false;
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
	notify : function(control, status) {
        if(status.success == true) {
            control.removeClass().addClass('alert alert-success').html(status.message).fadeTo(2000, 0);
        } else {
            control.removeClass().addClass('alert alert-error').html(status.message).fadeTo(2000, 0);
        }
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
	},
    microtime : function(get_as_float) {
        var now = new Date().getTime() / 1000;
        var s = parseInt(now, 10);

        return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s;
    },
    redirect : function(path, element) {
        element.append(" seconds");
        setInterval(function(){
            if(parseInt(element.text()) == 0) {
                window.location.href = path;
            } else {
                element.text(parseInt(element.text()) - 1);
                element.append(" seconds");
            }
        }, 1000);
    }
}

// addMethod - By John Resig (MIT Licensed)
function addMethod(object, name, fn){
    var old = object[ name ];
    if ( old )
        object[ name ] = function(){
            if ( fn.length == arguments.length )
                return fn.apply( this, arguments );
            else if ( typeof old == 'function' )
                return old.apply( this, arguments );
        };
    else
        object[ name ] = fn;
}

