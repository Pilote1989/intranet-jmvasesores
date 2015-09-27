////////////////////////////////////////////////////////////////////////
// Funciones para el cuadro de Ayuda

var horizontal_offset="9px" //horizontal offset of hint box from anchor link

/////No further editting needed

var vertical_offset="0" //horizontal offset of hint box from anchor link. No need to change.
var ie=document.all
var ns6=document.getElementById&&!document.all

function getposOffset(what, offsettype){
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null){
	totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
	parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}

function iecompattest(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
	var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
	if (whichedge=="rightedge"){
		var windowedge=ie && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-30 : window.pageXOffset+window.innerWidth-40
		dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
		if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
		edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset)
	}
	else{
		var windowedge=ie && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
		dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
		if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
		edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight
	}
	return edgeoffset
}

function showhint(menucontents, obj, e, tipwidth){
	if ((ie||ns6) && document.getElementById("hintbox")){
		dropmenuobj=document.getElementById("hintbox")
		dropmenuobj.innerHTML=menucontents
		dropmenuobj.style.left=dropmenuobj.style.top=-500
		if (tipwidth!=""){
			dropmenuobj.widthobj=dropmenuobj.style
			dropmenuobj.widthobj.width=tipwidth
		}
		dropmenuobj.x=getposOffset(obj, "left")
		dropmenuobj.y=getposOffset(obj, "top")
		dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+obj.offsetWidth+"px"
		dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+"px"
		dropmenuobj.style.visibility="visible"
		obj.onmouseout=hidetip
	}
}
function showHintEducacion(menucontents, obj, e, tipwidth){
	if ((ie||ns6) && document.getElementById("hintbox")){
		dropmenuobj=document.getElementById("hintbox")
		dropmenuobj.innerHTML=menucontents
		dropmenuobj.style.left=dropmenuobj.style.top=-500
		if (tipwidth!=""){
			dropmenuobj.widthobj=dropmenuobj.style
			dropmenuobj.widthobj.width=tipwidth
		}
		dropmenuobj.x=getposOffset(obj, "left")
		dropmenuobj.y=getposOffset(obj, "top")
		dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
		dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+parseInt(5)+"px"
		dropmenuobj.style.visibility="visible"
		obj.onmouseout=hidetip
	}
}

function hidetip(e){
	dropmenuobj.style.visibility="hidden"
	dropmenuobj.style.left="-500px"
}

function createhintbox(){
	var divblock=document.createElement("div")
	divblock.setAttribute("id", "hintbox")
	document.body.appendChild(divblock)
}

if (window.addEventListener)
window.addEventListener("load", createhintbox, false)
else if (window.attachEvent)
window.attachEvent("onload", createhintbox)
else if (document.getElementById)
window.onload=createhintbox

// Fin de funciones para el cuadro de ayuda.
///////////////////////////////////////////////////////////////////////////

function mostrarOcultar(selectInput){
	if(typeof(selectInput)!='object'){
		selectInput=document.getElementById(selectInput);
	}
	if(selectInput.checked==true){
		div=document.getElementById(selectInput.value);
		div.style.display="block";
	}
	else{
		div=document.getElementById(selectInput.value);
		div.style.display="none";
	}
}

function disableEnterKey(e){
		var key;

		if(window.event)
			key = window.event.keyCode;     //IE
		else
			key = e.which;     //firefox

		if(key == 13)
			return false;
		else
			return true;
}


function comer(asd){
	alert(asd);
	return true;
}
//Opcion Otra

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
function elegirDiv(selectInput,inline){
	if(typeof(selectInput)!='object'){
	selectInput=document.getElementById(selectInput);
	}
	for(x=0;x<selectInput.length;x++){
	if(dv=document.getElementById(selectInput.options[x].value)){
	ocultarDiv(dv);
	}
	}
	if(dv=document.getElementById(selectInput.value)){
	mostrarDiv(dv,inline);
	}
}

//Seleccionar todos/Minguno

function seleccionarTodos(){
	var formulario=document.getElementById("formularioInscripciones");
	for(x=0;x<formulario.elements.length;x++){
	
		tipoInput=formulario.elements[x].type;
		if(tipoInput=="checkbox"){
			formulario.elements[x].checked=true;
		}
	}
}

function seleccionarNinguno(){
	var formulario=document.getElementById("formularioInscripciones");
	for(x=0;x<formulario.elements.length;x++){
	
		tipoInput=formulario.elements[x].type;
		if(tipoInput=="checkbox"){
			formulario.elements[x].checked=false;
		}
	}
}



//Seleccionar todos/Minguno  Con formulario de parametro

function seleccionarTodosF(form){
	var formulario=document.getElementById(form);
	for(x=0;x<formulario.elements.length;x++){
	
		tipoInput=formulario.elements[x].type;
		if(tipoInput=="checkbox"){
			formulario.elements[x].checked=true;
		}
	}
}

function seleccionarNingunoF(form){
	var formulario=document.getElementById(form);
	for(x=0;x<formulario.elements.length;x++){
	
		tipoInput=formulario.elements[x].type;
		if(tipoInput=="checkbox"){
			formulario.elements[x].checked=false;
		}
	}
}

function alerta(){
	alert("Fuck!");
}
function validate_email(field){
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if(reg.test(field) == false) {
		return false;
	}else{
		return true;
	}
}

function validate_date(date){
	re = /^\d{1,2}\/\d{1,2}\/\d{4}$/; 
	if(!date.value.match(re)){
		return false;
	}
	return true;
}

