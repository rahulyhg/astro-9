window.addEventListener('load', initJigsawPuzzle, false);

//config vars
var pieces;

//script vars
var pictureSelectElement;
var picture;
var canvasArray = new Array()
var corPieces;
var puzzleDiv;
var dragSrcEl = null;
var textInputPieces;


function initJigsawPuzzle(){
	pictureSelectElement = document.getElementById('picture_select');
	
	pictureSelectElement.addEventListener('change', getPicture, false);
	//pictureSelectElement.addEventListener('blur', selectPicture, false);
	
	puzzleDiv = document.getElementById('puzzle_inner');
	textInputPieces = document.getElementById('pieces_count');
	pieces = textInputPieces.value;
	
	var resetButton = document.getElementById('reset_button');
	resetButton.addEventListener('mouseup' , resetPuzzle, false);
}

function handleDragStart(e){
	this.style.opacity = '0.4';
		
	dragSrcEl = this;
	
	e.dataTransfer.effectAllowed = 'move';
 	e.dataTransfer.setData('Text', this.id);
}
	
function handleDragEnter(e){
	//alert('handleDragEnter');
	if(e.preventDefault){
		e.preventDefault(); // Necessary. Allows us to drop.
	}
	
	//this.addClassName('over');
}

function handleDragOver(e){
	//alert('handleDragOver');
	if(e.preventDefault){
		e.preventDefault(); // Necessary. Allows us to drop.
	}
	
	e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
	
	return false;
}
	
function handleDragLeave(e){
	//alert('handleDragLeave');
	this.removeClassName('over');
}

function handleDrop(e){
	//alert('handleDrop');
	// this / e.target is current target element.
	
	if(e.stopPropagation){
		e.stopPropagation(); // stops the browser from redirecting.
	}
	
	// See the section on the DataTransfer object.
	
	if(dragSrcEl != this){
		// Set the source column's HTML to the HTML of the columnwe dropped on.
		dragSrcEl.appendChild(this.getElementsByTagName('canvas')[0]);
		while(this.firstChild){
			this.removeChild(this.firstChild);
		}
		//alert(document.getElementById(e.dataTransfer.getData('Text')).innerHTML);
		var canvasElement = document.getElementById(e.dataTransfer.getData('Text')).getElementsByTagName('canvas')[0];
		//alert('canvasElement: ' + canvasElement);
		this.appendChild(canvasElement);
	}
	
	return false;
}

function handleDragEnd(e){
	//alert('handleDragEnd');
	// this/e.target is the source node.
	
	this.style.opacity = '1';
		
	for(var i=0;i<canvasArray.length;i++){
		canvasArray[i].removeClassName('over');
	}
	
	if(checkPuzzle()){
		alert('puzzle solved!!!');
	}
}

function checkPuzzle(){
	var piecesElements = puzzleDiv.getElementsByClassName('piece');
	
	//alert('cur: ' + piecesElements[0].getElementsByTagName('canvas')[0].id + ' orig: ' +  canvasArray[0].id);
	
	var returnValue = true
	for(var i=0;i<canvasArray.length;i++){
		if(parseInt(piecesElements[i].getElementsByTagName('canvas')[0].id) == parseFloat(canvasArray[i].id)){
			returnValue = true;
		}
		else {
			return false;
		}
	}
	
	return returnValue;
}

function resetPuzzle(){
	pieces = textInputPieces.value;
	
	loadPuzzle();
}

function getPicture(){
	picture = new Image();
	
	picture.onload = loadPuzzle;
	picture.src = this.value;
}

function loadPuzzle(){
	canvasArray = new Array();
	puzzleDiv.style.width = picture.width + 'px';
	puzzleDiv.style.height = picture.height + 'px';
	//alert('width: ' + picture.width + ' height: ' + picture.height);
	if(picture.width > picture.height){
		var ratio = picture.width / picture.height;
	}
	else if(picture.height > picture.width){
		var ratio = picture.height / picture.width;
	}
		
	var pieceSidePixels = Math.sqrt((picture.height * picture.width) / pieces);
		
	var columns = Math.round(picture.width / pieceSidePixels);
	
	var rows = Math.round(picture.height / pieceSidePixels);
	
	//alert('Rows: ' + rows + ' columns: ' + columns); // 5 x 8
		
	corPieces = rows * columns;
	
	var rowNo = 0;
	var colNo = 0;
	for(var i=0;i<corPieces;i++){
		var canvasElement = null;
		var wrapperDivElement = document.createElement('div');
		canvasElement = document.createElement('canvas');
		canvasElement.id = i + '_piece';
		canvasElement.width = picture.width / columns;
		canvasElement.height = picture.height / rows;
		wrapperDivElement.id = i + '_wrapper_piece';
		wrapperDivElement.draggable = true;
		wrapperDivElement.addClassName('piece');
		wrapperDivElement.style.width = canvasElement.width + 'px';
		wrapperDivElement.style.height = canvasElement.height + 'px';
		wrapperDivElement.appendChild(canvasElement);
		
		//painting picture
		var ctx = canvasElement.getContext('2d');
		ctx.drawImage(picture, rowNo * canvasElement.width, colNo * canvasElement.height, canvasElement.width, canvasElement.height, 0, 0, canvasElement.width, canvasElement.height);
		
		if(rowNo >= columns-1){
			rowNo = 0
			colNo ++;
		}
		else {
			rowNo ++;
		}
		
		canvasArray.push(wrapperDivElement);
	}
		
	while(puzzleDiv.firstChild){
		puzzleDiv.removeChild(puzzleDiv.firstChild);
	}
	
	var canvasArrayTemp = canvasArray.slice(0);
	
	for(var i=0;i<canvasArray.length;i++){
		var indexNo = Math.floor(Math.random() * (canvasArrayTemp.length));
		//alert('i: ' + i + ' Arraylength: ' + canvasArrayTemp.length + ' indexNo ' + indexNo + ' canvasArray.length: ' + canvasArray.length);
		var divElements = canvasArrayTemp.splice(indexNo, 1);
		//for(var j=0;j<canvasArrayTemp.length;j++){
		//	alert('j: ' + j + ' i: ' + indexNo + ' content: ' + canvasArrayTemp[j]);
		//}
		canvasArrayTemp.sort();
		//alert(canvasArrayTemp.toString());
		
		divElements[0].addEventListener('dragstart', handleDragStart, false);
		divElements[0].addEventListener('dragover', handleDragOver, false);
		divElements[0].addEventListener('dragenter', handleDragEnter, false);
		divElements[0].addEventListener('dragleave', handleDragLeave, false);
		divElements[0].addEventListener('drop', handleDrop, false);
		divElements[0].addEventListener('dragend', handleDragEnd, false);
		puzzleDiv.appendChild(divElements[0]);
	}
}