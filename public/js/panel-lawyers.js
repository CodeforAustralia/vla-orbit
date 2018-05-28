// The following example creates a marker in Stockholm, Sweden using a DROP
// animation. Clicking on the marker will toggle the animation between a BOUNCE
// animation and no animation.

var markers = [];
var infowindow;
var map;
var bounds;
var loc;

function getAddress() 
{
	const csrf  = document.getElementsByName("_token")[0].content;
	let address = document.querySelector('#address').value;
	let serviceName = document.getElementById("serviceName").innerText;
	if ( address != '') {

		$.ajax({
			headers: 
			{
			  'X-CSRF-TOKEN': csrf
			},
			method: 'POST',
			url: '/panel_lawyers/get_closet_by_address',
			data: { address,
					serviceName }
		})
		.done(function( msg ) 
		{							
			if ( msg.hasOwnProperty("client_address") && msg.hasOwnProperty("closest") ) 
			{			
			    const client_address =  JSON.parse(msg.client_address);
			    const closest = JSON.parse(msg.closest);
			    createPoint( client_address, closest );
			    $('#nearest').val(msg.closest);
				$('#map').show();
			}
			else
			{
				swal("Alert", "Address not found", "warning");
				$('#map').hide();
			}
		});
	}
	else 
	{
		swal("Alert", "Please provide an address", "warning");
		$('#map').hide();
	}
}

function createPoint(client_address, closest) 
{
	document.getElementById("map").style.height = "400px";
	var map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 12,
	  center: {lat: client_address.lat, lng: client_address.lng}
	});
	markers = [];
	//Set Client Marker
	setMarker( map, client_address);
	for (let i = closest.length - 1; i >= 0; i--) 
	{          
	  let office = closest[i].office;
	  let lat = parseInt(office.lat);
	  let lng = parseInt(office.lng);
	  //Set CLosest offices marker
	  setMarker( map, office);
	}
	map.fitBounds(bounds);       // auto-zoom
	map.panToBounds(bounds);     // auto-center
}

function setMarker( map, office)
{
	let contentString = "Client's house";
	let title = "Client's house";
	let options = {
	  map: map,
	  draggable: false,
	  animation: google.maps.Animation.DROP,
	  position: {lat:  parseFloat(office.lat), lng: parseFloat(office.lng)},
	  title: title,
	  //icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
	  //icon: 'https://findicons.com/files/icons/1580/devine_icons_part_2/128/home.png'
	};
	if( office.hasOwnProperty("OfficeName"))
	{
	  options.title = office.OfficeName;
	  contentString = '<div id="content" style = "width:200px;min-height:40px">'+              
	      '<h4>' + office.OfficeName + '</h4>'+              
	      '<p><strong>Address: </strong>' + office.FullAddress + '</p>'+
	      '<p><strong>Phone: </strong>' + office.OfficePhone + '</p>'+              
	      '</div>';	      
	}
	else
	{
	    options.icon = '/../../assets/layouts/layout2/img/OrbitCasa_icon.png';
    	bounds  = new google.maps.LatLngBounds();
	}
	// Search for markers in the same position
    markers.forEach(function(markerObject, index, markers){
    	if(options.position.lat == markerObject.position.lat() && options.position.lng == markerObject.position.lng() && office.hasOwnProperty("OfficeName") )
    	{	    		
    		contentString += "<hr>"+ markerObject.infowindow.content;
    		markerObject.infowindow.content = '';
    	}
    	
    });	

	var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
	options.infowindow = infowindow;

	let marker = new google.maps.Marker(options);

    markers.push(marker);

	marker.addListener('click', function() {
	    hideAllInfoWindows(map);
	    infowindow.open(map, marker);
	});

	loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
	bounds.extend(loc);
}

function toggleBounce() {
	if (marker.getAnimation() !== null) {
	  marker.setAnimation(null);
	} else {
	  marker.setAnimation(google.maps.Animation.BOUNCE);
	}
}

function hideAllInfoWindows(map) {
   markers.forEach(function(marker) {
     marker.infowindow.close(map, marker);
  }); 
}

function initMap() 
{
	//set center to 570 Bourke St
	var map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 13,
	  center: {lat: -37.815419, lng: 144.956719 }
	});

    var input = document.getElementById('address');
    var autocomplete = new google.maps.places.Autocomplete(input);

    // Bind the map's bounds (viewport) property to the autocomplete object,
    // so that the autocomplete requests use the current map bounds for the
    // bounds option in the request.
    autocomplete.bindTo('bounds', map);
};


function get_lat_lng()
{
	const csrf  = document.getElementsByName("_token")[0].content;
	let get_address_field = document.getElementById("get_address");
	if(get_address_field)
	{
		document.getElementById("get_address").addEventListener("click", function(){
			let address = document.getElementById("address").value;
		    $.ajax({
		        headers: {
		            'X-CSRF-TOKEN': csrf
		        },		    	
		      	method: "POST",
		      	url: "/panel_lawyers/get_lat_lng/",
		      	data: { address }		      
		    })
		    .done(function( coordinates ) {
		    	document.getElementById("lat").value = coordinates.lat;
		    	document.getElementById("lng").value = coordinates.lng;
		    })
		    .fail(function(xhr, status, error) {
	    		 swal("Alert", "Address not found", "warning");
			});			
		}); 
		
	}
};	



document.addEventListener('DOMContentLoaded', function() {    
    get_lat_lng();
    initMap();        
    var pacContainerInitialized = false; 
    document.getElementById("address").onkeypress = function() 
    {
        if (!pacContainerInitialized) { 
                $('.pac-container').css('z-index', '99999'); 
                pacContainerInitialized = true; 
        } 
    };
}, false);


