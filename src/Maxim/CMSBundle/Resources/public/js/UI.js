UI = {
    constants: {
        'regexp_email':         new RegExp('\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b'),
        'regexp_whitespace':    new RegExp('[ \t\r\n]'),
        'regexp_creditcard':    new RegExp('^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$'),
        'regexp_phone':         new RegExp(/[0-9-()+]{3,20}/),
        'regexp_url':           new RegExp('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'),
        'regexp_image':         new RegExp(/([^\s]+(?=\.(jpg|gif|png))\.\2)/gm)
    },
    preload: function()
    {
        var imgs = $('img.preload');
        $.each(imgs, function(){
            var img = $(this);
            img.load(function(){
                img.css('background', 'none');
            });

            if(img[0].complete) {
                img.trigger('load');
                console.log('hi');
            }
        });
    },
	editor: function(control, width, height, content, source)
	{


		try {
			width 	= typeof width !== 'undefined' ? width : 550;
			height 	= typeof height !== 'undefined' ? height : 400;
			content = typeof content !== 'undefined' ? content : null;
            source = typeof source !== 'undefined' ? source : false;
            var path;
			CKEDITOR.basePath = "/bundles/maximcms/plugins/ckeditor/";

            if(source == true)
            {
                path = '/bundles/maximcms/plugins/ckeditor/config_source.js';
            }
            else
            {
                path = '/bundles/maximcms/plugins/ckeditor/config.js';
            }

			CKEDITOR.replace(control,
            {
                customConfig: path

            });

            return CKEDITOR;
		} catch(e) {
			console.log('CKEDITOR has not been initiated');
		}
	},
	validate: function(control)
    {
        var elements = $(control).find('input[type=text], input[type=password], select, textarea, input[type=checkbox]');
        var options = [];
        var errors  = [];
        var options_extra = [];
        var valid   = true;

        $(elements).each(function(index, element){
            //GET OPTIONS
            if($(this).attr("data-validate-options") !== undefined)
            {
                options = $(this).attr("data-validate-options").split("|");
                $.each(options, function(i, value){
                    var val = value;
                    //first of all, check if it contains a '=', if yes split again
                    options_extra = value.split('=');
                    if(options_extra.length >= 2){
                        //contains extra attribute
                        val = options_extra[0];
                    }
                    switch(val){
                        case "required":
                            if($(element).val().length == 0){
                                //check if there is a custom error message
                                if($(element).attr("data-validate-required") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-required");
                                }
                                else
                                {
                                    errors[index] = "Field " + $(element).attr("placeholder") + " is required";
                                }
                                valid = false;
                            }
                            break;
                        case "number":
                            if(!($.isNumeric($(element).val())))
                            {
                                if($(element).attr("data-validate-number") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-number");
                                }
                                else
                                {
                                    errors[index] = "Field " + $(element).attr("placeholder") + " must be numeric";
                                }
                                valid = false;
                            }
                            break;
                        case "email":
                            if(UI.constants.regexp_email.test($(element).val())){
                                //check if there is a custom error message
                                if($(element).attr("data-validate-email") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-email");
                                }
                                else
                                {
                                    errors[index] = "Field " + $(element).attr("placeholder") + " does not contain a valid email";
                                }
                                valid = false;
                            }
                            break;
                        case "whitespace":
                            if(UI.constants.regexp_whitespace.test($(element).val())){
                                //check if there is a custom error message
                                if($(element).attr("data-validate-whitespace") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-whitespace");
                                }
                                else
                                {
                                    errors[index] = "Field " + $(element).attr("placeholder") + " can not contain whitespaces";
                                }
                                valid = false;
                            }
                            break;
                        case "length":
                            if($(element).val().length < options_extra[1]){
                                //check if there is a custom error message
                                if($(element).attr("data-validate-length") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-length");
                                }
                                else
                                {
                                    errors[index] = "Field " + $(element).attr("placeholder") + " should contain " + options_extra[1] + " characters";
                                }
                                valid = false;
                            }
                            break;
                        case "regexp":
                            if(!options_extra[1].test($(element).val())){
                                //check if there is a custom error message
                                if($(element).attr("data-validate-length") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-length");
                                }
                                else
                                {
                                    errors[index] = "Field " + $(element).attr("placeholder") + " is not valid";
                                }
                                valid = false;
                            }
                            break;
                        case "selectable":
                            if($(element).val() == "")
                            {
                                //check if there is a custom error message
                                if($(element).attr("data-validate-selectable") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-selectable");
                                }
                                else
                                {
                                    errors[index] = "Field " + $(element).find("option:first-child").text() + " is required";
                                }
                                valid = false;
                            }
                            break;
                        case "checked":
                            if(!$(element).is(':checked')){

                                //check if there is a custom error message
                                if($(element).attr("data-validate-checked") !== undefined)
                                {
                                    errors[index] = $(element).attr("data-validate-checked");
                                }
                                else
                                {
                                    errors[index] = "You must accept our terms first.";
                                }
                                valid = false;
                            }
                            break;
                    }
                });
            }
        });
        if(valid)
        {
            return true;
        }
        else
        {
            return errors;
        }
    },
    validateDate: function(y, m, d) {
        var date = new Date(y,m-1,d);
        var convertedDate =
            ""+date.getFullYear() + (date.getMonth()+1) + date.getDate();
        var givenDate = "" + y + m + d;
        return ( givenDate == convertedDate);
    },
    parseErrorArray: function(errors)
    {
        var output = "";

        $.each(errors, function(index, value){
            if(!(typeof(value) === 'undefined')){
                output += value + '<br/>';
            }
        });

        return output;
    },
	popup: function(control, timeout)
	{
		setTimeout(function()
		{
			$(control).reveal({
		 		animation: 'fadeAndPop',                   //fade, fadeAndPop, none
				animationspeed: 300,                       //how fast animtions are
				closeonbackgroundclick: true,              //if you click background will modal close?
		    	dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
		 	});
		}, timeout);
	},
	
	popup: function(control, timeout, cookieName, cookieDay)
	{
		var setCookie = false;
		//Get cookie
		if($.cookie(cookieName) != null)
		{
			var cookieValue = $.cookie(cookieName);
			//Check when cookie has been made
			if(cookieValue <= ($.now() - (cookieDay * 24 * 3600)))
			{
				//SHOW POPUP AND CREATE COOKIE
				setCookie = true;
			}
		}
		else
		{
			setCookie = true;
		}
		
		if(setCookie == true)
		{
			setTimeout(function()
			{
				$(control).reveal({
			 		animation: 'fadeAndPop',                   //fade, fadeAndPop, none
					animationspeed: 300,                       //how fast animtions are
					closeonbackgroundclick: true,              //if you click background will modal close?
			    	dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
			 	});
			 	//Reset cookie
			 	$.cookie(cookieName, null);
			 	
			 	//set cookie
			 	$.cookie(cookieName, $.now(), { expires: cookieDay });
			 	console.log('cookie updated');
			}, timeout);
		}
	},
	
	slider: function()
	{
		$("#dvSlider").easySlider({
			auto: true,
			continuous: true 
		});
	},
	
	msDropdown: function(controls)
	{
		$.each(controls, function(index, value)
		{
			try {
				$(value).msDropDown();
			} catch(e) {
				console.log('msDropDown could not be loaded');
			}
		});
		
	},
	
	placeholder: function()
	{
		$("label.inlined + input.input-text").each(function(type) {
			if (!$(this).val() == "") {
				$(this).css('text-indent', '9999px');
				console.log($(this).val());
			}
			
			$(this).focus(function() {
				$(this).prev("label.inlined").addClass("focus");
				$(this).css('text-indent', '0px');
				$(this).val("");
			});
	
			$(this).keypress(function() {
				$(this).prev("label.inlined").addClass("has-text").removeClass("focus");
				$(this).css('text-indent', '0px');
			});
	
			$(this).blur(function() {
				if ($(this).val() == "") {
					$(this).prev("label.inlined").removeClass("has-text").removeClass("focus");
					$(this).val($(this).prev("label.inlined").text()).css('text-indent', '9999px');
				}
			});
		});
		$("label.inlined + textarea.input-text").each(function(type) {
			$(this).focus(function() {
				$(this).prev("label.inlined").addClass("focus");
			});
	
			$(this).keypress(function() {
				$(this).prev("label.inlined").addClass("has-text").removeClass("focus");
			});
	
			$(this).blur(function() {
				if ($(this).val() == "") {
					$(this).prev("label.inlined").removeClass("has-text").removeClass("focus");
				}
			});
		});
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
	}
}