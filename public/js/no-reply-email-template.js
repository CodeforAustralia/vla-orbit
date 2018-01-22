var noReplyEmailTemplate = function()
{

	var enableSummernote = function ()
	{
		$('#template').summernote({
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

	var form_validate = function()
	{
	    $("#nre_template_form").validate({       
	       
	        ignore:":not(:visible)",

	        submitHandler: function(form, event) 
	        {
	          event.preventDefault();	          
	          form.submit();
	        }
	    });
	};

    return {
        //main function to initiate the module
        init: function () 
        {
        	enableSummernote();
        	form_validate();
        }

    }
}();


jQuery(document).ready(function() 
{
    noReplyEmailTemplate.init();
});