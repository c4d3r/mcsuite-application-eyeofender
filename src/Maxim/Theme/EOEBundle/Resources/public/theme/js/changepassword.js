$(document).ready(function() {
	$("#frmChangePass").submit(function() {
		//get the url for the form
		AJAX.post(
			$("#frmChangePass").attr("action"), 
			{
				_password_current : $("#currentPass").val(),
				_password : $("#newPass").val(),
				_password_confirm : $("#newPass2").val()
			}, 
			$("#msgbox_changepass"), 
			$("#btnChangepass"));
		//we dont what the browser to submit the form
		return false;
	});
});

