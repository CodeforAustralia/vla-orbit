var noReplyEmailTemplate = function()
{
	var borderTable = function (context) 
	{
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button(
	  {
	    contents: '<i class="fa fa-table"/>',
	    tooltip: 'Border',
	    click: function (event)
	    {	
	    	event.preventDefault();
			selection = window.getSelection();
			if (selection.rangeCount && selection.getRangeAt)
			{
				range = selection.getRangeAt(0);
			}
			if (range) 
			{
				
				var node = $(range.commonAncestorContainer)			
				if (node.parent().is('p.textBorder'))
				{
					node.unwrap();
				}
				else
				{	

				    node = document.createElement('p');
				    node.className='textBorder';
				    node.style.cssText='border:2px solid #000409;padding:2%'
				    node.appendChild(range.extractContents());				    				    
				    context.invoke('editor.pasteHTML', node);
				}				
				
			}
	     }
	  });

	  return button.render();   // return button as jquery object
	}
	var background = function (context) 
	{
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button(
	  {
	    contents: '<i class="fa fa-square"/>',
	    tooltip: 'Text Boxed',
	    click: function ()
	    {	
			selection = window.getSelection();
			if (selection.rangeCount && selection.getRangeAt)
			{
				range = selection.getRangeAt(0);
			}
			if (range) 
			{	
				var node = $(range.commonAncestorContainer)
				if (node.parent().is('p.textBoxed'))
				{
					node.unwrap();
				}
				else
				{
				    node = document.createElement('p');
				    node.className='textBoxed';
				    node.style.cssText='background-color: #eef3e9;padding: 15px 20px 10px 20px'
				    node.appendChild(range.extractContents());
				    context.invoke('editor.pasteHTML', node);			    
				}
			}
	     }
	  });

	  return button.render();   // return button as jquery object
	}

	var enableSummernote = function ()
	{
		$('#template').summernote({
			toolbar: [
			    ['style', ['style','bold','italic']],
				['customButton', ['borderTable','background']],
			    ['fontsize', ['fontname','fontsize']],
    			['color', ['color']],
			    ['para', ['ul']], 
			    ['link', ['linkDialogShow', 'unlink']],
			    ['misc',['undo','redo', 'codeview']],    			
			],
			buttons:{
				borderTable: borderTable,
				background: background
			},
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

