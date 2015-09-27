<?php
class menuAdministracion extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$user = $this->getUsuario();
		if($this->checkAccess("crearUsuario", true)){
			$this->addBlock("admin");
		}
		if($this->checkAccess("hacerSolicitud", true)){
			$this->addBlock("cliente");
		}
		if($this->checkAccess("verPolizas", true)){
			$this->addBlock("vendedor");
		}
		$this->addVar("mail", $user->getMail());
		$this->addVar("seed", $user->getPassword());
		$this->addEmptyVar("inicio");
		$this->addEmptyVar("administracion");
		$this->addEmptyVar("personas.inscribirUsuario");
		$this->addEmptyVar("personas.verPersonas");
		$this->addEmptyVar("personas.cambiarClave");
		$this->addEmptyVar("polizas");
		$this->addEmptyVar("polizas.editarDatosBasicos");
		$this->addEmptyVar("polizas.verPolizas");
		$this->addEmptyVar("clientes");
		$this->addEmptyVar("clientes.editarDatosBasicos");
		$this->addEmptyVar("clientes.verClientes");
		$this->addEmptyVar("companias");
		$this->addEmptyVar("companias.editarDatosBasicos");
		$this->addEmptyVar("companias.verCompanias");
		$this->addEmptyVar("reportes");
		$this->addEmptyVar("reportes.generar");
		$this->addEmptyVar("reportes.historico");
		$this->addEmptyVar("solicitudes");
		$this->addEmptyVar("solicitudes.crearSolicitud");
		$this->addEmptyVar("solicitudes.verSolicitudes");
		if($this->request->doFalso == "personas.verPortada"){
			$this->addVar("inicio", "class='active'");
		}elseif($this->request->doFalso == "solicitudes.crearSolicitud"){
			$this->addVar("solicitudes", "class='active open'");
			$this->addVar("solicitudes.crearSolicitud", "class='active'");
		}elseif($this->request->doFalso == "solicitudes.verSolicitudes"){
			$this->addVar("solicitudes", "class='active open'");
			$this->addVar("solicitudes.verSolicitudes", "class='active'");
		}elseif($this->request->doFalso == "personas.inscribirUsuario"){
			$this->addVar("administracion", "class='active open'");
			$this->addVar("personas.inscribirUsuario", "class='active'");
		}elseif($this->request->doFalso == "personas.verPersonas"){
			$this->addVar("administracion", "class='active open'");
			$this->addVar("personas.verPersonas", "class='active'");
		}elseif($this->request->doFalso == "personas.verDatosBasicos"){
			$this->addVar("administracion", "class='active open'");
			$this->addVar("personas.verPersonas", "class='active'");
		}elseif($this->request->doFalso == "personas.cambiarClave"){
			$this->addVar("administracion", "class='active open'");
			$this->addVar("personas.cambiarClave", "class='active'");
		}elseif($this->request->doFalso == "personas.inscribirUsuario"){
			$this->addVar("administracion", "class='active open'");
			$this->addVar("personas.inscribirUsuario", "class='active'");
		}elseif($this->request->doFalso == "polizas.ver"){
			$this->addVar("polizas", "class='active'");
		}elseif($this->request->doFalso == "polizas.editarDatosBasicos"){
			$this->addVar("polizas", "class='active open'");
			$this->addVar("polizas.editarDatosBasicos", "class='active'");
		}elseif($this->request->doFalso == "polizas.verPolizas"){
			$this->addVar("polizas", "class='active open'");
			$this->addVar("polizas.verPolizas", "class='active'");
		}elseif($this->request->doFalso == "clientes.verDatosBasicos"){
			$this->addVar("clientes", "class='active'");;
		}elseif($this->request->doFalso == "clientes.editarDatosBasicos"){
			$this->addVar("clientes", "class='active open'");
			$this->addVar("clientes.editarDatosBasicos", "class='active'");
		}elseif($this->request->doFalso == "clientes.verClientes"){
			$this->addVar("clientes", "class='active open'");
			$this->addVar("clientes.verClientes", "class='active'");
		}elseif($this->request->doFalso == "companias.verDatosBasicos"){
			$this->addVar("companias", "class='active'");
		}elseif($this->request->doFalso == "companias.editarDatosBasicos"){
			$this->addVar("companias", "class='active open'");
			$this->addVar("companias.editarDatosBasicos", "class='active'");
		}elseif($this->request->doFalso == "companias.verCompanias"){
			$this->addVar("companias", "class='active open'");
			$this->addVar("companias.verCompanias", "class='active'");
		}elseif($this->request->doFalso == "reportes.generar"){
			$this->addVar("reportes", "class='active open'");
			$this->addVar("reportes.generar", "class='active'");
		}elseif($this->request->doFalso == "reportes.historico"){
			$this->addVar("reportes", "class='active open'");
			$this->addVar("reportes.historico", "class='active'");
		}elseif($this->request->doFalso == "reportes.pantalla"){
			$this->addVar("reportes", "class='active open'");
			$this->addVar("reportes.pantalla", "class='active'");
		}elseif($this->request->doFalso == "liquidaciones.verLiquidaciones"){
			$this->addVar("liquidaciones", "class='active open'");
			$this->addVar("liquidaciones.verLiquidaciones", "class='active'");
		}elseif($this->request->doFalso == "liquidaciones.editarDatosBasicos"){
			$this->addVar("liquidaciones", "class='active open'");
			$this->addVar("liquidaciones.editarDatosBasicos", "class='active'");
		}elseif($this->request->doFalso == "liquidaciones.reportes"){
			$this->addVar("liquidaciones", "class='active open'");
			$this->addVar("liquidaciones.reportes", "class='cedidas'");
		}elseif($this->request->doFalso == "liquidaciones.reportes"){
			$this->addVar("liquidaciones", "class='active open'");
			$this->addVar("liquidaciones.cedidas", "class='active'");
		}
		$this->processTemplate("menus/menuAdministracion.html");
	}
}
?>
