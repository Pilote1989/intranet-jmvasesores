<?php
class menuNavegacion extends SessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$user = $this->getUsuario();
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
		$matriz_navegacion = array(
			"polizas.ver" => array(
				"nombre" => "Ver Poliza",
				"key" => "CiaNumero",
				"table" => "Poliza",
				"link" => "?do=polizas.ver&idPoliza=",
			),
			"polizas.editarDatosBasicos" => array(
				"nombre" => "Editar Poliza",
				"key" => "CiaNumero",
				"table" => "Poliza",
				"link" => "?do=polizas.ver&idPoliza=",
			),
			"polizas.agregarRenovacion" => array(
				"nombre" => "Agregar Renovacion",
				"key" => "CiaNumero",
				"table" => "Poliza",
				"link" => "?do=polizas.ver&idPoliza=",
			),
			"compras.verCompra" => array(
				"nombre" => "Ver Compra",
				"key" => "Id",
				"table" => "Compra",
				"link" => "?do=compras.verCompra&idCompra=",
			),
			"compras.editar" => array(
				"nombre" => "Editar Compra",
				"key" => "Id",
				"table" => "Compra",
				"link" => "?do=compras.verCompra&idCompra=",
			),
			"personas.verDatosBasicos" => array(
				"nombre" => "Ver Persona",
				"key" => "NombreCompleto",
				"table" => "Persona",
				"link" => "?do=personas.verDatosBasicos&idPersona=",
			),
			"personas.editarDatosBasicos" => array(
				"nombre" => "Ver Persona",
				"key" => "NombreCompleto",
				"table" => "Persona",
				"link" => "?do=personas.verDatosBasicos&idPersona=",
			),
			"ramos.ver" => array(
				"nombre" => "Ver Ramo",
				"key" => "Sigla",
				"table" => "Ramo",
				"link" => "?do=ramos.ver&idRamo=",
			),
			"ramos.editarDatosBasicos" => array(
				"nombre" => "Editar Ramo",
				"key" => "Sigla",
				"table" => "Ramo",
				"link" => "?do=ramos.ver&idRamo=",
			),
			"vehiculos.ver" => array(
				"nombre" => "Ver Vehiculo",
				"key" => "Placa",
				"table" => "Vehiculo",
				"link" => "?do=vehiculos.ver&idPoliza=",
			),
			"liquidaciones.ver" => array(
				"nombre" => "Ver Liquidacion",
				"key" => "Factura",
				"table" => "Liquidacion",
				"link" => "?do=liquidaciones.ver&idLiquidacion=",
			),
			"liquidaciones.editarDatosBasicos" => array(
				"nombre" => "Editar Liquidacion",
				"key" => "Factura",
				"table" => "Liquidacion",
				"link" => "?do=liquidaciones.ver&idLiquidacion=",
			),
			"liquidaciones.editarCedida" => array(
				"nombre" => "Editar Liquidacion",
				"key" => "Factura",
				"table" => "Liquidacion",
				"link" => "?do=liquidaciones.ver&idLiquidacion=",
			),
			"clientes.ver" => array(
				"nombre" => "Ver Cliente",
				"key" => "Nombre",
				"table" => "Cliente",
				"link" => "?do=clientes.ver&idCliente=",
			),
			"clientes.editarDatosBasicos" => array(
				"nombre" => "Editar Cliente",
				"key" => "Nombre",
				"table" => "Cliente",
				"link" => "?do=clientes.ver&idCliente=",
			),
			"companias.verDatosBasicos" => array(
				"nombre" => "Ver Compañia",
				"key" => "Nombre",
				"table" => "Compania",
				"link" => "?do=companias.ver&idCompania=",
			),
			"companias.editarDatosBasicos" => array(
				"nombre" => "Editar Compañia",
				"key" => "Nombre",
				"table" => "Compania",
				"link" => "?do=companias.ver&idCompania=",
			),
		);
		$blank=false;
		$foundValue=false;
		if($this->request->foundValue!=""){
			$foundValue = true;
		}else{
			$blank = true;
		}		
		$comando = $this->request->baseCommand;
		$encontrado = false;
		$line = array();
		$lineFind = "";
		foreach($matriz as $item){
			$line[1]=$item["nombre"];
			foreach($item["submenu"] as $subitem){
				$line[2]=$subitem["nombre"];
				if($comando==$subitem["link"]){
					$lineFind=$line[1] . "," . $line[2];
				}else{
					foreach($subitem["link_n"] as $temp_link){
						if($temp_link["dir"]==$this->request->baseCommand){
							if(array_key_exists("cond",$temp_link)){
								if($$temp_link["cond"]==$temp_link["val"]){	
									if(array_key_exists($this->request->baseCommand,$matriz_navegacion)){
										if(array_key_exists("link",$matriz_navegacion[$this->request->baseCommand])){
											$temp = Fabrica::getFromDB($matriz_navegacion[$this->request->baseCommand]["table"],$this->request->foundValue);
											$temp_command = "get".$matriz_navegacion[$this->request->baseCommand]["key"];
											$temp_link = $matriz_navegacion[$this->request->baseCommand]["link"];
											$lineFind=$line[1] . "," . $line[2].",<a href=".$temp_link.$this->request->foundValue.">".$temp->$temp_command()."</a>,".$this->lastCommand();
										}else{
											$temp = Fabrica::getFromDB($matriz_navegacion[$this->request->baseCommand]["table"],$this->request->foundValue);
											$temp_command = "get".$matriz_navegacion[$this->request->baseCommand]["key"];
											$lineFind=$line[1] . "," . $line[2].",".$temp->$temp_command().",".$this->lastCommand();	
										}
									}else{
										$lineFind=$line[1] . "," . $line[2].",".$this->request->foundValue.",".$this->lastCommand();	
									}									
									$encontrado = true;
									break;
								}
							}else{
								if(array_key_exists($this->request->baseCommand,$matriz_navegacion)){
									if(array_key_exists("link",$matriz_navegacion[$this->request->baseCommand])){
										$temp = Fabrica::getFromDB($matriz_navegacion[$this->request->baseCommand]["table"],$this->request->foundValue);
										$temp_command = "get".$matriz_navegacion[$this->request->baseCommand]["key"];
										$temp_link = $matriz_navegacion[$this->request->baseCommand]["link"];
										$lineFind=$line[1] . "," . $line[2].",<a href=".$temp_link.$this->request->foundValue.">".$temp->$temp_command()."</a>,".$this->lastCommand();
									}else{
										$temp = Fabrica::getFromDB($matriz_navegacion[$this->request->baseCommand]["table"],$this->request->foundValue);
										$temp_command = "get".$matriz_navegacion[$this->request->baseCommand]["key"];
										$lineFind=$line[1] . "," . $line[2].",".$temp->$temp_command().",".$this->lastCommand();	
									}
								}else{
									$lineFind=$line[1] . "," . $line[2].",".$this->request->foundValue.",".$this->lastCommand();	
								}								
								$encontrado = true;
								break;
							}
						}
					}
				}
				if(!$encontrado){
					foreach($subitem["submenu"] as $sub){
						if($comando==$sub["link"] || in_array($this->request->baseCommand,$sub["extra"])){
							$line[3]=$sub["nombre"];
							if(in_array($this->request->baseCommand,$sub["extra"])){
								$lineFind=$line[1] . "," . $line[2] . "," . $line[3].",".$this->lastCommand();
							}else{
								$lineFind=$line[1] . "," . $line[2] . "," . $line[3];	
							}
							$encontrado=true;
						}
					}
				}
			}
		}
		$menu = "";
		if($lineFind!=""){
			$places = explode(",",$lineFind);
			if(count($places)>0){
				$i=0;
				foreach ($places as $place) {
					$i++;
					if($i != count($places)){
						$menu .= "<li>" . $place . "&nbsp;&nbsp;</li>";
					}else{
						$menu .= "<li class='active'>" . $place . "</li>";
					}
				}
				$this->addVar("places", $menu);
			}
		}
		$this->addVar("places", $menu);
		$this->addVar("command", $this->request->baseCommand);
		$this->processTemplate("menus/menuNavegacion.html");
	}
	function lastCommand(){
		$ar = explode(".",$this->request->baseCommand);
		$arr = preg_split('/(?=[A-Z])/',ucfirst($ar[1]));
		return trim(implode(" ",$arr));
	}
}
?>