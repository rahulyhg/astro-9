addEventHandler(window, 'load', initLocationsMap);

//config vars


//script vars
var locationsMap;
var locationsArray = new Array();
var buttonArray = new Array();
var markerArray = new Array();//ivm timeout
var startDate;
var endDate;
var locationsObject;

function initLocationsMap(){
	var defaultLocation = new google.maps.LatLng(50, 7);
	var mapOptions = {
		zoom: 4,
    	center: defaultLocation,
    	mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	
	locationsMap = new google.maps.Map(document.getElementById('locations_map'), mapOptions);
	
	locationsObject = new Locations(locationsMap, $('observation_counter'), [$('icon_user'), $('icon_device'), $('icon_object_type'), $('icon_device_type')]);
	
	initMenu();
	requestLocations();
}

function initMenu(){
	$('start_date').onmouseup = function(){
		$('start_date_input').setAttribute('class', 'active');
		$('end_date_input').setAttribute('class', 'hidden');
	}
	
	$('end_date').onmouseup = function(){
		$('start_date_input').setAttribute('class', 'hidden');
		$('end_date_input').setAttribute('class', 'active');
	}
	
	buttonArray.push([$('day_start'), $('day_start_min'), $('day_start_plus')]);
	buttonArray.push([$('month_start'), $('month_start_min'), $('month_start_plus')]);
	buttonArray.push([$('year_start'), $('year_start_min'), $('year_start_plus')]);
	buttonArray.push([$('hour_start'), $('hour_start_min'), $('hour_start_plus')]);
	buttonArray.push([$('minute_start'), $('minute_start_min'), $('minute_start_plus')]);
	buttonArray.push([$('day_end'), $('day_end_min'), $('day_end_plus')]);
	buttonArray.push([$('month_end'), $('month_end_min'), $('month_end_plus')]);
	buttonArray.push([$('year_end'), $('year_end_min'), $('year_end_plus')]);
	buttonArray.push([$('hour_end'), $('hour_end_min'), $('hour_end_plus')]);
	buttonArray.push([$('minute_end'), $('minute_end_min'), $('minute_end_plus')]);
	
	for(var i=0;i < buttonArray.length;i++){
		addEventHandler(buttonArray[i][0], 'onblur', dateChange);
		addEventHandler(buttonArray[i][1], 'mouseup', dateChange);
		addEventHandler(buttonArray[i][2], 'mouseup', dateChange);
	}
	
	
	updateDate();
	//addEventHandler($('update_range'), 'mouseup', updateRange);
	$('update_range').onmouseup = function(){
		locationsObject.updateRange();
	}
}

function dateChange(){
	for(var i=0;i < buttonArray.length;i++){
		if(this == buttonArray[i][1]){
			buttonArray[i][0].value--;
			if(checkValidDate()){
				updateDate();
			}
			else {
				buttonArray[i][0].value++;
			}
		}
		else if(this == buttonArray[i][2]){
			buttonArray[i][0].value++;
			if(checkValidDate()){
				updateDate();
			}
			else {
				buttonArray[i][0].value--;
			}
		}
	}
}

function checkValidDate(type){
	var tempStartDate = new Date($('year_start').value, $('month_start').value-1, $('day_start').value, $('hour_start').value, $('minute_start').value, 0);
	var tempEndDate = new Date($('year_end').value, $('month_end').value-1, $('day_end').value, $('hour_end').value, $('minute_end').value, 0);
	//alert(tempStartDate.getDate() + ' == ' + $('day_start').value + ' && ' + tempStartDate.getMonth() + ' == ' + $('month_start').value + ' && ' + tempStartDate.getFullYear() + ' == ' + $('year_start').value);
	
	switch(type){
		case 'update':
			if(tempStartDate < tempEndDate){
				return true;
			}
			else {
				//alert('Start date can\'t be lower as end date.');
				return false;
			}
			break
		default:
 			if(tempStartDate.getDate() == $('day_start').value && tempStartDate.getMonth() == $('month_start').value-1 && tempStartDate.getFullYear() == $('year_start').value && tempStartDate.getHours() == $('hour_start').value && tempStartDate.getMinutes() == $('minute_start').value
 			&& tempEndDate.getDate() == $('day_end').value && tempEndDate.getMonth() == $('month_end').value-1 && tempEndDate.getFullYear() == $('year_end').value && tempEndDate.getHours() == $('hour_end').value && tempEndDate.getMinutes() == $('minute_end').value){
 				return true;
 			}
 			else {
 				return false;
 			}
			break;
	}	
}


function updateDate(){
	if(checkValidDate()){
		startDate = new Date($('year_start').value, $('month_start').value-1, $('day_start').value,  $('hour_start').value, $('minute_start').value, 0);
		endDate = new Date($('year_end').value, $('month_end').value-1, $('day_end').value, $('hour_end').value, $('minute_end').value, 0);
	}
	//alert(startDate + ' | ' + endDate);
}


function requestLocations(){
	var requestLocation = createRequest();
	
	if(!requestLocation){
		alert('Request could not be created');
		return;
	}
	
	if(!checkValidDate('update')){
		alert('Start date can\'t be lower as end date.');
		return;
	}
	
	var startMonth = startDate.getMonth()+1;
	var endMonth = endDate.getMonth()+1;
	
	var url = 'php_scripts/getLocation.php?start_time=' + startDate.getFullYear() + '-' + startMonth + '-' + startDate.getDate() + ' ' + startDate.getHours() + ':' + startDate.getMinutes() + '&end_time=' + endDate.getFullYear() + '-' + endMonth + '-' + endDate.getDate() + ' ' + endDate.getHours() + ':' + endDate.getMinutes();
	//alert(url);
	requestLocation.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			//parseLocation(this.responseXML);
			locationsObject.parseNewMarkers(this.responseXML);
			locationsObject.setMap();
			
			//icon buttons
			$('icon_user').onmouseup = function(e){
				locationsObject.switchIcon(e);
			}
			$('icon_device').onmouseup = function(e){
				locationsObject.switchIcon(e);
			}
			$('icon_object_type').onmouseup = function(e){
				locationsObject.switchIcon(e);
			}
			$('icon_device_type').onmouseup = function(e){
				locationsObject.switchIcon(e);
			}
		}
	}
	
	requestLocation.open("GET", url, true);//POST van maken!
	requestLocation.send(null);
}


