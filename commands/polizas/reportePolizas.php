<?php
class reportePolizas extends SessionCommand{
	function execute(){
		$this->fc->import("lib.Paginador");
		$this->fc->import("lib.Poliza");
		
		$usuario=$this->getUsuario();
		$where=array();/*
		$where=array();
		$_SESSION["busquedaPolizas"]["ramo"]=$this->request->idRamo;
		if($this->request->idRamo){
			$where[]="Poliza.idRamo = '".$this->request->idRamo."'";
		}
		$_SESSION["busquedaPolizas"]["contratante"]=$this->request->contratante;
		if($this->request->contratante){
			$where[]="c.nombre LIKE '%".$this->request->contratante."%'";
		}*/
		
		if($_SESSION["busquedaPolizas"]["ramo"]!=""){
			$where[]="Poliza.idRamo = '".$_SESSION["busquedaPolizas"]["ramo"]."'";
		}
		
		if($_SESSION["busquedaPolizas"]["compania"]!=""){
			$where[]="Poliza.idCompania = '".$_SESSION["busquedaPolizas"]["compania"]."'";
		}
		
		if($_SESSION["busquedaPolizas"]["vendedor"]!=""){
			$where[]="Poliza.idPersona = '".$_SESSION["busquedaPolizas"]["vendedor"]."'";
		}
		
		if($_SESSION["busquedaPolizas"]["contratante"]!=""){
			$where[]="c.nombre LIKE '%".$_SESSION["busquedaPolizas"]["contratante"]."%'";
		}
			
		$where[]="Poliza.idCliente = c.idCliente";
		$where[]="r.idRamo = Poliza.idRamo";
		$where[]="cia.idCompania = Poliza.idCompania";
		
		$where[]="Poliza.estado = '1'";
		
		if(sizeof($where)){
			$whereCondition=" WHERE (".implode(") AND (",$where).")";
		}

		$polizas=array();



		if($this->checkAccess("crearUsuario", true)){
			$query="
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT r.nombre as ramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza, cia.nombre as cia, cia.sigla as scia, min(inicioVigencia) as minimoVig, max(finVigencia) as maxVig, count(*)
				FROM Poliza, Cliente c, Ramo r, Compania cia
				".$whereCondition."
				GROUP BY numeroPoliza
				ORDER BY
					maxVig DESC
			";
		}else{
			$query="
				SELECT SQL_CALC_FOUND_ROWS
					DISTINCT r.nombre as ramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza, cia.nombre as cia, cia.sigla as scia, min(inicioVigencia) as minimoVig, max(finVigencia) as maxVig, count(*)
				FROM Poliza, Cliente c, Ramo r, Compania cia
				".$whereCondition." AND idPersona = " . $usuario->getId() . "
				GROUP BY numeroPoliza
				ORDER BY
					maxVig DESC
			";
		}


/*

		$query="
			SELECT SQL_CALC_FOUND_ROWS
				DISTINCT r.nombre as ramo, c.nombre as contratante, idPoliza, inicioVigencia, finVigencia, numeroPoliza
			FROM Poliza, Cliente c, Ramo r
			".$whereCondition." AND idPersona = " . $usuario->getId() . "
			ORDER BY
				finVigencia DESC
			LIMIT ".$minimoDePagina.", ".$limite."
		";*/
		//echo '<div>'.$query.'</div>'; 
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
			
			$listaPolizas=array();
			
			while($row=$result->fetch_assoc()){
				$listaPolizas[]=$row;
			}
			
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
		
		$i=0;
		//print_r($_SESSION);
		
		include("lib/PHPExcel.php");
		
		$objPHPExcel = new PHPExcel();
		$F=$objPHPExcel->getActiveSheet();
		$Line=1;
		$F->setCellValue('A'.$Line,  "idPoliza")
			->setCellValue('B'.$Line, "Numero de Poliza")
			->setCellValue('C'.$Line, "Contratante")
			->setCellValue('D'.$Line, "Ramo")
			->setCellValue('E'.$Line, "Sigla")
			->setCellValue('F'.$Line, "Compania")
			->setCellValue('G'.$Line, "Inicio de Vigencia")
			->setCellValue('H'.$Line, "Fin de Vigencia");//write in the sheet
		++$Line;		
		
		foreach($listaPolizas as $poliza){
			//$pol = Fabrica::getFromDB("Poliza",$poliza["idPoliza"]);
			$F->setCellValue('A'.$Line, $poliza["idPoliza"])
				->setCellValueExplicit('B'.$Line, $poliza["numeroPoliza"], PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('C'.$Line, $poliza["contratante"])
				->setCellValue('D'.$Line, $poliza["ramo"])
				->setCellValue('E'.$Line, $poliza["scia"])
				->setCellValue('F'.$Line, $poliza["cia"])
				->setCellValue('G'.$Line, date("d/m/Y",strtotime($poliza["inicioVigencia"])))
				->setCellValue('H'.$Line, date("d/m/Y",strtotime($poliza["finVigencia"])));//write in the sheet
			++$Line;			
			
			//$actual = Fabrica::getFromDB("Poliza", $poliza["idPersona"]);
			$polizas[$i]["contratante"] = $poliza["contratante"];
			$polizas[$i]["ramo"] = $poliza["ramo"];
			$polizas[$i]["numeroPoliza"] = $poliza["numeroPoliza"];			
			$polizas[$i]["idPoliza"] = $poliza["idPoliza"];		
			$polizas[$i]["cia"] = $poliza["cia"];		
			$polizas[$i]["scia"] = $poliza["scia"];
			//$polizas[$i]["fechaFinVigencia"] = date("d/m/Y",strtotime($poliza["finVigencia"]));
			//$polizas[$i]["fechaInicioVigencia"] = date("d/m/Y",strtotime($poliza["inicioVigencia"]));
			$polizas[$i]["fechaFinVigencia"] = date("d/m/Y",strtotime($poliza["maxVig"]));
			$polizas[$i]["fechaInicioVigencia"] = date("d/m/Y",strtotime($poliza["minimoVig"]));
			$i++;				
		}
		$F->getColumnDimension('A')->setAutoSize(true);
		$F->getColumnDimension('B')->setAutoSize(true);
		$F->getColumnDimension('C')->setAutoSize(true);
		$F->getColumnDimension('D')->setAutoSize(true);
		$F->getColumnDimension('E')->setAutoSize(true);
		$F->getColumnDimension('F')->setAutoSize(true);
		$F->getColumnDimension('G')->setAutoSize(true);
		$F->getColumnDimension('H')->setAutoSize(true);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="report.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
		
		
		
		//$tablaPaginas=Paginador::crearHtmlAjax($paginaActual,$num_rows,"?do=polizas.busquedaPolizas".$pPaginador,"divBusquedaPolizas", $limite);

		//$this->addVar("paginas",$tablaPaginas);
		//$this->addVar("num_rows",$num_rows);
		
		//$this->addLoop("polizas",$polizas);

		//$this->processTemplate("polizas/busquedaPolizas.html");*/
		
	}
}
?>
