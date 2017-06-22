$(document).ready(function() {
	
	$.get("/matter/listFormated", function(data, status){
        $("#matters").select2({
        	data: data,
            width: '100%'
        });
        loadServiceMatters();
    });
    
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
    
});