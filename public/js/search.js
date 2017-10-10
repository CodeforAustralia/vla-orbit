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
            setSelectedValues();
	    });

	};
	var setLegalIssue = function () 
	{		
	    $.get("/matter/listFormatedTrimmed", function(data, status){
	        $(".legal_issue #single").select2({
	            data: data,
	            width: '100%',
	            placeholder: "Legal issue ( e.g. overdue fines, insurance )",
	            matcher: matchCustom
	        });
	    }).done(function(){
	        $("#contentLoading").modal("hide");
	    	setSelectedValues();
	    });
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

	var setSelectedValues = function () 
	{
		if( getUrlParameter('mt_id') != null )
		{
			$('#single').val( getUrlParameter('mt_id') ).trigger('change');
		}

		if( getUrlParameter('ca_id') != null )
		{
			$('#single-prepend-text').val( getUrlParameter('ca_id') ).trigger('change');
		}
	}

	//Function taken from https://stackoverflow.com/questions/19491336/get-url-parameter-jquery-or-how-to-get-query-string-values-in-js
	//var tech = getUrlParameter('ca_id');
	var getUrlParameter = function (sParam) 
	{
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	    sURLVariables = sPageURL.split('&'),
	    sParameterName,
	    i;

	    for (i = 0; i < sURLVariables.length; i++) 
	    {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) 
	        {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};

    var matchCustom = function (params, data) 
    {
      	// If there are no search terms, return all of the data
      	if ($.trim(params.term) === '') {
      	  	return data;
      	}

        // Skip if there is no 'children' property
        if (typeof data.children === 'undefined') {
        	return null;
        }

        // `data.children` contains the actual options that we are matching against
        var filteredChildren = [];
        $.each(data.children, function (idx, child) {
        //Compare against matter name and tags inside matters
	        if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) !== -1 || child.Tag.toUpperCase().indexOf(params.term.toUpperCase()) !== -1 ) {
	          filteredChildren.push(child);
	        }
        });

      	// If we matched any of the timezone group's children, then set the matched children on the group
      	// and return the group object
        if (filteredChildren.length) {
	        var modifiedData = $.extend({}, data, true);
	        modifiedData.children = filteredChildren;

	        // You can return modified objects from here
	        // This includes matching the `children` how you want in nested data sets
	        return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

	var redirectStep = function ( mt_id, ca_id ) 
	{
		$("#contentLoading").modal("show");    	
	    window.location.href = "/referral/create/details/?ca_id=" + ca_id + "&mt_id=" + mt_id;
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

jQuery(document).ready(function() 
{
    searchPage.init();
});