<?php
class renovaciones extends sessionCommand{
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
			"COMPANIA",
			"CLIENTE",
			"RAMO",
			"NUMERO DE POLIZA",
			"VIGENCIA",
			"MONEDA",
			"PRIMA NETA",
		);
		$query='
SELECT SQL_CALC_FOUND_ROWS
		Compania.nombre,
    Cliente.nombre as cliente,
       Ramo.nombre as ramo,
       rtf.numeroPoliza as numeropoliza,
	concat(DATE_FORMAT(rtf.inicioVigencia,\'%d/%m/%Y\')," - ",DATE_FORMAT(rtf.finVigencia,\'%d/%m/%Y\')) as vigencia,
rtf.moneda as moneda,
       Sum(Cobro.primaneta) as primaneta
FROM   reporteTodoF AS rtf,
       Cobro,
       Cliente,
       Ramo,
Compania
WHERE  rtf.idCobro = Cobro.idCobro
       AND rtf.idCliente = Cliente.idCliente
       AND rtf.idRamo = Ramo.idRamo
       AND YEAR(rtf.finVigencia) = "'.$this->request->anio.'" AND MONTH(rtf.finVigencia) = "'.$this->request->mes.'"
AND rtf.idCompania = Compania.idCompania
GROUP  BY idPoliza
ORDER BY Compania.nombre, rtf.numeroPoliza, rtf.inicioVigencia';
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