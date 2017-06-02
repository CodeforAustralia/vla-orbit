$(document).ready(function() {
	
	$.get("/matter/listFormated", function(data, status){
        $("#matters").select2({
        	data: data
        });
        loadServiceMatters();
        console.log("Outer");
    });

});