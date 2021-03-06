$(document).ready(function() {
	
    $( "#next-eligibility" ).on( "click", function(e) {
        e.preventDefault();

        $("#contentLoading").modal("show");

        var eligibility = $('input:checked').map(function(){

          return $(this).attr('id');

        });
        
        if( Object.keys(eligibility.get()).length > 0 ) {
            window.location.href = "/referral/create/questions/?ca_id=" + getUrlParameter('ca_id') + 
                                    "&mt_id=" + getUrlParameter('mt_id') + 
                                    "&vls_id=" + eligibility.get().toString() + 
                                    '&filters=' + getUrlParameter('filters');
        } else {
            window.location.href = "/referral/create/questions/?ca_id=" + getUrlParameter('ca_id') + 
                                    "&mt_id=" + getUrlParameter('mt_id') +
                                    '&filters=' + getUrlParameter('filters');
        }
    });

    $( "#back" ).on( "click", function(e) {
        e.preventDefault();        
        $("#contentLoading").modal("show");

        window.location.href = "/referral/create/location/?ca_id=" + getUrlParameter('ca_id') + '&mt_id=' + getUrlParameter('mt_id') + '&filters=' + getUrlParameter('filters');
    });
    
    $( "#show-help" ).on( "click", function(e) {
        if ($("#show-help").html() === "Show help")
        {
            $(".mt-checkbox-list small").show();
            $("#show-help").html("Hide help");
            
        } 
        else 
        {
            $(".mt-checkbox-list small").hide();
            $("#show-help").html("Show help");
        }
    });
    $(".mt-checkbox-list small").hide();
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