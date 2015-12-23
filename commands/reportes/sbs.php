<?php
class sbs extends sessionCommand{
	function execute(){
		// -> Banner
		/*
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);		*/
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$totales = array();
		
		if($this->request->periodo){
			$periodo = explode(" - ", $this->request->periodo);
			$periodo[0] = $this->procesarFecha($periodo[0]);
			$periodo[1] = $this->procesarFecha($periodo[1]);
			$where[]="fechaFactura >= '".$periodo[0]."'";
			$where[]="fechaFactura <= '".$periodo[1]."'";
		}
		
		$where[]="compania = c.sigla";
		
		if(sizeof($where)){
			$whereCondition = " WHERE (" . implode(") AND (",$where) . ")";
		}
		
		$query="
		SELECT  c.idCompania, sigla, c.nombre as nom
		FROM  reporteComisiones, Compania c
		".$whereCondition."
		GROUP BY compania
		";
		//echo $query;
			$query=utf8_decode($query);		
			$link=&$this->fc->getLink();
			
			if($result=$link->query($query)){
				$countQuery="SELECT FOUND_ROWS() as total";
			
				if($countResult=$link->query($countQuery)){
					$row=$countResult->fetch_assoc();
					$num_rows=$row['total'];
				}else{
					printf("Error: %s\n", $dbLink->error);
					return null;
				}
				$listaCompanias=array();
				while($row=$result->fetch_assoc()){
					$listaCompanias[]=$row;
				}
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$i = 0;
			$lista = array();
			foreach($listaCompanias as $compania){
				$lista[$i]["idLista"] = ++$i;
				$lista[$i]["compania"] = $compania["nom"];
				$totales[$i]["nom"] = $compania["nom"];
				$totales[$i][1]["prima"] = 0;
				$totales[$i][2]["prima"] = 0;
				$totales[$i][3]["prima"] = 0;
				$totales[$i][4]["prima"] = 0;
				$totales[$i][1]["comision"] = 0;
				$totales[$i][2]["comision"] = 0;
				$totales[$i][3]["comision"] = 0;
				$totales[$i][4]["comision"] = 0;
				
				$where2 = array();
				
				$where2[]= "compania = '" . $compania["sigla"] . "'";
				if($this->request->periodo){
					$periodo = explode(" - ", $this->request->periodo);
					$periodo[0] = $this->procesarFecha($periodo[0]);
					$periodo[1] = $this->procesarFecha($periodo[1]);
					$where2[]="fechaFactura >= '".$periodo[0]."'";
					$where2[]="fechaFactura <= '".$periodo[1]."'";
				}
				
		
				if(sizeof($where2)){
					$whereCondition = " WHERE (" . implode(") AND (",$where2) . ")";
				}
				
				$query="
				SELECT * 
				FROM  `reporteComisiones`
				".$whereCondition."
				";
				//echo $query;
				$query=utf8_decode($query);		
				$link=&$this->fc->getLink();
				
				if($result=$link->query($query)){
					$countQuery="SELECT FOUND_ROWS() as total";
				
					if($countResult=$link->query($countQuery)){
						$row=$countResult->fetch_assoc();
						$num_rows=$row['total'];
					}else{
						printf("Error: %s\n", $dbLink->error);
						return null;
					}
					$listaCobros=array();
					while($row=$result->fetch_assoc()){
						$listaCobros[]=$row;
					}
				}else{
					printf("Error: %s\n", $link->error);
					return null;
				}
				
				$j = 0;
				foreach($listaCobros as $cobro){		
					$lista[$i]["cobro"][$j]["idLista"]=++$j;
					$lista[$i]["cobro"][$j]["avisoDeCobranza"]=$cobro["avisoDeCobranza"];
					$lista[$i]["cobro"][$j]["nombre"]=$cobro["nombre"];
					$lista[$i]["cobro"][$j]["numeroPoliza"]=$cobro["numeroPoliza"];
					$lista[$i]["cobro"][$j]["primaNeta"]=number_format($cobro["primaNeta"],2);
					$lista[$i]["cobro"][$j]["comisionP"]=number_format($cobro["comisionP"],2);
					$lista[$i]["cobro"][$j]["comision"]=number_format($cobro["comision"],2);
					$lista[$i]["cobro"][$j]["fechaFactura"]=date("d/m/y", strtotime($cobro["fechaFactura"]));
					$lista[$i]["cobro"][$j]["ramo"]=$cobro["ramo"];
					$lista[$i]["cobro"][$j]["compania"]=$cobro["compania"];
					if($cobro["moneda"]=="Soles"){
						$lista[$i]["cobro"][$j]["moneda"]="1";
						$totales[$i][1]["prima"] += $cobro["primaNeta"];
						$totales[$i][1]["comision"] += $cobro["comision"];
					}elseif($cobro["moneda"]=="Dolares"){
						$lista[$i]["cobro"][$j]["moneda"]="2";
						$totales[$i][2]["prima"] += $cobro["primaNeta"];
						$totales[$i][2]["comision"] += $cobro["comision"];
					}elseif($cobro["moneda"]=="Euros"){
						$lista[$i]["cobro"][$j]["moneda"]="3";
						$totales[$i][3]["prima"] += $cobro["primaNeta"];
						$totales[$i][3]["comision"] += $cobro["comision"];
					}else{
						$lista[$i]["cobro"][$j]["moneda"]="0";
						$totales[$i][4]["prima"] += $cobro["primaNeta"];
						$totales[$i][4]["comision"] += $cobro["comision"];
					}
					$lista[$i]["cobro"][$j]["factura"]=$cobro["factura"];
					$lista[$i]["cobro"][$j]["totalFactura"]=number_format($cobro["totalFactura"],2);
					$lista[$i]["cobro"][$j]["inicioVigencia"]=date("d/m/y", strtotime($cobro["inicioVigencia"]));
					$lista[$i]["cobro"][$j]["finVigencia"]=date("d/m/y", strtotime($cobro["finVigencia"]));
					$lista[$i]["cobro"][$j]["doc"]=$cobro["doc"];
				}
			}
			$this->addLoop("comisiones", $lista);
			$tablaTotal=array();
			$i = 0;
			foreach($totales as $total){
				$tablaTotal[$i]["idLista"] = $total["nom"];
				$tablaTotal[$i]["solesC"] = number_format($total["1"]["comision"],2);
				$tablaTotal[$i]["dolaresC"] = number_format($total["2"]["comision"],2);
				$tablaTotal[$i]["eurosC"] = number_format($total["3"]["comision"],2);
				$tablaTotal[$i]["solesP"] = number_format($total["1"]["prima"],2);
				$tablaTotal[$i]["dolaresP"] = number_format($total["2"]["prima"],2);
				$tablaTotal[$i]["eurosP"] = number_format($total["3"]["prima"],2);
				$i++;
			}
			$this->addLoop("tablaTotal", $tablaTotal);
		if($this->request->vista=="2"){
			$this->addLayout("adminAlone");
			$this->processTemplate("reportes/sbs.html");
		}elseif($this->request->vista=="1"){
			$this->processTemplate("reportes/sbs.html");
		}
	}
	function procesarFecha($fecha){
		return date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
	}
}
?>