$(document).ready(function() {
	$(function() {
        $( "#register_dateOfBirth" ).datepicker({ dateFormat: 'yy-mm-dd', minDate: "-100Y", maxDate: "+0D",yearRange: "-100:+0", changeMonth: true, changeYear: true });
    });
	$("#frmRegister").submit(function() {
		//get the url for the form
		AJAX.post(
			$("#frmRegister").attr("action"),  
			{
				_register_username : $("#register_username").val(),
				_register_password : $("#register_password").val(),
				_register_email    : $("#register_email").val(),
				_register_password_confirm : $('#confirm_password').val(),
				_register_dateOfBirth : $('#register_dateOfBirth').val(),
				_register_location : $('#countries').val(),
				_register_skype : $('#register_skype').val()
			},
			$("#msgboxRegister"),  
			$("#btnRegister"));
		//we dont what the browser to submit the form
		return false;
	});
});
