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
				"link_n" => array(
					1 => array(
						"dir" => "personas.verPortada"
					),
				),
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
						"link" => "solicitudes.crearSolicitud",
						"link_n" => array(
							1 => array(
								"dir" => "solicitudes.crearSolicitud"
							),
						),
					),
					2 => array(
						"nombre" => "Ver Mis Solicitudes",
						"link" => "solicitudes.verSolicitudes",
						"link_n" => array(
							1 => array(
								"dir" => "solicitudes.verSolicitudes"
							),
							2 => array(
								"dir" => "transportes.verPoliza"
							),
						),
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
						"link_n" => array(
							1 => array(
								"dir" => "personas.inscribirUsuario"
							),
						),
						"link" => "personas.inscribirUsuario",
						"acceso" => "administracion",
					),
					2 => array(
						"nombre" => "Ver Personas",
						"link" => "personas.verPersonas",
						"acceso" => "administracion",
						"link_n" => array(
							1 => array(
								"dir" => "personas.verPersonas"
							),
							2 => array(
								"dir" => "personas.verDatosBasicos"
							),
							3 => array(
								"dir" => "personas.editarDatosBasicos"
							),
						),
						"extra" => array(
							"personas.verDatosBasicos",
							"personas.editarDatosBasicos"
						),
					),
					3 => array(
						"nombre" => "Ver Ramos",
						"link" => "ramos.busqueda",
						"link_n" => array(
							1 => array(
								"dir" => "ramos.busqueda"
							),
							2 => array(
								"dir" => "ramos.ver"
							),
							3 => array(
								"dir" => "ramos.editarDatosBasicos"
							),
						),
						"extra" => array(
							"ramos.ver",
							"ramos.editarDatosBasicos"
						),
					),
					4 => array(
						"nombre" => "Cambiar mi clave",
						"link" => "personas.cambiarClave&mail=".$user->getMail()."&seed=".$user->getPassword()."",
						"extra" => array("personas.cambiarClave"),
						"link_n" => array(
							1 => array(
								"dir" => "personas.cambiarClave"
							),
						),
					),
					5 => array(
						"nombre" => "Registro de Cambios",
						"link" => "admin.changelog",
						"link_n" => array(
							1 => array(
								"dir" => "admin.changelog"
							),
						),
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
						"link" => "contabilidad.meses",
						"link_n" => array(
							1 => array(
								"dir" => "contabilidad.meses"
							),
						),
					),
					2 => array(
						"nombre" => "Reporte SBS",
						"link" => "reportes.generaSBS",
						"link_n" => array(
							1 => array(
								"dir" => "reportes.generaSBS"
							),
						),
					),
					3 => array(
						"nombre" => "Estadisticas por Año",
						"link" => "contabilidad.estadisticas",
						"link_n" => array(
							1 => array(
								"dir" => "contabilidad.estadisticas"
							),
						),
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
						"link" => "polizas.editarDatosBasicos",
						"link_n" => array(
							1 => array(
								"dir" => "polizas.editarDatosBasicos",
								"cond" => "foundValue",
								"val" => false
							),
						),
					),
					2 => array(
						"nombre" => "Buscar Polizas",
						"link" => "polizas.busqueda",
						"extra" => array(
							"polizas.ver",
							"polizas.agregarRenovacion"
						),
						"link_n" => array(
							1 => array(
								"dir" => "polizas.busqueda"
							),
							2 => array(
								"dir" => "polizas.ver"
							),
							3 => array(
								"dir" => "polizas.agregarRenovacion"
							),
							4 => array(
								"dir" => "polizas.editarDatosBasicos",
								"cond" => "foundValue",
								"val" => true
							),
						),
					),
					3 => array(
						"nombre" => "Busqueda Especial",
						"link" => "polizas.busquedaEspecial",
						"link_n" => array(
							1 => array(
								"dir" => "polizas.busquedaEspecial"
							),
						),
					),
					4 => array(
						"nombre" => "Buscar Vehiculos",
						"link" => "vehiculos.busqueda",
						"extra" => array("vehiculos.ver"),
						"link_n" => array(
							1 => array(
								"dir" => "vehiculos.busqueda"
							),
							2 => array(
								"dir" => "vehiculos.ver"
							),
						),
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
								"link" => "liquidaciones.editarDatosBasicos",
								"link_n" => array(
									1 => array(
										"dir" => "liquidaciones.editarDatosBasicos",
										"cond" => "foundValue",
										"val" => false
									),
								),
							),
							2 => array(
								"nombre" => "Bonos",
								"icon" => "pencil",
								"link" => "liquidaciones.crearBono",
								"link_n" => array(
									1 => array(
										"dir" => "liquidaciones.crearBono"
									),
								),
							)
						)
					),
					2 => array(
						"nombre" => "Ver Liquidaciones",
						"link" => "liquidaciones.busqueda",
						"extra" => array("liquidaciones.ver"),
						"link_n" => array(
							1 => array(
								"dir" => "liquidaciones.busqueda"
							),
							2 => array(
								"dir" => "liquidaciones.ver"
							),
							3 => array(
								"dir" => "liquidaciones.editarDatosBasicos",
								"cond" => "foundValue",
								"val" => true
							),
						),
					),
					3 => array(
						"nombre" => "Comisiones Cedidas",
						"submenu" => array(
							1 => array(
								"nombre" => "Crear Cedida",
								"icon" => "pencil",
								"link" => "liquidaciones.editarCedida",
								"link_n" => array(
									1 => array(
										"dir" => "liquidaciones.editarCedida",
										"cond" => "foundValue",
										"val" => false
									),
								),
							),
							2 => array(
								"nombre" => "Listado",
								"icon" => "leaf",
								"link" => "liquidaciones.verCedidas",
								"extra" => array("liquidaciones.verCedida"),
								"link_n" => array(
									1 => array(
										"dir" => "liquidaciones.verCedidas"
									),
									2 => array(
										"dir" => "liquidaciones.verCedida"
									),
									3 => array(
										"dir" => "liquidaciones.editarCedida",
										"cond" => "foundValue",
										"val" => true
									),
								),
							),
							3 => array(
								"nombre" => "Pendientes",
								"icon" => "inbox",
								"link" => "reportes.cedidasPendientes",
								"link_n" => array(
									1 => array(
										"dir" => "reportes.cedidasPendientes"
									),
								),
							)
						)
					),
					4 => array(
						"nombre" => "Reporte de Facturas",
						"link" => "liquidaciones.reportes",
						"link_n" => array(
							1 => array(
								"dir" => "liquidaciones.reportes"
							),
						),
					),
					5 => array(
						"nombre" => "Comisiones por Cobrar",
						"link" => "reportes.comisionesPendientes",
						"link_n" => array(
							1 => array(
								"dir" => "reportes.comisionesPendientes"
							),
						),
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
						"link" => "compras.agregar",
						"link_n" => array(
							1 => array(
								"dir" => "compras.agregar"
							),
						),
					),
					2 => array(
						"nombre" => "Buscar Compras",
						"link" => "compras.busqueda",
						"extra" => array("compras.verCompra"),
						"link_n" => array(
							1 => array(
								"dir" => "compras.busqueda"
							),
							2 => array(
								"dir" => "compras.verCompra"
							),
							3 => array(
								"dir" => "compras.editar"
							),
						),
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
						"link" => "clientes.editarDatosBasicos",
						"link_n" => array(
							1 => array(
								"dir" => "clientes.editarDatosBasicos",
								"cond" => "foundValue",
								"val" => false
							),
						),
					),
					2 => array(
						"nombre" => "Buscar Clientes",
						"link" => "clientes.busqueda",
						"extra" => array("clientes.ver"),
						"link_n" => array(
							1 => array(
								"dir" => "clientes.busqueda"
							),
							2 => array(
								"dir" => "clientes.ver"
							),
							3 => array(
								"dir" => "clientes.editarDatosBasicos",
								"cond" => "foundValue",
								"val" => true
							),
						),
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
						"link" => "companias.editarDatosBasicos",
						"link_n" => array(
							1 => array(
								"dir" => "companias.editarDatosBasicos",
								"cond" => "foundValue",
								"val" => false
							),
						),
					),
					2 => array(
						"nombre" => "Buscar Compañias",
						"link" => "companias.busqueda",
						"extra" => array("companias.verDatosBasicos"),
						"link_n" => array(
							1 => array(
								"dir" => "companias.busqueda"
							),
							2 => array(
								"dir" => "companias.verDatosBasicos"
							),
							3 => array(
								"dir" => "companias.editarDatosBasicos",
								"cond" => "foundValue",
								"val" => true
							),
						),
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
						"link" => "reportes.pantalla",
						"link_n" => array(
							1 => array(
								"dir" => "reportes.pantalla"
							),
						),
					),
					2 => array(
						"nombre" => "Reportes",
						"link" => "reportes.especial",
						"link_n" => array(
							1 => array(
								"dir" => "reportes.especial"
							),
						),
					)
				)
			),
		);
		$blank=false;
		$foundValue=false;
		if($this->request->foundValue!=""){
			$foundValue = true;
		}else{
			$blank = true;
		}
		foreach($matriz as $item){
			if($item["tipo"]=="menu-text"){
				foreach($item["link_n"] as $temp_link){
					if($temp_link["dir"]==$this->request->baseCommand){
						$menu .= '<li class="active"> <a href="?do='.$item["link"].'"> <i class="menu-icon fa fa-'.$item["icon"].'"></i><span class="menu-text"> '.$item["nombre"].' </span> </a> </li>';
					}else{
						$menu .= '<li> <a href="?do='.$item["link"].'"> <i class="menu-icon fa fa-'.$item["icon"].'"></i><span class="menu-text"> '.$item["nombre"].' </span> </a> </li>';	
					}
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
									$encontrado_sub_sub = false;
									foreach($sub["link_n"] as $temp_link){
										if($temp_link["dir"]==$this->request->baseCommand){
											if(array_key_exists("cond",$temp_link)){
												if($$temp_link["cond"]==$temp_link["val"]){	
													$encontrado = true;
													$encontrado_sub = true;
													$encontrado_sub_sub = true;
													break;
												}
											}else{
												$encontrado = true;
												$encontrado_sub = true;
												$encontrado_sub_sub = true;
												break;
											}
										}										
									}
									if($encontrado_sub_sub){
										$menu_sub_sub .= '<li class="active menu_item"> <a href="?do='.$sub["link"].'"> <i class="menu-icon fa fa-'.$sub["icon"].'"></i> '.$sub["nombre"].' </a> </li>';	
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
								$encontrado_s = false;
								foreach($subitem["link_n"] as $temp_link){
									if($temp_link["dir"]==$this->request->baseCommand){
										if(array_key_exists("cond",$temp_link)){
											if($$temp_link["cond"]==$temp_link["val"]){	
												$encontrado_s = true;
												$encontrado = true;
												break;
											}
										}else{
											$encontrado_s = true;
											$encontrado = true;
											break;
										}
									}
								}
								if($encontrado_s){
									$menu_sub .= '<li class="active menu_item"> <a href="?do='.$subitem["link"].'"> <i class="menu-icon fa fa-angle-double-right"></i> '.$subitem["nombre"].' </a> </li>';
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
