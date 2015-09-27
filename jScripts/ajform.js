/*
AJFORM - World's Easiest JavaScript AJAX ToolKit
http://projects.aphexcreations.net/ajform/

Brendon Crawford <info at projects dot aphexcreations dotnet>
Jim Manico <jim at manico dotnet>

## v1.4.4 UPDATED 2007-04-27 ##

## BSD LICENSE ##

	Copyright (c) 2005, 2006, 2007 Brendon Crawford
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions
	are met:
	1. Redistributions of source code must retain the above copyright
	   notice, this list of conditions and the following disclaimer.
	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.
	3. The name of the author may not be used to endorse or promote products
	   derived from this software without specific prior written permission.

	THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
	IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
	OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
	IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
	INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
	NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
	DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
	THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
	THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

	STATIC_DOM = new Object;
	
	STATIC_DOM.setEventListener = function( eventName , functionName ) {
		if( document.addEventListener ) {
			this.addEventListener( eventName , functionName , false );
		}
		else if( document.attachEvent ) {
			this.attachEvent( "on" + eventName , functionName );
		}
	}
	STATIC_DOM.getScrollLeft = function( elm ) {
		thisParent = elm.parentNode;
		while(
			thisParent != null &&
			thisParent != 'undefined' &&
			thisParent.nodeName.toLowerCase() != 'body'
		) {
			if( thisParent.scrollLeft > 0 || thisParent.scrollTop > 0 ) {
				return thisParent.scrollLeft;
			}
			thisParent = thisParent.parentNode;
		}
		return 0;
	}
	STATIC_DOM.getScrollTop = function( elm ) {
		thisParent = elm.parentNode;
		while(
			thisParent != null &&
			thisParent != 'undefined' &&
			thisParent.nodeName.toLowerCase() != 'body'
		) {
			if( thisParent.scrollLeft > 0 || thisParent.scrollTop > 0 ) {
				return thisParent.scrollTop;
			}
			thisParent = thisParent.parentNode;
		}
		return 0;
	}
	STATIC_DOM.setMouseToggle = function( thisElm, functionName ) {
		if( document.addEventListener ) {
			thisElm.addEventListener( 'mouseover' , functionName , true );
			thisElm.addEventListener( 'mouseout' , functionName , true );
		}
		else if( document.attachEvent ) {
			thisElm.attachEvent( "onmouseenter" , functionName );
			thisElm.attachEvent( "onmouseleave" , functionName );
		}
	}
	STATIC_DOM.getSourceElement = function( thisElm ) {
		if( window.event != undefined ) {
			return window.event.srcElement;
		}
		else {
			return thisElm;
		}
	}
	STATIC_DOM.isParent = function( child, parent ) {
		thisParent = child.parentNode;
		while( thisParent != null && thisParent != 'undefined' ) {
			if( thisParent == parent ) {
				return true;
			}
			thisParent = thisParent.parentNode;
		}
		return false;
	}
	STATIC_DOM.getEvent = function(e) {
		if( window.event ) {
			eventElm = window.event;
		}
		else if( e ) {
			eventElm = e;
		}
		else {
			alert( "Event not supported" );
		}
		thisEvent = new Object;
		//target element
		if( eventElm.srcElement ) {
			thisEvent.target = eventElm.srcElement;
		}
		else if( eventElm.target ) {
			thisEvent.target = eventElm.target;
		}
		//get pageX|pageY
		if( eventElm.pageX && eventElm.pageY ) {
			thisEvent.pageX = eventElm.pageX;
			thisEvent.pageY = eventElm.pageY;
		}
		else if( eventElm.clientX && eventElm.clientY ) {
			thisEvent.pageX = eventElm.clientX + eventElm.scrollLeft;
			thisEvent.pageY = eventElm.clientY +  eventElm.scrollTop;
		}
		//get elementX|elementY
		if( eventElm.offsetX && eventElm.offsetY ) {
			thisEvent.elementX = eventElm.offsetX;
			thisEvent.elementY = eventElm.offsetY;
		}
		else {
			thisEvent.elementX = thisEvent.pageX - STATIC_DOM.getAbsoluteLeft( thisEvent.target ) + STATIC_DOM.getScrollLeft( thisEvent.target );
			thisEvent.elementY = thisEvent.pageY - STATIC_DOM.getAbsoluteTop( thisEvent.target ) + STATIC_DOM.getScrollTop( thisEvent.target );;
		}
		//toTarget
		if( eventElm.relatedTarget ) {
			thisEvent.toTarget = eventElm.relatedTarget;
			thisEvent.fromTarget = eventElm.relatedTarget;
		}
		else if( eventElm.toElement || eventElm.fromElement ) {
			thisEvent.toTarget = eventElm.toElement;
			thisEvent.fromTarget = eventElm.fromElement;
		}
		//bubbling
		if( eventElm.stopPropagation ) {
			eventElm.stopPropagation();
		}
		else {
			eventElm.cancelBubble = true;
		}
		thisEvent.type = eventElm.type;
		thisEvent.type = (thisEvent.type == 'mouseenter' ? 'mouseover' : thisEvent.type);
		thisEvent.type = (thisEvent.type == 'mouseleave' ? 'mouseout' : thisEvent.type);
		return thisEvent;
	}

	STATIC_DOM.getAbsoluteTop = function(thisObject) {
		var totalTop = 0;
		while( thisObject != null && thisObject != document.body ) {
			totalTop += parseInt( thisObject.offsetTop );
			thisObject = thisObject.offsetParent;
		}
		return totalTop;
	}
	STATIC_DOM.getAbsoluteLeft = function(thisObject) {
		var totalLeft = 0;
		while( thisObject != null && thisObject != document.body ) {
			totalLeft += parseInt( thisObject.offsetLeft );
			thisObject = thisObject.offsetParent;
		}
		return totalLeft;
	}

	AJForm = new Object;
	AJForm.STATUS = {
		'SUCCESS' : 0 ,
		'HTTP_OBJECT_FAILED' : 1 ,
		'FILE_UPLOAD_FAILED' : 2 ,
		'SERVER_ERROR' : 3
	};
	AJForm.getHTTPRequest = function() {
		if( typeof window.ActiveXObject != 'undefined' ) {
			try {
				doc = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e) {
				return false;
			}
		}
		else if( typeof XMLHttpRequest != 'undefined' ) {
			try {
				doc = new XMLHttpRequest();
			}
			catch(e) {
				return false;
			}
		}
		return doc;
	}

	AJForm.activateForm = function() {
		thisForm = STATIC_DOM.getSourceElement(this);
		preRetVal = true;

		if( thisForm.ajform.preCallback != null ) {
			preRetVal = thisForm.ajform.preCallback( thisForm );
		}
		//if the preProcess function returrns false, then we will not send data to the server
		if( preRetVal ) {
			//form.submit() is mapped to AJForm.submitForm
			postRetVal = thisForm.ajform_submit();
			return postRetVal;
		}
		else {
			return false;
		}
	}

	/*
		FORM SUBMISSION:
			submitForm()
		SCRIPTED SUBMISSION:
			form.submitForm( [callbackFunction]] )
			
		ITS IS USEFUL TO KNOW THAT THERE ARE 3 MAJOR PITFALLS THAT CAN BE ENCOUNTERED
		IN BOTH IE AND MOZILLA WHEN DEALING WITH XMLHTTPREQUEST:
			1) DO NOT ATTEMPT TO REUSE THE XMLHTTP OBJECT AFTER A REQUEST. IT MUST BE DESTROYED AND
				A NEW ONE MUST BE CREATED WHEN DOING A NEW REQUEST
			2) USE ASYNCRONOUS DATA WHENEVER POSSIBLE
			3) WHEN USING ASYCRONOUS DATA, ALWAYS SET THE "onreadystatechange" PROPERTY AFTER CALLING
				THE "open()" METHOD.
			4) THE "setRequestHeader" METHOD MUST BE CALLED AFTER THE "onreadystatechange" PROPERTY IS SET 
				AND AFTER THE "open()" METHOD IS CALLED.
	*/
	AJForm.submitForm = function(){
		//if a second argument was specified
		if( AJForm.submitForm.arguments.length ) {
			userFunc =  eval( arguments[0] );
		}
		//if a callback was specified in the form
		else if( this.ajform.postCallback != null ) {
			userFunc = this.ajform.postCallback;
		}
		//If not a valid callback or no callback at all, then return
		if( userFunc == null      ||
			userFunc == ''        ||
			userFunc == undefined ||
			userFunc == 'undefined' ) {
			return true;
		}

		//no action specified
		if( (file = this.getAttribute('action')) == null ) {
			file = new String( window.location );
		}

		dataStr = AJForm.makeArgument("ajform" , "1" , true);
		//construct values
		childList = this.getElementsByTagName('*');
		for( var e = 0; e < childList.length; e++ ) {
			thisInput = childList[e];
			//preliminary check : input
			if( thisInput.nodeName.toLowerCase() == 'input' ) {
				thisElmType = thisInput.getAttribute('type');
				thisElmType = ( thisElmType == null ) ? 'text' : thisElmType.toLowerCase();
			}
			//preliminary check : button
			else if( thisInput.nodeName.toLowerCase() == 'button' ) {
				thisElmType = 'button';
			}
			//preliminary check : textarea
			else if( thisInput.nodeName.toLowerCase() == 'textarea' ) {
				thisElmType = 'textarea';
			}
			//preliminary check : select
			else if( thisInput.nodeName.toLowerCase() == 'select' ) {
				thisElmType = 'select';
			}
			//preliminary check : unknown
			else {
				continue;
			}
			//preliminary check : any element - no name
			if( thisInput.name == '' || thisInput.name == 'undefined' ) {
				continue;
			}
			/********************************

				FILE (NOT SUPPORTED)

			********************************/
			if( thisElmType == "file" ) {
				userFuncVal = userFunc( null , AJForm.STATUS['FILE_UPLOAD_FAILED'] , "Unable to handle file uploads." );
				return userFuncVal;
			}
			/********************************
			
				CHECKBOX, RADIO
			
			********************************/
			else if( thisElmType == "checkbox" || thisElmType == "radio" ) {
				if( !thisInput.checked ) {
					continue;
				}
			}
			/********************************
			
				SUBMIT IMAGE
			
			********************************/
			else if( thisElmType == "image" || thisElmType == "submit" ) {
				//only include images|submits which were submitted
				if( thisInput != this.ajform.submitter.elm ) {
					continue;
				}
				//server side image map coordinates
				if( thisElmType == "image" ) {
					imgConName = AJForm.URLEncode( false, thisInput.name );
					imgConNameX = imgConName + ".x";
					imgConNameY = imgConName + ".y";
					imgConValX = new String(this.ajform.submitter.x);
					imgConValY = new String(this.ajform.submitter.y);
					dataStr += AJForm.makeArgument(imgConNameX , imgConValX);
					dataStr += AJForm.makeArgument(imgConNameY , imgConValY);
				}
			}

			/********************************
			
				SELECT LIST
			
			********************************/
			if (thisElmType == 'select') {
				for( var s = 0; s < thisInput.length; s++ ) {
					thisOption = thisInput.options[s];
					if( thisOption.selected ) {
						dataStr += AJForm.makeArgument( thisInput.name , thisOption.value);
					}
				}
			}
			/********************************
			
				ALL OTHER ELEMENT TYPES
			
			********************************/
			else {
				dataStr += AJForm.makeArgument( thisInput.name , thisInput.value);
			}
		}

		thisForm = this;
		thisForm.userFunc = userFunc;
		requestType = this.getAttribute('method');
		requestType = ( requestType == null ) ? 'get' : requestType;
		
		thisDoc = AJForm.getHTTPRequest();
		
		//METHOD
		if( requestType.toLowerCase() == "get" ) {
			file += (file.match(/\?/)) ? ("&" + dataStr) : ("?" + dataStr);
			thisDoc.open( "GET", file, true );
			AJForm.setResponse( thisDoc, thisForm.userFunc );
			thisDoc.setRequestHeader( "Content-Type" , "application/x-www-form-urlencoded; charset=UTF-8" );
			thisDoc.send('');
		}
		else if( requestType.toLowerCase() == "post" ) {
			thisDoc.open( "POST", file, true );
			AJForm.setResponse( thisDoc, thisForm.userFunc );
			thisDoc.setRequestHeader( "Content-Type" , "application/x-www-form-urlencoded; charset=UTF-8" );
			thisDoc.send(dataStr);
		}
		thisDoc = undefined;
		return false;
	}
	
	AJForm.request = function( type, file ) {
		args = arguments[2] ? arguments[2] : false;
		func = arguments[3] ? arguments[3] : false;
		if( type == 'get' ) {
			sendType = "GET";
		}
		else if( type == 'post' ) {
			sendType = "POST";
		}
		else {
			return false;
		}
		dataStr = AJForm.makeArgument("ajform" , "1" , true);
		i = 0;
		if( args ) {
			for( arg in args ) {
				switch( args[arg] ) {
					case false :
						thisVal = "0"; break;
					case true :
						thisVal = "1"; break;
					default :
						thisVal = args[arg]; break;					
				}
				dataStr += AJForm.makeArgument( arg , thisVal );
				i++;
			}
		}
		if( type == 'get' ) {
			thisFile = file + dataStr;
			thisSend = "";
		}
		else if( type == 'post' ) {
			thisFile = file;
			thisSend = dataStr;
		}
		thisDoc = AJForm.getHTTPRequest();
		thisDoc.open( sendType, thisFile, true );
		if( func ) {
			AJForm.setResponse(thisDoc, func);
		}
		thisDoc.setRequestHeader( "Content-Type" , "application/x-www-form-urlencoded; charset=UTF-8" );
		thisDoc.send(thisSend);
		return thisDoc;
	}
	
	AJForm.kill = function( thisDoc ) {
		thisDoc.aborted = true;
		thisDoc.abort();
	}
	
	AJForm.makeArgument = function( name, val ) {
		var delim = "";
		if( !(arguments[2]) ) {
			delim = "&";
		}
		thisName = name == "" ? "" : AJForm.URLEncode( false, name );
		thisVal = val == "" ? "" : AJForm.URLEncode( false, val );
		thisArg = delim + thisName + "=" + thisVal;
		return thisArg;
	}
	
	
	/*
		MOZILLA HAS A BUG WHICH WILL THROW AN UNKNOWN ERROR
		IF AN ASYNCORNOUS REQUEST IS ABORTED USING THE abort() METHOD, THE ONREADYSTATECHANGE
		WILL STILL BE CALLED BUT THE responseText, status, and statusText
		PROPERTIES WILL THROW ERRORS. SO TO FIX THIS, WE NEED TO CHECK IF THE REQUEST WAS ABORTED.
	*/
	AJForm.setResponse = function( thisDoc, userFunc ) {
		thisDoc.onreadystatechange = function() {
			// only if req shows "loaded"
			if(thisDoc.readyState == 4 && !thisDoc.aborted) {
				ret_responseText = thisDoc.responseText;
				ret_status = thisDoc.status;
				ret_statusText = thisDoc.statusText;

				//get server message
				if( !ret_statusText || !ret_status ) {
					thisStatusText = "HTTP Code " + ret_status + "\nNo server message available."
				}
				else {
					thisStatusText = "HTTP Code " + ret_status + "\nServer responded, '" + ret_statusText + "'";
				}
				// only if "OK"
				if(ret_status == 200) {
					thisStatus = AJForm.STATUS['SUCCESS'];
					thisMessage =  "Operation completed successfully.\n" + thisStatusText;
				}
				else {
					thisStatus = AJForm.STATUS['SERVER_ERROR'];
					thisMessage = "A server error ocurred.\n" + thisStatusText;
				}
				userFunc( ret_responseText , thisStatus , thisMessage );
			}
		}
	}
	
	AJForm.register = function( thisForm ) {
		submitStr = AJForm.getAttributeText( thisForm.onsubmit );
		//if an onsubmit attribute exists
		if( submitStr == null ) {
			return false;
		}
		submitActionList = submitStr.split( ";" );
		pre_callback = null;
		post_callback = null;
		for( s = 0; s < submitActionList.length; s++ ) {
			arg_post = submitActionList[s].match( /(ajform:)?\s*([A-Za-z0-9\._\$]+)\s*\(.*?\)/ );
			if( RegExp.$1 ) {
				post_callback = RegExp.$2;
			}
			else if( RegExp.$2 ) {
				pre_callback = RegExp.$2;
			}
		}
		//if this is a specified AJFORM handler
		if( post_callback != null ) {
			thisForm.ajform = new Object;
			thisForm.ajform.preCallback = eval(pre_callback);
			thisForm.ajform.postCallback = eval(post_callback);
			thisForm.onsubmit = AJForm.activateForm;
			thisForm.ajform.submitter = new Object;
			thisForm.ajform.submitter.elm = null;
			thisForm.ajform.submitter.x = null;
			thisForm.ajform.submitter.y = null;
			thisForm.ajform_submit = AJForm.submitForm;

			//prepare the submit buttons
			inputList = thisForm.getElementsByTagName('input');
			for( y = 0; y < inputList.length; y++ ) {
				thisInput = inputList[y];
				thisInputType = thisInput.getAttribute( 'type' );
				if( thisInputType == null ) {
					return false;
				}
				if( thisInputType == 'submit' || thisInputType == 'image' ) {
					thisInput.setEventListener = STATIC_DOM.setEventListener;
					thisInput.setEventListener( 'click' , AJForm.setSubmitStatus );
				}
			}
		}
		return true;
	}

	AJForm.init = function() {
		//do some compatibility checks
		//check to see if we have proper UTF8 support
		if( AJForm.URLEncode(true) == null ) {
			return false;
		}
		for( var i = 0; i < document.forms.length; i++ ) {
			AJForm.register( document.forms[i] );
		}
	}

	AJForm.getAttributeText = function( thisAttribute ) {
		if( thisAttribute == 'undefined' || thisAttribute == null ) {
			return null;
		}
		if( (typeof thisAttribute).toLowerCase() == 'function' ) {
			attStr = new String(thisAttribute);
			attStr.match( /{\s*([\s\S]+?)\s*}/ );
			attText = RegExp.$1;
		}
		else {
			attText = thisAttribute;
		}
		return attText;
	}

	AJForm.setSubmitStatus = function(e) {
		thisElm = STATIC_DOM.getSourceElement(this);
		thisEvent = STATIC_DOM.getEvent(e);
		thisElm.form.ajform.submitter.elm = thisElm;
		thisElm.form.ajform.submitter.x = thisEvent.elementX;
		thisElm.form.ajform.submitter.y = thisEvent.elementY;
	}

	/*
		Concept and certain code portions courtesy of
		Mathias Schafer <molily at gmx dot de> 2005-09-18 06:58
	*/
	AJForm.URLEncode = function(checkCompat) {
		if( typeof encodeURIComponent != 'undefined' &&
			typeof encodeURIComponent !=  undefined
		) {
			if( checkCompat ) {
				return true;
			}
			else if( arguments[1] ) {
				str = arguments[1];
				code = encodeURIComponent(str);
				code = code.replace( /%20/g , "+" );
				return code;
			}
			else {
				return null;
			}
		}
		else {
			return null;
		}
	}

	//SET THE LISTENER TO INITIALIZE THE ACTIONS
	window.setEventListener = STATIC_DOM.setEventListener;
	window.setEventListener( 'load' , AJForm.init );

