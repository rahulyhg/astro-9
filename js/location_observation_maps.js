addEventHandler(window, 'load', initObservationLocation);

//script vars
var observationLocationMap;
var geocoder;

function initObservationLocation(){
	geocoder = new google.maps.Geocoder();
	var defaultLocation = new google.maps.LatLng(52.3, 6.6);
	var mapOptions = {
		zoom: 8,
    	center: defaultLocation,
    	mapTypeId: google.maps.MapTypeId.HYBRID
    };
	
	observationLocationMap = new google.maps.Map(document.getElementById('location_observation'), mapOptions);
	
	var requestLocation = createRequest();
	
	if(!requestLocation){
		alert('Request could not be created');
		return;
	}
	
	var observationId = getQueryVariable('observation_id');
	
	var url = 'php_scripts/getLocation.php?observation_id=' + observationId;
	
	requestLocation.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			parseLocation(this.responseXML);
		}
	}
	
	requestLocation.open("GET", url, true);
	requestLocation.send(null);
}

function parseLocation(XMLdoc){
	var locationElements = XMLdoc.getElementsByTagName('location');
	
	var locationArray = new Array();
	for(var i=0;i<locationElements.length;i++){
		locationArray.push(
			{'latitude': locationElements[i].getAttribute('latitude'),
			'longitude': locationElements[i].getAttribute('longitude'),
			'accuracy': locationElements[i].getAttribute('accuracy'),
			'avatar_url': locationElements[i].getElementsByTagName('user')[0].getAttribute('avatar_url')}
		);
	}
	//alert(locationElements.length);
	setLocation(locationArray);
}

function setLocation(locationArray){
	var markerArray = new Array();
	for(var i=0;i<locationArray.length;i++){
		geocoder.geocode({latLng: new google.maps.LatLng(locationArray[i]['latitude'], locationArray[i]['longitude'])}, function(results, status){
			if(status == google.maps.GeocoderStatus.OK){
				var observationId = getQueryVariable('observation_id');
				
				var url = 'php_scripts/updateLocation.php?observation_id=' + observationId
				
				for(var j=0;j<results[0]['address_components'].length;j++){
					url += '&' + results[0]['address_components'][j]['types'][0] + '=' + results[0]['address_components'][j]['long_name']; 
					//alert(results[0]['address_components'][j]['types'] + ': ' + results[0]['address_components'][j]['long_name'])
				}
				var requestUpdateAdress = createRequest();
				//alert(url);
				requestUpdateAdress.onreadystatechange = function(){
					if(this.readyState == 4 && this.status == 200){
						//alert(this.responseText);
					}
				}
				
				requestUpdateAdress.open("GET", url, true);
				requestUpdateAdress.send(null);
			}
		});
		//alert(markerArray[i]['latitude'] + ' - ' + locationArray[i]['longitude']);
		markerArray.push(new Array(new google.maps.Marker({
			position: new google.maps.LatLng(locationArray[i]['latitude'], locationArray[i]['longitude']),
			icon: 'images/avatars/' + locationArray[i]['avatar_url'],
			animation: google.maps.Animation.DROP,
			title: 'Observation: ' + locationArray[i]['accuracy']
		}), new google.maps.Circle({
			strokeColor: "#0000FF",
			strokeOpacity: 0.4,
			strokeWeight: 1,
			fillColor: "#0000FF",
			fillOpacity: 0.15,
			center: new google.maps.LatLng(locationArray[i]['latitude'], locationArray[i]['longitude']),
			radius: parseFloat(locationArray[i]['accuracy'])
		})));
		if(locationArray.length == 1){
			observationLocationMap.setCenter(new google.maps.LatLng(locationArray[i]['latitude'], locationArray[i]['longitude']));
			observationLocationMap.setZoom(15);
		}
	}
	
	for(var i=0;i<locationArray.length;i++){
		markerArray[i][0].setMap(observationLocationMap);
		markerArray[i][1].setMap(observationLocationMap);
	}
}

function alertAdress(){
	
}