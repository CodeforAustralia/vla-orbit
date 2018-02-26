var noReplyEmailTemplate = function()
{
	var enableSummernote = function ()
	{
		$('#template').summernote({
		    addclass: {
		        debug: false,
		        classTags: ["table-bordered", "text-primary", "text-warning", "text-danger", "text-success", "alert alert-success", "alert alert-info", "alert alert-warning", "alert alert-danger"]
		    },			
			toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['style','addclass','bold']],			    
			    ['fontsize', ['fontname','fontsize']],
    			['color', ['color']],
			    ['para', ['ul']],          
			    ['link', ['linkDialogShow', 'unlink']],
    			//['height', ['height']]
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