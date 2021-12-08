function getXmlHttpRequest()
{
	var httpRequest = null;
	try
	{
		httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e)
	{
		try
		{
			httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e)
		{
			httpRequest = null;
		}
	}
	if (!httpRequest && typeof XMLHttpRequest != "undefined")
	{
		httpRequest = new XMLHttpRequest();
	}
	return httpRequest;
}

function getUrlSync(url)
{
	return getUrl(url, false, null);
}

function getUrlAsync(url, handleStateChange)
{
	return getUrl(url, true, handleStateChange);
}

// call a url
function getUrl(url, async, opt_handleStateChange) {
	var xmlHttpReq = getXmlHttpRequest();

	if (!xmlHttpReq)
		return;

	if (opt_handleStateChange)
	{
		xmlHttpReq.onreadystatechange = function()
			{
				opt_handleStateChange(xmlHttpReq);
			};
	}
	else
	{
		xmlHttpReq.onreadystatechange = function() {;}
	}

	xmlHttpReq.open("GET", url, async);
	xmlHttpReq.send(null);
}

function postUrl(url, data, async, stateChangeCallback)
{ 
	var xmlHttpReq = getXmlHttpRequest(); 

	if (!xmlHttpReq)
		return;

	xmlHttpReq.open("POST", url, async);
	xmlHttpReq.onreadystatechange = function()
		{
			stateChangeCallback(xmlHttpReq);
		};
	xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttpReq.send(data);
	//alert ('url: ' + url + '\ndata: ' + data);
}

function urlEncodeDict(dict)
{ 
	var result = "";
	for (var i=0; i<dict.length; i++) {
		result += "&" + encodeURIComponent(dict[i].name) + "=" + encodeURIComponent(dict[i].value);
	}
	return result;
}

/* XMLResponseCallback class */
function XMLResponseCallback(successCallback, opt_errorCallback) {
	if (typeof successCallback == "object") {
		this.onSuccessCallback = successCallback.onSuccessCallback;
		this.onErrorCallback = successCallback.onErrorCallback;
	} else if (typeof successCallback == "function") {
		this.onSuccessCallback = successCallback;
		this.onErrorCallback = opt_errorCallback;
	}
}

XMLResponseCallback.prototype = {
	onSuccessCallback : null,
	onErrorCallback : null,
	// Called on success HTTP request, success application response
	onSuccess: function(xmlHttpReq) {
		if (this.onSuccessCallback != null) {
			this.onSuccessCallback(xmlHttpReq);
		}
	},
	// Called on success HTTP request, error application response
	onError: function(xmlHttpReq) {
		if (this.onErrorCallback != null) {
			this.onErrorCallback(xmlHttpReq);
		}
	}
};

/* XMLResponseCallbackJSON class */
function XMLResponseCallbackJSON(callback) {
	var cb = new XMLResponseCallback(callback);
	this.onSuccessCallback = function(xmlHttpReq) {
		cb.onSuccess(eval(getNodeValue(getRootNode(xmlHttpReq), "html_content")));
	};
	this.onErrorCallback = function(xmlHttpReq) {
		cb.onError(eval(getNodeValue(getRootNode(xmlHttpReq), "html_content")));
	};
}

function execOnSuccess(stateChangeCallback, opt_successCallback, opt_divId)
{
	return function(xmlHttpReq)
		{
			if (xmlHttpReq.readyState == 4 &&
					xmlHttpReq.status == 200) {
				if (opt_divId) {
					stateChangeCallback(xmlHttpReq, opt_successCallback, opt_divId);
				} else {
					stateChangeCallback(xmlHttpReq, opt_successCallback);
				}
			}
			//alert(xmlHttpReq + " " + xmlHttpReq.readyState + " " + xmlHttpReq.status);
		};
}

function postFormByForm(form, async, successCallback) {
	var formVars = new Array();
	for (var i = 0; i < form.elements.length; i++)
	{
		var formElement = form.elements[i];
		
		// Special handling for checkboxes and radios (we need an array of selected checkboxes..)!
		if((formElement.type == 'radio' || formElement.type=='checkbox') && !formElement.checked) {
			continue;
		} 
		var v=new Object;
		v.name=formElement.name;
		v.value=formElement.value;
		formVars.push(v);		
	} 
	postUrl(form.action, urlEncodeDict(formVars), async, execOnSuccess(successCallback));
}

