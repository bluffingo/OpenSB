var cookie_domain='localhost';
var cookie_prefix='';
function _gel(id){
return document.getElementById(id);
}
function ref(instance_or_id){
return(typeof(instance_or_id)=="string")?document.getElementById(instance_or_id):instance_or_id;
}
onLoadFunctionList=[];
function performOnLoadFunctions(){
for(var i=0;i<onLoadFunctionList.length;i++){
onLoadFunctionList[i]();
}
}
function hasAncestor(element,ancestor){
var el=ref(element);
var an=ref(ancestor);
while(el!=document&&el!=null){
if(el==an)return true;
el=el.parentNode;
}
return false;
}
var addListener=function(){
if(window.addEventListener){
return function(el,type,fn){
el.addEventListener(type,fn,false);
};
}
else if(window.attachEvent){
return function(el,type,fn){
var f=function(){
fn.call(el,window.event);
};
if(!el._listeners)el._listeners={};
if(!el._listeners[type])el._listeners[type]={};
el._listeners[type][fn]=f;
el.attachEvent('on'+type,f);
};
}
else{
return function(el,type,fn){
el['on'+type]=fn;
}
}
}();
var removeListener=function(el,type,func){
if(el.removeEventListener){
el.removeEventListener(type,func,false);
}else if(el.detachEvent&&el._listeners
&&el._listeners[type]&&el._listeners[type][func]){
el.detachEvent('on'+type,el._listeners[type][func]);
}
};
function buildUrl(url,params){
var pairs=new Array();
var result=url;
if(params){
for(var key in params){
pairs.push(key+"="+encodeURIComponent(params[key].toString()));
}
result+="?"+pairs.join("&");
}
return result;
}
function redirect(url,params){
document.location.href=buildUrl(url,params);
}
function setInnerHTML(div_id,value){
var dstDiv=_gel(div_id);
dstDiv.innerHTML=value;
}
function stopPropagation(e)
{
if(!e)var e=window.event;
e.cancelBubble=true;
if(e.stopPropagation)e.stopPropagation();
}
function openPopup(url,name,height,width,opt_show_scrollbars){
var scrollbar_param=opt_show_scrollbars?",scrollbars=1":"";
var newwindow=window.open(url,name,'height='+height+',width='+width+scrollbar_param);
if(newwindow&&!newwindow.opener){newwindow.opener=window;}
if(window.focus){newwindow.focus()}
return false;
}
var dropdownMenu={};
function dropdown(e,menuId,parentId,eventType){
/// If there's another menu open, close it and reset dropdownMenu
	hideDropdown();
	dropdownMenu.id = menuId;
	dropdownMenu.parentId = (parentId) ? parentId : _gel(menuId).parentNode.id;
	dropdownMenu.eventType = (eventType) ? eventType : "click";
	if (!e) e = window.event;
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
	showDiv(dropdownMenu.id);
}
/**
* Hide div that contains drop down menu if a drop down menu exists
*/
function hideDropdown() {
	if (dropdownMenu.id) {
		hideDiv(dropdownMenu.id);
		dropdownMenu={};
	}
}
/**
* Global click listener that hides an open drop down menu.
* Global so that any click on the page will close the menu,
* including a clicked menu object.
*/
addListener(document, "click", function(e) {
	hideDropdown()
});
/**
* Global mouseover listener that hides an open drop down menu
* if the drop down menu has "mouseover" as it's specified closure event
*and the mouseover is on an object that is not part of the menu.
*Global so any mouseover on an unrelated part of the page will close the menu.
*/
addListener(document,"mouseover",function(e){
var el=e.target||e.srcElement;
if(dropdownMenu&&dropdownMenu.eventType&&dropdownMenu.parentId){
/// Do not hide menu when mousing over menu children
if(e.type.indexOf(dropdownMenu.eventType)!=-1&&!hasAncestor(el,dropdownMenu.parentId))
hideDropdown();
}
});
function getTotalOffset(target_element,scrolled){
var element=target_element;
var x=element.offsetLeft;
var y=element.offsetTop;
if(element.offsetParent){
while(element=element.offsetParent){
x+=element.offsetLeft;
y+=element.offsetTop;
}
}
///get scrolled as well
element=target_element;
if(scrolled)
{
if(element.parentNode){
while(element=element.parentNode){
if(element.tagName.toUpperCase()=="BODY")
{
break;
}
if(element.scrollLeft||element.scrollTop)
{
x-=element.scrollLeft;
y-=element.scrollTop;
}
}
}
}
return[x,y];
}
function truncate(text,opt_max_length){
var max_length=opt_max_length;
if(!max_length){
max_length=30;
}
if(text.length>max_length-3){
return text.substring(0,max_length-3)+"...";
}
return text;
}
function toggleClass(element,className){
var e=ref(element);
if(!e)return;
if(hasClass(e,className)){
removeClass(e,className);
}else{
addClass(e,className);
}
}
function hasClass(element,_className){
if(!element){
return;
}
var upperClass=_className.toUpperCase();
if(element.className){
var classes=element.className.split(' ');
for(var i=0;i<classes.length;i++){
if(classes[i].toUpperCase()==upperClass){
return true;
}
}
}
return false;
}
function addClass(element,_class){
if(!hasClass(element,_class)){
element.className+=element.className?(" "+_class):_class;
}
}
function getClassList(element){
if(element.className){
return element.className.split(' ');
}else{
return[];
}
}
function removeClass(element,_class){
var upperClass=_class.toUpperCase();
var remainingClasses=[];
if(element.className){
var classes=element.className.split(' ');
for(var i=0;i<classes.length;i++){
if(classes[i].toUpperCase()!=upperClass){
remainingClasses[remainingClasses.length]=classes[i];
}
}
element.className=remainingClasses.join(' ');
}
}
function findAncestorByClass(element,className){
var temp=element;
while(temp!=document){
if(hasClass(temp,className))return temp;
temp=temp.parentNode;
}
return null;
}
function getElementsByTagNameAndClass(tag,className,parentEl){
var array=(parentEl?parentEl:document).getElementsByTagName(tag);
var matches=[];
/// RegExp used to locate class names.The \b qualifier
/// locates word boundaries,but considers '-' to be a word boundary,
/// The(?!\-)is a negative lookahead assertion for '-',necessary
/// to make sure that the matched className isn't a hyphen-divided
	// substring of a larger className.
	var re = new RegExp("\\b(?!\-)" + className + "(?!\-)\\b", "");
	for (var i = 0; i < array.length; i++) {
		if (re.test(array[i].className)) {
			matches.push(array[i]);
		}
	}
	return matches;
}
function setVisible(divName, onOrOff){
	var tempDiv = ref(divName);
	if (!tempDiv) {
		return;
	}
	if (onOrOff) {
		// true = visible
		tempDiv.style.visibility="visible";
	} else {
		// false = hidden
		tempDiv.style.visibility="hidden";
	}
}
function toggleDisplay(divName) {
	var tempDiv = ref(divName);
	if (!tempDiv) {
		return false;
	}
	if ((tempDiv.style.display == "block") || (tempDiv.style.display == "" && tempDiv.className.indexOf("hid") == 0)) {
		tempDiv.style.display = "none";
		return false;
	} else if ((tempDiv.style.display == "none") || (tempDiv.className.indexOf("hid") != 0)) {
		tempDiv.style.display = "block";
		return true;
	}
}
function toggleDisplay2() {
	var elements = Array.prototype.slice.call(arguments);
	arrayEach(elements, function (arg) {
		var element = ref(arg);
		if (element) {
			element.style.display = (element.style.display != "none" ? "none" : "");
		}
	});
}
function each(array, func) {
	/* This could be optimized using tricks that don't rely on constantly checking the length.
Checking the length is slow because in many browsers,it requires a full iteration.*/
for(var i=0;i<array.length;i++)func(array[i]);
}
var arrayEach=each;
function setDisplay(el,visible){
if(visible){
showDiv(el);
}else{
hideDiv(el);
}
}
function showDiv(divName){
var tempDiv=ref(divName);
if(!tempDiv){
return;
}
if(hasClass(tempDiv,"wasinline")){
tempDiv.style.display="inline";
removeClass(tempDiv,"wasinline");
}else if(hasClass(tempDiv,"wasblock")){
tempDiv.style.display="block";
removeClass(tempDiv,"block");
}else{
tempDiv.style.display=getDisplayStyleByTagName(tempDiv);
}
}
function getDisplayStyleByTagName(o){
var n=o.nodeName.toLowerCase();
return(n=="span"||n=="img"||n=="a")?"inline":(n=='tr'||n=='td'?"":"block");
}
function hideDiv(divName){
var tempDiv=ref(divName);
if(!tempDiv){
return;
}
if(tempDiv.style.display=="inline"){
addClass(tempDiv,"wasinline");
}else if(tempDiv.style.display=="block"){
addClass(tempDiv,"wasblock");
}
tempDiv.style.display="none";
}
function hideDivAfter(divName,delay){
window.setTimeout(function(){
hideDiv(divName)
},delay);
}
var yt=new function(){
var _throwOnNull=function(value){if(value==null)throw "ExpectedNotNull";};
var _throwOnInvalidType=function(obj,type){if(typeof(obj)!=type)throw "InvalidType";};
var _throwOnRegexMatch=function(str,regex){if(regex.test(str))throw "ExpectedRegexMismatch";};
var _throwOnRegexMismatch=function(str,regex){if(!regex.test(str))throw "ExpectedRegexMatch";};
this.UserPrefs=new function(){
var USER_PREFS_COOKIE=cookie_prefix+"PREF";
var prefs=new Object();
var _throwOnInvalidKey=function(value){
_throwOnRegexMismatch(value,/^\w+$/);
_throwOnRegexMatch(value,/^f([1-9][0-9]*)$/);
};
var _setValue=function(key,value){
prefs[key]=value.toString();
};
var _getNumber=function(key){
var value=_getString(key);
return((value!=null&&/^[A-Fa-f0-9]+$/.test(value))?parseInt(value,16):null);
};
var _getString=function(key){
var value=(prefs[key]!==undefined?prefs[key].toString():null);
return value;
};
var _setFlag=function(key,flag,bit){
var vector=_getNumber(key);vector=(vector!=null?vector:0);
var value=(bit?vector|flag:vector&~flag);
if(value==0){
_deleteValue(key);
}else{
_setValue(key,value.toString(16));
}
};
var _getFlag=function(key,flag){
var vector=_getNumber(key);
vector=(vector!=null?vector:0);
return((vector&flag)>0);
};
var _deleteValue=function(key){
delete prefs[key];
};
var _parse=function(string){
/// Need to regex match this string.
var pairs=unescape(string).split("&");
for(var i=0;i<pairs.length;i++){
var pair=pairs[i].split("=");
var key=pair[0];
var value=pair[1];
if(value)_setValue(key,value);
}
};
this.get=function(key,opt_def){
_throwOnInvalidKey(key);
var value=_getString(key);
return(value!=null?value:(opt_def?opt_def:""));
};
this.set=function(key,value){
_throwOnInvalidKey(key);
_throwOnNull(value);
_setValue(key,value);
};
this.getFlag=function(flag){return _getFlag('f1',flag);};
this.setFlag=function(flag,bit){return _setFlag('f1',flag,bit);};
this.remove=function(key){
_throwOnInvalidKey(key);
_deleteValue(key);
};
this.save=function(days){
var pairs=new Array();
for(var prop in prefs){
pairs.push(prop+"="+escape(prefs[prop]));
}
if(days==null)days=7;
createCookie(USER_PREFS_COOKIE,pairs.join("&"),days);
};
this.clear=function(){
prefs=new Object();
};
this.dump=function(){
var pairs=new Array();
for(var prop in prefs){
pairs.push(prop+"="+escape(prefs[prop]));
}
return pairs.join('&');
};
var data=readCookie(USER_PREFS_COOKIE);
if(data){
_parse(data);
}
};
this.EventManager=new function(){
var handlerTable=new Object();
this.fireEvent=function(name,arg){
if(handlerTable[name]==null)return;
var handlers=handlerTable[name];
for(var i=0;i<handlers.length;i++){
handlers[i](arg);
}
}
this.addHandler=function(name,fn){
if(handlerTable[name]==null)handlerTable[name]=new Array();
handlerTable[name].push(fn);
return fn;
}
this.removeHandler=function(name,fn){
if(handlerTable[name]==null)return false;
var index=handlerTable[name].indexOf(fn);
if(index==-1)return false;
handlerTable[name].splice(index,1);
return true;
}
};
};
var EventManager=yt.EventManager;///TODO Replace all native calls to EventManager with yt.EventManager
function readCookie(name,opt_fallback){
var nameEQ=name+"=";
var ca=document.cookie.split(';');
for(var i=0;i<ca.length;i++){
var c=ca[i];
while(c.charAt(0)==' ')c=c.substring(1,c.length);
if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length);
}
if(opt_fallback){
return opt_fallback;
}else{
return null;
}
}
function readIntCookie(name){
var val=readCookie(name);
if(val){
return parseInt(val,10);
}else{
return 0;
}
}
function createCookie(name,value,days){
var cookie="";
var domain=cookie_domain;/// Global var
var path="/";
cookie+=name+"="+value+";";
cookie+="domain=."+domain+";";
cookie+="path="+path+";";
if(days){
var date=new Date();
date.setTime(date.getTime()+(days*24*60*60*1000));
cookie+="expires="+date.toGMTString()+";";
}
document.cookie=cookie;
}
function eraseCookie(name){
createCookie(name,"",-1);
}
function closeLoginPicker(){
var loginBox=_gel('loginBoxZ');
if(loginBox){
if(loginBox.style.display!='none')
loginBox.style.display='none';
}
}
addListener(document,'click',function(e){
var element=e.target||e.srcElement;
if(!hasAncestor(element,'loginBoxZ')&&element.className.indexOf('loginBoxZ')==-1){
closeLoginPicker();
}
});
function openLoginBoxFromRatings(event){
var loginBox=_gel('loginBoxZ');
var totalOffset=getTotalOffset(_gel('watch-ratings-views'),false);
loginBox.style.left='85px';
loginBox.style.right='auto';
loginBox.style.top=(totalOffset[1]+25)+'px';
openLoginBox(event,true);
return false;
}
function openLoginBoxFromWatchTab(event){
var loginBox=_gel('loginBoxZ');
var totalOffset=getTotalOffset(_gel('watch-actions-area'),false);
loginBox.style.left='85px';
loginBox.style.right='auto';
loginBox.style.top=(totalOffset[1]+75)+'px';
openLoginBox(event,true);
return false;
}
function openLoginBox(event,setPositionManually){
if(event){
stopPropagation(event);
}
var loginBox=_gel('loginBoxZ');
if(!setPositionManually){
loginBox.style.left='auto';
loginBox.style.right='0px';
loginBox.style.top='33px';
}
toggleDisplay('loginBoxZ');
if(_gel('loginNextZ').value=='/signup'){
_gel('loginNextZ').value="/index";
}else if(_gel('loginNextZ').value.indexOf('/signup')==0){
_gel('loginNextZ').value=_gel('loginNextZ').value.substring(13);
}
if(_gel('loginBoxZ').style.display!='none'){
_gel('loginUserZ').focus();
}
_hbLink('LogIn','UtilityLinks');
return false;
}
function selectLocale(loc){
var current_url,next_url,anchor_url;
current_url=window.location.href;
///Check for # sign and insert parameters in between
current_url=current_url.split('#');
anchor_url=(current_url.length==2?'#'+current_url[1]:'');
current_url=current_url[0];
///Check if the URL string contains any site redirect values;if yes,chop string to remove those values
if(current_url.indexOf('?locale=')!=-1){
var url_array=current_url.split("?locale=");
current_url=url_array[0];
}else if(current_url.indexOf('&locale=')!=-1){
url_array=current_url.split("&locale=");
current_url=url_array[0];
}
///Check if the URL string already has parameters;if yes start with "&",if no start with "?"
next_url=current_url+(current_url.indexOf('?')==-1?"?":"&")+"locale="+loc+"&persist_locale=1"
+anchor_url;
window.location=next_url;
}
flagImgsLoaded=false;
function loadFlagImgs(){
if(flagImgsLoaded){
return;
}
var flagsHTML='';
for(var x=0;x<gLocales.length;++x){
flagsHTML+='<div class="flagDiv"><a href="#" onclick="selectLocale(\''+gLocales[x][0]+'\'); return false;" class="localePickerFlagLink"><img id="flag_'+gLocales[x][0]+'" src="'+gPixelGif+'" width="17" height="11" class="currentFlag flag_'+gLocales[x][0]+'" alt="" locale="'+gLocales[x][0]+'"></a> <a href="#" id="countryDiv_'+gLocales[x][0]+'" onclick="selectLocale(\''+gLocales[x][0]+'\'); return false;" class="localePickerTitle">'+gLocales[x][1]+'</a></div>';
}
_gel('flagDivInner').innerHTML=flagsHTML;
flagImgsLoaded=true;
}
function closeLocalePicker(){
var localePickerBox=_gel('localePickerBox');
if(!localePickerBox){
localePickerBox=_gel('localePickerBoxProfile');
}
if(localePickerBox){
if(localePickerBox.style.display!='none'){
localePickerBox.style.display='none';
}
}
}
addListener(document,'click',function(e){
var element=e.target||e.srcElement;
if(element.className.indexOf('localePickerLink')==-1&&element.className.indexOf('currentFlag')==-1){
closeLocalePicker();
}
});
function toogleWatcher(token,current_video_id,stringOn,stringOff)
{
if(!self.sharing_active){
enableWatcherShare(token,current_video_id,stringOn);
}else{
disableWatcherShare(token,current_video_id,stringOff);
}
}
function enableWatcherShare(token,current_video_id,stringOn){
_gel('shareSpan').style.backgroundColor="#6c0";
var varg="";
if(current_video_id)
varg="&v="+current_video_id;
getUrlXMLResponse("/watcher?action_start_share=1"+varg+"&t="+token,showEnabledWatcher(stringOn));
}
function disableWatcherShare(token,current_video_id,stringOff){
_gel('shareSpan').style.backgroundColor="#f66";
var varg="";
if(current_video_id)
varg="&v="+current_video_id;
getUrlXMLResponse("/watcher?action_stop_share=1"+varg+"&t="+token,showDisabledWatcher(stringOff));
}
function showEnabledWatcher(newString){
self.sharing_active=true;
var img=document.getElementById("sharingImg");
var img2=_gel('watchSharingImg');
removeClass(img,'activeSharingRed');
addClass(img,'activeSharingGreen');
if(img2){
removeClass(img2,'activeSharingRed');
addClass(img2,'activeSharingGreen');
}
if(document.getElementById("watch-active-sharing-status-on")){
hideDiv('watch-active-sharing-status-off');
showDiv('watch-active-sharing-status-on');
}
alert(newString);
img.title=newString;
_gel('shareSpan').style.backgroundColor="#fff";
if(document.getElementById("activesharing_start_button")){
hideDiv('activesharing_start_button');
showDiv('activesharing_stop_button');
}
if(document.getElementById("activesharing_masthead_start")){
hideDiv('activesharing_masthead_start');
showDiv('activesharing_masthead_stop');
}
}
function showDisabledWatcher(newString){
self.sharing_active=false;
var img=document.getElementById("sharingImg");
var img2=document.getElementById("watchSharingImg");
removeClass(img,'activeSharingGreen');
addClass(img,'activeSharingRed');
if(img2){
removeClass(img2,'activeSharingGreen');
addClass(img2,'activeSharingRed');
}
if(document.getElementById("watch-active-sharing-status-on")){
hideDiv('watch-active-sharing-status-on');
showDiv('watch-active-sharing-status-off');
}
alert(newString);
img.title=newString;
_gel('shareSpan').style.backgroundColor="#fff";
if(document.getElementById("activesharing_start_button")){
showDiv('activesharing_start_button');
hideDiv('activesharing_stop_button');
}
if(document.getElementById("activesharing_masthead_start")){
showDiv('activesharing_masthead_start');
hideDiv('activesharing_masthead_stop');
}
}
/// Inclusive of current node!
function isPanelExpanded(panel){
return hasClass(panel,'expanded');
}
function expandPanel(panel){
if(!isPanelExpanded(panel)){
addClass(panel,'expanded');
fireInlineEvent(panel,'expanded');
}
}
function collapsePanel(panel){
if(isPanelExpanded(panel)){
removeClass(panel,'expanded');
fireInlineEvent(panel,'collapsed');
}
}
function togglePanel(panel){
if(isPanelExpanded(panel)){
collapsePanel(panel);
}else{
expandPanel(panel);
}
}
function fireInlineEvent(element,eventName){
var target=ref(element);
if(target[eventName]==null){
var attributeName='on'+eventName.toLowerCase();
var attribute=target.attributes.getNamedItem(attributeName);
if(attribute){
target[eventName]=function(){
eval(attribute.value);
}
}
}
if(target[eventName])target[eventName]();
}
function getXmlHttpRequest()
{
var httpRequest=null;
try
{
httpRequest=new ActiveXObject("Msxml2.XMLHTTP");
}
catch(e)
{
try
{
httpRequest=new ActiveXObject("Microsoft.XMLHTTP");
}
catch(e)
{
httpRequest=null;
}
}
if(!httpRequest&&typeof XMLHttpRequest!="undefined")
{
httpRequest=new XMLHttpRequest();
}
return httpRequest;
}
function getUrlSync(url)
{
return getUrl(url,false,null);
}
function getUrlAsync(url,handleStateChange)
{
return getUrl(url,true,handleStateChange);
}
/// call a url
function getUrl(url,async,opt_handleStateChange){
var xmlHttpReq=getXmlHttpRequest();
if(!xmlHttpReq)
return;
if(opt_handleStateChange)
{
xmlHttpReq.onreadystatechange=function()
{
opt_handleStateChange(xmlHttpReq);
};
}
else
{
xmlHttpReq.onreadystatechange=function(){;}
}
xmlHttpReq.open("GET",url,async);
xmlHttpReq.send(null);
}
function postUrl(url,data,async,stateChangeCallback)
{
var xmlHttpReq=getXmlHttpRequest();
if(!xmlHttpReq)
return;
xmlHttpReq.open("POST",url,async);
xmlHttpReq.onreadystatechange=function()
{
stateChangeCallback(xmlHttpReq);
};
xmlHttpReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
xmlHttpReq.send(data);
///alert('url: '+url+'\ndata: '+data);
}
function urlEncodeDict(dict)
{
var result="";
for(var i=0;i<dict.length;i++){
result+="&"+encodeURIComponent(dict[i].name)+"="+encodeURIComponent(dict[i].value);
}
return result;
}
function XMLResponseCallback(successCallback,opt_errorCallback){
if(typeof successCallback=="object"){
this.onSuccessCallback=successCallback.onSuccessCallback;
this.onErrorCallback=successCallback.onErrorCallback;
}else if(typeof successCallback=="function"){
this.onSuccessCallback=successCallback;
this.onErrorCallback=opt_errorCallback;
}
}
XMLResponseCallback.prototype={
onSuccessCallback:null,
onErrorCallback:null,
/// Called on success HTTP request,success application response
onSuccess:function(xmlHttpReq){
if(this.onSuccessCallback!=null){
this.onSuccessCallback(xmlHttpReq);
}
},
/// Called on success HTTP request,error application response
onError:function(xmlHttpReq){
if(this.onErrorCallback!=null){
this.onErrorCallback(xmlHttpReq);
}
}
};
function XMLResponseCallbackJSON(callback){
var cb=new XMLResponseCallback(callback);
this.onSuccessCallback=function(xmlHttpReq){
cb.onSuccess(eval(getNodeValue(getRootNode(xmlHttpReq),"html_content")));
};
this.onErrorCallback=function(xmlHttpReq){
cb.onError(eval(getNodeValue(getRootNode(xmlHttpReq),"html_content")));
};
}
function execOnSuccess(stateChangeCallback,opt_successCallback,opt_divId)
{
return function(xmlHttpReq)
{
if(xmlHttpReq.readyState==4&&
xmlHttpReq.status==200){
if(opt_divId){
stateChangeCallback(xmlHttpReq,opt_successCallback,opt_divId);
}else{
stateChangeCallback(xmlHttpReq,opt_successCallback);
}
}
///alert(xmlHttpReq+" "+xmlHttpReq.readyState+" "+xmlHttpReq.status);
};
}
function postFormByForm(form,async,successCallback){
var formVars=new Array();
for(var i=0;i<form.elements.length;i++)
{
var formElement=form.elements[i];
/// Special handling for checkboxes and radios(we need an array of selected checkboxes..)!
if((formElement.type=='radio'||formElement.type=='checkbox')&&!formElement.checked){
continue;
}
var v=new Object;
v.name=formElement.name;
v.value=formElement.value;
formVars.push(v);
}
postUrl(form.action,urlEncodeDict(formVars),async,execOnSuccess(successCallback));
}
function postForm(formName,async,successCallback)
{
/// postFormByName
var form=document.forms[formName];
return postFormByForm(form,async,successCallback);
}
function replaceDivContents(xmlHttpRequest,dstDivId)
{
var dstDiv=document.getElementById(dstDivId);
dstDiv.innerHTML=xmlHttpRequest.responseText;
}
function getUrlXMLResponseCallback(xmlHttpReq,successCallback){
var callback=new XMLResponseCallback(successCallback);
if(xmlHttpReq.responseXML==null){
alert("Error while processing your request.");
return;
}
var root_node=getRootNode(xmlHttpReq);
var return_code=getNodeValue(root_node,'return_code');
///alert("return code "+return_code);
if(return_code==0){
var redirect_val=getNodeValue(root_node,'redirect_on_success');
if(redirect_val!=null){
window.location=redirect_val;
}else{
var success_message=getNodeValue(root_node,'success_message');
if(success_message!=null){
alert(success_message);
}
callback.onSuccess(xmlHttpReq);
}
}else{
var error_msg=getNodeValue(root_node,'error_message');
if(error_msg!=null){
alert(error_msg)
}
callback.onError(xmlHttpReq);
if(!callback.onErrorCallback&&!error_msg){
/// no callback and no error message;alert something
alert("An error occured while performing this operation.");
}
}
}
function getUrlXMLResponseCallbackFillDiv(xmlHttpReq,successCallback,div_id){
getUrlXMLResponseCallback(xmlHttpReq,successCallback);
document.getElementById(div_id).innerHTML=getNodeValue(xmlHttpReq.responseXML,"html_content");
}
function getUrlXMLResponseCallbackJSON(xmlHttpReq,successCallback){
getUrlXMLResponseCallback(xmlHttpReq,new XMLResponseCallbackJSON(successCallback));
}
function getNodeValue(obj,tag)
{
var node=obj.getElementsByTagName(tag);
if(node!=null&&node.length>0){
return node[0].firstChild.nodeValue;
}else{
return null;
}
}
function getRootNode(xmlHttpReq){
return xmlHttpReq.responseXML.getElementsByTagName('root')[0];
}
function getUrlXMLResponse(url,successCallback){
getUrl(url,true,execOnSuccess(getUrlXMLResponseCallback,successCallback))
}
function getUrlXMLResponseAndFillDiv(url,div_id,opt_successCallback){
getUrl(url,true,execOnSuccess(getUrlXMLResponseCallbackFillDiv,opt_successCallback,div_id))
}
function getUrlXMLResponseJSON(url,successCallback){
getUrl(url,true,execOnSuccess(getUrlXMLResponseCallbackJSON,successCallback))
}
getUrlXMLResponseJSON.prototype.getUrlXMLResponseCallbackJSON=getUrlXMLResponseCallbackJSON;
getUrlXMLResponseJSON.prototype.getUrlXMLResponseCallback=getUrlXMLResponseCallback;
function postUrlXMLResponse(url,data,successCallback){
postUrl(url,data,true,execOnSuccess(getUrlXMLResponseCallback,successCallback))
}
function postUrlXMLResponseJSON(url,data,successCallback){
postUrl(url,data,true,execOnSuccess(getUrlXMLResponseCallbackJSON,successCallback))
}
function postUrlXMLResponseAndFillDiv(url,data,div_id,successCallback){
postUrl(url,data,true,execOnSuccess(getUrlXMLResponseCallbackFillDiv,successCallback,div_id))
}
/// ANGUS-This appears to be unused...
function confirmAndPostUrlXMLResponse(url,confirmMessage,data,successCallback){
if(confirm(confirmMessage)){
postUrlXMLResponse(url,data,successCallback);
}
}
function postFormXMLResponse(formName,successCallback){
postForm(formName,true,execOnSuccess(getUrlXMLResponseCallback,successCallback))
}
/// CZACH-Commenting it out because it is buggy...hopefully nothing breaks.
/// FIXME:VFL this is hard for the resource analyzer to find
/// better to always reference resources by site-relative urls
var UT_RATING_IMG='icn_star_full_19x20';
var UT_RATING_IMG_HOVER='http://s.ytimg.com/yt/img/star_hover-vfl2056.gif';
var UT_RATING_IMG_HALF='icn_star_half_19x20';
var UT_RATING_IMG_BG='icn_star_empty_19x20';
var UT_RATING_IMG_REMOVED='http://s.ytimg.com/yt/img/star_removed-vfl2028.gif';
function UTRating(ratingElementId,maxStars,objectName,formName,ratingMessageId,componentSuffix,size,messages,starCount,callback)
{
this.ratingElementId=ratingElementId;
this.maxStars=maxStars;
this.objectName=objectName;
this.formName=formName;
this.ratingMessageId=ratingMessageId
this.componentSuffix=componentSuffix
this.messages=messages;
this.callback=callback;
this.starTimer=null;
this.starCount=0;
if(starCount){
this.starCount=starCount;
var that=this;
onLoadFunctionList.push(function(){that.drawStars(that.starCount,true);});
}
if(size=='S'){
UT_RATING_IMG='icn_star_full_11x11'
UT_RATING_IMG_HALF='icn_star_half_11x11'
UT_RATING_IMG_BG='icn_star_empty_11x11'
}
/// pre-fetch image
///(new Image()).src=UT_RATING_IMG;
///(new Image()).src=UT_RATING_IMG_HALF;
function showStars(starNum,skipMessageUpdate){
this.clearStarTimer();
this.greyStars();
this.colorStars(starNum);
if(!skipMessageUpdate)
this.setMessage(starNum,messages);
}
function setMessage(starNum){
if(starNum>0){
if(!this.savedMessage){
this.savedMessage=document.getElementById(this.ratingMessageId).innerHTML;
}
document.getElementById(this.ratingMessageId).innerHTML=this.messages[starNum-1];
}else if(this.savedMessage){
document.getElementById(this.ratingMessageId).innerHTML=this.savedMessage;
}
}
function colorStars(starNum){
var fullStars=Math.floor(starNum+0.25);
var halfStar=(starNum-fullStars>0.25);
for(var i=0;i<fullStars;i++){
removeClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG_HALF);
removeClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG_BG);
addClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG);
}
if(halfStar){
removeClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG);
removeClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG_BG);
addClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG_HALF);
}
}
function greyStars(){
for(var i=0;i<this.maxStars;i++){
removeClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG);
removeClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG_HALF);
addClass(document.getElementById('star_'+this.componentSuffix+"_"+(i+1)),UT_RATING_IMG_BG);
}
}
function setStars(starNum){
this.starCount=starNum;
this.drawStars(starNum);
document.forms[this.formName]['rating'].value=this.starCount;
var ratingElementId=this.ratingElementId;
that=this;
postForm(this.formName,true,function(req){
replaceDivContents(req,ratingElementId);
if(that.callback){
that.callback();
}
});
}
function drawStars(starNum,skipMessageUpdate){
this.starCount=starNum;
this.showStars(starNum,skipMessageUpdate);
}
function clearStars(){
this.starTimer=window.setTimeout(this.objectName+".resetStars()",300);
}
function resetStars(){
this.clearStarTimer();
if(this.starCount)
this.drawStars(this.starCount);
else
this.greyStars();
this.setMessage(0);
}
function clearStarTimer(){
if(this.starTimer){
window.clearTimeout(this.starTimer);
this.starTimer=null;
}
}
this.clearStars=clearStars;
this.clearStarTimer=clearStarTimer;
this.greyStars=greyStars;
this.colorStars=colorStars;
this.resetStars=resetStars;
this.setStars=setStars;
this.drawStars=drawStars;
this.showStars=showStars;
this.setMessage=setMessage;
}
/// Additional helper for hover handling which is use whether or not the UTRating exists(ie.static widget)
ratingHoverTimers=[];
function ratingHoverOver(componentSuffix){
if(componentSuffix==""){
componentSuffix=="reserved"
}
_clearHoverTimer(componentSuffix);
hideDiv('defaultRatingMessage'+componentSuffix);
showDiv('hoverMessage'+componentSuffix);
}
function ratingHoverOut(componentSuffix){
if(componentSuffix==""){
componentSuffix=="reserved"
}
ratingHoverTimers[componentSuffix]=window.setTimeout(function(){_ratingHoverClear(componentSuffix);},300);
}
function _ratingHoverClear(componentSuffix){
if(componentSuffix==""){
componentSuffix=="reserved"
}
_clearHoverTimer();
hideDiv('hoverMessage');
showDiv('defaultRatingMessage');
}
function _clearHoverTimer(opt_componentSuffix){
if(opt_componentSuffix==""){
opt_componentSuffix=="reserved"
}
if(ratingHoverTimers[opt_componentSuffix]){
window.clearTimeout(ratingHoverTimers[opt_componentSuffix]);
ratingHoverTimers[opt_componentSuffix]=null;
}
}
/// Javascript for the YVA player
function newRoadBlock(adTag){
var a=adTag.split(';');
if(a.length>0){
for(var x=0;x<=a.length-1;x++){
if(a[x].indexOf('sz=')==0){
var size=a[x].substring(3);
var dims=size.split('x');
var height=dims[0];
var width=dims[1];
loadRBs('myAd_banner',height,width,adTag);
}
}
}
}
function loadRBs(s,w,h,adTag){
document.getElementById(s).innerHTML=
'<iframe src="'+adTag+'" id="ifr_companion" width="'+w+'" height="'+h+
'" marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=no>'+
'</iframe>';
}
function setFlashVars(myObjName){
var pvaTag=document.getElementById("pvaTag").value;
document.getElementById("FLASH_"+myObjName).SetVariable("myAdTag",pvaTag);
var canv=document.getElementById("canv").value;
document.getElementById("FLASH_"+myObjName).SetVariable("canv",canv);
var burl=document.getElementById("burl").value;
document.getElementById("FLASH_"+myObjName).SetVariable("dc_PVAurl",burl);
var hl=document.getElementById("pvaHl").value
document.getElementById("FLASH_"+myObjName).SetVariable("hl",hl);
document.getElementById("FLASH_"+myObjName).SetVariable("rtg","1");
}
function showPVA(){
var banner=document.getElementById("myAd_banner");
if(banner){
banner.style.visibility="visible";
banner.style.height=35;
}
}
/// The following functions rely on javascript found in templates/includes/_comments2.tmpl
function approveComment(comment_id,comment_type,entity_id,token)
{
if(isLoggedIn==false)
return false;
///if(!confirm("Really approve this comment?"))
///	return true;
///postFormByForm(form,true,execOnSuccess(commentApproved));
///postUrl("/comment_servlet",urlEncodeDict(formVars),true,execOnSuccess(commentApproved));
postUrlXMLResponse("/comment_servlet","&field_approve_comment=1&comment_id="+comment_id+"&comment_type="+comment_type+"&entity_id="+entity_id+"&"+token,self.commentApproved);
return false;
}
function removeComment(div_id,deleter_user_id,comment_id,comment_type,entity_id,token)
{
if(isLoggedIn==false)
return;
///if(!confirm("Really remove comment?"))
///	return;
var callback=function(){hideDiv(div_id);};
postUrlXMLResponse("/comment_servlet","deleter_user_id="+deleter_user_id+"&remove_comment&comment_id="+comment_id+"&comment_type="+comment_type+"&entity_id="+entity_id+"&"+token,callback);
return false;
}
function hideCommentReplyForm(form_id){
var div_id="div_"+form_id;
var reply_id="reply_"+form_id;
showDiv(reply_id);
hideDiv(div_id);
///setInnerHTML(div_id,"");
}
function handleStateChange(xmlHttpReq){
document.getElementById("all_comments_content").innerHTML=getNodeValue(xmlHttpReq.responseXML,"html_content");
style2=document.getElementById("recent_comments").style;
style2.display="none";
var style2=document.getElementById("all_comments").style;
style2.display="";
}
function commentResponse_js(xmlHttpRequest,messages)
{
var response_str=xmlHttpRequest.responseText;
var response_code=response_str.substr(0,response_str.indexOf(" "));
var form_id=response_str.substr(response_str.indexOf(" ")+1);
var form=document.forms[form_id];
var dstDiv=form.add_comment_button;
var discard_button=form.discard_comment_button;
var commentDiv=form.comment;
if(response_code=="OK"){
dstDiv.value=messages["ok"];
dstDiv.disabled=true;
discard_button.disabled=true;
discard_button.style.display="none";
}else if(response_code=="PENDING"){
dstDiv.value=messages["pending"]
dstDiv.disabled=true;
discard_button.disabled=true;
discard_button.style.display="none";
}else if(response_code=="LOGIN"){
dstDiv.disabled=false;
}else if(response_code=="EMAIL"){
if(confirm(messages["email"])){
window.location="/email_confirm"
}
dstDiv.disabled=false;
}else{
if(response_code=="BLOCKED"){
dstDiv.disabled=true;
}else if(response_code=="TOOSOON"){
dstDiv.disabled=false;
alert(messages["toosoon"]);
}else if(response_code=="TOOLONG"){
alert(messages["toolong"]);
dstDiv.disabled=false;
commentDiv.disabled=false;
}else if(response_code=="TOOSHORT"){
alert(messages["tooshort"]);
dstDiv.disabled=false;
commentDiv.disabled=false;
commentDiv.focus();
}else if(response_code=="FAILED"){
dstDiv.disabled=true;
}else if(response_code=="FAILADDED"){
dstDiv.disabled=true;
}else if(response_code=="CAPTCHAFAIL"){
alert(messages["catpchaFail"]);
dstDiv.disabled=false;
}else{
dstDiv.disabled=false;
}
dstDiv.value=messages["default"];
}
}
function ThreadedCommentHandler_js(comment_form,comment_form_id,messages)
{
var comment=comment_form.comment;
var comment_button=comment_form.comment_button;
if(comment.value.length==0||comment.value==null)
{
alert(messages["empty"]);
comment.disabled=false;
comment.focus();
return false;
}
if(comment.value.length>500)
{
alert(messages["toolong"]);
comment.disabled=false;
comment.focus();
return false;
}
postFormByForm(comment_form,true,commentResponse);
return true;
}
function postThreadedComment_js(comment_form_id,messages)
{
if(isLoggedIn==false)
return false;
var form=document.forms[comment_form_id];
if(ThreadedCommentHandler(form,comment_form_id)){
var add_button=form.add_comment_button;
add_button.value=messages["add"];
form.comment.disabled=true;
add_button.disabled=true;
}
}
function load_all_comments_js(video_id,is_watch2,messages){
var remove_btn=document.getElementById('all_comments_button');
if(remove_btn){
remove_btn.value=messages["remove"];
remove_btn.disabled=true;
}
if(is_watch2)
var watch2_str="&watch2"
else
var watch2_str=""
getUrlXMLResponse("/comment_servlet?get_comments&v="+video_id+watch2_str,handleStateChange);
}
function showCommentReplyForm_js(form_id,reply_parent_id,is_main_comment_form,messages){
if(!isLoggedIn){
alert(messages["post"]);
return false;
}
printCommentReplyForm(form_id,reply_parent_id,is_main_comment_form);
}
function updateCharCount_js(charCount_id,label_id,textArea,maxChars,messages){
if(textArea.value.length>maxChars){
if(_gel(label_id).innerHTML!=messages["exceeded"]){
_gel(label_id).innerHTML=messages["exceeded"];
}
_gel(charCount_id).value=textArea.value.length-maxChars;
}else{
if(_gel(label_id).innerHTML!=messages["remaining"]){
_gel(label_id).innerHTML=messages["remaining"];
}
_gel(charCount_id).value=maxChars-textArea.value.length;
}
}
function loginMsg_js(div_id,display_val,messages){
var login_msg_div_id="comment_msg_"+div_id;
if(display_val==1){
setInnerHTML(login_msg_div_id,messages["login"]);
}
else{
setInnerHTML(login_msg_div_id,'');
}
}
function printCommentReplyForm_js(form_id,reply_parent_id,is_main_comment_form,comment_type,bidiSupport,id_field_name,id_field_value,comment_xsrf_token,maxChars,messages){
var div_id="div_"+form_id;
var reply_id="reply_"+form_id;
var reply_comment_form="comment_form"+form_id;
var maxCharLabelId="maxCharLabel"+form_id;
var charCountId="charCount"+form_id;
var discard_visible="";
if(is_main_comment_form)
discard_visible="style='display: none'";
var innerHTMLContent='\
	<form name="'+reply_comment_form+'" id="'+reply_comment_form+'" onSubmit="return false" method="post" action="/comment_servlet" >\
		<input type="hidden" name="'+id_field_name+'" value="'+id_field_value+'">\
		<input type="hidden" name="add_comment" value="">\
		'+comment_xsrf_token+'\
		<input type="hidden" name="form_id" value="'+reply_comment_form+'">\
		<input type="hidden" name="reply_parent_id" value="'+reply_parent_id+'">\
		<input type="hidden" name="comment_type" value="'+comment_type+'">\
		<textarea name="comment" \
		cols="46" rows="5" onkeyup="updateCharCount(\''+charCountId+'\', \''+maxCharLabelId+'\', this); '+bidiSupport+'"\
		onpaste="updateCharCount(\''+charCountId+'\', \''+maxCharLabelId+'\', this);"\
		oninput="updateCharCount(\''+charCountId+'\', \''+maxCharLabelId+'\', this);"\
		></textarea>\
		<br/>\
		<div style="float:left;clear:left">\
		<input align="left" type="button" name="add_comment_button"\
				value="'+messages['post']+'" \
				onclick="postThreadedComment(\''+reply_comment_form+'\');">\
		<input align="left" type="button" name="discard_comment_button"\
				value="'+messages['discard']+'" '+discard_visible+'\
				onclick="hideCommentReplyForm(\''+form_id+'\',false);">\
		<span id="'+maxCharLabelId+'">'+messages["remaining"]+'</span><input readonly="true" class="watch-comment-char-count" type="text" id="'+charCountId+'" value='+maxChars+'>\
		</div>\
	</form><br style="clear:both"><br>';
if(!is_main_comment_form){
hideDiv(reply_id);
if(reply_parent_id&&_gel("comment_body_"+reply_parent_id).style.display=="none"){
displayHideCommentLink(reply_parent_id);
}
}
setInnerHTML(div_id,innerHTMLContent);
showDiv(div_id);
}
function spam(comment_id,vid_id){
var url_string="&mark_comment_as_spam="+comment_id+"&entity_id="+vid_id;
postUrlXMLResponse('/comment_servlet',url_string);
displayShowCommentLink(comment_id);
hideSpam(comment_id);
}
function hideSpam(cid){
if(document.getElementById('reply_comment_form_id_'+cid)){
document.getElementById('reply_comment_form_id_'+cid).style.display='none';
}
if(document.getElementById('comment_body_'+cid)){
document.getElementById('comment_body_'+cid).style.display='none';
}
if(document.getElementById('comment_spam_bug_'+cid)){
document.getElementById('comment_spam_bug_'+cid).style.display='inline';
}
}
function displayHideCommentLink(comm_id){
var header_div="comment_header_"+comm_id;
var comment_body_div="comment_body_"+comm_id;
var comment_vote_div="comment_vote_"+comm_id;
var span_hide_id="hide_link_"+comm_id;
var span_show_id="show_link_"+comm_id;
showDiv(comment_body_div);
_gel(span_show_id).style.visibility='hidden';
hideDiv(span_show_id);
showDiv(span_hide_id);
_gel(span_hide_id).style.visibility='visible';
if(_gel(header_div)){
_gel(header_div).className="watch-comment-head";
}
_gel(comment_vote_div).className="watch-comment-voting";
}
function displayShowCommentLink(comm_id){
var header_div="comment_header_"+comm_id;
var comment_body_div="comment_body_"+comm_id;
var comment_vote_div="comment_vote_"+comm_id;
var span_hide_id="hide_link_"+comm_id;
var span_show_id="show_link_"+comm_id;
hideDiv(comment_body_div);
_gel(span_hide_id).style.visibility='hidden';
hideDiv(span_hide_id);
showDiv(span_show_id);
_gel(span_show_id).style.visibility='visible';
if(_gel(header_div)){
_gel(header_div).className="watch-comment-head-hidden opacity80";
}
_gel(comment_vote_div).className="watch-comment-voting-off";
}
function voteComment(comment_id,vid_id,comment_ref_id,increment){
var url_string="/comment_voting.php?a="+increment+"&id="+comment_id+"&video_id="+vid_id+"&old_vote="+comment_ref_id;
var vote_div_id="comment_vote_"+comment_id;
var comment_body="comment_body_"+comment_id;
var hide_link_id="hide_link_"+comment_id;
var show_link_id="show_link_"+comment_id;
if(_gel(vote_div_id).className=='watch-comment-voting-off'){
return;
}
getUrlXMLResponseAndFillDiv(url_string,vote_div_id);
if(increment<0){
hideDiv(comment_body);
displayShowCommentLink(comment_id);
}
showLoadingIcon(vote_div_id);
}
function voteCommentHidden(comment_id,vid_id,comment_ref_id,increment){
var comment_body_div="comment_body_"+comment_id;
var vote_div_id="comment_vote_"+comment_id;
var hide_link_id="hide_link_"+comment_id;
var show_link_id="show_link_"+comment_id;
if(_gel(vote_div_id).className=='watch-comment-voting-off'){
return;
}
if(_gel(comment_body_div).style.display=='none'){
displayHideCommentLink(comment_id);
}
else{
voteComment(comment_id,vid_id,comment_ref_id,increment);
}
}
function showLoadingIcon(div_id){
var temp_HTML="<img src=/img/icn_loading_animated.gif>";
_gel(div_id).innerHTML=temp_HTML;
}
function showLoading(div_id){
var temp_HTML="<br><br><br><br><br><center><img src=/img/icn_loading_animated.gif></center><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
_gel(div_id).innerHTML=temp_HTML;
document.body.focus();
}
function showAjaxDivLoggedIn(divName,url,opt_callback){
getUrlXMLResponse(url,showAjaxDivResponseLater(divName,opt_callback));
}
/// Calls optional callback if the ajax call succeeds
function showAjaxPostDivLoggedIn(divName,url,data,opt_callback){
postUrlXMLResponse(url,data,showAjaxDivResponseLater(divName,opt_callback));
}
var showAjaxDivNotLoggedIn=showAjaxDivLoggedIn;
function showAjaxDiv(divName,url){
if(isLoggedIn){
showAjaxDivLoggedIn(divName,url)
}else{
alert(MSG_Login);
}
}
function showAjaxPostDiv(divName,url,data){
if(isLoggedIn){
showAjaxPostDivLoggedIn(divName,url,data)
}else{
alert(MSG_Login);
}
}
function showAjaxDivResponseLater(divName,callback){
var callbackWrapper=new XMLResponseCallback(callback);
return new XMLResponseCallback(
function(req){
var nodeValue=getNodeValue(req.responseXML,"html_content");
_gel(divName).innerHTML=nodeValue?nodeValue:'';
callbackWrapper.onSuccess(req);
},
function(req){
callbackWrapper.onError(req);
}
);
}
function showAjaxDivResponse(req,divName){
_gel(divName).innerHTML=getNodeValue(req.responseXML,"html_content");
showDiv(divName);
}
function postAjaxForm(divName,formName,opt_successCallback){
postFormXMLResponse(formName,closeAjaxDivLater(divName,opt_successCallback));
}
function closeAjaxDivLater(divName,callback){
var callbackWrapper=new XMLResponseCallback(callback);
return new XMLResponseCallback(
function(req){
hideDiv(divName);
callbackWrapper.onSuccess(req);
},
function(req){
hideDiv(divName);
callbackWrapper.onError(req);
}
);
}
var delayLoadRegistry=[];
var delayLoadCompleted=[];
function delayLoad(id,img,src){
delayLoadRegistry[delayLoadRegistry.length]=[id,img,src];
delayLoadCompleted[id]=false;
}
function performDelayLoad(id){
if(!delayLoadCompleted[id]){
delayLoadCompleted[id]=true;
for(var i=0;i<delayLoadRegistry.length;i++){
if(delayLoadRegistry[i][0]==id){
delayLoadRegistry[i][1].onload="";
delayLoadRegistry[i][1].src=delayLoadRegistry[i][2];
}
}
}
}
function writeMoviePlayer(player_div,force){
var v="7";
if(force)
v="0";
var fo=new SWFObject(swfUrl,"movie_player","480","385",v,"#FFFFFF");
fo.addParam("allowFullscreen","true");
for(var x in swfArgs){
fo.addVariable(x,swfArgs[x]);
}
if(watchGamUrl!=null){
fo.addVariable("gam",watchGamUrl);
}
if(watchDCUrl!=null){
fo.addVariable("ad_tag",watchDCUrl);
}
if(!watchIsPlayingAll){
fo.addVariable("playnext",0);
}
if(watchSetWmode){
fo.addParam("wmode","opaque");
}
if(ad_eurl){
fo.addVariable("ad_eurl",ad_eurl);
}
fo.addVariable("enablejsapi",1);
fo.addParam("AllowScriptAccess","always");
player_written=fo.write(player_div);
}
function openFull(){
var fs=window.open(fullscreenUrl,
"FullScreenVideo","toolbar=no,width="+screen.availWidth+",height="+screen.availHeight
+",status=no,resizable=yes,fullscreen=yes,scrollbars=no");
fs.focus();
}
function checkCurrentVideo(videoId)
{
if(pageVideoId!=videoId){
window.location.href="/watch?v="+videoId;
}
}
/// Interactive Videos/ Video Annotations:
///
/// TODO(rant):remove the generic-named trackEvent method and leave only
/// trackAnnotationsEvent-this has to be synched with actionscript code
///
function trackEvent(action,opt_label,opt_value){
annotationsTracker._trackEvent(action,opt_label,opt_value);
}
function trackAnnotationsEvent(action,opt_label,opt_value){
annotationsTracker._trackEvent(action,opt_label,opt_value);
}
/// New player events support
function onYouTubePlayerReady(playerId){
var player=document.getElementById("movie_player");
player.addEventListener("onStateChange","handleWatchPagePlayerStateChange");
}
function handleWatchPagePlayerStateChange(newState){
if(newState==0){
gotoNext();
}
}
yt.VideoQualityConstants={
AUTO:0,
LOW:1,
HIGH:2
}
var playerVideoQuality=yt.VideoQualityConstants.AUTO;
function onPlayerFormatChanged(format,vq){
playerVideoQuality=vq;
updateVideoQualitySettings();
}
function updateVideoQualitySettings(){
var videoQualitySettingsElm=_gel("watch-video-quality-setting");
if(videoQualitySettingsElm&&playerVideoQuality&&playerVideoQuality!=yt.VideoQualityConstants.AUTO){
removeClass(videoQualitySettingsElm,"high");
if(playerVideoQuality==yt.VideoQualityConstants.HIGH){
addClass(videoQualitySettingsElm,"high");
}
}
}
function checkVideoQualitySettings(){
try{/// wrap in a try/catch b/c we get errors thrown from p.getVariable("vq");
var p=_gel("movie_player");
if(p){
var vq=p.GetVariable("vq");
if(vq&&vq!=playerVideoQuality){
playerVideoQuality=vq;
updateVideoQualitySettings();
}
}
}catch(ex){
/// do nothing
}
window.setTimeout("checkVideoQualitySettings()",500);
}
function changeVideoQuality(quality){
var p=_gel("movie_player");
p.SetVariable("setVideoQuality",quality);
}
function watchSelectTab(tab){
var el=tab.parentNode.firstChild;
while(el){
removeClass(el,'watch-tab-sel');
el=el.nextSibling;
}
addClass(tab,'watch-tab-sel');
el=_gel(tab.id+'-body').parentNode.firstChild;
while(el){
removeClass(el,'watch-tab-sel');
el=el.nextSibling;
}
addClass(_gel(tab.id+'-body'),'watch-tab-sel')
}
function submitToBlog(self){
_gel('submitToBlogBtn').disabled=true;
if((_gel('watch-share-video-div').style.display!='none')){
toggleMoreShare('fewer-options','more-options');
postAjaxForm('watch-share-video-div',self.name,addToBlogClose);
}
else{
postAjaxForm('watch-share-blog-quick',self.name,addToBlogClose);
}
}
function addToBlogClose(){
toggleDisplay('addToBlogResult');
hideDivAfter('addToBlogResult',3000);
_gel('submitToBlogBtn').disabled=false;
}
function resetSharing(){
hideDiv('watch-share-video-div');
hideDiv('shareMessageQuickDiv');
hideDiv('watch-share-blog-quick');
showDiv('aggregationServicesDiv');
toggleMoreShare('fewer-options','more-options');
}
function readOnlyMode(error_div,error){
_gel(error_div).innerHTML=error;
}
function toggleMoreShare(hide,show){
hideDiv(hide);
showDiv(show);
}
function processShareVideo(eVideoID,divID,component){
shareVideo(eVideoID,divID,component);
//urchinTracker('/VideoWatch/ShareVideo');
showDiv('aggregationServicesDiv');
toggleMoreShare('more-options','fewer-options');
return false;
}
function shareVideo(videoId,divID,component,opt_blogInfoID){
var locale=window.ytLocale||'en_US';
var el=_gel(divID);
var action='video_id='+videoId;
if(component=='all'&&locale){
closeAll(divID);
toggleDisplay(divID);
toggleMoreShare('more-options','fewer-options');
action=action+'&locale='+locale+'&action_get_share_video_component=1';
}
else if(component=='email'){
closeMoreShareIfOpen();
closeAll(divID);
toggleDisplay(divID);
action=action+'&action_get_share_message_component=1';
}
else if(component=='blog'&&opt_blogInfoID){
closeMoreShareIfOpen();
closeAll(divID);
toggleDisplay(divID);
action=action+'&blog_info_id='+opt_blogInfoID+'&action_get_share_blog_component=1';
}
showDiv('aggregationServicesDiv');
if(el.style.display!="none"){
if(el.loaded===undefined){
var onSuccess=function(){
el.loaded=true;
if(opt_blogInfoID){
el.currBlog=opt_blogInfoID;
}
}
var onFailure=function(){
el.loaded=undefined;
hideDiv(divID);
}
showAjaxDivLoggedIn(divID,'/watch_ajax?'+action,new XMLResponseCallback(onSuccess,onFailure));
}
else if(opt_blogInfoID){
if(el.currBlog!=opt_blogInfoID){
showAjaxDivLoggedIn(divID,'/watch_ajax?'+action,true);
el.currBlog=opt_blogInfoID;
}
}
//urchinTracker('/VideoWatch/ShareVideo');
}
if(isLoggedIn){
//urchinTracker('/VideoWatch/ActionTab/ShareVideo/Loggedin');
}else{
//urchinTracker('/VideoWatch/ActionTab/ShareVideo/Loggedout');
}
}
function closeAll(except){
var divs=['watch-share-video-div','watch-share-blog-quick','shareMessageQuickDiv','shareVideoEmailDiv'];
for(var i=0;i<divs.length;i++){
if((divs[i]!=except)&&(_gel(divs[i]))){
var theDiv=_gel(divs[i]);
if(theDiv){
theDiv.style.display="none";
}
}
}
}
function closeMoreShareIfOpen(){
if((_gel('watch-share-video-div').style.display!='none')){
toggleMoreShare('fewer-options','more-options');
}
}
function shareVideoEmail(videoId){
toggleDisplay('shareVideoEmailDiv');
}
function shareVideoClose(){
if(_gel('watch-share-video-div').style.display!="none"){
toggleDisplay('watch-share-video-div');
}
else{
toggleDisplay('shareMessageQuickDiv');
}
toggleMoreShare('fewer-options','more-options');
toggleDisplay('shareVideoResult');
hideDivAfter('shareVideoResult',3000);
}
function shareVideoMessageClose(){
toggleDisplay('shareVideoMessageDiv');
toggleDisplay('shareVideoResult');
hideDivAfter('shareVideoResult',3000);
}
function recordServiceUsage(service_name,video_id,locale){
getUrl("/sharing_services?name="+encodeURIComponent(service_name)+"&v="+video_id+"&locale="+locale,true);
}
var defaultRecipientFieldCount=2;
var recipientFieldNamePrefix="recipient";
var recipientFieldCount=defaultRecipientFieldCount;
var lastRecipientFieldId=recipientFieldNamePrefix+recipientFieldCount;
var maxRecipients=10;
function resetRecipients(){
recipientFieldCount=defaultRecipientFieldCount;
lastRecipientFieldId=recipientFieldNamePrefix+recipientFieldCount;
}
function shareVideoFromFlash(){
shareVideo(pageVideoId,'watch-share-video-div','all');
smoothScrollIntoView(_gel("watch-share-video-div"),20);
}
var scrollStep=100;
var scrollStepDelay=50;
function smoothScrollIntoView(node,padding){
/// padding is 0 by default
if(!padding)
padding=0;
smoothScrollIntoViewWorker(node,padding,null);
}
function smoothScrollIntoViewWorker(node,padding,lastTop){
var nodeTop=getPageOffsetTop(node);
var currentTop=getBodyScrollTop();
var deltaTop=Math.min(nodeTop-currentTop-padding,scrollStep);
window.scrollBy(0,deltaTop);
if(currentTop!=lastTop){
window.setTimeout(function(){smoothScrollIntoViewWorker(node,padding,currentTop)},scrollStepDelay);
}
}
/// based on quircksmode
function getPageOffsetTop(element){
var curtop=0;
if(element.offsetParent){
curtop=element.offsetTop
while(element=element.offsetParent){
curtop+=element.offsetTop
}
}
return curtop;
}
function getBodyScrollTop(){
if(window.innerHeight){
return window.pageYOffset;
}else if(document&&document.documentElement&&document.documentElement.scrollTop){
return document.documentElement.scrollTop;
}else if(document&&document.body){
return document.body.scrollTop;
}
}
function addToFaves(formName,event){
watchSelectTab(_gel('watch-tab-favorite'));
if(isLoggedIn){
showDiv('watch-add-faves-loading');
hideDiv('watch-add-faves-result');
hideDiv('watch-remove-faves');
hideDiv('watch-add-faves');
hideDiv('watch-add-to-faves-switch');
hideDiv('watch-remove-faves-btn');
hideDiv('watch-add-faves-btn');
var onSuccess=function(){
showDiv('watch-add-faves-result');
showDiv('watch-remove-faves');
hideDiv('watch-add-faves');
showDiv('watch-add-to-faves-switch');
showDiv('watch-remove-faves-btn');
hideDiv('watch-add-faves-btn');
hideDiv('watch-add-faves-loading');
};
var onFailure=function(){
hideDiv('watch-add-faves');
hideDiv('watch-add-faves-btn');
hideDiv('watch-add-faves-loading');
watchSelectTab(_gel('watch-tab-share'));
};
postAjaxForm('watch-add-faves-div',formName,
new XMLResponseCallback(onSuccess,onFailure));
_gel('watch-action-favorite-link').blur();
//urchinTracker('/VideoWatch/ActionTab/AddToFavs/Loggedin');
}
else{
showDiv('addToFavesLogin');
//urchinTracker('/VideoWatch/ActionTab/AddToFavs/Loggedout');
}
}
function removeFromFaves(formName,event){
showDiv('watch-add-faves');
hideDiv('watch-remove-faves');
showDiv('watch-add-faves-btn');
hideDiv('watch-remove-faves-btn');
postAjaxForm('watch-remove-faves-div',formName);
_gel('watch-action-favorite-link').blur();
//urchinTracker('/VideoWatch/ActionTab/RemoveFromFavs/Loggedin');
}
var gWatchLoading='';
function addToPlaylist(videoId,event){
watchSelectTab(_gel('watch-tab-playlists'));
if(isLoggedIn){
if(!gWatchLoading){
gWatchLoading=_gel('addToPlaylistDiv').innerHTML;
}else{
_gel('addToPlaylistDiv').innerHTML=gWatchLoading;
}
showDiv('addToPlaylistDiv');
showAjaxDivLoggedIn('addToPlaylistDiv','/watch_ajax?video_id='+videoId+'&action_get_playlists_component=1',true);
//urchinTracker('/VideoWatch/ActionTab/AddToPlaylists/Loggedin');
}else{
showDiv('addToPlaylistLogin');
//urchinTracker('/VideoWatch/ActionTab/AddToPlaylists/Loggedout');
}
}
function submitToPlaylist(self){
if(!self.form.playlist_id.value){
return;
}
self.disabled=true;
postAjaxForm('addToPlaylistDiv',self.form.name,addToPlaylistClose);
}
function addToPlaylistClose(){
toggleDisplay('addToPlaylistResult');
var func=function(){
hideDiv('addToPlaylistResult');
watchSelectTab(_gel('watch-tab-share'));
};
window.setTimeout(func,3000);
}
function reportConcern(videoId,event){
closeAllReportConcernsInfo();
watchSelectTab(_gel('watch-tab-flag'));
if(isLoggedIn){
showDiv('inappropriateVidDiv');
if(_gel('inappropriateVidDiv').innerHTML.toLowerCase().indexOf('<div')!=-1){
return;
}
showAjaxDivLoggedIn('inappropriateVidDiv','/watch_ajax?video_id='+videoId+'&action_get_flag_video_component=1',reportConcernCallback);
//urchinTracker('/VideoWatch/ActionTab/Flag/Loggedin');
}
else{
showDiv('inappropriateMsgsLogin');
//urchinTracker('/VideoWatch/ActionTab/Flag/Loggedout');
}
}
function reportConcernCallback(){
_gel('inappropriateMsgsDiv').innerHTML=_gel('inappropriateMsgs').innerHTML;
_gel('inappropriateMsgs').innerHTML='';
showDiv('inappropriateMsgsDiv');
}
function flagReasonSelection(selID,reason,subreason){
var selCurrent=_gel('selectedFlagReason')
var selNew=_gel(selID);
/// clear styles if previously selected
if(_gel('flag_id').value){
clearSelectionStyles(_gel(_gel('flag_id').value));
clearSelectionStyles(_gel((_gel('flag_id').value).substring(0,2)));
}
/// highlight background selection
setSelectionStyles(selNew);
/// highlight parent level if any
if(selID.length==5){
setSelectionStyles(_gel(selID.substring(0,2)));
}
/// set selected reason and subreason
_gel('flag_reason').value=reason;
_gel('flag_sub_reason').value=subreason;
_gel('flag_id').value=selID;
selCurrent.innerHTML=_gel(selID).innerHTML;
setVisible('flag_'+selID.substring(0,2),false);
setVisible('flag_main',false);
closeAllFlagMoreInfo();
}
function closeAllFlagMoreInfo(){
var divs=['flagMoreInfo1','flagMoreInfo2','flagMoreInfo3','flagMoreInfo4','flagMoreInfo5','flagMoreInfo6','flagError'];
for(var i=0;i<divs.length;i++){
var theDiv=_gel(divs[i]);
if(theDiv){
theDiv.style.display='none';
}
}
}
function closeAllReportConcernsInfo(){
var divs=['reportConcernResult1','reportConcernResult2','reportConcernResult3','reportConcernResult4','reportConcernResult5'];
for(var i=0;i<divs.length;i++){
var theDiv=_gel(divs[i]);
if(theDiv){
theDiv.style.display='none';
}
}
}
function processFlagForm(elForm){
var formElement=elForm;
if(formElement){
var sel=formElement.flag_id.value;
if(sel){
if(sel=='hc_hv'){
if(formElement.protected_group.options[formElement.protected_group.selectedIndex].value!=''){
toggleDisplay('reportConcernResult3');
}
else{
toggleDisplay('flagError');
return;
}
}
else if(sel=='vc_af'||sel=='sc_su'){
toggleDisplay('reportConcernResult2');
}
else{
toggleDisplay('reportConcernResult1');
}
toggleDisplay('inappropriateVidDiv');
postAjaxForm('inappropriateVidDiv',elForm.name);
_gel('selectedFlagReason').innerHTML='- '+MSG_FlagDefault+' -';
if(_gel('flag_id').value){
clearSelectionStyles(_gel(_gel('flag_id').value));
clearSelectionStyles(_gel((_gel('flag_id').value).substring(0,2)));
_gel('flag_id').value='';
}
closeAllFlagMoreInfo();
addClass(_gel('watch-tab-flag'),'disabled');
}
else{
toggleDisplay('flagError');
}
}
}
function flagError(elName,errorText){
if(elName){
_gel(elName).innerHTML=errorText;
toggleDisplay(elName);
}
}
function clearSelectionStyles(elName){
if(elName){
elName.style.backgroundColor='';
elName.style.color='';
}
}
function setSelectionStyles(elName){
if(elName){
elName.style.backgroundColor='#6681ba';
elName.style.color='#fff';
}
}
function stripNonNumber(val){
return val.replace(/[^\d]/g,'')
}
var gExpandedStats=false;
function watchExpandStatBody(){
if(gExpandedStats){
return;
}
toggleFullStats();
toggleWatchMap();
gExpandedStats=true;
}
function toggleFullStats(){
togglePanel(_gel('watch-honors'));
if(_gel('additionalStatsDiv').innerHTML.toLowerCase().indexOf('<a')==-1){
showAjaxDivLoggedIn('additionalStatsDiv',additionalStatsHonorsUrl,false);
}
}
function toggleWatchMap(){
if(_gel('watch-map')){
togglePanel(_gel('watch-map'));
if(_gel('watch-map-div').src=="about:blank"){
_gel('watch-map-div').src=watchMapUrl;
}
}
}
function toggleLinkStats(){
togglePanel(_gel('watch-refer-div'));
}
function hideLinkingSite(video_id,site){
showAjaxPostDivLoggedIn('referersList','/watch_ajax','action_hide_linking_site=1&video_id='+video_id+'&url='+site+'&'+axc,hideLinkingSiteCallback);
return false;
}
function showLinkingSite(video_id,site){
showAjaxPostDivLoggedIn('referersList','/watch_ajax','action_show_linking_site=1&video_id='+video_id+'&url='+site+'&'+axc,hideLinkingSiteCallback);
return false;
}
function hideLinkingSiteCallback(){
hideDiv('referersList');
showDiv('referersList');
}
/// Referrers show/hide
function hideLinkingSites(){
postUrlXMLResponse('/watch_ajax','action_hide_linking_sites=1&video_id='+pageVideoId+'&'+axc,hideLinkingSitesCallback);
return false;
}
function showLinkingSites(){
showAjaxPostDivLoggedIn('watch-refer-list','/watch_ajax','action_show_linking_sites=1&video_id='+pageVideoId+'&'+axc,showLinkingSitesCallback);
return false;
}
function showLinkingSitesCallback(){
showDiv('watch-refer-list');
showDiv('hideLinkingSites');
hideDiv('showLinkingSites');
hideDiv('watch-linking-sites-disabled');
}
function hideLinkingSitesCallback(){
hideDiv('watch-refer-list');
hideDiv('hideLinkingSites');
showDiv('showLinkingSites');
showDiv('watch-linking-sites-disabled');
}
var subscribeTimer;
function subscribe(username,token,show_recommendations){
if(isLoggedIn){
window.clearTimeout(subscribeTimer);
postUrlXMLResponse('/subscribe.php',
'subscribe_to_user='+username+'&session_token='+token+(show_recommendations?'&show_recommendations':''),
function(result){
var subscribeMsgNode=_gel('subscribeMessage');
subscribeMsgNode.innerHTML=getNodeValue(getRootNode(result),'html_content');
subscribeMsgNode.style.display='block';
addClass(subscribeMsgNode,'greenBG');
addClass(_gel('subscribeDiv'),'hid');
removeClass(_gel('unsubscribeDiv'),'hid');
if(!show_recommendations){
subscribeTimer=window.setTimeout("hideDiv('subscribeMessage')",5000);
}
});
//urchinTracker('/VideoWatch/Subscription/'+username+'/Loggedin');
}else{
var subscribeMsgNode=_gel('subscribeMessage');
addClass(subscribeMsgNode,'errorBox');
subscribeMsgNode.style.display='block';
//urchinTracker('/VideoWatch/Subscription/'+username+'/Loggedout');
}
}
function unsubscribe(username,token){
window.clearTimeout(subscribeTimer);
postUrlXMLResponse('/subscribe.php','unsubscribe_from_user='+username+'&session_token='+token,
function(result){
var subscribeMsgNode=_gel('subscribeMessage');
subscribeMsgNode.innerHTML=getNodeValue(getRootNode(result),'html_content');
subscribeMsgNode.style.display='block';
addClass(subscribeMsgNode,'greenBG');
removeClass(_gel('subscribeDiv'),'hid');
addClass(_gel('unsubscribeDiv'),'hid');
subscribeTimer=window.setTimeout("hideDiv('subscribeMessage')",5000);
});
}
function customizeEmbed(){
if(toggleDisplay('watch-customize-embed-div')){
if(_gel('watch-customize-embed-div').innerHTML.toLowerCase().indexOf('<div')!=-1){
return;
}
showAjaxDivLoggedIn('watch-customize-embed-div','/watch_ajax?action_customize_embed=1',applyUserPrefs);
}
}
var selectedThemeColor='blank';
function onChangeColor(color){
var oldTheme=document.getElementById('theme_color_'+selectedThemeColor+'_img');
var newTheme=document.getElementById('theme_color_'+color+'_img');
yt.UserPrefs.set('emt',color);
yt.UserPrefs.save();
removeClass(oldTheme,'radio_selected');
addClass(newTheme,'radio_selected');
selectedThemeColor=color;
onUpdatePreviewImage();
return false;
}
function generateEmbed(){
var query='';
if(yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_EMBED_INCLUDE_RELATED_VIDEOS)){
query+='&rel=0';
}
var color=yt.UserPrefs.get('emt');
if(color!='blank'&&color!=''){
var hexColors=gCustomEmbedThemes[color].split(' ');
query+='&color1=0x'+hexColors[0]+'&color2=0x'+hexColors[1];
}
var showBorder=yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_EMBED_SHOW_BORDER);
query+=showBorder?'&border=1':'';
var height=(showBorder?349:344);
var embedCode='<object width="425" height="'+height+'"><param name="movie" value="'+embedUrl+query+'"><\/param><param name="wmode" value="transparent"><\/param><embed src="'+embedUrl+query+'" type="application/x-shockwave-flash" wmode="transparent" width="425" height="'+height+'"><\/embed><\/object>';
document.embedForm.embed_code.value=embedCode;
}
function onUpdatePreviewImage(){
var previewImage=document.getElementById('watch-customize-embed-theme-preview');
var showBorderCheckBox=document.getElementById('show_border_checkbox');
var border=(!showBorderCheckBox.checked?'_nb':'');
previewImage.src='img/preview_embed_'+selectedThemeColor+'_sm'+border+'.gif';
}
function applyUserPrefs(){
if(_gel('watch-customize-embed-theme')){
var showBorderCheckBox=_gel('show_border_checkbox');
showBorderCheckBox.checked=yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_EMBED_SHOW_BORDER);
if(!yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_EMBED_INCLUDE_RELATED_VIDEOS)){
_gel('embedCustomization1').checked=true;
}else{
_gel('embedCustomization0').checked=true;
}
var color=yt.UserPrefs.get('emt');
if(color!='blank'&&color!=''){
onChangeColor(color);
}
}
if(_gel('watch-customize-embed-div')){
generateEmbed();
}
}
function onChangeBorder(border){
yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_EMBED_SHOW_BORDER,(!!border));
yt.UserPrefs.save();
onUpdatePreviewImage();
}
function onChangeRelated(related){
yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_EMBED_INCLUDE_RELATED_VIDEOS,!related);
yt.UserPrefs.save();
}
function readPanelState(){
return;
}
function savePanelState(){
if(ref('watch-video-details')){yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_WATCH_EXPAND_ABOUT_PANEL,isPanelExpanded(ref('watch-video-details')));}
if(ref('more-from-panel')){yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_WATCH_EXPAND_MOREFROM_PANEL,isPanelExpanded(ref('more-from-panel')));}
if(ref('watch-related-videos-panel')){yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_WATCH_COLLAPSE_RELATED_PANEL,!isPanelExpanded(ref('watch-related-videos-panel')));}
if(ref('playlist-panel')){yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_WATCH_COLLAPSE_PLAYLIST_PANEL,!isPanelExpanded(ref('playlist-panel')));}
if(ref('quicklist-panel')){yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_WATCH_COLLAPSE_QUICKLIST_PANEL,!isPanelExpanded(ref('quicklist-panel')));}
if(ref('watch-active-sharing')){yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_WATCH_EXPAND_ALSOWATCHING_PANEL,isPanelExpanded(ref('watch-active-sharing')));}
yt.UserPrefs.save();
}
function showRelatedAsList(doAjaxCall){
if(doAjaxCall){
setInnerHTML('watch-related-vids-body',MSG_Loading);
var callback=function(){addQLIcons(_gel('watch-related-vids-body'));};
showAjaxDivLoggedIn('watch-related-vids-body',relatedVideoListUrl,callback);
}
removeClass(_gel('watch-related-vids-body'),'watch-discoverbox-grid-view');
addClass(_gel('watch-related-vids-body'),'watch-discoverbox-list-view');
showDiv("btn-listview-on");
hideDiv("btn-listview-off");
showDiv("btn-gridview-off");
hideDiv("btn-gridview-on");
return false;
}
function showRelatedAsGrid(){
removeClass(_gel('watch-related-vids-body'),'watch-discoverbox-list-view');
addClass(_gel('watch-related-vids-body'),'watch-discoverbox-grid-view');
performDelayLoad('related');
hideDiv("btn-listview-on");
showDiv("btn-listview-off");
hideDiv("btn-gridview-off");
showDiv("btn-gridview-on");
return false;
}
function showWatchboxAsList(boxIdKey){
removeClass(_gel('watch-related-vids-body'),'watch-discoverbox-grid-view');
addClass(_gel('watch-related-vids-body'),'watch-discoverbox-list-view');
showDiv("btn-listview-on");
hideDiv("btn-listview-off");
showDiv("btn-gridview-off");
hideDiv("btn-gridview-on");
return false;
}
function showWatchboxAsGrid(boxIdKey){
removeClass(_gel('watch-related-vids-body'),'watch-discoverbox-list-view');
addClass(_gel('watch-related-vids-body'),'watch-discoverbox-grid-view');
performDelayLoad('related');
hideDiv("btn-listview-on");
showDiv("btn-listview-off");
hideDiv("btn-gridview-off");
showDiv("btn-gridview-on");
return false;
}
function toggleChannelVideos(username){
if(!_gel('more_channel_videos')){
var callback=function(){addQLIcons(_gel('watch-more-from'));};
showAjaxDivLoggedIn('watch-more-from','/watch_ajax?user='+username+'&video_id='+pageVideoId+'&action_channel_videos',callback);
}
return false;
}
function updateSearchResultsLater(form,searchingDivName,resultsDivName){
return function(req){updateSearchResults(req,form,searchingDivName,resultsDivName)};
}
function updateSearchResults(req,form,searchingDivName,resultsDivName){
if(_gel("watchSearchSubmit")){
_gel("watchSearchSubmit").disabled=false;
}
_gel(resultsDivName).innerHTML=getNodeValue(req.responseXML,"html_content");
if(_gel('resultsBelow')){
_gel('resultsBelow').style.display="none";
}
_gel(resultsDivName).style.textAlign="left";
}
function submitWatchSearchRequest(formName,searchingDivName,resultsDivName){
_gel(resultsDivName).style.textAlign="center";
_gel(resultsDivName).style.display="block";
_gel(resultsDivName).innerHTML="<img src='http://s.ytimg.com/yt/img/LoadingGraphic-vfl3869.gif'>";
if(_gel("watchSearchSubmit")){
_gel("watchSearchSubmit").disabled=true;
}
var form=document.forms[formName];
postFormXMLResponse(formName,updateSearchResultsLater(form,searchingDivName,resultsDivName));
}
var first_time=1;
function changeBanner(img_url,ref_url,is_flash){
var e=_gel("gad_leaderboardAd");
if(first_time)
{
e.style.height="90px";
first_time=0;
}
var url="";
if(is_flash=="true")
{
url+="<object width='72"+"8' height='9"+"0'>";
url+="<"+"param value='clickTAG="+encodeURIComponent(ref_url)+"' /"+">";
url+="<"+"embed src='"+img_url+"'";
url+=" type='application/x-shockwave-flash' wmode='transparent'";
url+=" flashvars='clickTAG="+encodeURIComponent(ref_url)+"'";
url+=" width='72"+"8' height='9"+"0' /"+">";
url+="</object>";
}
else
{
url="<"+"a href='"+ref_url+"' target='_blank'>";
url+="<img src='"+img_url+"'>";
url+="</a>";
}
e.innerHTML=url;
}
function setCompanion(ad_tag,show_ad)
{
var ord=Math.random()*10000000000000000;
if(show_ad=='true'){
ad_tag=ad_tag+"?";
showDiv("watch-channel-brand-div");
_gel("ad300x250").innerHTML='<iframe src="'+ad_tag+'" name="ifr_300x250ad" id="ifr_300x250ad" width="300" height="250" marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=no>'+'<'+'/iframe>';
}
}
function setCompanionError()
{
var ord=Math.random()*10000000000000000;
var watchDCDefaultAd=gCompanionAdUrl+'ord='+ord+'?';
showDiv("watch-channel-brand-div");
_gel("ad300x250").innerHTML='<iframe src="'+watchDCDefaultAd+'" name="ifr_300x250ad" id="ifr_300x250ad" width="300" height="250" marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=no>'+'<'+'/iframe>';
}
function toggleSearchSettings(){
///toggleClass(_gel('search-settings-form'),'hid');
addClass(_gel('search-advanced-form'),'hid');
toggleClass(_gel('search-settings-clr'),'hid');
return false;
}
function toggleAdvSearch(search_query,search_duration,search_hl,search_categories,search_sort,search_uploaded){
toggleClass(_gel('search-advanced-form'),'hid');
///addClass(_gel('search-settings-form'),'hid');
toggleClass(_gel('search-settings-clr'),'hid');
if(_gel('search-advanced-form').innerHTML.toLowerCase().indexOf('<form')!=-1){
return false;
}
var params=new Object();
params['action_advanced']='1';
params['search_query']=search_query;
params['search_duration']=search_duration;
params['search_hl']=search_hl;
params['search_sort']=search_sort;
params['search_uploaded']=search_uploaded;
var url=buildUrl('/results_ajax',params);
var categories=search_categories.split(',');
for(var i=0;i<categories.length;i++){
url+='&search_category='+categories[i];
}
var callback=function(){
var setting=yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_SAFE_SEARCH);
_gel('search-filter-checkbox').checked=setting;
setting=gGoogleSuggest||yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_SUGGEST_ENABLED);
_gel('search-suggest-checkbox').checked=setting;
};
showAjaxDivLoggedIn('search-advanced-form',url,callback);
return false;
}
function toggleAdvSearchCategories(){
if(_gel('search-categories-all').checked){
hideDiv('search-advanced-categories');
}else{
showDiv('search-advanced-categories');
}
}
function submitRegularSearchRequest(){
if(document.advSearchForm){
if(document.searchForm.search_query.value&&!document.advSearchForm.and_queries.value){
document.advSearchForm.and_queries.value=document.searchForm.search_query.value;
}
submitAdvancedSearchRequest();
setTimeout("document.advSearchForm.submit()",0);
return false;
}else{
return true;
}
}
function submitAdvancedSearchRequest(){
var and_queries=document.advSearchForm.and_queries.value;
var exact_query=document.advSearchForm.exact_query.value;
var or_queries=document.advSearchForm.or_queries.value;
var negative_queries=document.advSearchForm.negative_queries.value;
var search_query=and_queries;
if(exact_query.length>0){
search_query+=' "'+exact_query+'"';
}
var or_queries_array=or_queries.split(' ');
for(var x=or_queries_array.length-1;x>=0;x--){
if(or_queries_array[x]==''){
or_queries_array.splice(x,1);
}
}
if(or_queries_array.length>0){
search_query+=' ('+or_queries_array.join(' OR ')+')';
}
var negative_queries_array=negative_queries.split(' ');
for(var x=negative_queries_array.length-1;x>=0;x--){
if(negative_queries_array[x]==''){
negative_queries_array.splice(x,1);
}else{
negative_queries_array[x]='-'+negative_queries_array[x];
}
}
if(negative_queries_array.length>0){
search_query+=' '+negative_queries_array.join(' ');
}
_gel('adv_search_query').value=search_query;
return true;
}
function setSafeSearchPref(){
if(gIsResultsPage){
window.location.href=gResultsSafeSearchOn?gResultsURLSafeSearchOff:gResultsURLSafeSearchOn;
_gel('search-filter-checkbox').disabled=true;
}else{
var setting=yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_SAFE_SEARCH);
yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_SAFE_SEARCH,!setting);
yt.UserPrefs.save();
}
}
function setSearchSuggestPref(){
var setting=yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_SUGGEST_ENABLED);
var hasSetting=yt.UserPrefs.getFlag(yt.UserPrefs.Flags.FLAG_HAS_SUGGEST_ENABLED);
yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_SUGGEST_ENABLED,hasSetting?!setting:!gGoogleSuggest);
yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_HAS_SUGGEST_ENABLED,true);
yt.UserPrefs.save();
}
function searchViewType(viewType){
hideDiv(viewType=='L'?"btn-listview-off":"btn-listview-on");
showDiv(viewType=='L'?"btn-listview-on":"btn-listview-off");
hideDiv(viewType=='L'?"btn-gridview-on":"btn-gridview-off");
showDiv(viewType=='L'?"btn-gridview-off":"btn-gridview-on");
if(_gel('video_grid')){
var type=_gel('video_grid').className.indexOf('browse')!=-1?'browse':'members';
removeClass(_gel('video_grid'),viewType=='L'?type+'GridView':type+'ListView');
addClass(_gel('video_grid'),viewType=='L'?type+'ListView':type+'GridView');
}
yt.UserPrefs.setFlag(yt.UserPrefs.Flags.FLAG_GRID_VIEW_SEARCH_RESULTS,viewType=='G');
yt.UserPrefs.save();
return false;
}
var videolist=new Array();
var removelist=new Array();
var qlUIEnabled=false;
function initWatchQueue(isUIEnabled){
qlUIEnabled=isUIEnabled;
}
function append_token_for_queue(queryParams){
return queryParams+'&'+gXSRF_ql_pair;
}
function clearWatchQueue(){
postUrlXMLResponse("/watch_queue_ajax",append_token_for_queue("&action_clear_queue"),watchQueueCleared);
var suffix="QL";
var videosInPlaylist=getPlaylistVideoCount(suffix);
decrementPlaylistVideoCount(suffix,videosInPlaylist);
for(var i=videosInPlaylist-1;i>=0;i--){
var videoRow=_gel(getId("playlistRow",suffix,i));
videoRow.parentNode.removeChild(videoRow);
}
var container=_gel(getId("playlistContainer",suffix));
addClass(container,"watch-playlist-auto-height");
removeClass(container,"watch-playlist-fixed-height175");
videolist=new Array();
removelist=new Array();
for(var i=0;i<quickAddDoneList.length;i++){
removeClass(quickAddDoneList[i],'QLIconImgOver');
removeClass(quickAddDoneList[i],'QLIconImgDone');
addClass(quickAddDoneList[i],'QLIconImg');
showDiv(quickAddDoneList[i]);
hideDiv(getQuicklistInlist(quickAddDoneList[i]));
}
quickAddDoneList=new Array();
hideDiv("quicklistDiv");
var qUtil=getQuicklistUtility();
if(qUtil){
qUtil.innerHTML="0";
quicklistMastheadBlink();
}
}
function watch_remove_from_watch_queue(video_id){
removelist.push(video_id);
watch_delete_videos_from_server();
return false;
}
/// Invokes the given callback on success or on error.
function XMLResponseSuccsssOrFailureCallbackAdapter(callback){
this.onSuccessCallback=callback;
this.onErrorCallback=callback;
}
var qlIsEditing=false;
function watch_post_videos_to_server(){
if(videolist.length>0&&!qlIsEditing){
qlIsEditing=true;
var nextVideoId=videolist.shift();
var queryParams="&action_add_to_queue&video_id="+nextVideoId;
if(qlUIEnabled){
queryParams+="&ui=1";
}
postUrlXMLResponseJSON("/watch_queue_ajax",
append_token_for_queue(queryParams),
new XMLResponseSuccsssOrFailureCallbackAdapter(
function(response){
watchVideoQueued(response,nextVideoId);
}
)
);
}
}
var isRemoving=false;
function watch_delete_videos_from_server(){
if(removelist.length>0){
isRemoving=true;
postUrlXMLResponse("/watch_queue_ajax",append_token_for_queue("&action_remove_from_queue&video_id="+removelist[removelist.length-1]),self.watchVideoRemoved);
removelist.pop();
}
isRemoving=false;
if(delayedCompletePlaylistLoad){
completePlaylistLoad();
}
}
function watchQueueCleared(xmlHttpRequest){
}
function cleanupAfterFailedToAddVideoToPlaylist(vid){
var qUtil=_gel('quicklist-utility');
if(qUtil){
qUtil.innerHTML=parseInt(qUtil.innerHTML)-1;
}
var row=findQuickListRowElementFromVideoId(vid);
if(row){
watchRemoveVideo('QL',row);
}
}
function watchVideoQueued(videos,requestedVideoId){
qlIsEditing=false;
if(videos==undefined){
cleanupAfterFailedToAddVideoToPlaylist(requestedVideoId);
}
if(qlUIEnabled){
if(videos){
handleWatchQueueGet(videos);
}
}
watch_post_videos_to_server();
}
function watchVideoRemoved(xmlHttpRequest){
watch_delete_videos_from_server();
}
/// CZACH-Appears not to be used...is buggy(button_name==undefined)
var autoScrolledTo=null;
function scrollPlaylistToVideo(suffix,index){
var videoRow=_gel(getId('playlistRow',suffix,index));
_gel(getId("playlistContainer",suffix)).scrollTop=videoRow.offsetTop;
autoScrolledTo=_gel(getId("playlistContainer",suffix)).scrollTop;
}
function registerPlaylistAutoload(component){
if(component=='QL'&&qlAutoscrollDestination>0){
scrollPlaylistToVideo(component,qlAutoscrollDestination);
}
_gel(getId('playlistContainer',component)).onscroll=completePlaylistLoad;
if(autoScrolledTo&&autoScrolledTo!=_gel(getId("playlistContainer",'QL')).scrollTop){
completePlaylistLoad();
}
}
/// document me
function clickedQuickAdd(img,videoId,alreadyInList){
quickAddDoneList[quickAddDoneList.length]=img;
removeClass(img,'QLIconImg');
removeClass(img,'QLIconImgOver');
addClass(img,'QLIconImgDone');
///hideDiv(getQuicklistTooltip(img));
hideDiv(img);
showDiv(getQuicklistInlist(img));
if(!alreadyInList){
videolist.push(videoId);
watch_post_videos_to_server();
var qUtil=getQuicklistUtility();
if(qUtil){
qUtil.innerHTML=parseInt(qUtil.innerHTML)+1;
}
}
}
///
var quickAddDoneList=new Array();
function getUrlFromPlaylistRow(rowNode){
var links=rowNode.getElementsByTagName('a');
return links[0].href;
}
function watchRemoveVideo(suffix,playlistRow){
/// Be willing to climb up if we're not called with the row
	var playlistRow = findAncestorByClass(playlistRow, "watch-playlist-row");
	var vid = getNodeVideoId(playlistRow);
	playlistRow.parentNode.removeChild(playlistRow);
	// renumber	the remaining rows
	var newIndex = 0;
	var videos = getPlaylistVideoCount(suffix);
	for (var i = 0; i < videos; i++) {
		var row = _gel(getId("playlistRow", suffix, i));
		var index = _gel(getId("playlistRowIndex", suffix, i));
		if (row) {
			row.id = getId("playlistRow", suffix, newIndex);
			index.id = getId("playlistRowIndex", suffix, newIndex);
			index.innerHTML = newIndex + 1; // human index
			newIndex++;
		}
	}
	var newCount = decrementPlaylistVideoCount(suffix, 1);
	watch_remove_from_watch_queue(vid);
	var containerNode = _gel(getId("playlistContainer", 'QL'));
	if (newCount == 0) {
		hidePlaylist(suffix);
	} else if (newCount < 4) {
		addClass(containerNode, "watch-playlist-auto-height");
		removeClass(containerNode, "watch-playlist-fixed-height175");
	}
	if (containerNode.onscroll) {
		containerNode.onscroll();
	}
	var qUtil = getQuicklistUtility();
	if (qUtil) {
		qUtil.innerHTML = parseInt(qUtil.innerHTML) - 1;
		quicklistMastheadBlink();
	}
}
function hidePlaylist(component) {
	showDiv(getId("playlistClosed", component));
	hideDiv(getId("playlistOpen", component));
	return false;
}
function isCurrentlyPlayingVideoInPlaylist(component) {
	var videosInPlaylist = getPlaylistVideoCount(component);
	for (var i = 0; i < videosInPlaylist; i++) {
		var currentRow = _gel(getId('playlistRow', component, i));
		if (hasClass(currentRow, 'watch-playlist-row-playing')) {
			return true;
		}
	}
}
function gotoNext(loopPlayback) {
	if (playnextFrom) {
		var firstRow = _gel(getId("playlistRow", playnextFrom, 0));
		var nextRow = firstRow;
		var videosInPlaylist = getPlaylistVideoCount(playnextFrom);
		for (var i = 0; i < videosInPlaylist; i++) {
			var currentRow = _gel(getId('playlistRow', playnextFrom, i));
			if (hasClass(currentRow, 'watch-playlist-row-playing')) {
				if (i+1 < videosInPlaylist) {
					nextRow = _gel(getId('playlistRow', playnextFrom, i+1));
				} else if (loopPlayback) {
					nextRow = firstRow;
				} else {
					nextRow = null;
				}
				break;
			}
		}
		if (nextRow) {
			window.location = getUrlFromPlaylistRow(nextRow) + "&playnext=" + (parseInt(playnextCount) + 1) + "&playnext_from=" + playnextFrom;
		}
	}
}
var autoNextComponents = ['PL', 'QL'];
function autoNext(suffix) {
	playnextFrom = suffix;
	
	for (var i = 0; i < autoNextComponents.length; i++) {
		if (autoNextComponents[i] == suffix) {
			showDiv(getId("playingall", autoNextComponents[i]));
			hideDiv(getId("playall", autoNextComponents[i]));
		} else {
			hideDiv(getId("playingall", autoNextComponents[i]));
			showDiv(getId("playall", autoNextComponents[i]));
		}
	}
	var p = _gel("movie_player");
	var finishedPlayback = false;
	try {
		finishedPlayback = p.GetVariable("movie.is_playing") == "false" && p.GetVariable("movie.restart") == "true";
	} catch(err) {
		finishedPlayback = p.GetVariable("is_playing") == "false" && p.GetVariable("restart") == "true";
	}
	if(finishedPlayback || !isCurrentlyPlayingVideoInPlaylist(suffix)) {
		gotoNext();
	} else {
		p.SetVariable("playnext", "1");
	}
	return false;
}
function autoNextOff(suffix) {
	playnextFrom = "";
	_gel("movie_player").SetVariable("playnext", "0");
	showDiv(getId("playall", suffix));
	hideDiv(getId("playingall", suffix));
	return false;
}
function redirectToQuicklistFromWatch() {
	autoNext('QL');
}
function getId(baseId, component, opt_index) {
	var id = baseId;
	if (component != null)
		id += "_" + component;
	if (opt_index != null)
		id += "_" + opt_index;
	return id;
}
function findQuickListRowElementFromVideoId(encryptedId) {
	var suffix='QL';
	var videosInPlaylist = getPlaylistVideoCount(suffix);
	for (var i = 0; i < videosInPlaylist; i++) {
		var videoRow = _gel(getId("playlistRow", suffix, i));
		if (encryptedId == getNodeVideoId(videoRow)) {
			return videoRow;
		}
	}
	return undefined;
}
function onQuickAddClick(imgClicked, encryptedId) {
	//imgClicked.onclick = null;			// we need the onclick to stick around if we clear the list, and need to click again
	imgClicked.blur();
		
	var alreadyInList = findQuickListRowElementFromVideoId(encryptedId) != undefined;
	if (!alreadyInList) {
		// make the new row
		var newRow = _gel("playlistRow_placeholder_QL").cloneNode(true /*deep*/);
		var videoCount = getPlaylistVideoCount("QL");
		fillQuicklistRow(newRow, videoCount, "", "http://s.ytimg.com/yt/img/pixel-vfl73.gif", MSG_Loading, "", "");
		removeClass(newRow, "hide");
		setNodeVideoId(newRow, encryptedId);
		addClass(newRow, "loading");
		
		// insert at the end
		var rows = _gel("playlistRows_QL");
		var lastRow = _gel(getId('playlistRow', 'QL', videoCount-1));
		if (lastRow) {
			rows.insertBefore(newRow, lastRow.nextSibling);
		} else {
			rows.insertBefore(newRow, rows.firstChild);
		}
		scrollPlaylistToVideo('QL', videoCount);
		
		// become fixed sized at 5 videos and scroll from there on up
		if (incrementPlaylistVideoCount('QL', 1) > 4) {
			var containerNode = _gel(getId("playlistContainer", 'QL'));
			removeClass(containerNode, "watch-playlist-auto-height");
			addClass(containerNode, "watch-playlist-fixed-height175");
		}
	}
	
	clickedQuickAdd(imgClicked, encryptedId, alreadyInList);
	//urchinTracker('/VideoWatch/QuickList+AddTo');
	// in case it's hidden
showDiv('quicklistDiv');
return false;
}
/// Delay loading of videos
var delayedCompletePlaylistLoad=false;
function completePlaylistLoad(){
if(!isRemoving){
var suffix="QL";
_gel('playlistContainer_'+suffix).onscroll="";
postUrlXMLResponseJSON("/watch_queue_ajax",append_token_for_queue("v="+pageVideoId+"&action_get_all_queue_videos_component=1&all=1&watch3=1"),handleWatchQueueGet);
}else{
delayedCompletePlaylistLoad=true;
}
}
function handleWatchQueueGet(videos){
for(var i=0;i<videos.length;i++){
if(videos[i]){
var newRow=_gel("playlistRow_placeholder_QL").cloneNode(true);
removeClass(newRow,"hide");
videos[i].unshift(newRow);
fillQuicklistRow.apply(this,videos[i]);
newRow.id=getId("playlistRow","QL",videos[i][1]);
var oldNode=_gel(newRow.id);
if(hasClass(oldNode,"loading")){
oldNode.parentNode.replaceChild(newRow,oldNode);
}
}
}
}
/// Playlist videos count helpers
function getPlaylistVideoCount(component){
return parseInt(_gel('playlistVideoCount_'+component).innerHTML);
}
function incrementPlaylistVideoCount(component,increment_by){
var node=_gel('playlistVideoCount_'+component);
node.innerHTML=parseInt(node.innerHTML)+increment_by;
return parseInt(node.innerHTML);
}
function decrementPlaylistVideoCount(component,decrement_by){
return incrementPlaylistVideoCount(component,-1*decrement_by);
}
/// *************************STORED VALUES****************************
/// Specific value helpers
function getNodeVideoId(node){
return getStoredValue(node,"v")
}
function setNodeVideoId(node,id){
return setStoredValue(node,"v",id)
}
/// General implementation
function getStoredValue(node,name){
var classes=getClassList(node);
var pattern=classNameForNameValue(name,'');
var found=false;
for(var i=0;i<classes.length;i++){
if(classes[i].substr(0,pattern.length)==pattern){
return classes[i].substr(pattern.length,classes[i].length-pattern.length);
}
}
return '';
}
function setStoredValue(node,name,value){
var classes=getClassList(node);
var pattern=classNameForNameValue(name,'');
var found=false;
for(var i=0;i<classes.length;i++){
if(classes[i].substr(0,pattern.length)==pattern){
classes[i]=classNameForNameValue(name,value);
found=true;
}
}
if(!found){
classes.push(classNameForNameValue(name,value));
}
node.className=classes.join(' ');
}
function classNameForNameValue(name,value){
return name+"*"+value;
}
/// *************************TEMPLATES****************************
/// Template Implementation
function TemplateParameters(params){
this.addParameter=addParameter;
this.applyToNode=applyToNode;
this.fill=fill;
this.parameters=new Object();
for(var i=0;i<params.length;i++){
this.addParameter(params[i][0],params[i][1],params[i][2]);
}
function Parameter(attrName,value){
this.attrName=attrName;
this.value=value;
}
function addParameter(className,attrName,value){
if(!this.parameters[className]){
this.parameters[className]=new Array();
}
this.parameters[className].push(new Parameter(attrName,value));
}
function applyToNode(node){
var classList=getClassList(node);
for(var i=0;i<classList.length;i++){
var applyList=this.parameters[classList[i]];
if(applyList){
for(var j=0;j<applyList.length;j++){
if(typeof(applyList[j].value)=="function"){
node[applyList[j].attrName]=applyList[j].value();
}else{
node[applyList[j].attrName]=applyList[j].value;
}
}
}
}
}
function fill(node){
for(var i=0;i<node.childNodes.length;i++){
this.fill(node.childNodes[i]);
}
this.applyToNode(node);
}
}
/// Usage helpers
function fillQuicklistRow(rowDiv,index,videoUrl,imgSrc,title,username,duration,opt_videoId){
var params=new TemplateParameters(
[['watch-playlist-row','id',getId("playlistRow","QL",index)],
['watch-playlist-row','class',function(node){setNodeVideoId(rowDiv,opt_videoId)}],
['phIndex','id',getId("playlistRowIndex","QL",index)],
['phIndex','innerHTML',index+1],
['watch-playlist-row-link','href',videoUrl],
['vimg50','src',imgSrc],
['vtitle','innerHTML',title],
['phUsername','innerHTML',username],
['watch-playlist-item-duration','innerHTML',duration]]);
params.fill(rowDiv);
}
function quickListShowRelated(node){
var q='/related_ajax?view_type=L&watch3=1&video_id='+
getNodeVideoId(findAncestorByClass(node,"watch-playlist-row"));
getUrlXMLResponseAndFillDiv(
q,
'watch-related-vids-body',
function(){
addQLIcons(_gel('watch-related-vids-body'));
}
);
}
var gQuicklistTimeoutId;
function quicklistMastheadBlink(){
if(gQuicklistTimeoutId){
window.clearTimeout(gQuicklistTimeoutId);
gQuicklistTimeoutId=null;
}
quicklistMastheadBlinkHelper(1);
}
function quicklistMastheadBlinkHelper(on){
var qUtil=getQuicklistUtility();
qUtil.style.backgroundColor=on%2?'#ff0':'#fff';
++on;
if(on<=10){
gQuicklistTimeoutId=window.setTimeout(function(){quicklistMastheadBlinkHelper(on);},500);
}
}
function getQuicklistUtility(){
return self.utilLinksFrame?self.utilLinksFrame.document.getElementById('quicklist-utility'):_gel('quicklist-utility');
}
/// *************************functions used outside of the watch page****************************
var quicklistVideoIds=[];
function clicked_add_icon(imgClicked,video_ID,fromRelated){
/// make sure there is no duplicate on the list
for(var x=0;x<quicklistVideoIds.length;++x){
if(quicklistVideoIds[x]==video_ID){
return;
}
}
var qUtil=getQuicklistUtility();
if(qUtil){
qUtil.innerHTML=parseInt(qUtil.innerHTML)+1;
}
quicklistVideoIds.push(video_ID);
add_to_watch_queue(video_ID);
removeClass(imgClicked,'QLIconImg');
removeClass(imgClicked,'QLIconImgOver');
addClass(imgClicked,'QLIconImgDone');
imgClicked.blur();
///hideDiv(getQuicklistTooltip(imgClicked));
hideDiv(imgClicked);
showDiv(getQuicklistInlist(imgClicked));
}
function add_to_watch_queue(video_id){
videolist.push(video_id);
post_videos_to_server();
return false;
}
function post_videos_to_server(){
if(videolist.length>0){
postUrlXMLResponse("/watch_queue_ajax",append_token_for_queue("&action_add_to_queue&video_id="+videolist[videolist.length-1]),self.videoQueued);
videolist.pop();
}
}
function videoQueued(xmlHttpRequest){
var xmlObj=xmlHttpRequest.responseXML;
if(xmlObj!=null&&getNodeValue(xmlObj,"msg")!="exists"){
post_videos_to_server();
}
}
function remove_from_watch_queue(video_id){
removelist.push(video_id);
delete_videos_from_server();
return false;
}
function delete_videos_from_server(){
if(removelist.length>0){
postUrlXMLResponse("/watch_queue_ajax",append_token_for_queue("&action_remove_from_queue&video_id="+removelist[removelist.length-1]),self.videoRemoved);
removelist.pop();
}
}
function videoRemoved(xmlHttpRequest){
delete_videos_from_server();
}
function clear_watch_queue(){
postUrlXMLResponse("/watch_queue_ajax",append_token_for_queue("&action_clear_queue"),function(){window.location="watch_queue?all";});
}
function set_pop_status(pop_status){
postUrlXMLResponse("/watch_queue_ajax",append_token_for_queue("action_set_pop_status&pop_videos="+pop_status),function(){})
}
function mouseOverQuickAdd(img,id){
if(!img.className.match('Done')){
removeClass(img,'QLIconImg');
removeClass(img,'QLIconImgDone');
addClass(img,'QLIconImgOver');
///showDiv(getQuicklistTooltip(img));
}
}
function mouseOutQuickAdd(img,id){
if(!img.className.match('Done')){
removeClass(img,'QLIconImgOver');
removeClass(img,'QLIconImgDone');
addClass(img,'QLIconImg');
}
///hideDiv(getQuicklistTooltip(img));
}
function addQLIcons(el){
var img=(el?el:document).getElementsByTagName('img');
for(var x=0;x<img.length;++x){
var vId=img[x].getAttribute("qlicon");
var vId2=img[x].getAttribute("qliconalt");
var isPartner=img[x].getAttribute("partner");
if(!vId&&!vId2){
continue;
}
var id=vId?vId:vId2;
img[x].parentNode.parentNode.innerHTML+=
'<div class="addtoQL90">'
+'<a href="#" title="'+gQuickListTooltipText+'">'
+'<img class="QLIconImg" src="'+gPixelGif+'" border="0" '
+(vId?'onclick="return onQuickAddClick(this, \''+vId+'\')" '
:'onclick="clicked_add_icon(this, \''+vId2+'\', 0);_hbLink(\'QuickList+AddTo\',\'na\');return false;" ')
+'onmouseover="mouseOverQuickAdd(this, \''+id+'\')" '
+'onmouseout="mouseOutQuickAdd(this, \''+id+'\')">'
+'</a>'
+'<div class="hid quicklist-inlist">'+gInQuickListText+'</div>'
/// todo:rework for FF2
///+'<div class="clear"></div>'
///+'<div class="hid tooltip-wrapper-box">'
///+'<div class="tooltip-box">'
///+gQuickListTooltipText
///+'</div><img class="tooltip-box-bot" src="'+gPixelGif+'">\r\n</div>'
+'</div>'
+(isPartner?'<div class="partner-marker-box">'
+'<a href="/watch?v='+id+'" title="'+gPartnerVideoText+'">'
+'<img class="partner-marker" src="'+gPixelGif+'">'
+'</a>'
+'</div>'
:'');
img[x].removeAttribute("qlicon");
img[x].removeAttribute("qliconalt");
}
}
onLoadFunctionList.push(addQLIcons);
function getQuicklistInlist(img){
return(getElementsByTagNameAndClass('DIV','quicklist-inlist',img.parentNode.parentNode))[0];
}
///function getQuicklistTooltip(img){
///	return(getElementsByTagNameAndClass('DIV','tooltip-wrapper-box',img.parentNode.parentNode))[0];
///}
var imageBrowsers=new Object();
var images_loaded=false;
function shiftLeft(bar_id){
if(images_loaded==false){
return;
}
imageBrowsers[bar_id].shiftPageLeft();
imageBrowsers[bar_id].showImages();
}
function shiftRight(bar_id){
if(images_loaded==false){
return;
}
imageBrowsers[bar_id].shiftPageRight();
imageBrowsers[bar_id].showImages();
}
/// CLASS:ImageBrowser:Stores the array of ytImage and does stuff with them
function ImageBrowser(display_count,step_size,root_div_id){
this.display_count=display_count;
this.step_size=step_size;
this.root_div_id=root_div_id;
this.display_array=new Array();
this.addImage=imageBrowserAddImage;
this.initDisplay=imageBrowserInitDisplay;
this.shiftPageLeft=imageBrowserShiftPageLeft;
this.shiftPageRight=imageBrowserShiftPageRight;
this.shiftLeft=imageBrowserShiftLeft;
this.shiftRight=imageBrowserShiftRight;
this.showImages=imageBrowserShowImages;
this.prefetchNextPrevImages=imageBrowserPrefetchNextPrevImages;
this.printPageNumbers=imageBrowserPrintPageNumbers;
this.incrementIndex=imageBrowserIncrementIndex;
this.decrementIndex=imageBrowserDecrementIndex;
}
function imageBrowserAddImage(ytImg){
if(!this.images){
var images=new Array();
this.images=images;
}
///ytImg.title1="Index: "+this.images.length;
this.images.push(ytImg);
}
function imageBrowserInitDisplay(){
this.real_images_count=this.images.length;
/// Fill with empty images to match page size.
var empty_slots=this.display_count-(this.images.length%this.display_count);
if(empty_slots!=this.display_count){
for(var i=0;i<empty_slots;i++){
this.addImage(new ytImage("http://s.ytimg.com/yt/img/pixel-vfl73.gif","","&nbsp;","","&nbsp;","",true));
}
}
for(var i=0;i<this.display_count;i++){
this.display_array[i]=this.images[i];
}
this.head_index=0;
this.tail_index=this.display_array.length-1;
}
function imageBrowserIncrementIndex(index){
index=index+1;
if(index>this.images.length-1){
index=0;
}
return index;
}
function imageBrowserDecrementIndex(index){
index=index-1;
if(index<0){
index=this.images.length-1;
}
return index;
}
function imageBrowserShiftPageRight(){
for(var i=0;i<this.display_array.length;i++){
this.shiftRight();
}
}
function imageBrowserShiftPageLeft(){
for(var i=0;i<this.display_array.length;i++){
this.shiftLeft();
}
}
function imageBrowserShiftRight(){
/// First shift all elements in display_array,and fill the last one.
for(var i=0;i<this.display_array.length;i++){
this.display_array[i]=this.display_array[i+1];
}
this.head_index=this.incrementIndex(this.head_index);
this.tail_index=this.incrementIndex(this.tail_index);
this.display_array[this.display_array.length-1]=this.images[this.tail_index];
}
function imageBrowserShiftLeft(){
for(var i=this.display_array.length-1;i>0;i--){
this.display_array[i]=this.display_array[i-1];
}
this.head_index=this.decrementIndex(this.head_index);
this.tail_index=this.decrementIndex(this.tail_index);
this.display_array[0]=this.images[this.head_index];
}
function imageBrowserShowImages(){
for(var i=0;i<this.display_array.length;i++){
var tempDivId="img_"+this.root_div_id+"_"+i;
var block=document.getElementById("img_"+this.root_div_id+"_"+i);
var title1=document.getElementById("title1_"+this.root_div_id+"_"+i);
var title2=document.getElementById("title2_"+this.root_div_id+"_"+i);
var title3=document.getElementById("title3_"+this.root_div_id+"_"+i);
block.src=this.display_array[i].getImage();
title1.innerHTML=this.display_array[i].title1;
title2.innerHTML="<span style='color: #333'>"+this.display_array[i].title2+"</span>";
if(title3!=null){
title3.innerHTML="<span style='color: #666'>"+this.display_array[i].title3+"</span>";
}
var imgUrl=document.getElementById("href_"+this.root_div_id+"_"+i);
imgUrl.href=this.display_array[i].imageUrl;
if(this.display_array[i].id){
block.setAttribute('qlicon',this.display_array[i].id);
}
if(imgUrl.nextSibling&&imgUrl.nextSibling.nextSibling&&imgUrl.nextSibling.nextSibling.className=='addtoQL90'){
imgUrl.parentNode.removeChild(imgUrl.nextSibling.nextSibling);
}
var maindiv=document.getElementById("div_"+this.root_div_id+"_"+i);
var maindiv_alternate=document.getElementById("div_"+this.root_div_id+"_"+i+"_alternate");
if(this.display_array[i].is_dummy!=false){
maindiv.style.display="none";
maindiv_alternate.style.display="";
}else{
maindiv.style.display="";
maindiv_alternate.style.display="none";
}
addQLIcons(maindiv);
}
this.printPageNumbers();
///this.prefetchNextPrevImages();
}
function imageBrowserPrefetchNextPrevImages(){
/// Prefetch the next and previous image
var prev_index=this.decrementIndex(this.head_index);
this.images[prev_index].loadImage();
var next_index=this.incrementIndex(this.tail_index);
this.images[next_index].loadImage();
}
function imageBrowserPrintPageNumbers(){
var counter_index=this.head_index;
for(var i=0;i<this.display_array.length;i++){
counter_index=this.incrementIndex(counter_index);
}
if(counter_index==0){
counter_index=this.real_images_count;
}
var counter_div=document.getElementById("counter_"+this.root_div_id);
if(counter_div){
counter_div.innerHTML="["+(this.head_index+1)+" - "+counter_index+" of "+this.real_images_count+"]";
}
}
/// CLASS ytImage:Store the image and some metadata on it.
function ytImage(imgSrc,imgUrl,title1,title1Url,title2,title2Url,title3,opt_is_dummy){
this.smartImage=new smartImageObject(imgSrc,false);
this.imageUrl=imgUrl;
this.is_dummy=opt_is_dummy;
this.title1_full=title1.replace("'","&quot");
this.title2_full=title2.replace("'","&quot");
var max_len=20;
if(title1.length>max_len)
title1=title1.substring(0,max_len-3)+"..."
if(title2.length>max_len)
title2=title2.substring(0,max_len-3)+"..."
if(title3.length>max_len)
title3=title3.substring(0,max_len-3)+"..."
this.title3=title3
if(title1Url.length>0)
this.title1="<a href='"+title1Url+"' title='"+this.title1_full+"'>"+title1+"</a>";
else
this.title1=title1;
if(title2Url.length>0)
this.title2="<a class='dg' href='"+title2Url+"' title='"+this.title2_full+"'>"+title2+"</a>";
else
this.title2=title2;
this.getImage=ytGetImageFromSmartImage;
this.isImageLoaded=ytIsImageLoaded;
this.loadImage=ytLoadImage
}
/// CLASS ytImage:custom image
function customYtImage(imgSrc,imgUrl,title1,title2,title3,is_dummy,id){
this.smartImage=new smartImageObject(imgSrc,false);
this.imageUrl=imgUrl;
this.is_dummy=is_dummy;
this.id=id;
this.title1=title1;
this.title2=title2;
this.title3=title3;
this.getImage=ytGetImageFromSmartImage;
this.isImageLoaded=ytIsImageLoaded;
this.loadImage=ytLoadImage
}
function ytGetImageFromSmartImage(){
return this.smartImage.getImage();
}
function ytIsImageLoaded(){
return this.smartImage.isImageLoaded();
}
function ytLoadImage(){
if(!this.isImageLoaded()){
///this.smartImage.load();
}
}
/// CLASS:Smart Image:Pre-loads the image.
function smartImageObject(imgURI,preload){
this.URI=imgURI;
this.imageobj=null;
/// attach object's methods
		this.load = smartImageObjectLoad; //  force the loading of the image
		this.getImage = smartImageObjectGetImage;
		this.isImageLoaded = smartImageIsImageLoaded;
		if (preload) { 
			//this.load();
		}
	}
	
	function smartImageIsImageLoaded() {
		if(this.imageobj != null)
			return true;
		else
			return false;
	}
	
	function smartImageObjectLoad() {
		this.imageobj = new Image(); 
		this.imageobj.src = this.URI;
	}
	
	function smartImageObjectGetImage() {
		if (this.imageobj) { 
			return this.imageobj.src;
		} else { 
		//this.load();
		return this.URI;
		}
	}
	// Smart Image End.
	// Animation functions for fading in the thumbnail images
	function opacity(id, opacStart, opacEnd, millisec) {
		//speed for each frame
		var speed = Math.round(millisec / 100);
		var timer = 0;
		//determine the direction for the blending, if start and end are the same nothing happens
		if(opacStart > opacEnd) {
				for(var i = opacStart; i >= opacEnd; i--) {
						window.setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
						timer++;
				}
		} else if(opacStart < opacEnd) {
				for(var i = opacStart; i <= opacEnd; i++)
						{
						window.setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
						timer++;
				}
		}
	}
	//change the opacity for different browsers
	function changeOpac(opacity, id) {
		var object = document.getElementById(id).style;
		object.opacity = (opacity / 100);
		object.MozOpacity = (opacity / 100);
		object.KhtmlOpacity = (opacity / 100);
		object.filter = "alpha(opacity=" + opacity + ")";
	}
		function fadeOldImage(id, numColumns) {
			for (var i=0;i< numColumns;i++) {
				var tempDiv = "img_" + id + "_" + i;
				changeOpac(25, tempDiv);
			}
		}
