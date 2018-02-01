var orbitFunctions = function() 
{
    var confirmDialog = function() 
    {
        $( ".delete-content" ).on( "click", function(e) 
        {        
            var r = confirm("Are you sure that you want to delete it?\n To confirm press OK or Cancel.");
            if (r == true) 
            {
                //Continue to the event
            } else {
                e.preventDefault();       
            }
        });  
    }

    return {
        //main function to initiate the module
        init: function () 
        {
            confirmDialog();
        }
    }

}();

jQuery(document).ready(function() 
{
    orbitFunctions.init();
});