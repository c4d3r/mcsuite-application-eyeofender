$(document).ready(function() {
	$("#frmMinecraftAdd").submit(function() {
		//get the url for the form
		AJAX.post(
			$("#frmMinecraftAdd").attr("action"), 
			{
				_minecraft_name : $("#minecraft_name").val(),
				_minecraft_code : $("#minecraft_code").val()
			}, 
			$("#minecraftstatus"), 
			$("#btnAddAccount"));
		//we dont what the browser to submit the form
		return false;
	});
});