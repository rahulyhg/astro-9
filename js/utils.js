//versie 1.0
function createRequest() {
	try {
		
		request = new XMLHttpRequest();
		
	} catch(e){
		try {
			
			request = new ActiveXObject("Msxm12.XMLHTTP");
			
		} catch(e){
			try {
			
				request = new ActiveXObject("Microsoft.XMLHTTP");
				
			} catch(e){
				
				alert("Request object could not be created. Error: " + e);
				request = null;
			}
		}
	}
	
	return request;
	
}


//set of get de opacity waarde van een element
//versie 2.0, 8 april 2009
//verbeteringspunten;
// - kijken of het object een html element is
function opacValue(input, action, inputValue){
	if(typeof input == 'string'){
		if(typeof document.getElementById(input) == 'object'){
			var object = document.getElementById(input);
		}
		else{
			alert('function opacValue says: "Wrong input"');
		}
	}
	else{
		var object = input;
	}
	
	if(action == 'set'){
		if(object.style.filter != undefined){
			object.style.filter = 'alpha(opacity=' + inputValue * 100 + ')';
		}
		else {
			object.style.opacity = inputValue;
		}
	}
	else {
		if(object.style.filter != undefined){
			//IE
			var opacString = object.style.filter.substr(14);
			return parseFloat(opacString) / 100;
		}
		else {
			return object.style.opacity;
		}
	}
}

//adeventlistener
//versie 1.0, 10 april 2009
function addEventHandler(object, eventName, handler, capturing){
	if(!capturing){
		capturing = false;	
	}
	if(document.attachEvent){
		//IE
		object.attachEvent('on' + eventName, handler);
	}
	else if(document.addEventListener){
		//DOM Level 2 browsers
		object.addEventListener(eventName, handler, capturing);
	}
}

function returnDigits(i, digits){
	if(!digits){
		digits = 2;
	}
	var regExpressionDigits = new RegExp("\\d{" + digits + "}$");
	
	var iDigits = '';
	for(var j=0;j<digits;j++){
		iDigits += '0';
	}
	//alert(iDigits);
	i = i.toString();
	i = iDigits + i;
	i = regExpressionDigits.exec(i);
	
	return i;
}

//voegt het parent object toe aan de parent property van de Child
//versie 1.0, 31 mei 2009
function addChildReference(object, childName, childOb){
   object[childName] = childOb;
   childOb.parent = object;
}


//versie 1.1, 18 mei 2010
//2e level diep childnodes naar classname zoeken toegevoegd
function getElementsByClassName(node, classname){
	if(document.getElementsByClassName){
		return node.getElementsByClassName(classname);
	}
	else {
		var htmlClassnameElements = new Array();
		var childNode = node.firstChild;
		while(childNode){
			if(childNode.className == classname){
				htmlClassnameElements.push(childNode);
			}
			if(childNode.firstChild){
				var childChildNode = childNode.firstChild;
				while(childChildNode){
					if(childChildNode.className == classname){
						htmlClassnameElements.push(childChildNode);
					}
					childChildNode = childChildNode.nextSibling;
				}
			}
			childNode = childNode.nextSibling;
		}
		return htmlClassnameElements;
	}
}

function $(){
	if(arguments.length > 1){
		var elementsArray = new Array();
		for(var i=0;i<arguments.length;i++){
			elementsArray.push(document.getElementById(arguments[i]));
		}
		return elementsArray;
	}
	else {
		return document.getElementById(arguments[0]);
	}
}

//geeft de maand in een nl string terug van 3 karakters
//verbeteringspunten;
// - engels
// - aantal karakters
//versie 1.0 14 april 2010
function getMonthString(number, format, lang){
	var month;
	switch(number){
		case 1:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'january';
				}
				else {
					month = 'januari';
				}
			}
			else {
				month = 'jan';
			}
			break;
		case 2:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'february';
				}
				else {
					month = 'februari';
				}
			}
			else {
				month = 'feb';
			}
			break;
		case 3:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'march';
				}
				else {
					month = 'maart';
				}
			}
			else {
				month = 'mrt';
			}
			break;
		case 4:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'april';
				}
				else {
					month = 'april';
				}
			}
			else {
				month = 'apr';
			}
			break;
		case 5:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'may';
				}
				else {
					month = 'mei';
				}
			}
			else {
				month = 'mei';
			}
			break;
		case 6:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'june';
				}
				else {
					month = 'juni';
				}
			}
			else {
				month = 'jun';
			}
			break;
		case 7:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'july';
				}
				else {
					month = 'juli';
				}
			}
			else {
				month = 'jul';
			}
			break;
		case 8:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'august';
				}
				else {
					month = 'augustus';
				}
			}
			else {
				month = 'aug';
			}
			break;
		case 9:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'september';
				}
				else {
					month = 'september';
				}
			}
			else {
				month = 'sep';
			}
			break;
		case 10:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'october';
				}
				else {
					month = 'oktober';
				}
			}
			else {
				month = 'okt';
			}
			break;
		case 11:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'november';
				}
				else {
					month = 'november';
				}
			}
			else {
				month = 'nov';
			}
			break;
		case 12:
			if(format == 'full'){
				if(lang = 'en'){
					month = 'december';
				}
				else {
					month = 'december';
				}
			}
			else {
				month = 'dec';
			}
			break;
	}
	return month;
}


//checkt welke muisknop is ingedrukt
//verbeteringspunten;
//versie 1.0 11 mei 2010
function checkMouseButton(mEvent){
	if(!mEvent){
		var mouseEvent = window.event;
	}
	else {
		mouseEvent = mEvent;
	}
	
	if(!mEvent && mouseEvent.button == 1 || mouseEvent.button == 0){
		return true;
	}
	else {
		return false;	
	}
}

function displayFlashElements(display){
	var objectElements = document.getElementsByTagName('object');
	
	for(var i=0;i<objectElements.length;i++){
		objectElements[i].style.display = display;
	}
}

//
function getElementsByTagNames(elementsString, rootElement){
	if(!rootElement){
		rootElement = document;
	}
	else if(typeof rootElement == 'string'){
		rootElement = document.getElementById(rootElement);
	}
	else if(typeof rootElement != 'object'){
		alert('Wrong input rootElement!');
	}
	
	var elementStringArray = elementsString.split(',')
	
	var returnElementArray = new Array();
	for(var i=0;i<elementStringArray.length; i++){
		var elements = rootElement.getElementsByTagName(elementStringArray[i]);
		for(var j=0;j<elements.length;j++){
			returnElementArray.push(elements[j]);
		}
	}
	
	return returnElementArray;
}

function collectionToArray(col) {
    a = new Array();
    for (i=0; i<col.length; i++)
        a[a.length] = col[i];
    return a;
}

function getQueryVariable(variable){
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if(pair[0] == variable){
			return pair[1];
		}
	}
	return(false);
}

Element.prototype.hasClassName = function(name) {
	return new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)").test(this.className);
};

Element.prototype.addClassName = function(name) {
	//alert('prototype.addClassName');
	if(!this.hasClassName(name)) {
		this.className = this.className ? [this.className, name].join(' ') : name;
	}
};

Element.prototype.removeClassName = function(name) {
	//alert('prototype.removeClassName');
	if(this.hasClassName(name)) {
		var c = this.className;
		this.className = c.replace(new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)", "g"), "");
	}
};