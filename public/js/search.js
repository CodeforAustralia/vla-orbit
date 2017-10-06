var searchPage = function() 
{

	var getCatchments = function () 
	{
		$("#contentLoading").modal("show");
	    //Referral section
	    $.get("/catchment/listFormated", function(data, status){
	        $("#single-prepend-text").select2({
	            data: data,
	            width: '100%',
	            placeholder: "Location ( suburb, postcode or council )"
	        });
	    }).done(function(){
	        $("#contentLoading").modal("hide");
	    });

	};
	var setLegalIssue = function () 
	{		
		$('.legal_issue #single').select2({placeholder: "Legal issue ( e.g. overdue fines, insurance )"});
	}

	var nextPage = function () 
	{
	    $( "#next-page" ).on( "click", function(e) 
	    {

	        e.preventDefault();        

	        var legal_issue = $('.legal_issue #single').select2('data');
        	var catchment = $('.location #single-prepend-text').select2('data');

	        if( legal_issue[0].id != '' && catchment[0].id != '')
	        {
	        	/*
	        	if( catchment[0].id == ''  ) {	            	
	        		
	        		swal({
							  title: 'Location not provided, do you want to continue? (Can take longer)',
							  text: "You can provide where the client lives, works or studies.",
							  type: 'info',
							  showCancelButton: true,
							  confirmButtonColor: '#3085d6',
							  cancelButtonColor: '#d33',
							  confirmButtonText: 'Yes, continue'
							}, function() {
						      // Redirect the user						      
							  redirectStep( legal_issue[0].id, 0 );
						    });
						    
	        	} 
	        	*/
			  	redirectStep( legal_issue[0].id, catchment[0].id );
	        }
	        else
	        {        	
	            swal("Alert", "Please Select a Legal Issue and catchment", "error");  
	        }
	    });
	}


    return {
        //main function to initiate the module
        init: function () 
        {
            getCatchments();
            setLegalIssue();
            nextPage();
        }

    };
}();

	function redirectStep( mt_id, ca_id ) 
	{

    	$("#contentLoading").modal("show");    	
        window.location.href = "/referral/create/details/?ca_id=" + ca_id + "&mt_id=" + mt_id;
	}

jQuery(document).ready(function() 
{
    searchPage.init();
});