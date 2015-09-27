<?php
class genera extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		//print_r($_POST);
		
		$where=array();		
		
		$condiciones = "<p align='left'>";
		
		if($this->request->idRamo){
			$where[]="Poliza.idRamo = '".$this->request->idRamo."'";
			$condiciones .= "Poliza : " . Fabrica::getFromDB("Ramo",$this->request->idRamo)->getNombre() . "<br />";
		}
		
		if($this->request->idCompania){
			$where[]="Poliza.idCompania = '".$this->request->idCompania."'";
			$condiciones .= "Compania : " . Fabrica::getFromDB("Compania",$this->request->idCompania)->getNombre() . "<br />";
		}
		$where[]="Poliza.idCliente = c.idCliente";
		$where[]="r.idRamo = Poliza.idRamo";
		$where[]="cia.idCompania = Poliza.idCompania";

		if(sizeof($where)){
			$whereCondition = " WHERE (" . implode(") AND (",$where) . ")";
		}
		
		
		$busquedaCliente = array();	
		if(sizeof($this->request->asegurado)){
			$clienteCondition = " AND (c.idCliente = '" . implode("' OR c.idCliente = '", $this->request->asegurado) . "')";
			$condiciones .= "Clientes : <br />";
			foreach($this->request->asegurado as $aseg){
				//echo $aseg;
				$condiciones .= " - " . Fabrica::getFromDB("Cliente",$aseg)->getNombre() . "<br />";
			}
			//print_r(Fabrica::getFromDB("Cliente",$this->request->asegurado));
			//$condiciones .= "Clientes : " . implode(", ", Fabrica::getFromDB("Cliente",$this->request->asegurado)->getNombre()) . "<br />";
		}
		//echo $condiciones;
		$condiciones .= "</p>";
		
		$polizas=array();

		$query="
			SELECT SQL_CALC_FOUND_ROWS
			DISTINCT r.nombre as ramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza, cia.nombre as cia, cia.sigla as scia, min(inicioVigencia) as minimoVig, max(finVigencia) as maxVig, count(*)
			FROM Poliza, Cliente c, Ramo r, Compania cia
			".$whereCondition."
			".$clienteCondition."
			GROUP BY numeroPoliza
			ORDER BY
				maxVig DESC
		";
		echo "<br / >" . $query;

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
			
			$listaPolizas=array();
			$this->fc->import("lib.Paginador");
			
			while($row=$result->fetch_assoc()){
				$listaPolizas[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
	
		foreach($listaPolizas as $poliza){
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);		
			$polizas[$i]["idPoliza"] = $poliza["idPoliza"];		
			$polizas[$i]["contratante"] = $poliza["contratante"];
			$polizas[$i]["ramo"] = $poliza["ramo"];
			$polizas[$i]["numeroPoliza"] = " " . $poliza["numeroPoliza"] . " ";	
			$polizas[$i]["cia"] = $poliza["cia"];		
			$polizas[$i]["scia"] = $poliza["scia"];
			$polizas[$i]["fechaFinVigencia"] = date("d/m/Y",strtotime($poliza["maxVig"]));
			$polizas[$i]["fechaInicioVigencia"] = date("d/m/Y",strtotime($poliza["minimoVig"]));
			$i++;				
		}

		$this->fc->import("lib.PHPExcel");
		// Instantiate a new PHPExcel object
		$objPHPExcel = new PHPExcel(); 
		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0); 
		// Initialise the Excel row number
		$rowCount = 1; 
		// Iterate through each result from the SQL query in turn
		// We fetch each database result row into $row in turn
		$objPHPExcel->getActiveSheet()->setCellValue("A1", "Id");
		$objPHPExcel->getActiveSheet()->setCellValue("B1", "Cliente");
		$objPHPExcel->getActiveSheet()->setCellValue("C1", "Ramo");
		$objPHPExcel->getActiveSheet()->setCellValue("D1", "Numero");
		$objPHPExcel->getActiveSheet()->setCellValue("E1", "Compania");
		$objPHPExcel->getActiveSheet()->setCellValue("F1", "Siglas");
		$objPHPExcel->getActiveSheet()->setCellValue("G1", "Fin de Vigencia");
		$objPHPExcel->getActiveSheet()->setCellValue("H1", "Inicio de Vigencia");
		$objPHPExcel->getActiveSheet()->fromArray($polizas, null, 'A2');
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		foreach(range('A','H') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		$actual = time();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'. $actual . '-reporteExcel.xls"');
		header('Cache-Control: max-age=0');
		  // Do your stuff here
		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$writer->save("../home21.xls");
		$writer->save("reportes/". $actual . "-reporteExcel.xls");
		
		$this->fc->import("lib.Reporte");
		
		$reporte = new Reporte();
		$reporte->setLink("reportes/". $actual . "-reporteExcel.xls");
		$reporte->setFecha(date('Y-m-d H:i:s'));
		$reporte->setDatos($condiciones);
		$reporte->storeIntoDB();

		//$this->addVar("query",$query);
		//$this->addLoop("polizas",$polizas);
		//$this->addLayout("admin");
		//$this->processTemplate("reportes/genera.html");
		$fc->redirect("?do=reportes.generar");
	}
}
?>