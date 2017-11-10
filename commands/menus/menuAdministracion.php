<?php
class menuAdministracion extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$user = $this->getUsuario();
		$permisos = $user->getMenus();
		$menu = '<ul class="nav nav-list" id="mainMenu">';
		$matriz= array(
			1 => array(
				"nombre" => "Inicio",
				"icon" => "dashboard",
				"tipo" => "menu-text",
				"link" => "personas.verPortada"
			),
			2 => array(
				"nombre" => "Solicitudes",
				"icon" => "edit",
				"tipo" => "dropdown-toggle",
				"acceso" => "solicitudes",
				"submenu" => array(
					1 => array(
						"nombre" => "Hacer una Solicitud",
						"link" => "solicitudes.crearSolicitud"
					),
					2 => array(
						"nombre" => "Ver Mis Solicitudes",
						"link" => "solicitudes.verSolicitudes",
						"extra" => array("transportes.verPoliza")
					)
				)
			),
			3 => array(
				"nombre" => "Administracion",
				"icon" => "desktop",
				"tipo" => "dropdown-toggle",
				"submenu" => array(
					1 => array(
						"nombre" => "Inscribir Usuario",
						"link" => "personas.inscribirUsuario",
						"acceso" => "administracion",
					),
					2 => array(
						"nombre" => "Ver Personas",
						"link" => "personas.verPersonas",
						"acceso" => "administracion",
						"extra" => array(
							"personas.verDatosBasicos",
							"personas.editarDatosBasicos"
						),
					),
					3 => array(
						"nombre" => "Ver Ramos",
						"link" => "ramos.busqueda"
					),
					4 => array(
						"nombre" => "Cambiar mi clave",
						"link" => "personas.cambiarClave&mail=".$user->getMail()."&seed=".$user->getPassword()."",
						"extra" => array("personas.cambiarClave"),
					),
					5 => array(
						"nombre" => "Registro de Cambios",
						"link" => "admin.changelog",
						"acceso" => "administracion"
					)
				)
			),
			4 => array(
				"nombre" => "Contabilidad",
				"icon" => "usd",
				"tipo" => "dropdown-toggle",
				"acceso" => "contabilidad",
				"submenu" => array(
					1 => array(
						"nombre" => "Meses Contables",
						"link" => "contabilidad.meses"
					),
					2 => array(
						"nombre" => "Reporte SBS",
						"link" => "reportes.generaSBS"
					),
					3 => array(
						"nombre" => "Estadisticas por Año",
						"link" => "contabilidad.estadisticas"
					)
				)
			),
			5 => array(
				"nombre" => "Polizas",
				"icon" => "folder-open",
				"tipo" => "dropdown-toggle",
				"acceso" => "polizas",
				"submenu" => array(
					1 => array(
						"nombre" => "Crear una Poliza",
						"link" => "polizas.editarDatosBasicos"
					),
					2 => array(
						"nombre" => "Buscar Polizas",
						"link" => "polizas.busqueda",
						"extra" => array(
							"polizas.ver",
							"polizas.agregarRenovacion"
						),
					),
					3 => array(
						"nombre" => "Busqueda Especial",
						"link" => "polizas.busquedaEspecial"
					),
					4 => array(
						"nombre" => "Buscar Vehiculos",
						"link" => "vehiculos.busqueda",
						"extra" => array("vehiculos.ver"),
					)
				)
			),
			6 => array(
				"nombre" => "Comisiones",
				"icon" => "money",
				"tipo" => "dropdown-toggle",
				"acceso" => "comisiones",
				"submenu" => array(
					1 => array(
						"nombre" => "Crear Liquidacion",
						"submenu" => array(
							1 => array(
								"nombre" => "Comisiones",
								"icon" => "leaf",
								"link" => "liquidaciones.editarDatosBasicos"
							),
							2 => array(
								"nombre" => "Bonos",
								"icon" => "pencil",
								"link" => "liquidaciones.crearBono"
							)
						)
					),
					2 => array(
						"nombre" => "Ver Liquidaciones",
						"link" => "liquidaciones.verLiquidaciones",
						"extra" => array("liquidaciones.ver"),
					),
					3 => array(
						"nombre" => "Comisiones Cedidas",
						"submenu" => array(
							1 => array(
								"nombre" => "Crear Cedida",
								"icon" => "pencil",
								"link" => "liquidaciones.editarCedida"
							),
							2 => array(
								"nombre" => "Listado",
								"icon" => "leaf",
								"link" => "liquidaciones.verCedidas",
								"extra" => array("liquidaciones.verCedida"),
							),
							3 => array(
								"nombre" => "Pendientes",
								"icon" => "inbox",
								"link" => "reportes.cedidasPendientes"
							)
						)
					),
					4 => array(
						"nombre" => "Reporte de Facturas",
						"link" => "liquidaciones.reportes"
					),
					5 => array(
						"nombre" => "Comisiones por Cobrar",
						"link" => "reportes.comisionesPendientes"
					)
				)
			),
			7 => array(
				"nombre" => "Compras",
				"icon" => "shopping-cart",
				"tipo" => "dropdown-toggle",
				"acceso" => "compras",
				"submenu" => array(
					1 => array(
						"nombre" => "Agregar Compra",
						"link" => "compras.agregar"
					),
					2 => array(
						"nombre" => "Buscar Compras",
						"link" => "compras.busqueda",
						"extra" => array("compras.verCompra"),
					)
				)
			),
			8 => array(
				"nombre" => "Clientes",
				"icon" => "group",
				"tipo" => "dropdown-toggle",
				"acceso" => "clientes",
				"submenu" => array(
					1 => array(
						"nombre" => "Agregar Cliente",
						"link" => "clientes.editarDatosBasicos"
					),
					2 => array(
						"nombre" => "Buscar Clientes",
						"link" => "clientes.busqueda",
						"extra" => array("clientes.ver"),
					)
				)
			),
			9 => array(
				"nombre" => "Compañias",
				"icon" => "briefcase",
				"tipo" => "dropdown-toggle",
				"acceso" => "companias",
				"submenu" => array(
					1 => array(
						"nombre" => "Agregar Compañia",
						"link" => "companias.editarDatosBasicos"
					),
					2 => array(
						"nombre" => "Buscar Compañias",
						"link" => "companias.busqueda",
						"extra" => array("companias.verDatosBasicos"),
					)
				)
			),
			10 => array(
				"nombre" => "Reportes",
				"icon" => "bar-chart",
				"tipo" => "dropdown-toggle",
				"acceso" => "reportes",
				"submenu" => array(
					1 => array(
						"nombre" => "Consultas Web",
						"link" => "reportes.pantalla"
					),
					2 => array(
						"nombre" => "Reportes",
						"link" => "reportes.especial"
					)
				)
			),
		);
		foreach($matriz as $item){
			if($item["tipo"]=="menu-text"){
				if($item["link"]==$this->request->doFalso || in_array($this->request->doFalso,$item["extra"])){
					$menu .= '<li class="active"> <a href="?do='.$item["link"].'"> <i class="menu-icon fa fa-'.$item["icon"].'"></i><span class="menu-text"> '.$item["nombre"].' </span> </a> </li>';
				}else{
					$menu .= '<li> <a href="?do='.$item["link"].'"> <i class="menu-icon fa fa-'.$item["icon"].'"></i><span class="menu-text"> '.$item["nombre"].' </span> </a> </li>';
				}				
			}elseif($item["tipo"]=="dropdown-toggle"){
				if(in_array($item["acceso"],$permisos) || $item["acceso"]==null){
					$menu_sub = "";
					$encontrado = false;
					foreach($item["submenu"] as $subitem){
						if(in_array($subitem["acceso"],$permisos) || $subitem["acceso"]==null){
							if(array_key_exists("submenu",$subitem)){
								$menu_sub_sub = "";
								$encontrado_sub = false;
								foreach($subitem["submenu"] as $sub){
									if($sub["link"]==$this->request->doFalso || in_array($this->request->doFalso,$sub["extra"])){
										$menu_sub_sub .= '<li class="active menu_item"> <a href="?do='.$sub["link"].'"> <i class="menu-icon fa fa-'.$sub["icon"].'"></i> '.$sub["nombre"].' </a> </li>';	
										$encontrado = true;
										$encontrado_sub = true;
									}else{
										$menu_sub_sub .= '<li class="menu_item"> <a href="?do='.$sub["link"].'"> <i class="menu-icon fa fa-'.$sub["icon"].'"></i> '.$sub["nombre"].' </a> </li>';
									}
								}
								if($encontrado_sub){
									$menu_sub .= '<li class="open">';
								}else{
									$menu_sub .= '<li>';
								}
								$menu_sub .= '<a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-angle-double-right"></i> '.$subitem["nombre"].' <b class="arrow fa fa-angle-down"></b></a>';
								$menu_sub .= '<ul class="submenu">';
								$menu_sub .= $menu_sub_sub;
								$menu_sub .= '</ul>';
								$menu_sub .= '</li>';
							}else{
								if($subitem["link"]==$this->request->doFalso || in_array($this->request->doFalso,$subitem["extra"])){
									$menu_sub .= '<li class="active menu_item"> <a href="?do='.$subitem["link"].'"> <i class="menu-icon fa fa-angle-double-right"></i> '.$subitem["nombre"].' </a> </li>';
									$encontrado = true;
								}else{
									$menu_sub .= '<li class="menu_item"> <a href="?do='.$subitem["link"].'"> <i class="menu-icon fa fa-angle-double-right"></i> '.$subitem["nombre"].' </a> </li>';
								}								
							}
						}
					}
					if($encontrado){
						$menu .= '<li class="active open"> <a class="dropdown-toggle" href="#"> <i class="menu-icon fa fa-'.$item["icon"].'"></i> <span class="menu-text"> '.$item["nombre"].' </span> <b class="arrow fa fa-angle-down"></b> </a>';
					}else{
						$menu .= '<li> <a class="dropdown-toggle" href="#"> <i class="menu-icon fa fa-'.$item["icon"].'"></i> <span class="menu-text"> '.$item["nombre"].' </span> <b class="arrow fa fa-angle-down"></b> </a>';
					}
					$menu .= '<ul class="submenu">';
					$menu .= $menu_sub;
					$menu .= '</ul>';
				}
			}
		}
		$menu .= '</ul>';
		$this->addVar("menu", $menu);
		$this->processTemplate("menus/menuAdministracion.html");
	}
}
?>