function validate_number(number){
	var re = /^[0-9]{1,10}[.]{0,1}[0-9]{0,2}$/;
	if (re.test(number) == false){
		return false;
	}else{
		return true;
	}
}
function validate_char(field){
	var reg = /^([A-Za-z])$/;
	if(reg.test(field) == false) {
		return false;
	}else{
		return true;
	}
}
function validarDatosBasicos(){
	var textoError="";
	var ok=true;

	var nombreActividad=document.getElementById("nombreActividad");
	var lugar=document.getElementById("lugar");
	var fechaInicio=document.getElementById("fechaInicio");
	var fechaFin=document.getElementById("fechaFin");
	var inscripciones=document.getElementById("inscripciones");
	var fechaInicioInscripciones=document.getElementById("fechaInicioInscripciones");
	var fechaFinInscripciones=document.getElementById("fechaFinInscripciones");
	var casasPlanificadas=document.getElementById("casasPlanificadas");
	var tipoConstruccion=document.getElementById("tipoConstruccion");
	var idUnidadOrganizacional=document.getElementById("idUnidadOrganizacional");
	
	if(nombreActividad.value==false){
		textoError=textoError+"<li>Debes ingresar el nombre de la Construcción</li>";
		ok=false;
	}
	if(lugar.value==false){
		textoError=textoError+"<li>Debes ingresar el el lugar de la Construcción</li>";
		ok=false;
	}
	if(fechaInicio.value==false){
		textoError=textoError+"<li>Debes ingresar la Fecha de Inicio de Construcciones</li>";
		ok=false;
	}
	else{
		if(!validate_date(fechaInicio)){
			textoError=textoError+"<li>La fecha de Inicio no es válida</li>";
			ok=false;
		}
	}
	if(fechaFin.value==false){
		textoError=textoError+"<li>Debes ingresar la fecha de Fin de Construcciones</li>";
		ok=false;
	}
	else{
		if(!validate_date(fechaFin)){
			textoError=textoError+"<li>La fecha de Fin no es válida</li>";
			ok=false;
		}
	}
	if(inscripciones.checked){
		//alert(1);
		if(fechaInicioInscripciones.value==false){
			textoError=textoError+"<li>Debes ingresar la Fecha de Inicio de Inscripciones</li>";
			ok=false;
		}
		else{
			if(!validate_date(fechaInicio)){
				textoError=textoError+"<li>La fecha de Inicio de inscripciones no es válida</li>";
				ok=false;
			}
		}
		
		//alert(2);
		if(fechaFinInscripciones.value==false){
			textoError=textoError+"<li>Debes ingresar la fecha de Fin de Inscripciones</li>";
			ok=false;
		}
		else{
			if(!validate_date(fechaFin)){
				textoError=textoError+"<li>La fecha de Fin de inscripciones no es válida</li>";
				ok=false;
			}
		}
	}
	if(casasPlanificadas.value==false){
		textoError=textoError+"<li>Debes ingresar el número de casas Planificadas</li>";
		ok=false;
	}
	if(tipoConstruccion.value==false){
		textoError=textoError+"<li>Debes selenombreActividadccionar un tipo de Construcción</li>";
		ok=false;
	}
	if(idUnidadOrganizacional.value==false){
		textoError=textoError+"<li>Debes seleccionar una Unidad Organizacional</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}

function validarDatosFinancieros(){
	var formulario=document.getElementById("formularioFinanzas");
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
				// Elimina el div vacío
				tags[i].parentNode.removeChild(tags[i]);
				i--;
			}
		}
	}
	
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
		
		// Parche exlusivamente para la parte de finanzas de la construcción
		if(tipoInput=="text" && formulario.id=="formularioFinanzas"){
			formulario.elements[x].style.backgroundColor = "#FFFFFF";

			if(formulario.elements[x].value==false && (formulario.elements[x].name.indexOf("montoTotal")==-1 && formulario.elements[x].name.indexOf("empresa")==-1)){
				formulario.elements[x].style.backgroundColor = "#FFCAD3";
				error++;
			}else{
				if(formulario.elements[x].name.indexOf("fechaEgreso")!=-1 && validate_date(formulario.elements[x])==false){
					formulario.elements[x].style.backgroundColor = "#FFCAD3";
					error1++;
				}else{
					if((formulario.elements[x].name.indexOf("cantidad")!=-1 || formulario.elements[x].name.indexOf("montoTotal")!=-1 || formulario.elements[x].name.indexOf("montoMercado")!=-1) && validate_number(formulario.elements[x].value)==false){
						formulario.elements[x].style.backgroundColor = "#FFCAD3";
						error2++;
					}else{
						if(formulario.elements[x].name.indexOf("cantidad")!=-1 || formulario.elements[x].name.indexOf("montoTotal")!=-1 || formulario.elements[x].name.indexOf("montoMercado")!=-1){
							formulario.elements[x].value = parseFloat(formulario.elements[x].value);
						}
					}
				}
			}
		}
		// Fin del Parche
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
	
	return true;
}

