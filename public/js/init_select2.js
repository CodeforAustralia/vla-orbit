var initSelect2Fields = function()
{
    var loadMatters = function()
    {
        $.get("/matter/listFormated", function(data, status){
            $("#matters").select2({
                data: data,
                width: '100%'
            });
            loadServiceMatters();
        });
    }

    var loadLGC = function()
    {    
        $.get("/catchment/listLgcs", function(data, status){
            $("#lga").select2({
                data: data,
                width: '100%'
            });
        })
        .done(function() {
            $.get("/catchment/listSuburbs", function(data, status){
                $("#suburbs").select2({
                    data: data,
                    width: '100%'
                });
                loadCatchments();
            });
            
        });
    }

    var loadServiceProviders = function()
    { 
         var csrf = $('meta[name=_token]').attr('content');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            method: "POST",
            url: "/service_provider/listFormated", 
            data: {
                scope: 'All'
            } 
        })
        .done(function(data){
            $("#referral_conditions").select2({
                data: data,
                width: '100%'
            });
            loadReferralConditions();
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            method: "POST",
            url: "/service_provider/listFormated", 
            data: {
                scope: 'VLA'
            } 
        })
        .done(function(data){
            $("#booking_conditions").select2({
                data: data,
                width: '100%'
            });
            loadBookingConditions();    
        });
    }

    var loadEReferrals = function()
    {
        var csrf = $('meta[name=_token]').attr('content');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': csrf
            },
            method: "POST",
            url: "/service_provider/listFormated",
            data: {
                scope: 'All'
            }
        })
        .done(function (data) {
            $("#e_referral_conditions").select2({
                data: data,
                width: '100%'
            });
            loadEReferralConditions();
        });

        $.get("/e_referral/listFormsFormated", function (data, status) {
            $("#e_referral_forms").select2({
                data: data.data,
                width: '100%'
            });            
            loadEReferralForms();
        });
        
    }

    var enableSelectAllOptions = function()
    {
        $('[select-all-sp]').on('click',function()
        {
           var action =  $(this).attr('select-all-sp');
           $('#' + action + '_conditions > option').prop("selected",true).trigger("change");
        });
    }

    var enableClearAllOptions = function()
    {
        $('[clear-all-sp]').on('click',function()
        {            
           var action =  $(this).attr('clear-all-sp');
           $('#' + action + '_conditions > option').prop("selected",false).trigger("change");
        });
    }

    return {
        init: function()
        {
            loadMatters();
            loadLGC();
            loadServiceProviders();
            loadEReferrals();
            enableSelectAllOptions();
            enableClearAllOptions();
        }
    }
}();

$(document).ready(function() {
   initSelect2Fields.init();
});