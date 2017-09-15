<?php
class cartera extends sessionCommand{
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
			"Cobroker",
			"Nombre",
			"Encargado",
			"Tipo",
			"Documento",
			"Correo",
			"Direccion",
			"Compania",
			"Ramo",
			"Numero de Poliza",
			"Inicio de Vigencia",
			"Fin de Vigencia",
			"Moneda",
			"Prima Neta",
			"Prima Comercial",
			"Comision",
			"Movimiento"	
		);
		$query="
				SELECT SQL_CALC_FOUND_ROWS 
				CONCAT(Persona.nombres, ' ', Persona.apellidoPaterno, ' ', Persona.apellidoMaterno) AS coBroker,
				Cliente.nombre AS nombre, 
				Cliente.encargado AS encargado, 
				Cliente.tipoDoc AS tipo, 
				Cliente.doc AS documento, 
				Cliente.correo AS correo, 
				Cliente.direccion AS direccion,
				Compania.nombre AS compania,
				Ramo.nombre AS ramo,
				reporteTodoF.numeroPoliza AS poliza, 
				reporteTodoF.inicioVigencia AS inicioVigencia, 
				reporteTodoF.finVigencia AS finVigencia, 
				Cobro.moneda AS moneda, 
				Cobro.primaNeta AS neta, 
				Cobro.primaComercial AS comercial, 
				Cobro.comisionP AS comision,
				reporteTodoF.doc AS mov
				FROM reporteTodoF, Cobro, Cliente, Compania, Ramo, Persona
				WHERE reporteTodoF.idCobro = Cobro.idCobro
				AND reporteTodoF.idCliente = Cliente.idCliente
				AND reporteTodoF.idCompania = Compania.idCompania
				AND reporteTodoF.idRamo = Ramo.idRamo
				AND Cobro.idPersona = Persona.idPersona";
		$query=utf8_decode($query);		
		$link=&$this->fc->getLink();
		if($result=$link->query($query)){
			$filename = "reporte"; 
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
		                $schema_insert .= "NULL".$sep;
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