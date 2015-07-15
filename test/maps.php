<!DOCTYPE html>
<html>
<head>
<title>Maps javascript API test omgeving</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html {
  	height: 100%;
  }
  
body {
 height: 100%;
 margin: 0px;
 padding: 0px;
}
  
  #map_canvas {
  	height: 100%;
  }
  
#map_canvas .button {
 font-family: 'Trebuchet MS', arial, sans-serif;
 font-size: 13px;
 background-color: rgba(255, 255, 255, 0.8);
 margin-right: 10px;
 text-align: center;
 padding: 2px 6px;
 cursor: pointer
}

#map_canvas .button:hover {
 background-color: rgba(200, 200, 255, 0.8);
}

</style>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true">
</script>
<script type="text/javascript">
	var chicago = new google.maps.LatLng(41.850033, -87.6500523);
	var dennis = new google.maps.LatLng(52.38217, 6.475040);
	var niels = new google.maps.LatLng(52.254279, 7.000819);
	var starparty = new google.maps.LatLng(52.350806, 6.496424);

	function createLocationButton(name, place, marker, map){
		var buttonElement = document.createElement('div');
		buttonElement.setAttribute('class', 'button');
		buttonElement.appendChild(document.createTextNode(name));
		
		google.maps.event.addDomListener(buttonElement, 'click', function() {
    		map.panTo(place);
    		marker.setAnimation(google.maps.Animation.DROP);
  		});
		
		return buttonElement;
	}

	function initialize() {
		var latlng = starparty;
		var myOptions = {
		zoom: 13,
		mapTypeControlOptions: {
    		style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
    	},
    	center: latlng,
    	mapTypeId: google.maps.MapTypeId.ROADMAP
    	};
    
    var markerDennis = new google.maps.Marker({
		position: dennis,
		animation: google.maps.Animation.DROP,
		title:"Eat at Dennis!"
	});
	var markerNiels = new google.maps.Marker({
		position: niels,
		animation: google.maps.Animation.DROP,
		title:"Starparty at Niels!"
	});
	var markerStarparty = new google.maps.Marker({
		position: starparty,
		animation: google.maps.Animation.DROP,
		icon: 'marker_star.png',
		title:"Starparty!"
	});
    
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(createLocationButton('Eat at Dennis', dennis, markerDennis, map));
	map.controls[google.maps.ControlPosition.RIGHT_TOP].push(createLocationButton('Starparty at Niels', niels, markerNiels, map));
	map.controls[google.maps.ControlPosition.RIGHT_TOP].push(createLocationButton('Starparty by Niels', starparty, markerStarparty, map));
	
	// To add the marker to the map, call setMap();
	markerDennis.setMap(map);
	markerNiels.setMap(map);
	markerStarparty.setMap(map);

}

</script>
</head>
<body onload="initialize()">
  <div id="map_canvas" style="width:100%; height:100%"></div>
</body>
</html>