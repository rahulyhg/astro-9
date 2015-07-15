window.addEventListener('load', initGraph, false);

//config vars
var indent = 40;
var columnHeight = 210;
var columnWidth = 40;
var columnColumnWidth = 440 / 12;
var columnBottom = 230;
var rows = 5;
var roundingFactor = 10;
var graphType = 'column';//default
var canvasWidth = 500;
var canvasHeight = 250;

//script vars
var ctx;
var mcArray = new Array();
var steps;
var stepValue;
var percentage = 0;
var intervalHandler;

function initGraph(){
	ctx = document.getElementById('observations_graph').getContext('2d');
	ctx.strokeStyle = 'rgb(255, 255, 255)';
	ctx.fillStyle = 'rgb(255, 255, 255)';
	//makeLayout();
	
	getGraphData();
}

function makeLayout(){
	ctx.save();
	ctx.moveTo(39.5, 20);
	ctx.lineTo(39.5, 230.5);
	ctx.lineTo(480, 230.5);
	
	ctx.stroke();
	
	ctx.font = "11px 'Trebuchet MS'";
	ctx.textAlign = 'center';
	ctx.textBaseline = 'middle';
	
	for(var i=0; i<12; i++){
		var month = undefined;
		
		switch(i){
			case 0:
				month = 'jan';
				break;
			case 1:
				month = 'feb';
				break;
			case 2:
				month = 'mar';
				break;
			case 3:
				month = 'apr';
				break;
			case 4:
				month = 'may';
				break;
			case 5:
				month = 'jun';
				break;
			case 6:
				month = 'jul';
				break;
			case 7:
				month = 'aug';
				break;
			case 8:
				month = 'sep';
				break;
			case 9:
				month = 'oct';
				break;
			case 10:
				month = 'nov';
				break;
			case 11:
				month = 'dec';
				break;
		}
		if(graphType == 'line'){
			ctx.fillText(month, indent+i*columnWidth, 240);
		}
		else if(graphType == 'column'){
			ctx.fillText(month, Math.round(indent+(i)*columnColumnWidth)+Math.round(columnColumnWidth/2), 240);
		}
	}
	ctx.restore();
}

function getGraphData(){
	var requestGraph = createRequest();
	
	if(!requestGraph){
		alert('Request could not be created');
		return;
	}
	
	if(getQueryVariable('constellation_id')){
		var url = 'php_scripts/getGraphData.php?constellation_id=' + getQueryVariable('constellation_id');
	}
	else if(getQueryVariable('user_id')){
		var url = 'php_scripts/getGraphData.php?user_id=' + getQueryVariable('user_id');
	}
	else if(getQueryVariable('object_id')){
		var url = 'php_scripts/getGraphData.php?object_id=' + getQueryVariable('object_id');
	}
	
	requestGraph.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			makeDataArray(this.responseXML);
		}
	}
	
	requestGraph.open("GET", url, true);
	requestGraph.send(null);
}

function makeDataArray(XMLdoc){
	var monthElements = XMLdoc.getElementsByTagName('month');
	
	for(var i=0;i<12;i++){
		mcArray[i] = 0;
		for(var j=0;j<monthElements.length;j++){
			if(monthElements[j].getAttribute('month') == i+1){
				mcArray[i] = monthElements[j].getAttribute('observation_count');
			}
		}
	}	
	var mcArrayTemp = mcArray.slice(0);

	mcArrayTemp.sort(sortNumber)
	
	var topValue = calculateTopValue(mcArrayTemp[0]);
	stepValue = topValue / rows;
	
	var scaleFactor = Math.floor((mcArrayTemp[0] / topValue) * (columnHeight / mcArrayTemp[0]));
	
	steps = columnHeight / rows;
		
	for(var i=0;i<mcArray.length;i++){
		mcArray[i] = mcArray[i] * scaleFactor;
	}
	//alert(mcArray.toString());
	
	drawGraph();
}

function drawGraph(){
	makeLayout();
	if(graphType == 'column'){
		ctx.save();
		ctx.translate(0, columnBottom);
		drawYaxisText();
		ctx.restore();
		intervalHandler = setInterval(drawColumn, 40);
	}
	else if(graphType == 'line'){
		ctx.save();
		ctx.translate(0, columnBottom);
		drawYaxis(true);
		ctx.restore();
		drawLine();
	}
}

function drawYaxis(drawText){
	ctx.strokeStyle = 'rgba(255, 255, 255, 0.15)';
	ctx.beginPath();
	for(var i=1;i<=rows;i++){
		ctx.moveTo(indent, (i*steps / -1)+ 0.5);
		ctx.lineTo(480, (i*steps / -1)+ 0.5);
	}
	ctx.stroke()
	if(drawText){
		drawYaxisText();
	}
	ctx.stroke();
}

function drawYaxisText(){
	ctx.textAlign = 'right';
	ctx.beginPath();
	for(var i=1;i<=rows;i++){
		ctx.fillText(i*stepValue, indent-10, (i*steps / -1)+ 0.5);
	}
	ctx.stroke();
}

function drawColumn(){
	ctx.save();
	ctx.translate(0, columnBottom);
	ctx.clearRect(indent, 0, canvasWidth-indent, canvasHeight/-1);
	drawYaxis(false);
	ctx.strokeStyle = 'rgb(255, 0, 0)';
	ctx.lineWidth = 20;
	ctx.beginPath();
	for(var i=0;i<mcArray.length;i++){
		ctx.moveTo(Math.round(indent+(i)*columnColumnWidth)+Math.round(columnColumnWidth/2), 0);
		ctx.lineTo(Math.round(indent+(i)*columnColumnWidth)+Math.round(columnColumnWidth/2), (mcArray[i] /-1) * percentage);
		//return -mcArray[i] * (percentage/=100)*(percentage-2) + 100;
	}
	ctx.stroke();
	if(percentage >= 1){
		clearInterval(intervalHandler);
	}
	percentage += 0.02;
	ctx.restore();
}

function drawLine(){
	ctx.save();
	ctx.translate(0, columnBottom);
	ctx.strokeStyle = 'rgb(255, 0, 0)';
	ctx.beginPath();
	for(var i=0;i<mcArray.length;i++){
		//alert(mcArray[i]);

		ctx.lineTo(indent+columnWidth*i, (mcArray[i] /-1)+0.5);
		//alert('mcArray[' + i + '] = ' + mcArray[i]);
		//alert((mcArray[i] /-1)+0.5);
	}
	ctx.stroke();
	ctx.restore();
}


function sortNumber(a,b){
	return b - a;
}

function calculateTopValue(obsValue){
	return (Math.ceil((Math.ceil(obsValue / rows)/roundingFactor))*roundingFactor)*rows;
}