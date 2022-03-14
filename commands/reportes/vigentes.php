<?php
class vigentes extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->length==""){
			$limite = 10;
		}else{
			$limite = $this->request->length;
		}
		if($this->request->start==""){
			$inicio = 0;
		}else{
			$inicio = $this->request->start;
		}
		$busquedaCliente = array();
		$columnas = array(
			"COD REG CONTRATO",
			"RAZON SOCIAL CIA DE SEGUROS",
			"NRO. DE POLIZA",
			"RIESGO",
			"MODALIDAD DE SEGURO",
			"CONTRATANTE",
			"TIPO",
			"DOCUMENTO",
			"CORREO",
			"DOMICILIO",
			"TELEFONO",
			"Fecha Solicit Seguros",
			"Fecha Aceptac de la empresa",
			"Fecha entrega de la poliza",
			"Inicio Vig",
			"Fin Vig",
			"Suma Asegurada Cobertura Principal",
            "Monto comision",
            "Fecha Renovacion de la poliza"
		);
		$query='
				SELECT SQL_CALC_FOUND_ROWS 
				Poliza.numeroInterno AS "COD REG CONTRATO",
				Compania.nombre AS "RAZON SOCIAL CIA DE SEGUROS",
				reporteTodoF.numeroPoliza AS "NRO. DE POLIZA", 
				Ramo.nombre AS "RIESGO",
				Poliza.modalidad AS "MODALIDAD DE SEGURO",
				Cliente.nombre AS "CONTRATANTE",
				Cliente.tipoDoc AS "TIPO", 
				Cliente.doc AS "DOCUMENTO", 
				Cliente.correo AS "CORREO", 
				Cliente.direccion AS "DOMICILIO",
				Cliente.telefono AS "TELEFONO",
				reporteTodoF.inicioVigencia AS "Fecha Solicit Seguros",
				Poliza.fechaAceptacion AS "Fecha Aceptac de la empresa",
				Poliza.fechaEntrega AS "Fecha entrega de la poliza", 
				reporteTodoF.inicioVigencia AS "Inicio Vig", 
				reporteTodoF.finVigencia AS "Fin Vig", 
				Poliza.sumaAsegurada AS "Suma Asegurada Cobertura Principal",
				FORMAT(Cobro.comision,2) AS "Monto comision",
				reporteTodoF.finVigencia AS "Fecha Renovacion de la poliza"
				FROM reporteTodoF, Cobro, Cliente, Compania, Ramo, Persona, Poliza
				WHERE reporteTodoF.idCobro = Cobro.idCobro
				AND reporteTodoF.idCliente = Cliente.idCliente
				AND reporteTodoF.idCompania = Compania.idCompania
				AND reporteTodoF.idRamo = Ramo.idRamo
				AND Cobro.idPersona = Persona.idPersona
				AND reporteTodoF.idPoliza = Poliza.idPoliza
				AND reporteTodoF.inicioVigencia <= CURRENT_DATE()
				AND reporteTodoF.finVigencia >= CURRENT_DATE()';
		$query=utf8_decode($query);
		$link=&$this->fc->getLink();
		if($result=$link->query($query)){
			$filename = "reportepolizas";
            ob_end_clean();
			header("Content-Type: application/xls");
			header("Content-Disposition: attachment; filename=$filename.xls");
			header("Pragma: no-cache"); 
			header("Expires: 0");
			$sep = "\t"; 

			foreach($columnas as $columna){
				echo $columna . "\t";
			}
			print("\n");
		    while($row = $result->fetch_assoc())
		    {
		    	$schema_insert = "";
		        foreach($row as $item){
		            if(!isset($item))
		                $schema_insert .= " ".$sep;
		            elseif ($item != "")
		                $schema_insert .= "$item".$sep;
		            else
		                $schema_insert .= "".$sep;
		        }

		        $schema_insert = str_replace($sep."$", "", $schema_insert);
		        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
		        $schema_insert .= "\t";
		        print(trim($schema_insert));
		        print "\n";
		    }   
		}else{
			printf("Error: %s\n", $link->error);
			return null;
		}
	}
}
?>