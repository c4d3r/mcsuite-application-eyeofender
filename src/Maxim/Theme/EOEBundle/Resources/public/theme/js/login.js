$(document).ready(function() {
	//listen for the form beeing submitted
	$("#myForm").submit(function() {
		//get the url for the form
		AJAX.post(($("#myForm").attr("action")), {_username : $("#login_username").val(),_password : $("#login_password").val() }, $("#login-message"), $("#btnlogin"));
		//we dont what the browser to submit the form
		return false;
	});

    //listen for the form beeing submitted
    $("#frmLogin").submit(function() {
        //get the url for the form
        AJAX.post(($("#frmLogin").attr("action")), {_username : $("#frmLogin #login_username").val(),_password : $("#frmLogin #login_password").val() }, $("#login_statusbox"), $("#btnlogin"));
        //we dont what the browser to submit the form
        return false;
    });

    var temp = $('#register-minecraft');
    $('#register_minecraftservers').click(function(){
        if($(this).is(":checked"))
        {
            $(temp).slideDown();
        }
        else
        {
            if($(temp).is(":visible")){
                $(temp).slideUp();
            }
        }
    });
});