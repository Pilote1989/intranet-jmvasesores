<?php
class busquedaAjax extends sessionCommand{
	function execute(){
		$fc=&FrontController::instance();
		$usuario=$this->getUsuario();
		$debug = false;
		$mapa = array(
			"liquidaciones" => array(
				"tabla" => "Liquidacion",
				"tablasExtra" => array("Compania"),
				"tablasLink" => array(
					"Liquidacion.idCompania = Compania.idCompania"
				),
				"session" => "busquedaLiquidaciones",
				"columnas" => array(
					"factura",
					"compania",
					"fecha",
				),
				"columnasSQL" => array(
					"Liquidacion.idLiquidacion as id",
					"Liquidacion.factura as factura",
					"DATE_FORMAT(Liquidacion.fechaFactura, '%d/%m/%Y') as fecha",
					"Compania.nombre as compania"
				),
				"busqueda" => array(
					"factura" => array(
						"order" => 0,
						"tabla" => "Liquidacion",
						"campo" => "factura",
						"tipo" => "LIKE%"
					),
					"idCompania" => array(
						"order" => 1,
						"tabla" => "Compania",
						"campo" => "idCompania",
						"tipo" => "EQUAL"
					),
				),
				"extras" => array(
					"ver"
				)
			),
			"vehiculos" => array(
				"tabla" => "Vehiculo",
				"tablasExtra" => array("Marca","Modelo"),
				"tablasLink" => array(
					"Vehiculo.idModelo = Modelo.idModelo",
					"Marca.idMarca = Modelo.idMarca"
				),
				"session" => "busquedaVehiculos",
				"columnas" => array(
					"placa",
					"modelo",
					"marca",
					"anio",
				),
				"columnasSQL" => array(
					"Vehiculo.idVehiculo as id",
					"Vehiculo.placa as placa",
					"Modelo.modelo as modelo",
					"Marca.marca as marca",
					"Vehiculo.anio as anio",
				),
				"busqueda" => array(
					"vendedor" => array(
						"order" => 0,
						"tabla" => "Vehiculo",
						"campo" => "placa",
						"tipo" => "LIKE%"
					),
				),
				"extras" => array(
					"ver"
				)
			),
			"compras" => array(
				"tabla" => "Compra",
				"tablasExtra" => array("Cliente"),
				"tablasLink" => array(
					"Compra.idCliente = Cliente.idCliente"
				),
				"session" => "busquedaCompras",
				"columnas" => array(
					"numeroFactura",
					"vendedor",
					"tipo",
					"moneda",
					"subTotal",
					"igv",
					"otros",
					"total",
					"fechaCompra"
				),
				"columnasOrder" => array(
					"8" => "STR_TO_DATE(fechaCompra, '%d/%m/%Y')"
				),
				"columnasSQL" => array(
					"Compra.idCompra as id",
					"Cliente.nombre as vendedor",
					"Compra.numeroFactura as numeroFactura",
					"Compra.tipo as tipo",
					"Compra.moneda as moneda",
					"FORMAT(Compra.subTotal,2) as subTotal",
					"FORMAT(Compra.igv,2) as igv",
					"FORMAT(Compra.otros,2) as otros",
					"FORMAT(Compra.total,2) as total",
					"DATE_FORMAT(Compra.fecha,'%d/%m/%Y') as fechaCompra"
				),
				"busqueda" => array(
					"vendedor" => array(
						"order" => 0,
						"tabla" => "Cliente",
						"campo" => "nombre",
						"tipo" => "LIKE%"
					),
					"tipo" => array(
						"order" => 1,
						"tabla" => "Compra",
						"campo" => "tipo",
						"tipo" => "LIKE"
					),
					"moneda" => array(
						"order" => 2,
						"tabla" => "Compra",
						"campo" => "moneda",
						"tipo" => "LIKE"
					)
				),
				"extras" => array(
					"verCompra"
				)
			),
			"clientes" => array(
				"tabla" => "Cliente",
				"tablasExtra" => array(),
				"tablasLink" => array(),
				"session" => "busquedaClientes",
				"columnas" => array(
					"nombre",
					"direccion",
					"documento"
				),
				"columnasSQL" => array(
					"Cliente.idCliente as id",
					"Cliente.nombre as nombre",
					"Cliente.direccion as direccion",
					"CONCAT(Cliente.tipoDoc,' - ',Cliente.doc) as documento"
				),
				"busqueda" => array(
					"nombre" => array(
						"order" => 0,
						"tabla" => "Cliente",
						"campo" => "nombre",
						"tipo" => "LIKE%"
					),
					"doc" => array(
						"order" => 1,
						"tabla" => "Cliente",
						"campo" => "doc",
						"tipo" => "LIKE%"
					)
				),
				"extras" => array(
					"ver"
				)
			),
			"ramos" => array(
				"tabla" => "Ramo",
				"tablasExtra" => array(),
				"tablasLink" => array(),
				"session" => "busquedaRamos",
				"columnas" => array(
					"sigla",
					"nombre"
				),
				"columnasSQL" => array(
					"Ramo.idRamo as id",
					"Ramo.sigla as sigla",
					"Ramo.nombre as nombre"
				),
				"busqueda" => array(
					"nombre" => array(
						"order" => 0,
						"tabla" => "Ramo",
						"campo" => "nombre",
						"tipo" => "LIKE%"
					)
				),
				"extras" => array(
					"ver"
				)
			),
			"companias" => array(
				"tabla" => "Compania",
				"tablasExtra" => array(),
				"tablasLink" => array(),
				"session" => "busquedaCompanias",
				"columnas" => array(
					"sigla",
					"nombre"
				),
				"columnasSQL" => array(
					"Compania.idCompania as id",
					"Compania.sigla as sigla",
					"Compania.nombre as nombre"
				),
				"busqueda" => array(
					"nombre" => array(
						"order" => 0,
						"tabla" => "Compania",
						"campo" => "nombre",
						"tipo" => "LIKE%"
					)
				),
				"extras" => array(
					"verDatosBasicos"
				)
			),
			"polizas" => array(
				"tabla" => "Poliza",
				"tablasExtra" => array("Cliente","Ramo","Compania"),
				"tablasLink" => array(
					"Poliza.idCliente = Cliente.idCliente",
					"Poliza.idRamo = Ramo.idRamo",
					"Poliza.idCompania = Compania.idCompania",
					"Poliza.estado = '1'"
				),
				"session" => "busquedaPolizas",
				"group" => " GROUP BY Poliza.numeroPoliza, Compania.sigla",
				"columnas" => array(
					"ciaNumero",
					"cliente",
					"ramo",
					"vigencia"
				),
				"columnasSQL" => array(
					"Poliza.idPoliza as id",
					"CONCAT(Compania.sigla,' - ',Poliza.numeroPoliza) as ciaNumero",
					"Cliente.nombre as cliente",
					"Ramo.nombre as ramo",
					"CONCAT(DATE_FORMAT(MIN(Poliza.inicioVigencia),'%d/%m/%Y'),' - ',DATE_FORMAT(MAX(Poliza.finVigencia),'%d/%m/%Y')) as vigencia",
					"MIN(Poliza.inicioVigencia) as inicioVigencia",
					"MAX(Poliza.finVigencia) as finVigencia"
				),
				"busqueda" => array(
					"contratante" => array(
						"order" => 0,
						"tabla" => "Cliente",
						"campo" => "nombre",
						"tipo" => "LIKE%"
					),
					"idRamo" => array(
						"order" => 1,
						"tabla" => "Ramo",
						"campo" => "idRamo",
						"tipo" => "EQUAL"
					),
					"idCompania" => array(
						"order" => 2,
						"tabla" => "Compania",
						"campo" => "idCompania",
						"tipo" => "EQUAL"
					),
					"idVendedor" => array(
						"order" => 3,
						"tabla" => "Poliza",
						"campo" => "idVendedor",
						"tipo" => "EQUAL"
					)
				),
				"extras" => array(
					"verPoliza"
				)
			),
		);
		$baseGet="get";
		//var_dump($this->request);
		$matriz=$this->request->matriz;
		if(array_key_exists($matriz,$mapa)){
			$matrizBase = $mapa[$matriz];
			$_SESSION[$matrizBase["session"]]=array();
			if($this->request->length==""){
				$limite = 10;
			}else{
				$limite = $this->request->length;
				$_SESSION[$matrizBase["session"]]["length"] = stripslashes($this->request->length);
			}
			if($this->request->start==""){
				$inicio = 0;
			}else{
				$inicio = $this->request->start;
				$_SESSION[$matrizBase["session"]]["start"] = stripslashes($this->request->start);
			}
			$tabla = $matrizBase["tabla"];
			$listaResultados = array();
			$order = null;
			if(array_key_exists($this->request->order[0]["column"],$matrizBase["columnasOrder"])){
				$order = $matrizBase["columnasOrder"][$this->request->order[0]["column"]]." ".$this->request->order[0]["dir"];
			}else{
				$order = $matrizBase["columnas"][$this->request->order[0]["column"]]." ".$this->request->order[0]["dir"];
			}
			$search = array();
			$search[] = "'1' = '1'";
			foreach($matrizBase["busqueda"] as $buscando){
				$mapaLinks[]=$buscando["link"];
				$orden=$this->request->$buscando["order"];
				$_SESSION[$matrizBase["session"]][$buscando["campo"]] = $orden["value"];
				$mapaTablas[] = $buscando["tabla"];
				if($orden["value"]!=""){
					switch($buscando["tipo"]){
						case "LIKE%":
							$search[] = $buscando["tabla"].".".$buscando["campo"]." LIKE '%".$orden["value"]."%'";	
							break;
						case "LIKE":
							$search[] = $buscando["tabla"].".".$buscando["campo"]." LIKE '".$orden["value"]."'";	
							break;
						case "EQUAL":
						default:
							$search[] = $buscando["tabla"].".".$buscando["campo"]." = '".$orden["value"]."'";	
					}
				}
			}
			
			$stringTables="";
			if(count($matrizBase["tablasExtra"])){
				$stringTables=" ".implode(", ",$matrizBase["tablasExtra"]).", ";
			}
			$stringTables.=$tabla;
			$stringRows=" ".implode(", ",$matrizBase["columnasSQL"]);
			$stringWhere="";
			$stringOrder="";
			$stringLimit="";
			$stringWhere=" WHERE (".implode(") AND (",array_merge($matrizBase["tablasLink"], $search)).")";
			if($order){
				$stringOrder=" ORDER BY ".$order;
			}
			$stringLimit=" LIMIT ".$inicio.",".$limite;
			$query="SELECT SQL_CALC_FOUND_ROWS ".$stringRows." FROM ".$stringTables.$stringWhere.$matrizBase["group"].$stringOrder.$stringLimit;
			$query=utf8_decode($query);
			//echo "<div>".$query."</div>";
			$resultadoJson = array();
			$i = 0;
			$dbLink=&$fc->getLink();
			$dataJson=array();
			if($result=$dbLink->query($query)){
				$countQuery="SELECT FOUND_ROWS() as total";
				if($countResult=$dbLink->query($countQuery)){
					$row=$countResult->fetch_assoc();
					$total=$row['total'];
				}else{
					printf("Error: %s\n", $dbLink->error);
					return null;
				}
				$countResult->free();
				while($row=$result->fetch_assoc()){
					unset($temp);
					foreach($matrizBase["columnas"] as $columna){
						$temp[]=$row[$columna];
					}
					if(in_array('verPoliza',$matrizBase["extras"]))
						$temp[]='<a class="blue" href="?do='.$matriz.'.ver&id'.$tabla.'='.$row["id"].'&vig='.$row["id"].'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
					if(in_array('ver',$matrizBase["extras"]))
						$temp[]='<a class="blue" href="?do='.$matriz.'.ver&id'.$tabla.'='.$row["id"].'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
					if(in_array('verDatosBasicos',$matrizBase["extras"]))
						$temp[]='<a class="blue" href="?do='.$matriz.'.verDatosBasicos&id'.$tabla.'='.$row["id"].'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
					if(in_array('verCompra',$matrizBase["extras"]))
						$temp[]='<a class="blue" href="?do='.$matriz.'.verCompra&id'.$tabla.'='.$row["id"].'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
					$dataJson[]=$temp;
				}				
			}
			$resultadoJson["_query"] = $query;
			$resultadoJson["draw"] = (int)$this->request->draw;
			$resultadoJson["recordsTotal"] = (int)$total;
			$resultadoJson["recordsFiltered"] = (int)$total;
			$resultadoJson["data"]=$dataJson;
			//var_dump($resultadoJson);
			echo json_encode($resultadoJson);
		}else{
			echo "No existe el key";
		}
	}
}
?>