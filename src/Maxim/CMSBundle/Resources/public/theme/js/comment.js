//listen for the form beeing submitted
$(document).ready(function(){
	$("#frmComment").submit(function() {
		//get the url for the form
		AJAX.post((
			$("#frmComment").attr("action")), 
			{ 
				_news_id : $("#hdnNews").val(), 
				_news_message : $("#news_message").val()
			}, 
			$("#commentstatus"), 
			$("#btnComment")
			);
		//we dont what the browser to submit the form
		return false;
	});
})

