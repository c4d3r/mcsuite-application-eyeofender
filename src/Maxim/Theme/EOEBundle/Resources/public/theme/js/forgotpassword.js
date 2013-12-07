$(document).ready(function() {
	$("#frmForgotPassword").submit(function() {
		//get the url for the form
		AJAX.post(
			$("#frmForgotPassword").attr("action"), 
			{
				_email : $("#email").val()
			}, 
			$("#msgbox_forgotpass"), 
			$("#btnForgotPass"));
		//we dont what the browser to submit the form
		return false;
	});
});

