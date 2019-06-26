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
				placeholder: "Location ( suburb or postcode )",
				selectOnClose: true
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
				placeholder: "Legal matter ( e.g. overdue fines, insurance )",
				matcher: matchCustom,
				selectOnClose: true,
				escapeMarkup: function (text) {
					return text;
				}
			});
		}).done(function(){
			$("#contentLoading").modal("hide");
			setSelectedValues();
		});
	}

	var fillFilters = function()
	{
		let filters = ''
		if ($('#referral_CLC').is(":checked"))
		{
			filters += $('#referral_CLC').val() + ',';
		}
		if ($('#referral_VLA').is(":checked"))
		{
		filters +=  $('#referral_VLA').val()+ ',';
		}
		if ($('#referral_NLP').is(":checked"))
		{
			filters += $('#referral_NLP').val()+ ',';
		}
		if ($('#referral_PP').is(":checked"))
		{
			filters += $('#referral_PP').val()+ ',';
		}
		return filters;
	}

	var nextPage = function ()
	{
		$( "#next-page" ).on( "click", function(e)
		{
			e.preventDefault();

			var legal_issue = $('.legal_issue #single').select2('data');
			var catchment = $('.location #single-prepend-text').select2('data');
			// get checkbox value
			let filters = fillFilters();
			if(filters == ''){
				swal('Alert','Please Select a Service Provider Type', "warning");
			}
			else if( legal_issue[0].id != '' && catchment[0].id != '')
			{
				redirectStep( legal_issue[0].id, catchment[0].id, filters );
			}
			else
			{
				swal("Alert", "Please Select a Legal Matter and catchment", "warning");
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
		// Filter set values
		if(getUrlParameter('filters') != null)
		{
			count = 0;
			$('#referral_CLC').prop('checked', false).trigger('change');
			$('#referral_VLA').prop('checked', false).trigger('change');
			$('#referral_NLP').prop('checked', false).trigger('change');
			$('#referral_PP').prop('checked', false).trigger('change');
			$('#referral_All').prop('checked', false).trigger('change');
			let filters= getUrlParameter('filters');
			if(filters.indexOf('2')>=0)
			{
				$('#referral_CLC').prop('checked', true).trigger('change');
				count++;
			}
			if(filters.indexOf('3')>=0)
			{
				$('#referral_VLA').prop('checked', true).trigger('change');
				count++;
			}
			if(filters.indexOf('1')>=0)
			{
				$('#referral_NLP').prop('checked', true).trigger('change');
				count++;
			}
			if(filters.indexOf('5')>=0)
			{
				$('#referral_PP').prop('checked', true).trigger('change');
				count++;
			}
			if(count == 4)
			{
				$('#referral_All').prop('checked', true).trigger('change');
			}
			else if($('#collapse').hasClass('collapse'))
			{
				$('#collapse').addClass('in');
			}

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

	var redirectStep = function ( mt_id, ca_id, filters )
	{
		$("#contentLoading").modal("show");
		window.location.href = "/referral/create/details/?ca_id=" + ca_id + "&mt_id=" + mt_id + "&filters=" + filters;
	}
	// Function taken from https://www.sanwebe.com/2014/01/how-to-select-all-deselect-checkboxes-jquery
	var setServiceProviderSelect = function()
	{


	$("#select_all").change(function(){
		$(".checkbox").prop('checked', $(this).prop("checked"));
	});


	$('.checkbox').change(function(){

		if(false == $(this).prop("checked")){
			$("#select_all").prop('checked', false);
		}

		if ($('.checkbox:checked').length == $('.checkbox').length ){
			$("#select_all").prop('checked', true);
		}
	});

	$("#select_all").prop('checked', true).trigger('change');

	}

	return {
		//main function to initiate the module
		init: function ()
		{
			getCatchments();
			setLegalIssue();
			nextPage();
			setServiceProviderSelect();
		}

	};
}();

jQuery(document).ready(function()
{
	searchPage.init();
});