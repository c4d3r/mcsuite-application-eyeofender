$(document).ready(function() {
	$("#frmShout").submit(function() {
		//get the url for the form
		AJAX.post(
			$("#frmShout").attr("action"), 
			{
				_shout_text : $("#shout_text").val()
			}, 
			$("#msgbox_shout"), 
			$("#btnShout"));
		//we dont what the browser to submit the form
		return false;
	});
});


