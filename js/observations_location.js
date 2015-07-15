addEventHandler(window, 'load', initObservationLocation){


//config vars


//script vars


function initObservationLocation(){
	var aElements = document.getElementsByTagName('a');
	
	for(var i=0;i<aElements.length;i++){
		if(aElements[i].getElementsByTagName('img')[0].getAttribute('src') == 'images/link_maps.png'){
			aElememts[i].onmouseover = displayMap;
		}
	}
}

function displayMap(){
	
}