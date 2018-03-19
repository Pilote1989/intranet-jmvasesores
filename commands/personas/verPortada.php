<?php
class verPortada extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		if(!$usuario=$this->getUsuario()){
			$fc->redirect("?do=home");
		}
		$this->addVar("doFalso", $this->request->do);
		// Chequea si tiene perfiles Asociados
		if(!$perfiles = $usuario->getPerfiles()){
			$fc->redirect("?do=home");
		}
		$temporal = "";
		if($this->checkAccess("crearUsuario", true)){
			
			$sMes = array(
			    1 => "ENE",
			    2 => "FEB",
			    3 => "MAR",
			    4 => "ABR",
			    5 => "MAY",
			    6 => "JUN",
			    7 => "JUL",
			    8 => "AGO",
			    9 => "SET",
			    10 => "OCT",
			    11 => "NOV",
			    12 => "DIC",
			);
			$tMes = array(
			    1 => "Enero",
			    2 => "Febrero",
			    3 => "Marzo",
			    4 => "Abril",
			    5 => "Mayo",
			    6 => "Junio",
			    7 => "Julio",
			    8 => "Agosto",
			    9 => "Setiembre",
			    10 => "Octubre",
			    11 => "Noviembre",
			    12 => "Diciembre",
			);			
			$this->addBlock("admin");
			$porLiquidar = "X";
			$porVencer = "X";
			$recordatoriosPorEnviar = "X";
			//comisiones por liquidar
			$query='
			SELECT 
				COUNT(*) as c
			FROM
				jmvclientes.Cobro
			WHERE
				idLiquidacion IS NULL
			';
			$query=utf8_decode($query);		
			$link=&$this->fc->getLink();			
			if($result=$link->query($query)){
				$row=$result->fetch_assoc();
				//echo $row["c"];
				/*while($row=$result->fetch_assoc()){
					$listaComisiones[]=$row;
					$j++;
				}*/
				$porLiquidar = $row["c"];
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			//polizas por vencer
			$query='
			SELECT COUNT( DATEDIFF(  `finVigencia` , CURDATE( ) ) ) AS c
			FROM  `Poliza` 
			WHERE DATEDIFF(  `finVigencia` , CURDATE( ) ) >0
			AND DATEDIFF(  `finVigencia` , CURDATE( ) ) <7
			';
			$query=utf8_decode($query);		
			$link=&$this->fc->getLink();			
			if($result=$link->query($query)){
				$row=$result->fetch_assoc();
				$porVencer = $row["c"];
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$this->addVar("porLiquidar", $porLiquidar);
			$this->addVar("porVencer", $porVencer);
			$this->addVar("recordatoriosPorEnviar", $recordatoriosPorEnviar);
			
			$tempMes = 10;
			$tempAnio = 2016;
			
			//$url = "http://www.google.com/finance/converter?a=1&from=USD&to=PEN";
			$url = "http://finance.google.com/finance/converter?a=1&from=USD&to=PEN"; 
			$url = "https://www.google.com.pe/search?q=1+usd+to+pen";
			$request = curl_init(); 
			$timeOut = 0; 
			curl_setopt ($request, CURLOPT_URL, $url); 
			curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt ($request, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 
			curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, $timeOut); 
			$response = curl_exec($request); 
			curl_close($request); 
			//echo $response;
			//$regularExpression     = '#\<span class=bld\>(.+?)\<\/span\>#s';
			$regularExpression = '#\<div class=\"J7UKTe\"\>(.+?)\<\/div\>#s';
			preg_match($regularExpression, $response, $finalData);
			$exchangeRate = substr($finalData[1],14,4);
			//$exchangeRate = substr($finalData[0],16,6);
			$this->addVar("tc",$exchangeRate);
			$sqlCompania = "
				SELECT SQL_CALC_FOUND_ROWS  `compania` , moneda, TRUNCATE( SUM( IF(  `moneda` =  'Soles',  `comision` / " . $exchangeRate . ", `comision` ) ) , 2 ) AS valor
				FROM  `reporteComisiones` 
				WHERE MONTH(  `fechaFactura` ) = MONTH( NOW( ) )
				AND YEAR(  `fechaFactura` ) = YEAR( NOW( ) )
				GROUP BY  `compania` 
				ORDER BY valor DESC 		
			";
			
			$sqlRamo = "
				SELECT  SQL_CALC_FOUND_ROWS `ramo` , TRUNCATE( SUM( IF(  `moneda` =  'Soles',  `comision` / " . $exchangeRate . ", `comision` ) ) , 2 ) AS valor
				FROM  `reporteComisiones` 
				WHERE MONTH(  `fechaFactura` ) = MONTH( NOW( ) ) 
				AND YEAR(  `fechaFactura` ) = YEAR( NOW( ) ) 
				GROUP BY  `ramo`
				ORDER BY valor DESC
			";
			
			$sqlMes = "
				SELECT SQL_CALC_FOUND_ROWS 
					YEAR(  `fechaFactura` ) AS anio, 
					MONTH(  `fechaFactura` ) AS mes, 
					TRUNCATE( SUM( IF(  `moneda` =  'Soles',  `comision` / " . $exchangeRate . ", `comision` ) ) , 2 ) AS com, 
					TRUNCATE( SUM( IF(  `moneda` =  'Soles',  `primaNeta` / " . $exchangeRate . ", `primaNeta` ) ) , 2 ) AS pri
				FROM  `reporteComisiones` 
				WHERE  `fechaFactura` >= DATE_FORMAT( CURDATE( ) ,  '%Y-%m-01' ) - INTERVAL 3 MONTH 
				GROUP BY year(  `fechaFactura` ), MONTH(  `fechaFactura` ) 
				ORDER BY year(  `fechaFactura` ), MONTH(  `fechaFactura` ) 
			";
			
			$query=utf8_decode($sqlCompania);		
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
				
				$lista=array();
				$this->fc->import("lib.Paginador");
				
				while($row=$result->fetch_assoc()){
					$lista[]=$row;
				}
				
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$i=0;
			$companias = array();
			foreach($lista as $item){
				if($item["valor"]>0){
					$companias[$i]["sigla"] = $item["compania"];
					$companias[$i]["valor"] = $item["valor"];
					$i++;			
				}
			}
			$this->addLoop("companias",$companias);
			
			//////////////////////////////////////////
			$query=utf8_decode($sqlRamo);		
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
				
				$lista=array();
				$this->fc->import("lib.Paginador");
				
				while($row=$result->fetch_assoc()){
					$lista[]=$row;
				}
				
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$i=0;
			$ramos = array();
			foreach($lista as $item){
				if($item["valor"]>0){
					$ramos[$i]["sigla"] = $item["ramo"];
					$ramos[$i]["valor"] = $item["valor"];
					$i++;				
				}
			}
			$this->addLoop("ramos",$ramos);
			
			//////////////////////////////////////////
			$query=utf8_decode($sqlMes);		
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
				
				$lista=array();
				$this->fc->import("lib.Paginador");
				while($row=$result->fetch_assoc()){
					$lista[]=$row;
				}
			}else{
				printf("Error: %s\n", $link->error);
				return null;
			}
			
			$i=0;
			//print_r($lista);
			$tempMes = "";
			$meses = array();
			foreach($lista as $item){
				if($i==0){
					$temporal = $tMes[$item["mes"]];
				}
				$meses[$i]["mes"] = $sMes[$item["mes"]];
				$tempMes = $item["mes"];
				$meses[$i]["com"] = $item["com"];
				$meses[$i]["pri"] = $item["pri"];
				$i++;
			}
			
			$this->addVar("mesInicio",$temporal);
			$this->addVar("mesFin",$tMes[$tempMes]);
			
			$this->addLoop("meses",$meses);
			
			
		}
		$mesCheck = Fabrica::getAllFromDB("Mes", array("mes = '" . date('n') . "'","anio = '" . date('Y') . "'"));	
		if(count($mesCheck) == 0){
			$this->addBlock("crearMes");
		}
		
		// Nombre
		$this->addBlock("bloqueNombre");
		//$this->addVar("nombreUsuario", $usuario->getNombres());
		$this->addLayout("ace");
		$this->processTemplate("personas/verPortada.html");
	}
}
?>