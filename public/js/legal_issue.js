$(document).ready(function() {
	
    $( "#next-legal_issue" ).on( "click", function(e) {
        e.preventDefault();        
        var legal_issue = $('#single').select2('data');
        if( legal_issue[0].id != '')
        {
        	$("#contentLoading").modal("show");
            window.location.href = "/referral/create/details/?ca_id=" + getUrlParameter('ca_id') + "&mt_id=" + legal_issue[0].id;
        }
        else
        {        	
            swal("Alert", "Please Select a Legal Issue", "error");  
        }
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