var login = function()
{
	var open_sign_up_modal = function()
	{
		document.getElementById("login_signup").addEventListener("click", function(){
			$('#singup').modal('show');
		});

		

	};
	var send = function()
	{
		document.getElementById('login_send').addEventListener("click", function(){
			//Send email
			const message = document.getElementById('login-message').value			
			if(message.trim()=='')
			{
				swal("Alert", "The message cannot be empty");
			}
			else
			{			  	
		      $.ajax({
		        method: "GET",
		        url: "/signup",
		        data: { 
		                Message : message
		              }
		      })
		        .done(function() {
		        	const success = document.getElementById("login-result");
		        	success.style.display = "block";
		        	const content = document.getElementById("login_message");
		        	content.style.display = "none";		
		        });
			}

		});		
	}

	return {
	//main function to initiate the module
		init: function () 
		{
			open_sign_up_modal();
			send();
		}

	}

}();	
document.addEventListener("DOMContentLoaded", function(event) { 
	login.init();
});