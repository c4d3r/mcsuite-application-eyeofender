$(document).ready(function(){
    //add extra defaults
    var form    = $("#frmApplication");
    var labels  = new Array();
    var fields  = new Array();
    var error   = new Array();

    $(form).attr("action", form.attr("action"));
    $(form).attr("method", "post");

    //get label names and put them in an array
    form.find("label").each(function(index){
        labels[index] = $(this).text();
    });

    form.submit(function() {

        var validate = UI.validate($(form));

        if(validate == true)
        {
            $("#frmApplication input[type=text], #frmApplication input[type=password], #frmApplication input[type=checkbox], #frmApplication input[type=radio], #frmApplication textarea, #frmApplication select").each(function(index){
                fields[index] = $(this).val();
            });
            //get the url for the form
            AJAX.post((
                $("#frmApplication").attr("action")),
                {
                    _labels : labels.join(';'),
                    _fields : fields.join(';'),
                    _application_id   : $("#application_id").val()
                },
                $("#application-status"),
                $("#btnAppSubmit")
            );
            //we dont what the browser to submit the form
            return false;
        }
        else
        {
            var parse = UI.parseErrorArray(validate);
            $("#application-status").html(parse);
            $("#application-status").slideDown(300);
            return false;
        }


    });
})