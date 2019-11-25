var noReplyEmailTemplate = function () {
	// BEGIN OF SUMMERNOTE CUSTOM BUTTONS
	var borderText = function (context) {
		var ui = $.summernote.ui;
		// create button
		var button = ui.button(
			{
				contents: '<i class="fa fa-minus-square-o"/>',
				tooltip: 'Safety alert box',
				click: function (event) {
					event.preventDefault();
					selection = window.getSelection();
					if (selection.rangeCount && selection.getRangeAt) {
						range = selection.getRangeAt(0);
						var node = $(range.commonAncestorContainer);
						if (node.parents().is('div.note-editor')) {
							if (node.parents().is('p.textBorder')) {
								node.parent().is('p.textBorder') ? node.unwrap() : node.parentsUntil("p.textBorder").unwrap();
							}
							else {
								node = document.createElement('p');
								node.className = 'textBorder';
								node.style.cssText = 'border:2px solid #000409;padding:2%';
								node.appendChild(range.extractContents());
								context.invoke('editor.pasteHTML', node);
							}
						}
					}
				}
			});

		return button.render();   // return button as jquery object
	}
	var alertText = function (context) {
		var ui = $.summernote.ui;
		// create button
		var button = ui.button(
			{
				contents: '<i class="fa fa-pencil-square-o"/>',
				tooltip: 'Shaded text box',
				click: function (event) {
					event.preventDefault();
					selection = window.getSelection();
					if (selection.rangeCount && selection.getRangeAt) {
						range = selection.getRangeAt(0);
						var node = $(range.commonAncestorContainer);
						if (node.parents().is('div.note-editor')) {
							if (node.parents().is('p.textBoxed')) {
								node.parent().is('p.textBoxed') ? node.unwrap() : node.parentsUntil("p.textBoxed").unwrap();
							}
							else {
								node = document.createElement('p');
								node.className = 'textBoxed';
								node.style.cssText = 'background-color: #eef3e9;padding: 15px 20px 10px 20px';
								node.appendChild(range.extractContents());
								context.invoke('editor.pasteHTML', node);
							}
						}
					}
				}
			});

		return button.render();   // return button as jquery object
	}
	var alertTextBold = function (context) {
		var ui = $.summernote.ui;
		// create button
		var button = ui.button(
			{
				contents: '<i class="fa fa-pencil-square"/>',
				tooltip: 'Shaded text box bold',
				click: function (event) {
					event.preventDefault();
					selection = window.getSelection();
					if (selection.rangeCount && selection.getRangeAt) {
						range = selection.getRangeAt(0);
						var node = $(range.commonAncestorContainer);
						if (node.parents().is('div.note-editor')) {
							if (node.parents().is('p.textBoxedBold')) {
								node.parent().is('p.textBoxedBold') ? node.unwrap() : node.parentsUntil("p.textBoxedBold").unwrap();
							}
							else {
								node = document.createElement('p');
								node.className = 'textBoxedBold';
								node.style.cssText = 'background-color: #eef3e9;padding: 15px 20px 10px 20px;font-weight: bold';
								node.appendChild(range.extractContents());
								context.invoke('editor.pasteHTML', node);
							}
						}
					}
				}
			});

		return button.render();   // return button as jquery object
	}
	var headingAlert = function (context) {
		var ui = $.summernote.ui;
		// create button
		var button = ui.button(
			{
				contents: '<i class="fa fa-exclamation-circle"/>',
				tooltip: 'Safety alert heading',
				click: function (event) {
					event.preventDefault();
					selection = window.getSelection();
					if (selection.rangeCount && selection.getRangeAt) {
						range = selection.getRangeAt(0);
						var node = $(range.commonAncestorContainer);
						if (node.parents().is('div.note-editor')) {
							if (node.parents().is('p.alertHeading')) {
								node.parent().is('p.alertHeading') ? node.unwrap() : node.parentsUntil("p.alertHeading").unwrap();
							}
							else {
								node = document.createElement('p');
								node.className = 'alertHeading';
								node.style.cssText = 'color: red;font-size: larger';
								node.appendChild(range.extractContents());
								context.invoke('editor.pasteHTML', node);
							}
						}
					}
				}
			});

		return button.render();   // return button as jquery object
	}
	var clearAll = function (context) {
		var ui = $.summernote.ui;
		// create button
		var button = ui.button(
			{
				contents: '<i class="fa fa-eraser"/>',
				tooltip: 'Clear Format',
				click: function (event) {
					event.preventDefault();
					selection = window.getSelection();
					if (selection.rangeCount && selection.getRangeAt) {
						range = selection.getRangeAt(0);
						var node = $(range.commonAncestorContainer);
						if (node.parents().is('div.note-editor')) {
							node.parentsUntil('div.note-editable').removeAttr('style');
						}
					}
				}
			});

		return button.render();   // return button as jquery object
	}
	// END OF SUMMERNOTE CUSTOM BUTTONS
	var enableSummernote = function () {
		$('#template').summernote({
			styleTags: ['p', 'blockquote', 'h1', 'h2', 'h3'],
			toolbar: [
				['style', ['style', 'bold', 'italic', 'underline', 'borderText', 'alertText', 'alertTextBold', 'headingAlert']],
				['para', ['ul']],
				['link', ['linkDialogShow', 'unlink']],
				['misc', ['undo', 'codeview']],
				['cleaner', ['cleaner']],
				['clear', ['clearAll']],
			],
			cleaner: {
				action: 'none', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
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
			buttons: {
				borderText: borderText,
				alertText: alertText,
				alertTextBold: alertTextBold,
				headingAlert: headingAlert,
				clearAll: clearAll
			},
			height: 500
		});
	}

	var form_validate = function () {
		$("#nre_template_form").validate({

			ignore: ":not(:visible)",

			submitHandler: function (form, event) {
				event.preventDefault();
				form.submit();
			}
		});
	};

	var disable_service_provider = function () {
		if ($('#all').is(":checked")) {
			$('#Section').attr('disabled', true);
		}

		$('#all').click(function () {
			$('#Section').attr('disabled', this.checked).val([])
		});
	};

	return {
		//main function to initiate the module
		init: function () {
			enableSummernote();
			form_validate();
			disable_service_provider();
		}

	}
}();


jQuery(document).ready(function () {
	noReplyEmailTemplate.init();
});

