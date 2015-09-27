function ajaxManager(){
	var args = ajaxManager.arguments;
	switch (args[0]){
		case "load_page":	
			if (document.getElementById){
				var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
			}
			if (x){
				x.onreadystatechange = function(){
					if (x.readyState == 4){
						el = document.getElementById(args[2]);
						if(args[4]=="concat"){
							el.innerHTML = old+x.responseText;
						}
						else{
							el.innerHTML = x.responseText;
							
							
							if(el.innerHTML.indexOf("<script")!=-1 ||el.innerHTML.indexOf("<SCRIPT")!=-1){
								//alert("Hola");
								//truco para que iexplore interprete los scripts incluidos
								var objNodeDiv=document.createElement("div");
								objNodeDiv.innerHTML="<div style=\"display: none\">&nbsp;</div>\n"+el.innerHTML;
								executeJavascript(objNodeDiv);
							}
						}
						if(args[3]){
							eval(args[3]);	
						}
					}
				}
				var el;
				el= document.getElementById(args[2]);
				if(args[4]=="concat"){
					var old;
					old=el.innerHTML;	
				}
				else{
					el.innerHTML = "<img src=\"images/loading.gif\"/>";
				}
				
				x.open("GET", args[1], true);
				x.send(null);	
			}
			break;
			
		case "load_command":	
			if (document.getElementById){
				var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
			}
			if (x){
				
				x.onreadystatechange = function(){
					if (x.readyState == 4){
						if(args[3]){
							eval(args[3]);	
						}
					}
				}				
				var el;
				
				x.open("GET", args[1], true);
				x.send(null);
			}
			break;
		case "start_main":
//			ajaxManager('load_page', 'header.html', 'masthead');
			break;
		
		// CON POST
		case "post_page":	
			if (document.getElementById){
				var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
			}
			if (x){
				x.onreadystatechange = function(){
					if (x.readyState == 4){
						el = document.getElementById(args[2]);
						if(args[4]=="concat"){
							el.innerHTML = old+x.responseText;
						}
						else{
							el.innerHTML = x.responseText;
							
							
							if(el.innerHTML.indexOf("<script")!=-1 ||el.innerHTML.indexOf("<SCRIPT")!=-1){
								//alert("Hola");
								//truco para que iexplore interprete los scripts incluidos
								var objNodeDiv=document.createElement("div");
								objNodeDiv.innerHTML="<div style=\"display: none\">&nbsp;</div>\n"+el.innerHTML;
								executeJavascript(objNodeDiv);
							}
						}
						if(args[3]){
							eval(args[3]);	
						}
					}
				}
				var el;
				el= document.getElementById(args[2]);
				if(args[4]=="concat"){
					var old;
					old=el.innerHTML;	
				}
				else{
					el.innerHTML = "<img src=\"images/loading.gif\"/>";
				}
				
				x.open("POST", args[1], true);
				x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				x.setRequestHeader("Content-length", args[5].length);
				x.setRequestHeader("Connection", "close");


				x.send(args[5]);	
			}
			break;
		
	}
}


function cargarFormulario(form, commandName,outputDiv){
	var formulario=document.getElementById(form);
	stringParams="";
	var error = 0;
	var error1 = 0;
	var error2 = 0;
	
	var tags = document.getElementsByTagName("div");
	
	// Para eliminar todas las filas de controles que esten vacias
	for(i=0;i<tags.length;i++){
		if(tags[i].className=="tabla"){
			var textos = tags[i].getElementsByTagName("input");
			
			// Busca si hay al menos un campo vacio en la fila
			for(j=0;j<textos.length;j++){
				if(textos[j].type=="text" && textos[j].value!=false){
					break;
				}
			}
			
			// Si todos los campos están vacíos
			if(j==7){
				// Agrega los parametros para borrar el registro de la base de datos
//				for(k=0;k<textos.length;k++){
//					if(textos[k].type=="text"){
//						stringParams+="&";
//						stringParams+=textos[k].name+"="+textos[k].value;
//					}
//				}
				
				// Elimina el div vacío
				tags[i].parentNode.removeChild(tags[i]);
				i--;
			}
		}
	}
	
	for(x=0;x<principal.elements.length;x++){
		if(principal.elements[x].name==''){
			continue;
		}
		
		tipoInput=principal.elements[x].type;
		
		if(tipoInput=="radio"||tipoInput=="checkbox"){
			if(!(principal.elements[x].checked)){
				continue;
			}
		}
		
	
		//if(x>0){
			stringParams+="&";
		//}
		stringParams+=principal.elements[x].name+"="+escape(principal.elements[x].value);
	}
	
	if(error>0){
		alert("Todos los Campos son Obligatorios.\n Favor completar los campos marcados.");
		return false;
	}else{
		if(error1>0){
			alert("Campos de fecha incorrectos.\n Favor corregir los campos marcados.");
			return false;
		}else{
			if(error2>0){
				alert("Campos de tipo número incorrectos.\n Favor corregir los campos marcados.");
				return false;
			}
		}
	}
	
	if(stringParams!=""){
		stringParams="&"+stringParams;
	}

	//alert(stringParams);
	if(outputDiv==""){
		window.location=commandName+stringParams;
	}else{
		//alert(stringParams.length);
		if(stringParams.length < 2000){
			// por GET es un promedio de 2000 caracteres
			ajaxManager('load_page',commandName+stringParams,outputDiv);
		}else{
			// Si es mayor lo enviamos por POST
			ajaxManager('post_page',commandName,outputDiv,"","",stringParams);
		}
	}
	
	return false;
}