/* #include /js/roger.js */
// wrap in an anonymous namespace
(function() {
	function checkRef(ref) {
		var a = ref.split('/',3);
if(a.length>=3&&a[0]=='http:'&&a[1]==''){
a=a[2].split('.').reverse();
if(a.length<2)return false;
var d0=a[0];
var d1=a[1];
if(d1=='localhost')return true;
if(d1=='google')return true;
if(a.length<3)return false;
if(a[2]=='google'&&((d1=='co'&&d0=='uk')||(d1=='com'&&d0=='au')))return true;
}
return false;
}
if(window!=window.top){
var ref=document.referrer;
if(!checkRef(ref)){
var data='location='+encodeURIComponent(ref)+'&self='+encodeURIComponent(window.location.href);
postUrl('/roger_rabbit',data,true,processReqChange);
}
}
function processReqChange(req){
if(req.readyState==4){
if(req.status==200){
if(req.responseText=='block'){
window.top.location.href='/';
}
}
}
}
})();
/// Auto-generated code.
/// This file depends on existing code in base.js.
if(yt&&yt.UserPrefs){
yt.UserPrefs.Flags={
FLAG_SAFE_SEARCH:0x1,
FLAG_GRID_VIEW_SEARCH_RESULTS:0x2,
FLAG_EMBED_INCLUDE_RELATED_VIDEOS:0x4,
FLAG_EMBED_SHOW_BORDER:0x8,
FLAG_GRID_VIEW_VIDEOS_AND_CHANNELS:0x10,
FLAG_WATCH_EXPAND_ABOUT_PANEL:0x20,
FLAG_WATCH_EXPAND_MOREFROM_PANEL:0x40,
FLAG_WATCH_COLLAPSE_RELATED_PANEL:0x80,
FLAG_WATCH_COLLAPSE_PLAYLIST_PANEL:0x100,
FLAG_WATCH_COLLAPSE_QUICKLIST_PANEL:0x200,
FLAG_WATCH_EXPAND_ALSOWATCHING_PANEL:0x400,
FLAG_WATCH_EXPAND_RESERVED:0x800,
FLAG_STATMODULES_INBOX_COLLAPSED:0x1000,
FLAG_STATMODULES_ABOUTYOU_COLLAPSED:0x2000,
FLAG_STATMODULES_ABOUTVIDEOS_COLLAPSED:0x4000,
FLAG_PERSONALIZED_HOMEPAGE_EXPERIMENT:0x8000,
FLAG_PERSONALIZED_HOMEPAGE_FEED_FEATURED_COLLAPSED:0x10000,
FLAG_PERSONALIZED_HOMEPAGE_FEED_RECOMMENDED_COLLAPSED:0x20000,
FLAG_PERSONALIZED_HOMEPAGE_FEED_SUBSCRIPTIONS_COLLAPSED:0x40000,
FLAG_PERSONALIZED_HOMEPAGE_FEED_POPULAR_COLLAPSED:0x80000,
FLAG_PERSONALIZED_HOMEPAGE_FEED_FRIENDTIVITY_COLLAPSED:0x100000,
FLAG_SUGGEST_ENABLED:0x200000,
FLAG_HAS_SUGGEST_ENABLED:0x400000,
FLAG_WATCH_BETA_PLAYER:0x800000,
FLAG_HAS_REDIRECTED_TO_LOCAL_SITE:0x1000000
}
}
var gCustomEmbedThemes={'blank':'b1b1b1 cfcfcf','storm':'3a3a3a 999999','iceberg':'2b405b 6b8ab6','acid':'006699 54abd6','green':'234900 4e9e00','orange':'e1600f febd01','pink':'cc2550 e87a9f','purple':'402061 9461ca','rubyred':'5d1719 cd311b'};
/// dynamically append a hidden field with the xsrf token to a form.
///
/// @form_obj handle to the form in question
/// @session_token the session token to insert
///
function dynamic_append_session_token(form_obj,opt_session_token)
{
var token=undefined;
if(opt_session_token==null)
{
token=gXSRF_token;
}
else
{
token=opt_session_token;
}
var token_elem=document.createElement('input');
token_elem.setAttribute('name',gXSRF_field_name);
token_elem.setAttribute('type','hidden');
token_elem.setAttribute('value',token);
form_obj.appendChild(token_elem);
}
/// global js variable of forms names to exclude from automagically
/// appending the xsrf token;this should only be used in the case where
/// the form to submit to(form.action)is determined on the client side
session_excluded_forms=new Array();
/// populates an xsrf token into each form on the page
function populate_session_token()
{
for(var form_pos=0;form_pos<document.forms.length;form_pos++)
{
var skip=false;
for(var exclude_pos=0;
exclude_pos<session_excluded_forms.length;
exclude_pos++)
{
/// we specifically exclude some forms since they'll be
			// submitted dynamically (via js) 
			if (document.forms[form_pos].name
					== session_excluded_forms[exclude_pos])
			{
				skip = true;
			}
		}
		var aform = document.forms[form_pos];
		if ((aform.method.toLowerCase() == 'post') && (skip == false))
		{
			// only add if it doesn't exist
var found=false;
for(var elem_pos=0;elem_pos<aform.elements.length;
elem_pos++)
{
var form_field=aform.elements[elem_pos];
if(form_field.name==gXSRF_field_name)
{
found=true;
}
}
if(!found)
{
dynamic_append_session_token(aform);
}
}
}
}
if(typeof deconcept=="undefined"){var deconcept={};}if(typeof deconcept.util=="undefined"){deconcept.util={};}if(typeof deconcept.SWFObjectUtil=="undefined"){deconcept.SWFObjectUtil={};}deconcept.SWFObject=function(_1,id,w,h,_5,c,_7,_8,_9,_a){if(!document.getElementById){return;}this.DETECT_KEY=_a?_a:"detectflash";this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);this.params={};this.variables={};this.attributes=[];if(_1){this.setAttribute("swf",_1);}if(id){this.setAttribute("id",id);}if(w){this.setAttribute("width",w);}if(h){this.setAttribute("height",h);}if(_5){this.setAttribute("version",new deconcept.PlayerVersion(_5.toString().split(".")));}this.installedVer=deconcept.SWFObjectUtil.getPlayerVersion();if(!window.opera&&document.all&&this.installedVer.major>7){if(!deconcept.unloadSet){deconcept.SWFObjectUtil.prepUnload=function(){__flash_unloadHandler=function(){};__flash_savedUnloadHandler=function(){};window.attachEvent("onunload",deconcept.SWFObjectUtil.cleanupSWFs);};window.attachEvent("onbeforeunload",deconcept.SWFObjectUtil.prepUnload);deconcept.unloadSet=true;}}if(c){this.addParam("bgcolor",c);}var q=_7?_7:"high";this.addParam("quality",q);this.setAttribute("useExpressInstall",false);this.setAttribute("doExpressInstall",false);var _c=(_8)?_8:window.location;this.setAttribute("xiRedirectUrl",_c);this.setAttribute("redirectUrl","");if(_9){this.setAttribute("redirectUrl",_9);}};deconcept.SWFObject.prototype={useExpressInstall:function(_d){this.xiSWFPath=!_d?"expressinstall.swf":_d;this.setAttribute("useExpressInstall",true);},setAttribute:function(_e,_f){this.attributes[_e]=_f;},getAttribute:function(_10){return this.attributes[_10]||"";},addParam:function(_11,_12){this.params[_11]=_12;},getParams:function(){return this.params;},addVariable:function(_13,_14){this.variables[_13]=_14;},getVariable:function(_15){return this.variables[_15]||"";},getVariables:function(){return this.variables;},getVariablePairs:function(){var _16=[];var key;var _18=this.getVariables();for(key in _18){_16[_16.length]=key+"="+_18[key];}return _16;},getSWFHTML:function(){var _19="";if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","PlugIn");this.setAttribute("swf",this.xiSWFPath);}_19="<embed type=\"application/x-shockwave-flash\" src=\""+this.getAttribute("swf")+"\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" style=\""+(this.getAttribute("style")||"")+"\"";_19+=" id=\""+this.getAttribute("id")+"\" name=\""+this.getAttribute("id")+"\" ";var _1a=this.getParams();for(var key in _1a){_19+=[key]+"=\""+_1a[key]+"\" ";}var _1c=this.getVariablePairs().join("&");if(_1c.length>0){_19+="flashvars=\""+_1c+"\"";}_19+="/>";}else{if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","ActiveX");this.setAttribute("swf",this.xiSWFPath);}_19="<object id=\""+this.getAttribute("id")+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" style=\""+(this.getAttribute("style")||"")+"\">";_19+="<param name=\"movie\" value=\""+this.getAttribute("swf")+"\" />";var _1d=this.getParams();for(var key in _1d){_19+="<param name=\""+key+"\" value=\""+_1d[key]+"\" />";}var _1f=this.getVariablePairs().join("&");if(_1f.length>0){_19+="<param name=\"flashvars\" value=\""+_1f+"\" />";}_19+="</object>";}return _19;},write:function(_20){if(this.getAttribute("useExpressInstall")){var _21=new deconcept.PlayerVersion([6,0,65]);if(this.installedVer.versionIsValid(_21)&&!this.installedVer.versionIsValid(this.getAttribute("version"))){this.setAttribute("doExpressInstall",true);this.addVariable("MMredirectURL",escape(this.getAttribute("xiRedirectUrl")));document.title=document.title.slice(0,47)+" - Flash Player Installation";this.addVariable("MMdoctitle",document.title);}}if(this.skipDetect||this.getAttribute("doExpressInstall")||this.installedVer.versionIsValid(this.getAttribute("version"))){var n=(typeof _20=="string")?document.getElementById(_20):_20;n.innerHTML=this.getSWFHTML();return true;}else{if(this.getAttribute("redirectUrl")!=""){document.location.replace(this.getAttribute("redirectUrl"));}}return false;}};deconcept.SWFObjectUtil.getPlayerVersion=function(){var _23=new deconcept.PlayerVersion([0,0,0]);if(navigator.plugins&&navigator.mimeTypes.length){var x=navigator.plugins["Shockwave Flash"];if(x&&x.description){_23=new deconcept.PlayerVersion(x.description.replace(/([a-zA-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split("."));}}else{if(navigator.userAgent&&navigator.userAgent.indexOf("Windows CE")>=0){var axo=1;var _26=3;while(axo){try{_26++;axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+_26);_23=new deconcept.PlayerVersion([_26,0,0]);}catch(e){axo=null;}}}else{try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");}catch(e){try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");_23=new deconcept.PlayerVersion([6,0,21]);axo.AllowScriptAccess="always";}catch(e){if(_23.major==6){return _23;}}try{axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash");}catch(e){}}if(axo!=null){_23=new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(","));}}}return _23;};deconcept.PlayerVersion=function(_29){this.major=_29[0]!=null?parseInt(_29[0]):0;this.minor=_29[1]!=null?parseInt(_29[1]):0;this.rev=_29[2]!=null?parseInt(_29[2]):0;};deconcept.PlayerVersion.prototype.versionIsValid=function(fv){if(this.major<fv.major){return false;}if(this.major>fv.major){return true;}if(this.minor<fv.minor){return false;}if(this.minor>fv.minor){return true;}if(this.rev<fv.rev){return false;}return true;};deconcept.util={getRequestParameter:function(_2b){var q=document.location.search||document.location.hash;if(_2b==null){return q;}if(q){var _2d=q.substring(1).split("&");for(var i=0;i<_2d.length;i++){if(_2d[i].substring(0,_2d[i].indexOf("="))==_2b){return _2d[i].substring((_2d[i].indexOf("=")+1));}}}return "";}};deconcept.SWFObjectUtil.cleanupSWFs=function(){var _2f=document.getElementsByTagName("OBJECT");for(var i=_2f.length-1;i>=0;i--){_2f[i].style.display="none";for(var x in _2f[i]){if(typeof _2f[i][x]=="function"){_2f[i][x]=function(){};}}}};if(!document.getElementById&&document.all){document.getElementById=function(id){return document.all[id];};}var getQueryParamValue=deconcept.util.getRequestParameter;var FlashObject=deconcept.SWFObject;var SWFObject=deconcept.SWFObject;
window.google=window.google||{};
(function(){
function H(a,b,c){var d="on"+b;if(a.addEventListener){a.addEventListener(b,c,false)}else if(a.attachEvent){a.attachEvent(d,c)}else{var f=a[d];a[d]=function(){var e=f.apply(this,arguments),h=c.apply(this,arguments);return e==undefined?h:(h==undefined?e:h&&e)}}};var l=window,p=document,ha,P,D,n,V,J="",M=null,y=null,v=null,B=-1,R,g,s,u,C=null,r=null,x,F,ma={},m=null,X,G=0,K=0,U=0,A=null,S,I=false,$=false,da,L,su="";{var xa=navigator.userAgent.toLowerCase();da=xa.indexOf("opera")!=-1;L=xa.indexOf("msie")!=-1&&!da}var Q=null,ya=new RegExp("^[\\s\\u1100-\\u11FF\\u3040-\\u30FF\\u3130-\\u318F\\u31F0-\\u31FF\\u3400-\\u4DBF\\u4E00-\\u9FFF\\uAC00-\\uD7A3\\uF900-\\uFAFF\\uFF65-\\uFFDC]+$"),j="google-ac-",la=true;function Ca(a,b,c,d,f,e,s1){R=a;g=b;ha=d;Q=f;la=e;su=s1;P=qa()==null&&
!ha;var h=l.google.kHL;S=/^(zh-(CN|TW)|ja|ko)$/.test(h);if(c=="search")c="";X="/complete/search?nolabels=t&hl="+ba(h)+(c?"&ds="+ba(c):"")+"&client=suggest";if(P)fa("");H(R,"submit",oa);g.setAttribute("autocomplete","off");H(g,"blur",Fa);H(g,"beforedeactivate",Ga);if(g.addEventListener){g.onkeypress=ua;g.onkeyup=va}else{H(g,L?"keydown":"keypress",ua);H(g,"keyup",va)}n=(V=(D=g.value));s=t("table");s.id="completeTable";s.cellSpacing=(s.cellPadding="0");u=s.style;w(s,j+"m");z();o(p.body,s);if(1){C=t("iframe");r=C.style;
C.id="completeIFrame";r.zIndex="1";r.position="absolute";r.display="block";r.borderWidth=0;o(p.body,C)}W();ja("",[],[]);Ba();if(P){var k=t("div"),i=k.style;O(i,false);i.position="absolute";i.left="0";i.top="-10000";i.width=(i.height="0");var q=t("iframe");q.name=(q.id="completionFrame");q.src=X;o(k,q);o(p.body,k)}H(l,"resize",W);H(l,"pageshow",Ja);if(S)l.setInterval(Ka,10);x=na("aq","f",false);F=na("oq","",true);Z()}function Ja(a){if(a.persisted)x.value="f";F.value=""}function Ba(){var a=p.body.dir==
"rtl",b=a?"right":"left",c=a?"left":"right",d=p.getElementsByTagName("head")[0],f=t("style"),e=null,h=null,k=false;if(p.styleSheets){o(d,f);k=true;e=f.sheet?f.sheet:f.styleSheet}if(!e){h=p.createTextNode("");o(f,h)}var i=function(q,E){var ia=q+" { "+E+" }";if(e){if(e.insertRule){e.insertRule(ia,e.cssRules.length)}else if(e.addRule){e.addRule(q,E)}}else{h.data+=ia+"\n"}};i("."+j+"m","font-size:13px;font-family:arial,sans-serif;cursor:default;line-height:17px;border:1px solid #999;z-index:99;position:absolute;background-color:white;margin:0;");
i("."+j+"a","background-color:white;");i("."+j+"b","background-color:#3366cc;color:white;");i("."+j+"c","white-space:nowrap;overflow:hidden;text-align:"+b+";padding-"+b+":3px;"+(L||da?"padding-bottom:1px;":""));i("."+j+"d","white-space:nowrap;overflow:hidden;font-size:10px;text-align:"+c+";color:#666;padding-"+b+":3px;padding-"+c+":3px;");i("."+j+"b td","color:white;");i("."+j+"e td","padding:0 3px 2px;text-align:"+c+";font-size:10px;line-height:15px;");i("."+j+"e td","color:#03c;text-decoration:underline;cursor:pointer;");
if(!k)o(d,f)}function W(){if(s){u.left=pa(g,"offsetLeft")+"px";u.top=pa(g,"offsetTop")+g.offsetHeight-1+"px";u.width=g.offsetWidth+"px";if(C){r.left=u.left;r.top=u.top;r.width=s.offsetWidth+"px";r.height=s.offsetHeight+"px"}}}function t(a){return p.createElement(a)}function o(a,b){a.appendChild(b)}function O(a,b){a.visibility=b?"visible":"hidden"}function na(a,b,c){var d=t("input");d.type="hidden";d.name=a;d.value=b;d.disabled=c;o(R,d);return d}function Fa(a){if(!I)z();I=false}function Ga(a){if(I){l.event.cancelBubble=
true;l.event.returnValue=false}I=false}function ua(a){var b=a.keyCode;if(b==27&&ka()){z();N(n);a.cancelBubble=true;a.returnValue=false;return false}if(!T(b))return true;U++;if(U%3==1)ea(b);return false}function va(a){var b=a.keyCode;if(!(S&&T(b))&&U==0)ea(b);U=0;return!T(b)}function ea(a){if(S&&T(a))Aa();if(g.value!=D||a==39){n=g.value;if(a!=39)F.value=n}if(ra(a)){wa(B+1)}else if(sa(a)){wa(B-1)}W();if(J!=n&&!A)A=l.setTimeout(z,500);D=g.value;if(D==""&&!M)Z()}function sa(a){return a==38||a==63232}
function ra(a){return a==40||a==63233}function T(a){return sa(a)||ra(a)}function Ha(){g.blur();x.value=B;N(this.completeString);if(la){if(oa()){R.submit()}}else{z()}}function ta(){if($)return;if(v)w(v,j+"a");w(this,j+"b");v=this;for(var a=0;a<y.length;a++){if(y[a]==v){B=a;break}}}function Ia(){if($){$=false;ta.call(this)}}function wa(a){if(J==""&&n!=""){V="";Z();return}if(n!=J||!M)return;if(!y||y.length<=0)return;if(!ka()){ga();return}var b=y.length;if(Q)b-=1;if(v)w(v,j+"a");if(a==b||a==-1){B=-1;
N(n);ca();x.value="f";return}else if(a>b){a=0}else if(a<-1){a=b-1}x.value=a;B=a;v=y.item(a);w(v,j+"b");N(v.completeString)}function z(){if(A){l.clearTimeout(A);A=null}O(u,false);if(C)O(r,false)}function ga(){O(u,true);if(C)O(r,true);W();$=true}function ka(){return u.visibility=="visible"}function za(a,b,c){if(c.length==0||c[0]<2)return;var d=[],f=[],e=c[0],h=Math.floor((c.length-1)/e);for(var k=0;k<h;k++){d.push(c[k*e+1]);f.push(c[k*e+2])}Y(a,b,d,f)}function Y(a,b,c,d){if(G>0)G--;ja(b,c,d);if(b!=
n)return;if(A){l.clearTimeout(A);A=null}J=b;La(s,c,d);B=-1;y=s.rows;if(y.length>0){ga()}else{z()}}function Da(a){var b;a.unshift(b);if(a.length>=3){if(a.length<4)a.push([])}Y.apply(null,a)}function ja(a,b,c){ma[a]=[b,c]}function oa(){if(P)fa("");z();F.disabled=true;if(F.value!=g.value){x.value=B;F.disabled=false}else if(K>=3||G>=10){x.value="o"}return true}function Z(){if(K>=3)return false;if(V!=n){var a=ba(n),b=ma[n];if(b){Y(null,n,b[0],b[1])}else{G++;if(ha){var c=t("script");c.setAttribute("type",
"text/javascript");c.setAttribute("charset","utf-8");c.setAttribute("id","jsonpACScriptTag");c.setAttribute("src","http://suggestqueries.google.com"+X+"&json=t&jsonp=window.google.ac.jsonRPCDone&q="+a);var d=p.getElementById("jsonpACScriptTag"),f=p.getElementsByTagName("head")[0];if(d){f.removeChild(d)}o(f,c)}else if(P){fa(a);l.frames.completionFrame.document.location.reload(true)}else{Ma(a)}}ca()}V=n;var e=100;for(var h=1;h<=(G-2)/2;++h){e*=2}e+=50;M=l.setTimeout(Z,e);return true}function ba(a){if(l.encodeURIComponent)return encodeURIComponent(a);
return escape(a)}function N(a){g.value=a;D=a}function ca(){g.focus()}function pa(a,b){var c=0;while(a){c+=a[b];a=a.offsetParent}return c}function fa(a){p.cookie="qu="+a+"; path=/complete/search"}function w(a,b){a.className=b}function aa(a,b){a.appendChild(p.createTextNode(b))}function La(a,b,c){while(a.rows.length>0)a.deleteRow(-1);for(var d=0;d<b.length;++d){var f=a.insertRow(-1);f.onmousedown=Ha;f.onmouseover=ta;f.onmousemove=Ia;f.completeString=b[d];w(f,j+"a");var e=t("td");aa(e,b[d]);w(e,j+"c");
if(L&&ya.test(b[d]))e.style.paddingTop="2px";o(f,e);var h=t("td");if(d==0)aa(h,su);w(h,j+"d");o(f,h)}if(Q&&b.length>0){var k=a.insertRow(-1);k.onmousedown=function(E){if(E&&E.stopPropagation){E.stopPropagation();ga();g.focus()}else{I=true}return false};var i=t("td");i.colSpan=2;w(k,j+"e");var q=t("span");o(k,i);o(i,q);aa(q,Q);q.onclick=function(){z();J="";l.clearTimeout(M);M=null;x.value="x"}}}function qa(){var a=null;try{a=new ActiveXObject("Msxml2.XMLHTTP")}catch(b){try{a=new ActiveXObject("Microsoft.XMLHTTP")}catch(c){a=
null}}if(!a&&typeof XMLHttpRequest!="undefined")a=new XMLHttpRequest;return a}function Ma(a){if(m&&m.readyState!=0&&m.readyState!=4){m.abort()}if(m)m.onreadystatechange=Ea;m=qa();if(m){m.open("GET",X+"&js=true&q="+a,true);m.onreadystatechange=function(){if(m.readyState==4&&m.responseText){switch(m.status){case 403:K=1000;break;case 302:case 500:case 502:case 503:K++;break;case 200:var b=m.responseText;if(b.charAt(0)!="<"&&(b.indexOf("sendRPCDone")!=-1||b.indexOf("Suggest_apply")!=-1)){eval(b)}else{G--}default:K=
0}}};m.send(null)}}function Ea(){}function Ka(){var a=g.value;if(a!=D)ea(0);D=a}function Aa(){I=true;g.blur();l.setTimeout(ca,10)}l.google.ac={install:Ca,Suggest_apply:za,sendRPCDone:Y,jsonRPCDone:Da,setFieldValue:N};
})();
/// Copyright 2007 Google Inc.
/// All Rights Reserved.
var goog=window.goog?window.goog:{};
goog.i18n={bidi:{}};
goog.i18n.bidi.initialized=false;
goog.i18n.bidi.isSafeUserAgent=-1;
goog.i18n.bidi.safeUserAgent=function(){
if(1==goog.i18n.bidi.isSafeUserAgent)return true;
if(0==goog.i18n.bidi.isSafeUserAgent)return false;
var userAgent=navigator.userAgent.toLowerCase();
var pat=new RegExp("applewebkit/(\\d*)");
var mt=userAgent.match(pat);
if(null==mt){
goog.i18n.bidi.isSafeUserAgent=1;
return true;
}
var ver=userAgent.match(pat)[1];
if(parseInt(ver)>=500){
goog.i18n.bidi.isSafeUserAgent=1;
return true;
}
goog.i18n.bidi.isSafeUserAgent=0;
return false;
}
goog.i18n.bidi.init=function(){
if(goog.i18n.bidi.initialized)return true;
if(!goog.i18n.bidi.safeUserAgent()){
return false;
}
goog.i18n.bidi.ltrChars_=
'A-Za-z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02B8\u0300-\u0590\u0800-\u1FFF'+
'\u2C00-\uFB1C\uFDFE-\uFE6F\uFEFD-\uFFFF';
goog.i18n.bidi.neutralChars_=
'\u0000-\u0020!-@[-`{-\u00BF\u00D7\u00F7\u02B9-\u02FF\u2000-\u2BFF';
goog.i18n.bidi.rtlChars_='\u0591-\u07FF\uFB1D-\uFDFD\uFE70-\uFEFC';
goog.i18n.bidi.ltrDirCheckRe_=new RegExp(
'^[^'+goog.i18n.bidi.rtlChars_+']*['+goog.i18n.bidi.ltrChars_+']');
goog.i18n.bidi.rtlDirCheckRe_=new RegExp(
'^[^'+goog.i18n.bidi.ltrChars_+']*['+goog.i18n.bidi.rtlChars_+']');
goog.i18n.bidi.neutralDirCheckRe_=new RegExp(
'^['+goog.i18n.bidi.neutralChars_+']*$|^http://');
goog.i18n.bidi.initialized=true;
return true;
}
goog.i18n.bidi.isRtlText=function(str){
/// if we cannot init we assume text is LTR
if(!goog.i18n.bidi.init())return false;
return goog.i18n.bidi.rtlDirCheckRe_.test(str);
}
goog.i18n.bidi.isLtrText=function(str){
/// if we cannot init we assume text is LTR
if(!goog.i18n.bidi.init())return true;
return goog.i18n.bidi.ltrDirCheckRe_.test(str);
}
goog.i18n.bidi.isNeutralText=function(str){
/// if we cannot init we assume text is LTR
if(!goog.i18n.bidi.init())return false;
return goog.i18n.bidi.neutralDirCheckRe_.test(str);
}
goog.i18n.bidi.setDirAttribute=function(e,field){
var text=field.value;
var dir='';
if(goog.i18n.bidi.isRtlText(text)){
dir='rtl';
}else if(!goog.i18n.bidi.isRtlText(text)){
dir='ltr';
}
field.dir=dir;
}
