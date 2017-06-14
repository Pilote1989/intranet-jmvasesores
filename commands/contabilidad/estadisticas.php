<?php
class estadisticas extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();
		setlocale(LC_ALL,"es_ES");
		$nomMeses = array("Ene.","Feb.","Mar.","Abr.","May.","Jun.","Jul.","Ago.","Sep.","Oct.","Nov.","Dic.");
		/*
		ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
		*/
		$anioSelec = "2015";
		if($this->request->anio){
			$anioSelec = $this->request->anio;
		}
		$this->addVar("anio",$anioSelec);
		$query2 = '
			SELECT YEAR(fechaFactura) AS anio, MONTH(fechaFactura ) AS mes, 
			FORMAT(SUM(IF(moneda = "Soles", comision, 0)),2) AS comisionSoles,
			FORMAT(SUM(IF(moneda = "Soles", primaNeta, 0)),2) AS primaNetaSoles,
			FORMAT(SUM(IF(moneda = "Dolares", comision, 0)),2) AS comisionDolares,
			FORMAT(SUM(IF(moneda = "Dolares", primaNeta, 0)),2) AS primaNetaDolares
			FROM reporteComisiones
			WHERE YEAR(fechaFactura) = "' . $anioSelec . '"
			GROUP BY YEAR(fechaFactura) , MONTH(fechaFactura) 
			ORDER BY anio, mes		
		';
		$query3 = '
			SELECT YEAR(fechaFactura) AS anio, MONTH(fechaFactura ) AS mes, 
			ROUND(SUM(IF(moneda = "Soles", comision, 0)),2) AS comisionSoles,
			ROUND(SUM(IF(moneda = "Soles", primaNeta, 0)),2) AS primaNetaSoles,
			ROUND(SUM(IF(moneda = "Dolares", comision, 0)),2) AS comisionDolares,
			ROUND(SUM(IF(moneda = "Dolares", primaNeta, 0)),2) AS primaNetaDolares
			FROM reporteComisiones
			WHERE YEAR(fechaFactura) = "2015"
			GROUP BY YEAR(fechaFactura) , MONTH(fechaFactura) 
			ORDER BY anio, mes		
		';
		$query = '
			SELECT l.Month, COALESCE( comisionSoles, 0 ) AS comisionSoles, COALESCE( primaNetaSoles, 0 ) AS primaNetaSoles, COALESCE( comisionDolares, 0 ) AS comisionDolares, COALESCE( primaNetaDolares, 0 ) AS primaNetaDolares
			FROM (
			
			SELECT 1 AS 
			MONTH UNION ALL SELECT 2 
			UNION ALL SELECT 3 
			UNION ALL SELECT 4 
			UNION ALL SELECT 5 
			UNION ALL SELECT 6 
			UNION ALL SELECT 7 
			UNION ALL SELECT 8 
			UNION ALL SELECT 9 
			UNION ALL SELECT 10 
			UNION ALL SELECT 11 
			UNION ALL SELECT 12
			) AS l
			LEFT JOIN (
			
			SELECT MONTH( fechaFactura ) AS mes, ROUND( SUM( IF( moneda =  "Soles", comision, 0 ) ) , 2 ) AS comisionSoles, ROUND( SUM( IF( moneda =  "Soles", primaNeta, 0 ) ) , 2 ) AS primaNetaSoles, ROUND( SUM( IF( moneda =  "Dolares", comision, 0 ) ) , 2 ) AS comisionDolares, ROUND( SUM( IF( moneda =  "Dolares", primaNeta, 0 ) ) , 2 ) AS primaNetaDolares
			FROM reporteComisiones
			WHERE YEAR(fechaFactura) = "' . $anioSelec . '"
			GROUP BY MONTH( fechaFactura )
			) AS s ON s.mes = l.month		
		';
		
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
			
			$resultados=array();
			$this->fc->import("lib.Paginador");
			/*
			
            [anio] => 2014
            [mes] => 12
            [comisionSoles] => 24,597.88
            [primaNetaSoles] => 161,900.68
            [comisionDolares] => 18,304.28
            [primaNetaDolares] => 129,409.51
			
			*/
			
			while($row=$result->fetch_assoc()){
				$resultados[]=$row;
				//$meses[] = $nomMeses[$row["Month"]-1];
				//$comisionSoles[] = $row["comisionSoles"];
				//$primaNetaSoles[] = $row["primaNetaSoles"];
				//$comisionDolares[] = $row["comisionDolares"];
				//$primaNetaDolares[] = $row["primaNetaDolares"];
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		
		$i=0;
		/*
		$this->addVar("meses",'["' . implode('","', $meses). '"]');
		$this->addVar("comisionSoles",'["' . implode('","', $comisionSoles). '"]');
		$this->addVar("primaNetaSoles",'["' . implode('","', $primaNetaSoles). '"]');
		$this->addVar("comisionDolares",'["' . implode('","', $comisionDolares). '"]');
		$this->addVar("primaNetaDolares",'["' . implode('","', $primaNetaDolares). '"]');
    	*/
    	foreach($resultados as $resultado){
			$data[$i]["mes"] = $nomMeses[$resultado["Month"]-1];
			$data[$i]["primaDolares"] = $resultado["primaNetaDolares"];
			$data[$i]["comisionDolares"] = $resultado["comisionDolares"];
			$data[$i]["primaSoles"] = $resultado["primaNetaSoles"];
			$data[$i]["comisionSoles"] = $resultado["comisionSoles"];
			$i++;
    	}
    	if($this->request->t){
			if($this->request->t == "2"){
				$this->addVar("p","checked");
				$this->addEmptyVar("c");
				$this->addEmptyVar("pyc");
				$this->addBlock("p");
			}elseif($this->request->t == "3"){
				$this->addVar("c","checked");
				$this->addEmptyVar("p");
				$this->addEmptyVar("pyc");
				$this->addBlock("c");
			}else{
				$this->addVar("pyc","checked");
				$this->addEmptyVar("p");
				$this->addEmptyVar("c");
				$this->addBlock("pyc");
			}
		}else{
			$this->addVar("pyc","checked");
			$this->addEmptyVar("p");
			$this->addEmptyVar("c");
			$this->addBlock("pyc");
		}
    	
    	
		$this->addLoop("data",$data);
		$i=0;
		for($anio=(date("Y")); 2013<=$anio; $anio--) {
			$anios[$i]["id"] = $anio;
			$i++;
		}
		$this->addLoop("anios",$anios);
		
    	//print_r($data);
		$this->addLayout("ace");
		$this->processTemplate("contabilidad/estadisticas.html");
	}
}
?>