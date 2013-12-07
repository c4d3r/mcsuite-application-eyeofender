$(document).ready(function() {
	//listen for the form beeing submitted
	$("#myForm").submit(function() {
		//get the url for the form
		AJAX.post(($("#myForm").attr("action")), {_username : $("#login_username").val(),_password : $("#login_password").val()}, $("#msgbox"), $("#btnlogin"));
		//we dont what the browser to submit the form
		return false;
	});
});

$(document).ready(function() {
	//listen for the form beeing submitted
	$("#frmLogin").submit(function() {
		//get the url for the form
		AJAX.post(($("#frmLogin").attr("action")), {_username : $("#frmLogin #login_username").val(),_password : $("#frmLogin #login_password").val()}, $("#msgbox"), $("#btnlogin"));
		//we dont what the browser to submit the form
		return false;
	});
});
