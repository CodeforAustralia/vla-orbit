$(document).ready(function() {
	
    //Referral section
    $.get("/catchment/listFormated", function(data, status){
        $("#single-prepend-text").select2({
            data: data,
            width: '100%',
            placeholder: "Search for a suburb, postcode or council"
        });
    });


    $( "#next-location" ).on( "click", function(e) {
        e.preventDefault();
        var catchment = $('#single-prepend-text').select2('data');
        if( catchment[0].id > 0)
        {            
            window.location.href = "/referral/create/legal_issue/?ca_id=" + catchment[0].id;
        } 
        else {
            alert("Please Select a catchment");
        }
    });

});