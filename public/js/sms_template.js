var SmsTemplateArea = function () {

	//Put tag text inside template content after clicking tag button
	var shortcut_button = function (){
		$("body").on("click", ".shortcut-tag", function(){	
		   var $txt = $("#template");
	       var caretPos = $txt[0].selectionStart;
	       var textAreaTxt = $txt.val();
	       var txtToAdd = $(this).text();
	       $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		});
	}

	//Character counter text and event
	var character_count = function (){
		var max_amount = 400;
		$("body").on("keyup", "#template", function(){
			limit_text(this , max_amount + substract_tags($(this).val()));
			$("body #char-count").text(  
										( max_amount - $(this).val().length ) + substract_tags( $(this).val() )
									   );
		});
	}

	//Avoid more than the allowed text in the template content
	var limit_text = function (limitField, limitNum) {
		if (limitField.value.length > limitNum) {
			limitField.value = limitField.value.substring(0, limitNum);
		} 
	}

	//Regec function to avoid counting tags from character count function
	var substract_tags = function (text) {
	    var mts = text.match(/\(([^()]+)\)/g );
	    var total_char = 0;
	    if(mts){	    	
		    mts.forEach(function(entry) {
		    	total_char += entry.length;
			});
	    }

		return total_char;
	}

    return {
        //main function to initiate the module
        init: function () {
            character_count();
            shortcut_button();
        }

    };
}();


jQuery(document).ready(function() {
    SmsTemplateArea.init();
});