function validarDatosBasicosActividad(){
	var textoError="";
	var ok=true;
	
	var nombreActividad=document.getElementById("nombreActividad");
	var lugar=document.getElementById("lugar");
	var fechaInicio=document.getElementById("fechaInicio");
	var fechaFin=document.getElementById("fechaFin");
	var inscripciones=document.getElementById("inscripciones");
	var fechaInicioInscripciones=document.getElementById("fechaInicioInscripciones");
	var fechaFinInscripciones=document.getElementById("fechaFinInscripciones");
	var idUnidadOrganizacional=document.getElementById("idUnidadOrganizacional");
	var idTipo=document.getElementById("idTipo");
	
	if(nombreActividad.value==false){
		textoError=textoError+"<li>Debes ingresar el nombre de la Actividad</li>";
		ok=false;
	}
	if(lugar.value==false){
		textoError=textoError+"<li>Debes ingresar el el lugar de la Actividad</li>";
		ok=false;
	}
	if(fechaInicio.value==false){
		textoError=textoError+"<li>Debes ingresar la Fecha de Inicio de Actividad</li>";
		ok=false;
	}
	else{
		if(!validate_date(fechaInicio)){
			textoError=textoError+"<li>La fecha de Inicio no es válida</li>";
			ok=false;
		}
	}
	if(fechaFin.value==false){
		textoError=textoError+"<li>Debes ingresar la fecha de Fin de Actividad</li>";
		ok=false;
	}
	else{
		if(!validate_date(fechaFin)){
			textoError=textoError+"<li>La fecha de Fin no es válida</li>";
			ok=false;
		}
	}
	if(inscripciones.checked){
		//alert(1);
		if(fechaInicioInscripciones.value==false){
			textoError=textoError+"<li>Debes ingresar la Fecha de Inicio de Actividad</li>";
			ok=false;
		}
		else{
			if(!validate_date(fechaInicio)){
				textoError=textoError+"<li>La fecha de Inicio de inscripciones no es válida</li>";
				ok=false;
			}
		}
		
		//alert(2);
		if(fechaFinInscripciones.value==false){
			textoError=textoError+"<li>Debes ingresar la fecha de Fin de Inscripciones</li>";
			ok=false;
		}
		else{
			if(!validate_date(fechaFin)){
				textoError=textoError+"<li>La fecha de Fin de inscripciones no es válida</li>";
				ok=false;
			}
		}
	}
	if(idUnidadOrganizacional.value==false){
		textoError=textoError+"<li>Debes seleccionar una Unidad Organizacional</li>";
		ok=false;
	}
	if(idTipo.value==false){
		textoError=textoError+"<li>Debes seleccionar un tipo de Actividad</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}
function validarDatosEscuela(){
		
	var textoError="";
	var ok=true;
	
	var nombre=document.getElementById("nombre_n");
	var lugar=document.getElementById("lugar_n");
	var casasPlanificadas=document.getElementById("casasPlanificadas_n");
	
	if(nombre.value==false){
		ok=false;
	}
	if(lugar.value==false){
		ok=false;
	}	
	if(casasPlanificadas.value==false){
		ok=false;
	}
	
	if(ok==false){
		alert("Completar los campos obligatorios");
		return false;
	}else{
		return true;
	}
}

function validarDatosLogin(){
	var textoError="";
	var ok=true;
	
	var correo=document.getElementById("email");
	var pass=document.getElementById("passwordl");

	if(validate_email(correo.value)==false){
		textoError=textoError+"<li>Debes ingresar un Correo válido</li>";
		ok=false;
	}
	if(pass.value==false){
		textoError=textoError+"<li>Debes ingresar tu contraseña</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}

}

// Igual a la funcion anterior pero con los textos en portugues
function validarDatosLoginBr(){
	var textoError="";
	var ok=true;
	
	var correo=document.getElementById("email");
	var pass=document.getElementById("passwordl");

	if(validate_email(correo.value)==false){
		textoError=textoError+"<li>Introduza um e-mail válido</li>";
		ok=false;
	}
	if(pass.value==false){
		textoError=textoError+"<li>Digite sua senha Teto</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}

}

function validarDatosRegistro(){
	var textoError="";
	var ok=true;

	var correo = document.getElementById("mail");
	var pass = document.getElementById("password");
	var pass2 = document.getElementById("password_2");
	var nombres = document.getElementById("nombres");
	var apellidoPaterno = document.getElementById("apellidoPaterno");
	var apellidoMaterno = document.getElementById("apellidoMaterno");
	var sexo = document.getElementById("sexo");
	var fechaNacimiento = document.getElementById("fechaNacimiento");
	var idPais = document.getElementById("idPais");
	var universidad = document.getElementById("idUniversidad");
	var carrera = document.getElementById("idCarrera");
	var anoEstudio = document.getElementById("anoEstudio");
	var dni = document.getElementById("dni");

	if(validate_email(correo.value)==false){
		textoError=textoError+"<li>Debes ingresar un Correo válido</li>";
		ok=false;
	}
	if(pass.value==false){
		textoError=textoError+"<li>Debes ingresar tu contraseña</li>";
		ok=false;
	}
	if(pass2.value==false || pass.value!=pass2.value){
		textoError=textoError+"<li>Contraseñas diferentes</li>";
		ok=false;
	}
	if(nombres.value==false){
		textoError=textoError+"<li>Debes ingresar tus Nombres</li>";
		ok=false;
	}
	if(apellidoPaterno.value==false){
		textoError=textoError+"<li>Debes ingresar tu Apellido Paterno</li>";
		ok=false;
	}
	if(apellidoMaterno.value==false){
		textoError=textoError+"<li>Debes ingresar tu Apellido Materno</li>";
		ok=false;
	}
	if(fechaNacimiento.value==false || validate_date(fechaNacimiento)==false){
		textoError=textoError+"<li>Fecha Inválida</li>";
		ok=false;
	}
	if(sexo.value==false){
		textoError=textoError+"<li>Debes seleccionar tu Sexo</li>";
		ok=false;
	}
	if(idPais.value==false){
		textoError=textoError+"<li>Debes seleccionar tu País</li>";
		ok=false;
	}
	if(universidad.value==""){
		textoError=textoError+"<li>Debes seleccionar tu Universidad</li>";
		ok=false;
	}
	if(carrera.value==""){
		textoError=textoError+"<li>Debes seleccionar tu Carrera</li>";
		ok=false;
	}
	if(anoEstudio.value==false){
		textoError=textoError+"<li>Debes seleccionar tu Año de Estudio</li>";
		ok=false;
	}
	if(dni.value==false){
		textoError=textoError+"<li>Debes ingresar tu No. de documento</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErroresRegistro");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}

// Igual a la funcion anterior pero con los textos en portugues
function validarDatosRegistroBr(){
	var textoError="";
	var ok=true;

	var correo = document.getElementById("mail");
	var pass = document.getElementById("password");
	var pass2 = document.getElementById("password_2");
	var nombres = document.getElementById("nombres");
	var apellidoPaterno = document.getElementById("apellidoPaterno");
	var apellidoMaterno = document.getElementById("apellidoMaterno");
	var sexo = document.getElementById("sexo");
	var fechaNacimiento = document.getElementById("fechaNacimiento");
	var idPais = document.getElementById("idPais");
	var universidad = document.getElementById("idUniversidad");
	var carrera = document.getElementById("idCarrera");
	var anoEstudio = document.getElementById("anoEstudio");
	var dni = document.getElementById("dni");

	if(validate_email(correo.value)==false){
		textoError=textoError+"<li>Introduza um e-mail válido</li>";
		ok=false;
	}
	if(pass.value==false){
		textoError=textoError+"<li>Digite sua senha Teto</li>";
		ok=false;
	}
	if(pass2.value==false || pass.value!=pass2.value){
		textoError=textoError+"<li>Senhas diferentes</li>";
		ok=false;
	}
	if(nombres.value==false){
		textoError=textoError+"<li>Digite seu nome</li>";
		ok=false;
	}
	if(apellidoPaterno.value==false){
		textoError=textoError+"<li>Digite seu Sobrenome</li>";
		ok=false;
	}
	if(apellidoMaterno.value==false){
		textoError=textoError+"<li>Digite seu Sobrenome de solteira da mãe</li>";
		ok=false;
	}
	if(fechaNacimiento.value==false || validate_date(fechaNacimiento)==false){
		textoError=textoError+"<li>Data inválida</li>";
		ok=false;
	}
	if(sexo.value==false){
		textoError=textoError+"<li>Selecione seu Sexo</li>";
		ok=false;
	}
	if(idPais.value==false){
		textoError=textoError+"<li>Seleccione seu País</li>";
		ok=false;
	}
	if(universidad.value==""){
		textoError=textoError+"<li>Seleccione seu Universidade</li>";
		ok=false;
	}
	if(carrera.value==""){
		textoError=textoError+"<li>Seleccione seu Carreira</li>";
		ok=false;
	}
	if(anoEstudio.value==false){
		textoError=textoError+"<li>Digite seu Ano</li>";
		ok=false;
	}
	if(dni.value==false){
		textoError=textoError+"<li>Digite seu RG ou CPF</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErroresRegistro");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}

function validarDatosRegistroIns(){
	var textoError="";
	var ok=true;
	
	// Esta parte valida que el correo sea validado antes de grabar los datos
	var nuevo = document.getElementById("nuevo");
	var correo = document.getElementById("mail");
	var nombres = document.getElementById("nombres");
	var apellidoPaterno = document.getElementById("apellidoPaterno");
	var apellidoMaterno = document.getElementById("apellidoMaterno");
	var telefono = document.getElementById("telefono");
	var telefonoMovil = document.getElementById("telefonoMovil");
	var sexo = document.getElementById("sexo");
	var fechaNacimiento = document.getElementById("fechaNacimiento");
	var idPais = document.getElementById("idPais");
	var universidad = document.getElementById("idUniversidad");
	var carrera = document.getElementById("idCarrera");
	var anoEstudio = document.getElementById("anoEstudio");
	var dni = document.getElementById("dni");
	var actividad = document.getElementById("idActividad");
	var errores = document.getElementById("divErrores");
	var actualizar = document.getElementById("actualizar");
	
	if(validate_email(correo.value)==false){
		textoError=textoError+"<li>Debes ingresar un Correo válido</li>";
		ok=false;
	}
	if(errores && actualizar){
		if(errores.innerHTML.toString() != false){
			textoError = textoError+"<li>No pueden existir dos usuarios con el mismo correo</li>";
			ok=false;
		}
	}
	if((parseInt(nuevo.value) == 0) && ok == true){
		document.getElementById("verificar").click();
		ok=false;
	}
	if(nombres.value==false){
		textoError=textoError+"<li>Debes ingresar los Nombres</li>";
		ok=false;
	}
	if(apellidoPaterno.value==false){
		textoError=textoError+"<li>Debes ingresar el Apellido Paterno</li>";
		ok=false;
	}
	if(telefono.value==false && telefonoMovil.value==false){
		textoError=textoError+"<li>Debes ingresar al menos un número de Teléfono</li>";
		ok=false;
	}
	if(actividad){
		if(actividad.value==""){
			textoError=textoError+"<li>Debes seleccionar la actividad</li>";
			ok=false;
		}
	}	
	/*if(apellidoMaterno.value==false){
		textoError=textoError+"<li>Debes ingresar tu Apellido Materno</li>";
		ok=false;
	}
	if(fechaNacimiento.value==false || validate_date(fechaNacimiento)==false){
		textoError=textoError+"<li>Fecha Inválida</li>";
		ok=false;
	}
	if(sexo.value==false){
		textoError=textoError+"<li>Debes seleccionar tu Sexo</li>";
		ok=false;
	}
	if(idPais.value==false){
		textoError=textoError+"<li>Debes seleccionar tu País</li>";
		ok=false;
	}
	if(universidad.value==""){
		textoError=textoError+"<li>Debes seleccionar tu Universidad</li>";
		ok=false;
	}
	if(carrera.value==""){
		textoError=textoError+"<li>Debes seleccionar tu Carrera</li>";
		ok=false;
	}
	if(anoEstudio.value==false){
		textoError=textoError+"<li>Debes seleccionar tu Año de Estudio</li>";
		ok=false;
	}
	if(dni.value==false){
		textoError=textoError+"<li>Debes ingresar tu No. de documento</li>";
		ok=false;
	}*/
	
	if(ok==false){
		if(parseInt(nuevo.value) == 1  || actividad){
			var el= document.getElementById("divErroresRegistro");
			el.innerHTML = textoError;
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}

function validarDatosPoblador(){
	var textoError="";
	var ok=true;
	
	// Esta parte valida que el correo sea validado antes de grabar los datos
	var rut = document.getElementById("rut");
	var digito = document.getElementById("digito");
	var nombres = document.getElementById("nombres");
	var apellidoPaterno = document.getElementById("apellidoPaterno");
	//var estadoCivil = document.getElementById("estadoCivil");
	var nuevo = document.getElementById("nuevo");
	//var opcionFps = document.formulario.opcionFps;
	//var opcionUni = document.formulario.opcionUni;
	
	if(verificarRut(rut.value, digito.value)==false){
		textoError=textoError+"<li>Debes ingresar un RUT válido</li>";
		ok=false;
	}

	if((parseInt(nuevo.value) == 0) && ok == true){
		alert(nombre.value);
		document.getElementById("verificar").click();
		ok=false;
	}

	if(nombres.value==false){
		textoError=textoError+"<li>Debes ingresar los Nombres del Poblador</li>";
		ok=false;
	}
	
	if(apellidoPaterno.value==false){
		textoError=textoError+"<li>Debes ingresar el Apellido Paterno del Poblador</li>";
		ok=false;
	}
	
//	if((opcionFps[0].checked==false) && (opcionFps[1].checked==false)){
//		textoError=textoError+"<li>Debes seleccionar una opcíon de la Ficha de Protección Social</li>";
//		ok=false;
//	}
//
//	if((opcionFps[1].checked==true) && (opcionUni[0].checked==false) && (opcionUni[1].checked==false) && (opcionUni[2].checked==false) && (opcionUni[3].checked==false) && (opcionUni[4].checked==false)){
//		textoError=textoError+"<li>Debes seleccionar una opción del Detalle Unipersonal</li>";
//		ok=false;
//	}
	
	if(ok==false && (parseInt(nuevo.value) == 1)){
		var el= document.getElementById("divErroresRegistro");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}


// Función para validar los datos del Poblador Latinoamerica
function validarDatosPobladorLT(){
	var textoError="";
	var ok=true;
	
	// Esta parte valida que el correo sea validado antes de grabar los datos
	var dni = document.getElementById("dni");
	var nombres = document.getElementById("nombres");
	var apellidoPaterno = document.getElementById("apellidoPaterno");
	var campamento = document.getElementById("idCampamentoLT");
	//var estadoCivil = document.getElementById("estadoCivil");
	var nuevo = document.getElementById("nuevo");
	//var opcionFps = document.formulario.opcionFps;
	//var opcionUni = document.formulario.opcionUni;
	
	if(dni.value==false){
		textoError=textoError+"<li>Debes ingresar el DNI</li>";
		ok=false;
	}

	if((parseInt(nuevo.value) == 0) && ok == true){
		alert(nombre.value);
		document.getElementById("verificar").click();
		ok=false;
	}

	if(nombres.value==false){
		textoError=textoError+"<li>Debes ingresar los Nombres del Poblador</li>";
		ok=false;
	}
	
	if(apellidoPaterno.value==false){
		textoError=textoError+"<li>Debes ingresar el Apellido Paterno del Poblador</li>";
		ok=false;
	}
	if(campamento.value==""){
		textoError=textoError+"<li>Seleccionar el Asentamiento</li>";
		ok=false;
	}
	
//	if((opcionFps[0].checked==false) && (opcionFps[1].checked==false)){
//		textoError=textoError+"<li>Debes seleccionar una opcíon de la Ficha de Protección Social</li>";
//		ok=false;
//	}
//
//	if((opcionFps[1].checked==true) && (opcionUni[0].checked==false) && (opcionUni[1].checked==false) && (opcionUni[2].checked==false) && (opcionUni[3].checked==false) && (opcionUni[4].checked==false)){
//		textoError=textoError+"<li>Debes seleccionar una opción del Detalle Unipersonal</li>";
//		ok=false;
//	}
	
	if(ok==false && (parseInt(nuevo.value) == 1)){
		var el= document.getElementById("divErroresRegistro");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}


function validarDatosNecesidad(){
	var textoError="";
	var ok=true;
	
	// Esta parte valida que el correo sea validado antes de grabar los datos
	var comuna = document.getElementById("idComuna");
	var sector = document.getElementById("sector");
	var unidad = document.getElementById("idUnidadOrganizacional");
	var prioridad = document.formulario.prioridad;
	var casas = document.getElementById("casasPlanificadas");
	var fechaLlegada = document.getElementById("fechaLlegada");
	
	if(comuna.value==""){
		ok=false;
	}
	if(sector.value==false){
		ok=false;
	}
	if(unidad.value==""){
		ok=false;
	}
	if((prioridad[0].checked==false) && (prioridad[1].checked==false)){
		ok=false;
	}
	if(casas.value==false){
		ok=false;
	}
	if(fechaLlegada.value==false){
		ok=false;
	}
	else{
		if(!validate_date(fechaLlegada)){
			alert("Fecha de llegada inválida");
			return false;
		}
	}
	
	if(ok==false){
		alert("Completar los campos obligatorios");
		return false;
	}else{
		return true;
	}
}

function validarDatosFamilia(){
	var textoError="";
	var ok=true;
	
	// Esta parte valida que el correo sea validado antes de grabar los datos
	var idFamilia = document.getElementById("idFamilia");
	
	if(idFamilia == null){
		var rut = document.getElementById("rut");
		var digito = document.getElementById("digito");
		var el= document.getElementById("divErrores");
	
		if(verificarRut(rut.value, digito.value)==false){
			alert("Debes ingresar un RUT válido");
			return false;
		}
		if(el.innerHTML != false){
			alert("Revisar problemas con el RUT " + el.value);
			return false;
		}
	}
	
	var nombres = document.getElementById("nombres");
	var apellidoPaterno = document.getElementById("apellidoPaterno");
	var comuna = document.getElementById("idComuna");
	var localidad = document.getElementById("localidad");
	/*
	var numeroPersonas = document.getElementById("numeroPersonas");
	var situacionPrevia = document.formulario.situacionPrevia;
	var terreno = document.formulario.terreno;
	var terrenoPlazo = document.formulario.terrenoPlazo;
	var subsidioHabitacionalPrevio = document.formulario.subsidioHabitacionalPrevio;
	var materialAnteriorLadrillo = document.getElementById("materialAnteriorLadrillo");
	var materialAnteriorAdobe = document.getElementById("materialAnteriorAdobe");
	var materialAnteriorMadera = document.getElementById("materialAnteriorMadera");
	var materialAnteriorMixta = document.getElementById("materialAnteriorMixta");
	var materialAnteriorHormigon = document.getElementById("materialAnteriorHormigon");
	var materialAnteriorTabiqueria = document.getElementById("materialAnteriorTabiqueria");
	*/

	if(nombres.value==false){
		ok=false;
	}
	if(apellidoPaterno.value==false){
		ok=false;
	}
	if(comuna.value==""){
		ok=false;
	}
	if(localidad.value==false){
		ok=false;
	}
	/*
	if(numeroPersonas.value==false){
		ok=false;
	}
	if((situacionPrevia[0].checked==false) && (situacionPrevia[1].checked==false) && (situacionPrevia[2].checked==false)){
		ok=false;
	}
	if((terreno[0].checked==false) && (terreno[1].checked==false) && (terreno[2].checked==false) && (terreno[3].checked==false) && (terreno[4].checked==false) && (terreno[5].checked==false)){
		ok=false;
	}
	if((terrenoPlazo[0].checked==false) && (terrenoPlazo[1].checked==false) && (terrenoPlazo[2].checked==false)){
		ok=false;
	}
	if((subsidioHabitacionalPrevio[0].checked==false) && (subsidioHabitacionalPrevio[1].checked==false)){
		ok=false;
	}
	
	// Material de la vivienda antes del terremoto
	if((materialAnteriorLadrillo.checked==false) && (materialAnteriorAdobe.checked==false) && (materialAnteriorMadera.checked==false) && (materialAnteriorMixta.checked==false) && (materialAnteriorHormigon.checked==false) && (materialAnteriorTabiqueria.checked==false)){
		ok=false;
	}
	*/
	if(ok==false){
		alert("Completar los campos obligatorios");
		return false;
	}else{
		return true;
	}
}

function validarCorreo(form){
	var textoError="";
	var ok=true;

	var correo = form.mail;
	
	if(validate_email(correo.value)==false){
		textoError=textoError+"<li>Debes ingresar un Correo válido</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}

function validarClave(form){
	var textoError="";
	var ok=true;

	var pass = form.password;
	var pass2 = form.password_2;
	
	if(pass.value==false){
		textoError=textoError+"<li>Debes ingresar tu contraseña</li>";
		ok=false;
	}
	if(pass2.value==false || pass.value!=pass2.value){
		textoError=textoError+"<li>Contraseñas diferentes</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}

function digitoVerificador(rut){
	var count=0;
	var count2=0;
	var factor=2;
	var suma=0;
	var sum=0;
	var digito=0;
	count2=rut.length - 1;
	
	while(count < rut.length){
		sum = factor * (parseInt(rut.substr(count2,1)));
		suma = suma + sum;
		sum=0;
		count = count + 1;
		count2 = count2 - 1;
		factor = factor + 1;
		
		if(factor > 7){
			factor=2;
		}
	}
	
	digito= 11 - (suma % 11);
	
	if(digito==11){
		digito=0;
	}
	
	if(digito==10){
		digito="k";
	}
	
	return digito;
}

function verificarRut(rut,digito){
	var textoError="";
	var ok=true;
	
	if(rut==false){
		textoError=textoError+"<li>Debes ingresar un RUT válido</li>";
		ok=false;
	}
	if(digito==false && digito!=0){
		textoError=textoError+"<li>Debes ingresar el digito verificador</li>";
		ok=false;
	}
	if(!(digitoVerificador(rut)==digito)){
		textoError=textoError+"<li>Debes ingresar un RUT válido</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		var el= document.getElementById("divErrores");
		el.innerHTML = "";
		return true;
	}
}

// función para validar los campos del formulario de Archivos de Diagnostico Mesa de Trabajo
function validarDatosArchivo(){
	var textoError="";
	var ok=true;
	
	var nombre=document.getElementById("nombre");
	var archivo=document.getElementById("archivo");

	if(nombre.value==false){
		textoError=textoError+"<li>Escriba un Nombre para identificar el Archivo</li>";
		ok=false;
	}
	if(archivo.value==false){
		textoError=textoError+"<li>Debe seleccionar el archivo</li>";
		ok=false;
	}
	
	if(ok==false){
		var el= document.getElementById("divErrores");
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}

function cargarPagina(tabla, filtro, valor, sigTabla, sel, rutina, parametro, idAdicional, mantenerNombreSel){
	var ruta = "?do=select.selectSimple&clase=" + tabla + "&filtro=" + filtro + "&valor=" + valor;
	
	if(sigTabla != ""){
		ruta += "&sigTabla=" + sigTabla;
	}
	
	if(sel != "" && sel!=undefined){
		ruta += "&sel=" + sel;
	}
	
	if(rutina!=undefined && rutina != ""){
		ruta += "&rutina=" + rutina;
	}
	
	if(parametro!=undefined && parametro != ""){
		ruta += "&parametro=" + parametro;
	}
	
	if(idAdicional != "" && idAdicional != undefined){
		tabla += "_" + idAdicional;
		ruta += "&idAdicional=" + idAdicional;
			
		if(mantenerNombreSel != undefined){
			ruta += "&mantenerNombreSel=1";
		}
	}
	

	ajaxManager('load_page',ruta,tabla);
}

function validarCajas(combo, boton){
	try{
		var tags = document.getElementsByTagName("input");
		button=document.getElementById(boton);
		button.disabled=true;
		combobox=document.getElementById(combo);
	
		for(i=0;i<tags.length;i++){
			if(tags[i].type=="checkbox"){
				if(tags[i].checked && combobox.value){
					button.disabled=false;
					break;
				}
			}
		}
	}catch(err){
		return;
	}
}

function validarEditarMetas(){
	var textoError="";
	var ok=true;

	var idUnidadOrganizacional=document.getElementById("idUnidadOrganizacional");
	var idMetaCategoria=document.getElementById("idMetaCategoria");
	var responsable=document.getElementById("idResponsable");
	var descripcion=document.getElementById("descripcion");
	var fechaFinMes=document.getElementById("fechaFinMes");
	var fechaFinAno=document.getElementById("fechaFinAno");
	
	if(idUnidadOrganizacional.value==false){
		textoError=textoError+"<li>Debes ingresar una oficina</li>";
		ok=false;
	}
	if(idMetaCategoria.value==false){
		textoError=textoError+"<li>Debes ingresar la categoria</li>";
		ok=false;
	}
	if(responsable.value==false){
		textoError=textoError+"<li>Debes ingresar un responsable</li>";
		ok=false;
	}
	if(descripcion.value==false){
		textoError=textoError+"<li>Debes ingresar una descripción</li>";
		ok=false;
	}
	if(fechaFinMes.value==false){
		textoError=textoError+"<li>Debes ingresar el mes estimado de cumplimiento de la meta</li>";
		ok=false;
	}
	if(fechaFinAno.value==false){
		textoError=textoError+"<li>Debes ingresar el ano estimado de cumplimiento de la meta</li>";
		ok=false;
	}
	if(ok==false){
		var el= document.getElementById("divErrores");
		textoError="<ul>"+textoError+"</ul>";
		el.innerHTML = textoError;
		return false;
	}else{
		return true;
	}
}

//Funcion para hacer edicion de un campo con ajax
/*
Uso: 
	En el div que envuelve el texto a editar, agregar en el onclick esta funcion.
Parametros:
	- elemento: El Div (Basta con poner this)
	- Datos de la Base de Datos:
		- tabla
		- id 
		- columna
	- Tipo: Define el tipo de input que se va a abrir. Opciones:
		* TEXT: Input de texto simple
		* AREA: Text area para textos mas largos
		* DATE: Fecha
		* NUMBER: El input debe ser un numero 
*/
	function editInline (elemento, id, tabla, columna, tipo)
	{
		var neoId = Math.floor(Math.random()*1001)+'_'+id+'_'+tabla+'_'+'columna';
		
		elemento.id = neoId;

		elemento.onclick = function(){
		}
		
		loadPage('?do=generic.loadFormEdit&id='+id+'&tabla='+tabla+'&columna='+columna+'&tipo='+tipo +'&pNodeId='+neoId,null,neoId,false,true,null);
	}
	
	var ultimoNumEditInline = new Array(); 
	function validadorNumEditInline (elInput, identificador)
	{
		if(elInput.value != '' && validate_number(elInput.value) == false)
		{
			document.getElementById('warning' + identificador).style.display="inline";
			elInput.focus();
			if (ultimoNumEditInline[identificador] == undefined)
				ultimoNumEditInline[identificador] = '' ;
			elInput.value = ultimoNumEditInline[identificador];
		}
		else
		{
			document.getElementById('warning' + identificador).style.display="none";
			ultimoNumEditInline[identificador] = elInput.value;
		}
	}
	
//Funcion para seleccionar un usuario inscrito en el sistema
/*
Uso: 
	En el input donde se guardara el idPersona poner un onFocus=selUsuario(this, '<id_Lugar_Para_El_Nombre>')
*/
	var InputDondeSeGuardaElUsuario;
	var idLogarDondeSeGuardaElNombreUsuario;
	var esperandoRespuestaSelUsuario = 0;
	function selUsuario(input, idLugarParaElNombre)
	{
		document.getElementById("SuggestSelUsuarioNombre").value="";
		document.getElementById("SuggestSelUsuarioApPat").value="";
		document.getElementById("SuggestSelUsuarioApMat").value="";
		document.getElementById("resSelUsuarioParaMi").innerHTML = "";
		document.getElementById("imagenLoadingSelUsuario").style.display = "none";
		
		esperandoRespuestaSelUsuario = 0;
		InputDondeSeGuardaElUsuario = input;
		idLogarDondeSeGuardaElNombreUsuario = idLugarParaElNombre;
		$('#selectorDeUsuarios').dialog("open");
		$("#selectorDeUsuarios").dialog('option', 'buttons', { });
	}
	
	function selUsuarioSelected (elemento)
	{
		InputDondeSeGuardaElUsuario.value = elemento.value;
		if (elemento.value != '')
		{
			document.getElementById(idLogarDondeSeGuardaElNombreUsuario).innerHTML = elemento.options[elemento.selectedIndex ].text ;
			$("#selectorDeUsuarios").dialog('option', 'buttons', { "Ok": function() { $(this).dialog("close"); } });
		}
		else
		{
			document.getElementById(idLogarDondeSeGuardaElNombreUsuario).innerHTML = "";
			$("#selectorDeUsuarios").dialog('option', 'buttons', { });
		}
	}
	
	function getSuggestSelUsuario(elemento)
	{		
		nom = document.getElementById("SuggestSelUsuarioNombre");
		ApP = document.getElementById("SuggestSelUsuarioApPat");
		ApM = document.getElementById("SuggestSelUsuarioApMat");
		
			esperandoRespuestaSelUsuario++;
			document.getElementById("imagenLoadingSelUsuario").style.display = "inline";
			loadPage('?do=generic.getUsuarios&name=' + nom.value + '&app=' + ApP.value + '&apm=' + ApM.value, null, 'resSelUsuarioParaMi' ,false,true,null);
	}
	
	function checkDondeSelUSuario ()
	{
		esperandoRespuestaSelUsuario--;
		if (esperandoRespuestaSelUsuario == 0)
			document.getElementById("imagenLoadingSelUsuario").style.display = "none";
	}
	
	//Sistema de metas
	var idInstanciaPlani;
	var yearMetas;
	function initCalendarioMetas(idIndicador, idIP, ano)
	{
		idInstanciaPlani = idIP;
		yearMetas = ano;
		
		for (i =0; i<idIndicador.length; i++)
		{
			for(mes = 1; mes<=12; mes++)
			{
				actualizarPanel(idIndicador[i], mes);
			}
		}
	}
	
	function actualizarPanel(idInd, mes)
	{
		var argv = actualizarPanel.arguments;
		var argc = argv.length;
		
		if (argc == 2 || argv[2]=='false')
		{
			if (document.getElementById(mes + '_' + idInd) != null)
				loadPage('?do=metas.loadPanelPlanificacion&year='+yearMetas+'&mes=' + mes + '&idInd=' + idInd+'&idInstanciaPlani=' + idInstanciaPlani, null, mes + '_' +idInd,false,true,null);
		}	
		else
		{
			if (document.getElementById(mes + '_' +idInd) != null)
				loadPage('?do=metas.editMetaPlanificacion&year='+yearMetas+'&mes=' + mes + '&idInd=' + idInd+'&lista=true&idInstanciaPlani=' + idInstanciaPlani + "&idMetVers=" + argv[3], null, mes + '_' + idInd,false,true,null);
		}
	}
	
	function miIsInteger (o)
	{
		s = o.value;
		var i;
		for (i = 0; i < s.length; i++)
		{
			var c = s.charAt(i);

			if (!miIsDigit(c)) 
				return false;
		}
		return true;
	}
	
	function miIsDigit (c)
	{
		return ((c >= "0") && (c <= "9"))
	}
	
	function validate_new_meta(mes, idInd, cuantif)
	{
		resp = document.getElementById('idResponsable' + mes + '_' + idInd);
		if (resp.value==null||resp.value=="")
		{
			alert("Debe seleccionar un responsable");
			return false;
		}
		
		if (cuantif)
		{
			meta = document.getElementById('meta' + mes + '_' + idInd );
			if (meta.value==null || meta.value=="")
			{
				alert("Debe ingresar una meta");
				return false;
			}
			if (miIsInteger (meta) == false)
			{
				alert("Meta debe ser de tipo numerico");
				return false;
			}
		}
		
		cargarFormulario('form' + mes + '_' + idInd,'?do=metas.saveMetaPlanificacion',mes + '_' + idInd);
	}
	
	function validate_actualizar_meta (mes, idInd)
	{  
		rep = document.getElementById('rep' + mes + '_' + idInd );
		if (rep.value==null || rep.value=="")
		{
			alert("Debe ingresar un valor reportado");
			return false;
		}
		if (miIsInteger (rep) == false)
		{
			alert("Valor reportado debe ser de tipo numerico");
			return false;
		}
		
		var argv = validate_actualizar_meta.arguments;
		var argc = argv.length;
		
		
		if (argc == 2 || argv[2] == 'false')
			cargarFormulario('form' + mes + '_' + idInd,'?do=metas.saveEstadoPlanificacion', mes + '_' + idInd);
		else
			cargarFormulario('form' + mes + '_' + idInd,'?do=metas.saveEstadoPlanificacion&lista='+argv[2], mes + '_' + idInd);
		
	}
	
	function validate_edit_meta (cuantif)
	{
		resp = document.getElementById('idResponsableEditor');
		if (resp.value==null||resp.value==""||resp.value=='0')
		{
			alert("Debe seleccionar un responsable");
			return false;
		}
		
		if (cuantif)
		{
			meta = document.getElementById('metaEditor');
			if (meta.value==null || meta.value=="")
			{
				alert("Debe ingresar una meta");
				return false;
			}
			if (miIsInteger (meta) == false)
			{
				alert("Meta debe ser de tipo numerico");
				return false;
			}

			rep = document.getElementById('reportadoEditor');
			if (rep.value==null || rep.value=="")
			{
				alert("Debe ingresar un valor reportado");
				return false;
			}
			if (miIsInteger (rep) == false)
			{
				alert("Valor reportado debe ser de tipo numerico");
				return false;
			}
		}
		
		return true;
	}

    function soyPorcentaje(elInput)
    {
        if(elInput.value != '' && (validate_number(elInput.value) == false || elInput.value<0 || elInput.value>100))
        {
            document.getElementById('warning' + elInput.name).style.display="inline";
            elInput.focus();
            if (ultimoNum[elInput.name] == undefined)
                ultimoNum[elInput.name] = '' ;
            elInput.value = ultimoNum[elInput.name];
            return false;
        }
        else
        {
            document.getElementById('warning' + elInput.name).style.display="none";
            ultimoNum[elInput.name] = elInput.value;
        }
        return true;
    }
	
	//  Funciones para el select de escuelas
	function escuela_function_select_construcciones(idUnidadOrganizacional, idAdicional){
		if(idAdicional != ""){
			ajaxManager("load_page","?do=select.selectConstrucciones&idUnidadOrganizacional="+idUnidadOrganizacional+"&idAdicional="+idAdicional, "escuela_div_select_construcciones_" + idAdicional);
		}else{
			ajaxManager("load_page","?do=select.selectConstrucciones&idUnidadOrganizacional="+idUnidadOrganizacional, "escuela_div_select_construcciones");
		}
	}
	function escuela_function_select_escuelas(idActividad, idAdicional){
		if(idAdicional != ""){
			ajaxManager("load_page","?do=select.selectEscuelas&idActividad="+idActividad, "escuela_div_select_escuelas_"+idAdicional);
		}else{
			ajaxManager("load_page","?do=select.selectEscuelas&idActividad="+idActividad, "escuela_div_select_escuelas");
		}
	}