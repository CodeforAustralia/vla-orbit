var dashboardObj = function()
{
    var sortableNotes = function()
    {        
        $( ".column" ).sortable({
            stop: function(event, ui) {
                var notesPosition = updateNotesPosition();  
                var csrf = $('meta[name=_token]').attr('content');                
                $.ajax({
                    headers: 
                    {
                        'X-CSRF-TOKEN': csrf
                    },
                    method: "POST",
                    url: "/dashboard/updatePositions",
                    data: { positions: notesPosition }
                })
                .done(function( msg ) {
                    //console.log(msg);
                });
            }
        });     
    }

    var updateNotesPosition = function()
    {
        var notesPosition = [];
        $.each( $(".column div"), function( index, value ) {          
          notesPosition[index] = this.id;
        });
        return notesPosition;
    }

    return {
        //main function to initiate the module
        init: function () 
        {
            sortableNotes();
        }
    }
}();

jQuery(document).ready(function() 
{
    dashboardObj.init();
});