//Locations Object
function Locations(map, counterElement, iconTypeButtons){
	var defaultIcon = 'avatar_url';
	var defaultPath = 'images/avatars/';
	
	if(iconTypeButtons.length > 0){
		this.iconTypeButtons = iconTypeButtons;
	}
	
	this.iconTypeButtons[0].setAttribute('class', 'active');// niet netjes met via funtie
	this.map = map;
	
	this.iconPath = defaultPath;
	this.iconType = defaultIcon;
	this.counterElement = counterElement;
}

Locations.prototype.parseNewMarkers = function(XMLdoc){
 if(typeof XMLdoc == 'object'){
		var locationElements = XMLdoc.getElementsByTagName('location');
		
		this.locationsArray = new Array();

		for(var i=0;i<locationElements.length;i++){
			if(locationElements[i].getElementsByTagName('object')[0].getAttribute('object_thumb_url')){
				var objectThumbUrl = locationElements[i].getElementsByTagName('object')[0].getAttribute('object_thumb_url');
			}
			else {
				var objectThumbUrl = '';
			}
			var locationInfo = new Object();
			
			locationInfo.latitude = locationElements[i].getAttribute('latitude');
			locationInfo.longitude = locationElements[i].getAttribute('longitude');
			locationInfo.accuracy = locationElements[i].getAttribute('accuracy');
			locationInfo.dateObject = new Date(locationElements[i].getAttribute('datetime'));
			locationInfo.first_name = locationElements[i].getElementsByTagName('user')[0].getAttribute('first_name'),
			locationInfo.surname = locationElements[i].getElementsByTagName('user')[0].getAttribute('last_name');
			locationInfo.avatar_url = locationElements[i].getElementsByTagName('user')[0].getAttribute('avatar_url');
			locationInfo.magnification = locationElements[i].getElementsByTagName('user')[0].getAttribute('magnification');
			locationInfo.object_id = locationElements[i].getElementsByTagName('object')[0].getAttribute('object_id');
			locationInfo.object_name = locationElements[i].getElementsByTagName('object')[0].getAttribute('object_name');
			locationInfo.object_thumb_url = objectThumbUrl;
			locationInfo.object_type_name = locationElements[i].getElementsByTagName('object')[0].getAttribute('object_type_name');
			locationInfo.object_type_thumb_url = locationElements[i].getElementsByTagName('object')[0].getAttribute('object_type_thumb_url');
			locationInfo.object_type_thumb_maps_url = locationElements[i].getElementsByTagName('object')[0].getAttribute('object_type_thumb_maps_url');
			locationInfo.device_name = locationElements[i].getElementsByTagName('device')[0].getAttribute('device_name');
			locationInfo.device_thumb_url = locationElements[i].getElementsByTagName('device')[0].getAttribute('thumb_url');
			locationInfo.device_thumb_maps_url = locationElements[i].getElementsByTagName('device')[0].getAttribute('thumb_maps_url');
			locationInfo.device_type_thumb_maps_url = locationElements[i].getElementsByTagName('device')[0].getAttribute('thumb_type_maps_url');
			
			var pElements = locationElements[i].getElementsByTagName('notes')[0].getElementsByTagName('p');
			
			if(pElements.length > 0){
				var newpElements = [];
				for(j in pElements){
					var node = pElements[j].firstChild;
					while(node){
						switch(node.nodeType){
							case 1:
								switch(node.nodeName){
									case 'br':
										if(newpElements[j] != undefined){
											newpElements[j] += '<br />';
										}
										else {
											newpElements[j] = '<br />';
											alert(newpElements[j]);
										}
										break;
								}
								break;
							case 3:
								if(newpElements[j] != undefined){
									newpElements[j] += node.nodeValue;
								}
								else {
									newpElements[j] = node.nodeValue;
								}
								break;
						}
						
						node = node.nextSibling;
					}
				}
				locationInfo.notespArray = newpElements;
			}
			
			this.locationsArray.push(locationInfo);
		}
		
		//alert(typeof this.counterElement);
		if(typeof this.counterElement == 'object'){
			while(this.counterElement.firstChild){
				this.counterElement.removeChild(this.counterElement.firstChild);
			}
			this.counterElement.appendChild(document.createTextNode(this.locationsArray.length));
		}
	}
}