function postForm(formName, async, successCallback)
{
	// postFormByName
	var form = document.forms[formName];
	return postFormByForm(form, async, successCallback);
}

function replaceDivContents(xmlHttpRequest, dstDivId)
{
	var dstDiv = document.getElementById(dstDivId);
	dstDiv.innerHTML = xmlHttpRequest.responseText;
}

function getUrlXMLResponseCallback(xmlHttpReq, successCallback) {
	var callback = new XMLResponseCallback(successCallback);

	if(xmlHttpReq.responseXML == null) {
		alert("Error while processing your request.");
		return;
	}
	var root_node = getRootNode(xmlHttpReq);
	var return_code = getNodeValue(root_node, 'return_code');
	//alert("return code " + return_code);

	if(return_code == 0) {
		var redirect_val = getNodeValue(root_node, 'redirect_on_success');
		if(redirect_val != null) {
			window.location=redirect_val;
		} else {
			var success_message = getNodeValue(root_node, 'success_message');
			if (success_message != null) {
				alert(success_message);
			}
			callback.onSuccess(xmlHttpReq);
		}
	} else {
		var error_msg = getNodeValue(root_node, 'error_message');
		if (error_msg != null) {
			alert(error_msg)
		}
		callback.onError(xmlHttpReq);
		if (!callback.onErrorCallback && !error_msg) {
			// no callback and no error message; alert something
			alert("An error occured while performing this operation.");
		}
	}
}

function getUrlXMLResponseCallbackFillDiv(xmlHttpReq, successCallback, div_id) {
	getUrlXMLResponseCallback(xmlHttpReq, successCallback);
	document.getElementById(div_id).innerHTML=getNodeValue(xmlHttpReq.responseXML, "html_content");
}

function getUrlXMLResponseCallbackJSON(xmlHttpReq, successCallback) {
	getUrlXMLResponseCallback(xmlHttpReq, new XMLResponseCallbackJSON(successCallback));
}

function getNodeValue(obj,tag)
{
	var node=obj.getElementsByTagName(tag);
	if(node!=null && node.length>0) {
		return node[0].firstChild.nodeValue;
	} else {
		return null;
	}
}

function getRootNode(xmlHttpReq) {
	return xmlHttpReq.responseXML.getElementsByTagName('root')[0];
}

function getUrlXMLResponse(url, successCallback) {
	getUrl(url, true, execOnSuccess(getUrlXMLResponseCallback, successCallback)) 
}


function getUrlXMLResponseAndFillDiv(url, div_id, opt_successCallback) {
	getUrl(url, true, execOnSuccess(getUrlXMLResponseCallbackFillDiv, opt_successCallback, div_id)) 
}

function getUrlXMLResponseJSON(url, successCallback) {
	getUrl(url, true, execOnSuccess(getUrlXMLResponseCallbackJSON, successCallback)) 
}
getUrlXMLResponseJSON.prototype.getUrlXMLResponseCallbackJSON = getUrlXMLResponseCallbackJSON;
getUrlXMLResponseJSON.prototype.getUrlXMLResponseCallback = getUrlXMLResponseCallback;

function postUrlXMLResponse(url, data, successCallback) {
	postUrl(url, data, true, execOnSuccess(getUrlXMLResponseCallback, successCallback))
}

function postUrlXMLResponseJSON(url, data, successCallback) {
	postUrl(url, data, true, execOnSuccess(getUrlXMLResponseCallbackJSON, successCallback))
}

function postUrlXMLResponseAndFillDiv(url, data, div_id, successCallback) {
	postUrl(url, data, true, execOnSuccess(getUrlXMLResponseCallbackFillDiv, successCallback, div_id))
}

// ANGUS - This appears to be unused...
function confirmAndPostUrlXMLResponse(url, confirmMessage, data, successCallback) {
	if (confirm(confirmMessage)) {
		postUrlXMLResponse(url, data, successCallback);
	}
}

function postFormXMLResponse(formName, successCallback) {
	postForm(formName, true, execOnSuccess(getUrlXMLResponseCallback, successCallback))
}
// CZACH - Commenting it out because it is buggy... hopefully nothing breaks.
/*
function postFormXMLResponseAndFillDiv(formName, div_id, successCallback) {
	postUrl(url, data, async, stateChangeCallback)
}*/
