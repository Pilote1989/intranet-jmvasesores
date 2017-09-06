<?php
class ver extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		if($this->request->idPoliza){
			$matriz = $this->request->idPoliza;
			$this->addVar("matriz", $matriz);
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			//revisar si esta mandando id
			if($this->request->vig){
				$seleccionada = $this->request->vig;		
			}else{
				//echo "no mando vigencia, mostrando la vigente <br />";
				//verificar si hay vigente, saco fecha de hoy
				$todays_date = date("Y-m-d"); 
				$today = strtotime($todays_date);
				//echo $today . "<br />";
				//por defecto la seleccionada es la solicitada, por si no encuentro mas polizas
				$seleccionada = $this->request->idPoliza;
				//comienzo el loop por las vigencias
				$vigencias = Fabrica::getAllFromDB("Poliza", array("numeroPoliza = '" . $poliza->getNumeroPoliza() . "'", "estado = '1'"), "inicioVigencia ASC");
				$e=false;
				foreach($vigencias as $vigencia){
					$inicio = $vigencia->getInicioVigencia();
					$fin = $vigencia->getFinVigencia();
					if($today<strtotime($fin) && $today>=strtotime($inicio)){
						//encontre la vigencia
						$seleccionada = $vigencia->getId();
						$e=true;
						//echo $seleccionada . " - 2 - ";
					}else if(!$e){
						//selecciono la ultima revisada si la 
						$seleccionada = $vigencia->getId();		
						//echo $seleccionada . " - 1 - ";				
					}
				}
			}
			$poliza = Fabrica::getFromDB("Poliza",$seleccionada);
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			if($poliza->getTipo()!="POL"){
				$this->addBlock("eliminaRenovacion");
			}else{
				if($poliza->renovaciones() == "0" && $cobro->getIdLiquidacion() == ""){
					$this->addBlock("eliminarPoliza");
				}
			}
			$inicio =  $poliza->getInicioVigencia("DATE");
			$fin =  $poliza->getFinVigencia("DATE");			
			$this->addVar("vigenciaSeleccionada", $inicio . " - " . $fin);			
			//echo $seleccionada;
			$this->addVar("seleccionada", $seleccionada);
			$this->addBlock("bloqueEditarPolizas");
			$compania = Fabrica::getFromDB("Compania", $poliza->getIdCompania());
			$this->addVar("compania", $compania->getNombre());
			$this->addVar("sigC", $compania->getSigla());
			$cliente = Fabrica::getFromDB("Cliente", $poliza->getIdCliente());
			$this->addVar("nombre", $cliente->getNombre());
			$ramo = Fabrica::getFromDB("Ramo", $poliza->getIdRamo());
			$this->addVar("ramo", $ramo->getNombre());
			$this->addVar("sigR", $ramo->getSigla());
			$this->addVar("num", $poliza->getNumeroPoliza());
			if($poliza->estadoColor()=="green"){
				//vigente			
				$this->addVar("colorEstado", "success");
			}elseif($poliza->estado()=="yellow"){
				//vencida
				$this->addVar("colorEstado", "warning");
			}elseif($poliza->estado()=="blue"){
				//por iniciar
				$this->addVar("colorEstado", "info");
			}else{
				//la cagada
				$this->addVar("colorEstado", "danger");
			} 
			$this->addVar("estado", $poliza->estado());
			//sin vigencias
			//$this->addVar("idPoliza",$this->request->idPoliza);
			//$cupones = Fabrica::getAllFromDB("Cupon", array("idPoliza = " . $poliza->getId()));
			//nuevo con cigencias
			if($poliza->getAnulada()=="1"){
				$this->addBlock("rehabilitaPoliza");
			}else{
				$this->addBlock("anulaPoliza");
			}
			
			$this->addVar("idPoliza",$seleccionada);
			$endosos = Fabrica::getAllFromDB("Endoso", array("idPoliza = " . $seleccionada));
			$this->addVar("endosos", count($endosos));
			$cupones = Fabrica::getAllFromDB("Cupon", array("idPoliza = " . $seleccionada));
			$this->addVar("cupones", count($cupones));
			$cartas = Fabrica::getAllFromDB("Carta", array("idPoliza = " . $seleccionada));
			$this->addVar("cartas", count($cartas));
			$i = 0;
			if($this->checkAccess("crearUsuario", true)){
				$this->addBlock("admin");
			}
			if($this->request->m=="ee"){
				$this->addBlock("ee");
			}
			$this->addLayout("ace");
			$this->processTemplate("polizas/ver.html");
		}
	}
}
?>