var viewService = function()
{
	var getServiceById = function( sv_id )
	{
		$.ajax({
          method: "GET",
          url: "/service/list_service_by_id/" + sv_id
        })
		.done(function( service ) {             
			$("#contentLoading").modal("hide");
			$('#viewService').modal('show')
			setServiceInformation(service);
		});
	}

	var onClickView = function()
	{
		$(document).on("click", ".view-content", function(){
			$("#contentLoading").modal("show");
			var sv_id = $(this).attr('id');
			getServiceById(sv_id);
		});
	}

	var setServiceInformation = function( service )
	{
		$('#serviceName').html( service.ServiceName );
		$('#phoneNumber').html( service.Phone );
		$('#email').html( service.Email );
		$('#location').html( service.Location );
		$('#url').html( service.URL );
		$('#description').html( service.Description );
		$('#service_provider').html( service.ServiceProviderName );
		$('#wait_time').html( service.Wait );
		$('#opening_hours').html( service.OpenningHrs );
		$('#service_level').html( service.ServiceLevelName );
		$('#service_type').html( service.ServiceTypeName );

		$('#legal_matters').html('');
		for( var i = 0 ; i < service.ServiceMatters.length ; i++ )
		{
			var matter = '<span class="badge badge-info badge-roundless">' + service.ServiceMatters[i].MatterName + '</span> ';
			$('#legal_matters').append( matter );
		}

		$('#eligibility_criteria').html('');
		for( var i = 0 ; i < service.vulnerabilities.length ; i++ )
		{
			var vulnerability = '<span class="badge badge-success badge-roundless">' + service.vulnerabilities[i] + '</span> ';
			$('#eligibility_criteria').append( vulnerability );
		}

		$('#catchment_area').html('');
		for( var i = 0 ; i < service.catchments.length ; i++ )
		{
			var catchments = '<span class="badge badge-primary badge-roundless">' + service.catchments[i] + '</span> ';
			$('#catchment_area').append( catchments );
		}
	}

	return {
		init: function () {
			onClickView();
		}
	}
}();

jQuery(document).ready(function() {
    viewService.init();
});