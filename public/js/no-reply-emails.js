var noReplyEmails = function()
{

	var enableSummernote = function ()
	{
		$('#message').summernote({
			toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['bold', 'italic', 'underline', 'color']],
			    ['fontsize', ['fontsize']],
			    ['para', ['ul', 'ol', 'paragraph']],          
			    ['link', ['linkDialogShow', 'unlink']]          
			],
        	height: 500
		});

	}

	var getTemplateById = function ( template_id )
	{

        $.ajax({
          method: "GET",
          url: "/no_reply_emails/listTemplateById",
          data: { template_id: template_id }
        })
        .done(function( template ) {
            if( Object.keys(template).length > 1)
            {
                console.log(template);
        		$('#message').summernote('code', template.TemplateText);
            } 
        });
	}

	var form_validate = function()
	{
	    $("#nre_form").validate({ 
	        submitHandler: function(form, event) 
	        {
	        	event.preventDefault();
	        	form.submit();
	        }
	    });
	};
 
	var onChangeTemplate = function()
	{
		$('#template_id').on("change",function() {
	        var template_id = $( this ).val();
	        if( template_id != '')
	        {
	        	getTemplateById(template_id);	        	
	        }
	    });
	}

    return {
        //main function to initiate the module
        init: function () 
        {
        	enableSummernote();
        	onChangeTemplate();
        	form_validate();
        }

    }
}();


jQuery(document).ready(function() 
{
    noReplyEmails.init();
});