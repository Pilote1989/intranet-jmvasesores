<?php
class busquedaAjax extends sessionCommand{
	function execute(){
		$usuario=$this->getUsuario();
		$mapa = array(
			"clientes" => array(
				"tabla" => "Cliente",
				"columnas" => array(
					"nombre",
					"direccion",
					"documento"
				),
				"extras" => array(
					"ver"
				)
			),
			"ramos" => array(
				"tabla" => "Ramo",
				"session" => "busquedaRamos",
				"columnas" => array(
					"sigla",
					"nombre"
				),
				"busqueda" => array(
					"nombre" => array(
						"order" => 0,
						"tabla" => "ramos",
						"campo" => "nombre",
						"tipo" => "LIKE%"
					)
				),
				"extras" => array(
					"ver"
				)
			),
		);
		$baseGet="get";
		$matriz=$this->request->matriz;
		if(array_key_exists($matriz,$mapa)){
			$_SESSION[$mapa[$matriz]["session"]]=array();
			if($this->request->length==""){
				$limite = 10;
			}else{
				$limite = $this->request->length;
				$_SESSION[$mapa[$matriz]["session"]]["length"] = $this->request->length;
			}
			if($this->request->start==""){
				$inicio = 0;
			}else{
				$inicio = $this->request->start;
				$_SESSION[$mapa[$matriz]["session"]]["start"] = $this->request->start;
			}
			$tabla = $mapa[$matriz]["tabla"];
			$listaResultados = array();
			$order = $mapa[$matriz]["columnas"][$this->request->order[0]["column"]]." ".$this->request->order[0]["dir"];
			$search = array();
			foreach($mapa[$matriz]["busqueda"] as $buscando){
				$orden=$this->request->$buscando["order"];
				$_SESSION[$mapa[$matriz]["session"]][$buscando["campo"]] = $orden["value"];
				switch($buscando["tipo"]){
					case "LIKE%":
						$search[] = $buscando["campo"]." LIKE '%".$orden["value"]."%'";	
						break;
					case "LIKE":
						$search[] = $buscando["campo"]." LIKE '".$orden["value"]."'";	
						break;
					case "EQUAL":
					default:
						$search[] = $buscando["campo"]." = ".$orden["value"]."";	
				}
			}
			$resultados = Fabrica::getAllFromDB($tabla,$search,$order,$inicio.",".$limite, false);
			$total = Fabrica::getCountFromDB($tabla,$search);
			$resultadoJson = array();
			$resultadoJson["draw"] = (int)$this->request->draw;
			$resultadoJson["recordsTotal"] = (int)$total;
			$resultadoJson["recordsFiltered"] = (int)$total;
			$resultadoJson["_column"] = var_export($this->request->order[0]["column"], true);
			$resultadoJson["_dir"] = var_export($this->request->order[0]["dir"], true);
			$i = 0;
			$dataJson=array();
			foreach($resultados as $resultado){
				$j = 0;
				unset($temp);
				foreach($mapa[$matriz][columnas] as $columna){
					$baseGet.=ucfirst($columna);
					$temp[]=$resultado->$baseGet();
					$baseGet="get";
				}
				if(in_array('ver',$mapa[$matriz]["extras"]))
					$temp[]='<div class="action-buttons"><a class="blue" href="?do='.$matriz.'.ver&id'.$tabla.'='.$resultado->getId().'"><i class="ace-icon fa fa-search-plus bigger-130"></i></a></div>';
				$dataJson[]=$temp;
				$i++;
			}
			$resultadoJson["data"]=$dataJson;
			echo json_encode($resultadoJson);
		}else{
			echo "No existe el key";
		}
	}
}
?>