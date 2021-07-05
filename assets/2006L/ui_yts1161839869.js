function showSheet(content)
{
	var sheet = document.getElementById('sheet');
	var sheetContent = document.getElementById('sheetContent');
	sheetContent.innerHTML = content;
	sheet.style.visibility = 'visible';
	return false;
}

function toggleVisibility(whichForm, setVisible)
{
	var newstate="none"
	if(setVisible == true) 
		newstate = ""

	//alert("element " + whichForm + " toggled to " + setVisible);
	
	if (document.getElementById)
	{
		// this is the way the standards work
		var style2 = document.getElementById(whichForm).style;
		style2.display = newstate;
	}
	else if (document.all)
	{
		// this is the way old msie versions work
		var style2 = document.all[whichForm].style;
		style2.display = newstate;
	}
	else if (document.layers)
	{
		// this is the way nn4 works
		var style2 = document.layers[whichForm].style;
		style2.display = newstate;
	}
}
	

function setInnerHTML(div_id, value)
{
	var dstDiv = document.getElementById(div_id);
	dstDiv.innerHTML = value;
}

function openPopup(url, name, height, width)
{
	newwindow=window.open(url, name,'height='+height+',width='+width);
	if (window.focus) {newwindow.focus()}
	return false;
}




function openDiv (elName) {
	var theElemenet = document.getElementById(elName);
	if (theElemenet) {
		theElemenet.style.display = "block";
	}
}
function closeDiv (elName) {
	var theElemenet = document.getElementById(elName);
	if (theElemenet) {
		theElemenet.style.display = "none";
	}
}

function showInline (elName) {
	var theElemenet = document.getElementById(elName);
	if (theElemenet) {
		theElemenet.style.display = "inline";
	}
}
function hideInline (elName) {
	var theElemenet = document.getElementById(elName);
	if (theElemenet) {
		theElemenet.style.display = "none";
	}
}


function blurElement (elName) {
	var theElement = document.getElementById(elName);
	if (theElement) {
		theElement.blur();
	}
}

function selectLink (elName) {
	var theElement = document.getElementById(elName);
	if (theElement) {
		theElement.className = "selectedNavLink";
	}
}
function unSelectLink (elName) {
	var theElement = document.getElementById(elName);
	if (theElement) {
		theElement.className = "unSelectedNavLink";
	}
}


function toggleDisplay(divName){
	var tempDiv = document.getElementById(divName);
	if (!tempDiv) {
		return;
	}  
	 if ((tempDiv.style.display=="block")||(tempDiv.style.display=="" && tempDiv.className.indexOf("hid") == 0)){
	 	tempDiv.style.display="none";
	 }
	 else {	
	 	if ((tempDiv.style.display=="none")||(tempDiv.className.indexOf("hid") != 0)){
			tempDiv.style.display="block";
		}
	 }
}

function hideDiv(divName){
	tempDiv = document.getElementById(divName);
	if (!tempDiv) {
		return;
	}
	if (tempDiv.style.display=="block"){
	     tempDiv.style.display="none";
	}
}

function showDiv(divName){
	tempDiv = document.getElementById(divName);
	if (!tempDiv) {
	  return;
	}
	if (tempDiv.style.display=="none"){
		tempDiv.style.display="block";
	 }
}


//Drop Down Menu Functions


function showDropdown() {
	toggleVisibility('myAccountDropdown',1);
	document.arrowImg.src='/img/icon_menarrwdrpdwn_down_14x14.gif';
}

function hideDropwdown() {
	toggleVisibility('myAccountDropdown',0);
	document.arrowImg.src='/img/icon_menarrwdrpdwn_regular_14x14.gif';
	tempDropdownShow = 0;
	return false;
}


function changeBGcolor(tempDiv, onOrOff) {
	if(onOrOff==1) { tempDiv.style.backgroundColor='#DDD'; tempDiv.style.cursor='pointer';tempDiv.style.cursor='hand';}
	else {tempDiv.style.backgroundColor='#FFF'}

}

function imgRollover(imgIdArr)
{
	if (navigator.userAgent.match(/Opera (\S+)/)) {
		var operaVersion = parseInt(navigator.userAgent.match(/Opera (\S+)/)[1]);
	}
	if (!document.getElementById||operaVersion <7) return;
	var i=0;
	var imgId='';
	var imgEle='';
	var imgArr=new Array;
	for (i=0;i<imgIdArr.length;i++)
	{
		if (document.getElementById(imgIdArr[i]))
		{
			imgArr.push(document.getElementById(imgIdArr[i]));
		}
	}
	var imgPreload=new Array();
	var imgSrc=new Array();
	var imgClass=new Array();
	for (i=0;i<imgArr.length;i++)
	{
		if (imgArr[i].className.indexOf('rollover')>-1)
		{
// If for some reason images are stored outside of the /img dir, this will break
			imgSrc[i]=imgArr[i].getAttribute('src');
			imgClass[i]=imgArr[i].className;
			imgPreload[i]=new Image();
			if (imgClass[i].match(/rollover (\S+)/)) 
			{
				imgPreload[i].src = '/img/'+imgClass[i].match(/rollover (\S+)/)[1]
			}
			imgArr[i].setAttribute('rsrc', imgSrc[i]);
			imgArr[i].onmouseover=function() 
			{
				this.setAttribute('src', '/img/'+this.className.match(/rollover (\S+)/)[1])
			}
			imgArr[i].onmouseout=function() 
			{
				this.setAttribute('src',this.getAttribute('rsrc'))
			}
		}
	}
}

//validates forms with URL fields and adds http:// in front of the string
function validateURL(inputField){
     if (inputField.value.indexOf("http://")==0) {
          return false;
     } else {

     	  inputField.value="http://" + inputField.value;
          return true;
     }
}