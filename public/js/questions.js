	$(document).ready(function() {
	
    $( "#submit-answers" ).on( "click", function(e) {
        e.preventDefault();

        var form_answers = $('#form_answers');
        
        console.log(form_answers.serialize());

        $(form_answers.prop('elements')).each(function(){
        	console.log($( this ).val());
        });

        form_answers.submit();
    });

});