Locations.prototype.setMap = function(){
	this.fitViewport();
	this.markerArray = new Array();

	for(var i=0;i < this.locationsArray.length;i++){
		var content = '<div class="maps_info_window">' +
			'<h4><a href="?p=astronomical_object&object_id=' + this.locationsArray[i].object_id + '">';
		if(this.locationsArray[i].object_thumb_url){
			content += '<img class="object_thumb" src="' + this.locationsArray[i].object_thumb_url + '" alt="thumbnail" />'
		}
		var hours = this.locationsArray[i].dateObject.getHours();
		var minutes = this.locationsArray[i].dateObject.getMinutes();
		
		if(hours < 10){
			hours = '0' + hours;
		}
		if(minutes < 10){
			minutes = '0' + minutes;
		}
		
		content += this.locationsArray[i].object_name + '</a><br /><span>' + this.locationsArray[i].dateObject.getDate() + ' ' + getMonthString(this.locationsArray[i].dateObject.getMonth()+1, 'full', 'en') + ' ' + this.locationsArray[i].dateObject.getFullYear() + ' (' + hours + ':' + minutes + ')</span></h4>' +
			'<table>' +
				'<tr>' +
					'<td>Observer</td>' +
					'<td>' + this.locationsArray[i].first_name + ' ' + this.locationsArray[i].surname + '</td>' +
				'</tr>' +
				'<tr>' +
					'<td>Obect Type</td>' +
					'<td>' + this.locationsArray[i].object_type_name + '</td>' +
				'</tr>' +
				'<tr>' +
					'<td>Device</td>' +
					'<td>' + this.locationsArray[i].device_name + '</td>' +
				'</tr>' +
				'<tr>' +
					'<td>Magnification</td>' +
					'<td>' + this.locationsArray[i].magnification + '</td>' +
				'</tr>' +
			'</table>';
		if(this.locationsArray[i].notespArray){
			content += '<h5>Notes</h5>';
			for(j in this.locationsArray[i].notespArray){
				content += '<p>';
				//alert(this.locationsArray[i].notespArray[j]);
				content += this.locationsArray[i].notespArray[j];
				content += '</p>';
			}
		}
		content += '</div>';
		//alert(markerArray[i]['latitude'] + ' - ' + locationArray[i]['longitude']);
		this.markerArray.push(new Array(new google.maps.Marker({
			position: new google.maps.LatLng(this.locationsArray[i].latitude, this.locationsArray[i].longitude),
			icon: this.iconPath + this.locationsArray[i][this.iconType],
			/*animation: google.maps.Animation.DROP,*/
			title: this.locationsArray[i].object_name
		}), new google.maps.InfoWindow({
			content: content,
			maxWidth: 300
		}), new google.maps.Circle({
			strokeColor: "#0000FF",
			strokeOpacity: 0.4,
			strokeWeight: 1,
			fillColor: "#0000FF",
			fillOpacity: 0.15,
			center: new google.maps.LatLng(this.locationsArray[i].latitude, this.locationsArray[i].longitude),
			radius: parseFloat(this.locationsArray[i].accuracy)
		})));
		if(this.locationsArray.length == 1){
			this.map.setCenter(new google.maps.LatLng(this.locationsArray[i].latitude, this.locationsArray[i].longitude));
			this.map.setZoom(15);
		}
	}
	
	for(var i=0;i < this.markerArray.length; i++){
		var object = this;
		google.maps.event.addListener(this.markerArray[i][0], 'click', function(){
 			object.displayInfoWindow(this);
		});
	}
	
	var interval = 100;
	//alert(window.setTimeout);
	for(var i=0;i<this.locationsArray.length;i++){
		//thisReferencingCallback
		//setTimeout(thisReferencingCallback(this, function(i){this.markerArray[i][0].setMap(this.map);}), i * interval);
		//setTimeout.call(obj, function(){this.markerArray[i][0].setMap(this.map);}, i * interval);
		//setTimeout(function(thisObj){ thisObj.markerArray[i][2].setMap(thisObj.map); }, i * interval, this);
		//setTimeout('this.obj.markerArray[i][0].setMap(this.obj.map)', i * interval);
		//setTimeout(function(){this.obj.markerArray[i][2].setMap(this.obj.map)}, i * interval);
		this.markerArray[i][0].setMap(this.map);
		this.markerArray[i][2].setMap(this.map);
	}
	//alert('waar zijn de icons?');
}