function loadPage(url,params,idDiv,showLoading,showLoaded,personalFunction){
	var httpRequest = false;
	if(window.XMLHttpRequest){
		// Si es Mozilla, Safari etc
		httpRequest= new XMLHttpRequest();
	}else if(window.ActiveXObject){
		// pero si es IE
		try{
			httpRequest = new ActiveXObject ("Msxml2.XMLHTTP");
		}catch (e){
			// en caso que sea una versión antigua
			try{
				httpRequest = new ActiveXObject ("Microsoft.XMLHTTP");
			}catch (e){
			}
		}
	}else{
		return false;
	}
	httpRequest.onreadystatechange = function(){
		contenedor=document.getElementById(idDiv);
		if(httpRequest.readyState==1 && showLoading){
			altura=contenedor.offsetHeight;
			contenedor.innerHTML=showLoading;
			try{
				contenedor.style.removeProperty('height');
				contenedor.style.height=altura;
			}catch(e){
			}
		}else if(httpRequest.readyState==4){
			try{
				contenedor.style.removeProperty('height');
			}catch(e){
			}
			txt=httpRequest.responseText;
			if(showLoaded){
				contenedor.innerHTML=txt;
				mostrarDiv(contenedor);
			}
			if(txt.indexOf("<script")!=-1 ||txt.indexOf("<SCRIPT")!=-1){
				//truco para que iexplore interprete los scripts incluidos
				var objNodeDiv=document.createElement("div");
				objNodeDiv.innerHTML="<div style=\"display: none\">&nbsp;</div>\n"+txt;
				executeJavascript(objNodeDiv);
			}
			if(personalFunction){
				personalFunction.call();
			}
		}
	}
	httpRequest.open ('POST', url, true);		
	httpRequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	httpRequest.send(params);
}
function executeJavascript(divElement){
	arrayScript=divElement.getElementsByTagName("script");
	jsCode="";
	for(x=0;x<arrayScript.length;x++){
		jsCode+=arrayScript[x].innerHTML+"\n";
	}
	eval(jsCode);
}
function submitForm(formulario, commandName,outputDiv){
	stringParams="";
	for(x=0;x<formulario.elements.length;x++){
		if(formulario.elements[x].name==''){
			continue;
		}
		tipoInput=formulario.elements[x].type;
		if(tipoInput=="radio"||tipoInput=="checkbox"){
			if(!(formulario.elements[x].checked)){
				continue;
			}
		}
		if(x>0){
		stringParams+="&";
		}
		stringParams+=formulario.elements[x].name+"="+formulario.elements[x].value;
	}
	//por defecto el resultado del formulario reemplaza a este (si no se especifica outputDiv)
	idContenedor=formulario.parentNode.id;
	if(outputDiv){
		idContenedor=outputDiv;
	}
	loadPage('index.php?do='+commandName,stringParams,idContenedor,'Enviando información...',false);
	return false;
}
function ocultarDiv(divName){
	if(typeof(divName)!='object'){
		divName=document.getElementById(divName);
	}
	divName.style.display="none";
}
function mostrarDiv(divName,inline){
	if(typeof(divName)!='object'){
		divName=document.getElementById(divName);
	}
	if(inline){
		divName.style.display="inline";
	}else{
		divName.style.display="block";
	}
}
function toggleDiv(divName){
	if(typeof(divName)!='object'){
		divName=document.getElementById(divName);
	}
	if(divName.style.display=="none"){
		mostrarDiv(divName);
	}else{
		ocultarDiv(divName);
	}
}
/*metodo util para conocer propiedades y metodos de objetos*/
function getProp(obj,div){
	var txt="<pre>[["+obj+"]]\n\n";
	for(x in obj){
		try{
			txt+= x+"="+eval("obj."+x)+"\n";
		}catch(e){
			txt+= x+".()\n";//+eval("obj."+x)
		}
	}
	txt+="_______________________________________</pre>";
	document.getElementById(div).innerHTML=txt;
}
