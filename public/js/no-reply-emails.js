var noReplyEmails = function()
{
	// BEGIN OF SUMMERNOTE CUSTOM BUTTONS
	var borderText = function (context) 
	{
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button(
	  {
	    contents: '<i class="fa fa-minus-square-o"/>',
	    tooltip: 'Border Text',
	    click: function (event)
	    {	
	    	event.preventDefault(); 
			selection = window.getSelection();
			if (selection.rangeCount && selection.getRangeAt)
			{
				range = selection.getRangeAt(0);
				var node = $(range.commonAncestorContainer);
				if (node.parents().is('div.note-editor')) 
				{	
					if (node.parents().is('p.textBorder'))
					{
						node.parent().is('p.textBorder') ? node.unwrap() : node.parentsUntil("p.textBorder").unwrap();
					}
					else
					{	
					    node = document.createElement('p');
					    node.className='textBorder';
					    node.style.cssText='border:2px solid #000409;padding:2%';
					    node.appendChild(range.extractContents());				    			    				    
					    context.invoke('editor.pasteHTML', node);
					}				
				}
			}
	     }
	  });

	  return button.render();   // return button as jquery object
	}
	var alertText = function (context) 
	{
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button(
	  {
	    contents: '<i class="fa fa-pencil-square-o"/>',
	    tooltip: 'Safety alert text',
	    click: function (event)
	    {	
	    	event.preventDefault(); 
			selection = window.getSelection();
			if (selection.rangeCount && selection.getRangeAt)
			{
				range = selection.getRangeAt(0);
				var node = $(range.commonAncestorContainer);
				if (node.parents().is('div.note-editor')) 
				{	
					if (node.parents().is('p.textBoxed'))
					{
						node.parent().is('p.textBoxed') ? node.unwrap() : node.parentsUntil("p.textBoxed").unwrap();
					}
					else
					{	
					    node = document.createElement('p');
					    node.className='textBoxed';
					    node.style.cssText='background-color: #eef3e9;padding: 15px 20px 10px 20px';
					    node.appendChild(range.extractContents());
					    context.invoke('editor.pasteHTML', node);
					}				
				}
			}
	     }
	  });

	  return button.render();   // return button as jquery object
	}
	var alertTextBold = function (context) 
	{
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button(
	  {
	    contents: '<i class="fa fa-pencil-square"/>',
	    tooltip: 'Safety alert text bold',
	    click: function (event)
	    {	
	    	event.preventDefault(); 
			selection = window.getSelection();
			if (selection.rangeCount && selection.getRangeAt)
			{
				range = selection.getRangeAt(0);
				var node = $(range.commonAncestorContainer);
				if (node.parents().is('div.note-editor')) 
				{	
					if (node.parents().is('p.textBoxedBold'))
					{
						node.parent().is('p.textBoxedBold') ? node.unwrap() : node.parentsUntil("p.textBoxedBold").unwrap();
					}
					else
					{	
					    node = document.createElement('p');
					    node.className='textBoxedBold';
					    node.style.cssText='background-color: #eef3e9;padding: 15px 20px 10px 20px;font-weight: bold';
					    node.appendChild(range.extractContents());
					    context.invoke('editor.pasteHTML', node);
					}				
				}
			}
	     }
	  });

	  return button.render();   // return button as jquery object
	}
	var headingAlert = function (context) 
	{
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button(
	  {
	    contents: '<i class="fa fa-exclamation-circle"/>',
	    tooltip: 'Safety alert heading',
	    click: function (event)
	    {	
	    	event.preventDefault(); 
			selection = window.getSelection();
			if (selection.rangeCount && selection.getRangeAt)
			{
				range = selection.getRangeAt(0);
				var node = $(range.commonAncestorContainer);
				if (node.parents().is('div.note-editor')) 
				{	
					if (node.parents().is('p.alertHeading'))
					{
						node.parent().is('p.alertHeading') ? node.unwrap() : node.parentsUntil("p.alertHeading").unwrap();
					}
					else
					{	
					    node = document.createElement('p');
					    node.className='alertHeading';
					    node.style.cssText='color: red;font-size: larger';
					    node.appendChild(range.extractContents());
					    context.invoke('editor.pasteHTML', node);
					}				
				}
			}
	     }
	  });

	  return button.render();   // return button as jquery object
	}
	var clearAll = function (context) 
	{
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button(
	  {
	    contents: '<i class="fa fa-eraser"/>',
	    tooltip: 'Clear Format',
	    click: function (event)
	    {	
	    	event.preventDefault(); 
			selection = window.getSelection();
			if (selection.rangeCount && selection.getRangeAt)
			{				
				range = selection.getRangeAt(0);
				var node = $(range.commonAncestorContainer);				
				if (node.parents().is('div.note-editor')) 
				{	
					node.parentsUntil('div.note-editable').removeAttr('style');										            		
				}
			}
	     }
	  });

	  return button.render();   // return button as jquery object
	}					
	// END OF SUMMERNOTE CUSTOM BUTTONS
	var enableSummernote = function ()
	{
		$('#message').summernote({
			styleTags: ['p', 'blockquote','h1', 'h2','h3'],
			toolbar: [
			    ['style', ['style','bold','italic', 'underline','borderText','alertText','alertTextBold','headingAlert']],
			    ['para', ['ul']], 
			    ['link', ['linkDialogShow', 'unlink']],
			    ['misc',['undo','codeview']], 
			    ['cleaner',['cleaner']],
			    ['clear',['clearAll']],   			
			],
			cleaner:{
		          action: 'paste', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
		          newline: '<br>', // Summernote's default is to use '<p><br></p>'
		          notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
		          icon: '<i class="note-icon">[Your Button]</i>',
				  keepHtml: true, // Remove all Html formats
		          keepOnlyTags: ['<p>', '<br>'], // If keepHtml is true, remove all tags except these
		          keepClasses: true, // Remove Classes
		          badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
		          badAttributes: ['style', 'start'], // Remove attributes from remaining tags
		          limitChars: false, // 0/false|# 0/false disables option
		          limitDisplay: 'both', // text|html|both
		          limitStop: false // true/false
		    },					
			buttons:{
				borderText		: borderText,
				alertText 		: alertText,
				alertTextBold  	: alertTextBold,
				headingAlert	: headingAlert,
				clearAll		: clearAll
			},        	
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
        		$('#subject').val(template.Subject);
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
	        	//form.submit();
	        	//
        		sendEmail();
	        	return false;
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

	var setTemplate = function(){
		$.get("/no_reply_emails/listTemplatesBySectionFormated", function(data, status){			
			$('#template_id').select2({
				data:data,
				width: '100%',
				placeholder:'Select from list',
			});

		});
		
	};

//  Start modal functions  //

	var sendEmail = function()
	{
		$("#contentLoading").modal("show");		
		const csrf 				= $('meta[name=_token]').attr('content');
		const templateId 		= $("#template_id").val();
		const to 				= $("#to").val();
		const subject 			= $("#subject").val();
		const message			= $("#message").val();		
		const mainAttachment 	= $("#main_attachment").prop('files')[0];
		const attachments 		= $("[name^='attachments']");
		let attachments_files 	= [];
		for (let i = attachments.length - 1; i >= 0; i--) {
			if(attachments[i].files[0]!=null)
			{			
				attachments_files.push( attachments[i].files[0] );
			}
		}
		var formData = new FormData();
		formData.append('templateId', templateId);
		formData.append('to', to);
		formData.append('subject', subject);
		formData.append('message', message);		
		if(mainAttachment != null)
		{
			formData.append('mainAttachment', mainAttachment);
		}
		attachments_files.forEach(function(attachment_file,i){
			formData.append('attachment'+i, attachment_file);
		});	
	    
	    $.ajax({
	    	headers: {
	        'X-CSRF-TOKEN': csrf
	      	},
	      	method: "POST",
	        url: "/no_reply_emails",
	        processData: false,
	        contentType: false,
            //mimeType: "multipart/form-data",
	        data: formData
	      })			
        .done(function( msg ) { 
        	$("#contentLoading").modal("hide");       		        	
	        $("#sendEmail").modal("show");

	        
        });
     	
	};


	var sendAnother = function()
	{
		$("#send_another").click(function(){
			clearFields();
    	});
		$("#start_over").click(function(){
			$("#to").val('');
			clearFields();

    	});     	 
	};
	var clearFields = function()
	{
		$("#template_id").val([]).change();
    	$("#subject").val('');
    	$("#message").summernote("reset");
    	$("#main_attachment").val('');
    	$("[name^='attachments']").val('');
    	$("#sendEmail").modal("hide");
	};
    return {
        //main function to initiate the module
        init: function () 
        {
        	enableSummernote();
        	onChangeTemplate();
        	form_validate();
        	setTemplate();
        	sendAnother();
        }

    }
}();


jQuery(document).ready(function() 
{
    noReplyEmails.init();
});