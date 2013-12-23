$(document).ready(function() {

    var form = $( '#frmRegister' );
    form.parsley({ successClass: 'validation-form-valid', errorClass: 'validation-form-invalid' });
    form.parsley('validate');

    form.submit(function() {
        var form = $( '#frmRegister' );

        //Validations
        var register_dob_day = $('#register_dob_day');
        var register_dob_month = $('#register_dob_month');
        var register_dob_year = $('#register_dob_year');

        register_dob_day.removeClass('validation-form-invalid');
        register_dob_month.removeClass('validation-form-invalid');
        register_dob_year.removeClass('validation-form-invalid');

        //validate date
        if(!UI.validateDate(register_dob_year.val(), register_dob_month.val(), register_dob_day.val())) {
            register_dob_day.addClass('validation-form-invalid');
            register_dob_month.addClass('validation-form-invalid');
            register_dob_year.addClass('validation-form-invalid');
            return false;
        }

        if(form.parsley( 'isValid' )) {
            //get the url for the form
            AJAX.post(
                form.attr("action"),
                {
                    _register_username : $("#register_username").val(),
                    _register_password : $("#register_password").val(),
                    _register_email    : $("#register_email").val(),
                    _register_password_confirm : $('#confirm_password').val(),
                    _register_dob_day : register_dob_day.val(),
                    _register_dob_month : register_dob_month.val(),
                    _register_dob_year : register_dob_year.val(),
                    _minecraft: $('#register_minecraftservers').is(":checked"),
                    _mcpass: $('#register_minecraftpass').val(),
                    _mcuser: $('#register_minecraftuser').val()
                },
                $("#msgboxRegister"),
                $("#btnRegister"));
            //we dont what the browser to submit the form
        }
		return false;
	});
});