Locations.prototype.displayInfoWindow = function(marker){
	for(var i=0;i < this.markerArray.length; i++){
		this.markerArray[i][1].close();
		if(marker == this.markerArray[i][0]){
			this.markerArray[i][1].open(this.map, this.markerArray[i][0]);
		}
	}
}

Locations.prototype.switchIcon = function(e){
	var path;
	var variable;
	switch(e.target.getAttribute('id')){
		case 'icon_user':
			variable = 'avatar_url'
			path = 'images/avatars/';
			break;
		case 'icon_device':
			variable = 'device_thumb_maps_url'
			path = 'images/thumbs/';
			break;
		case 'icon_device_type':
			variable = 'device_type_thumb_maps_url'
			path = 'images/thumbs/';
			break;
		case 'icon_object_type':
			variable = 'object_type_thumb_maps_url'
			path = 'images/thumbs/';
			break;
		default:
			variable = 'avatar_url'
			path = 'images/avatars/';
			break;
	}
	for(i in this.iconTypeButtons){
		if(this.iconTypeButtons[i] == e.target){
			this.iconTypeButtons[i].setAttribute('class', 'active');
		}
		else {
			this.iconTypeButtons[i].removeAttribute('class');
		}
	}
	
	this.iconPath = path;
	this.iconType = variable;
	for(var i=0; i < this.markerArray.length;i++){
		this.markerArray[i][0].setIcon(path + this.locationsArray[i][variable]);
	}
}

Locations.prototype.clearOverlays = function(){
	if(this.markerArray) {
		for(i in this.markerArray) {
			this.markerArray[i][0].setMap(null);
			this.markerArray[i][2].setMap(null);
		}
	}
}

Locations.prototype.updateRange = function(){
	this.clearOverlays();
	requestLocations();
}

Locations.prototype.fitViewport = function(){
	var south = this.locationsArray[0].latitude;
	var west = this.locationsArray[0].longitude;
	var north = this.locationsArray[0].latitude;
	var east = this.locationsArray[0].longitude;
	
	for(i in this.locationsArray){
		if(this.locationsArray[i].latitude > south){
			south = this.locationsArray[i].latitude;
		}
		if(this.locationsArray[i].latitude < north){
			north = this.locationsArray[i].latitude;
		}
		if(this.locationsArray[i].longitude > east){
			east = this.locationsArray[i].longitude;
		}
		if(this.locationsArray[i].longitude < west){
			west = this.locationsArray[i].longitude;
		}
	}
	var southWest = new google.maps.LatLng(south, west);
	var northEast = new google.maps.LatLng(north, east);
	var bounds = new google.maps.LatLngBounds(southWest, northEast);
	
	this.map.fitBounds(bounds);
}