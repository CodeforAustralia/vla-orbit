var footer = function()
{
	var set_sp = function()
	{
		document.getElementById('sp_send').addEventListener("click", function(){

			const sp = document.getElementById('sp_id').value;
			const name = document.getElementById('full_name').value;
			var csrf = document.getElementsByName("_token")[0].content;
			$.ajax({
			headers:
                {
                    'X-CSRF-TOKEN': csrf
                },
			method: "POST",
			url: "/service_provider/set_sp",
			data: {
			        sp,
			        name
			      }
			})
			.done(function() {
				const success = document.getElementById("sp-result");
				success.style.display = "block";
				const content = document.getElementById("sp-content");
				content.style.display = "none";
			});


		});
	}

	return {
	//main function to initiate the module
		init: function ()
		{
			set_sp();
		}

	}

}();
document.addEventListener("DOMContentLoaded", function(event) {
	footer.init();
});