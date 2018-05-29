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

    $( "#back" ).on( "click", function(e) {
        e.preventDefault();     
        window.location.href = "/referral/create/details/?ca_id=" + getUrlParameter('ca_id') + "&mt_id=" + getUrlParameter('mt_id') + "&filters=" + getUrlParameter('filters');
    });
});


//Function taken from https://stackoverflow.com/questions/19491336/get-url-parameter-jquery-or-how-to-get-query-string-values-in-js
//var tech = getUrlParameter('ca_id');
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
    sURLVariables = sPageURL.split('&'),
    sParameterName,
    i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};