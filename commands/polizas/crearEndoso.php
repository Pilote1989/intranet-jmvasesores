<?php
class crearEndoso extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Endoso");
		$fc->import("lib.Poliza");
		$fc->import("lib.Cobro");
		if($this->request->idPoliza){
			$this->addBlock("bloqueEditarEndoso");
			$poliza = Fabrica::getFromDB("Poliza", $this->request->idPoliza);
			$noDerecho = array("8","17");
			if(!in_array($poliza->getIdRamo(),$noDerecho)){
				$this->addBlock("derecho");
				//echo "Si tiene Derecho de Emision";
			}
			$this->addVar("idPoliza", $this->request->idPoliza);
			if($this->request->idEndoso){
				//Si mando idEndoso, estoy editando
				$endoso = Fabrica::getFromDB("Endoso",$this->request->idEndoso);				
				$this->addVar("accion", "Editar Endoso");
				$this->addBlock("bloqueIdEndoso");
				$this->addVar("idEndoso", $this->request->idEndoso);
				$this->addVar("documento", $endoso->getDocumento());
				$this->addVar("detalle", $endoso->getDetalle());
				$this->addVar("finVigencia", $endoso->getFinVigencia("DATE"));
				$this->addVar("inicioVigencia", $endoso->getInicioVigencia("DATE"));
				$cobro = Fabrica::getFromDB("Cobro",$endoso->getIdCobro());
				$this->addVar("cobranza", $cobro->getAvisoDeCobranza());
				$this->addVar("primaNeta", $cobro->getPrimaNeta());
				$this->addVar("derechoEmision", $cobro->getDerechoEmision());
				$this->addVar("primaComercial", $cobro->getPrimaComercial());
				$this->addVar("igv", $cobro->getIgv());
				$this->addVar("intereses", $cobro->getIntereses());
				$this->addVar("totalFactura", $cobro->getTotalFactura());
				$this->addVar("comisionP", $cobro->getComisionP());
				$this->addVar("comision", $cobro->getComision());
				$this->addVar("comisionVendedorP",$cobro->getComisionCedidaP());
				$this->addVar("comisionVendedor",$cobro->getComisionCedida());
				
				$this->addVar("idPersona",$cobro->getIdPersona());
				$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
				$selectCompania = '<option value=""></option>';
				foreach($personas as $persona){
					if($cobro->getIdPersona()!=$persona->getId()){
						$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
					}else{
						$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" selected="selected" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
						
					}
				}
				$this->addVar("personas",$selectPersona);				
				
				
				if($cobro->getIdLiquidacion() == ""){
					$this->addEmptyVar("readonly");
				}else{
					$this->addVar("readonly","readonly");	
					$this->addBlock("bloqueado");				
				}
				if($cobro->getIdCedida() == ""){
					$this->addEmptyVar("readonlyVendedor");
				}else{
					$this->addVar("readonlyVendedor","readonly");	
					$this->addBlock("bloqueadoVendedor");				
				}
			}else{
				//si no lo mando, creo
				$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
				$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
				$cliente = Fabrica::getFromDB("Cliente",$poliza->getIdCliente());
				$this->addVar("accion", "Crear Endoso");
				$this->addEmptyVar("documento");
				$this->addEmptyVar("detalle");
				$this->addVar("finVigencia","");
				$this->addVar("inicioVigencia",date("d/m/Y"));
				$this->addVar("finVigencia", $poliza->getFinVigencia("DATE"));
				$this->addEmptyVar("cobranza");
				$this->addVar("primaNeta","0.00");
				$this->addVar("derechoEmision","0.00");
				$this->addVar("primaComercial","0.00");
				$this->addVar("igv","0.00");
				$this->addVar("intereses","0.00");
				$this->addVar("totalFactura","0.00");
				$this->addVar("comisionP",$cobro->getComisionP());
				$this->addVar("comision","0.00");
				$this->addVar("comisionVendedor","0.00");
				$this->addEmptyVar("idPersona");
				$personas=Fabrica::getAllFromDB("Persona",array(),"nombres ASC");	
				$selectCompania = '<option value=""></option>';
				foreach($personas as $persona){
					if($cliente->getIdPersona()!=$persona->getId()){
						$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
					}else{
						$selectPersona=$selectPersona.'\n<option value="'.$persona->getId().'" selected="selected" x-com='.$persona->getComision().'>'.$persona->getNombres().' '.$persona->getApellidoPaterno().' '.$persona->getApellidoMaterno().'</option>';
						$this->addVar("comisionVendedorP",number_format($persona->getComision(),2));
					}	
				}
				$this->addVar("personas",$selectPersona);			
				
			}
		}
		$this->processTemplate("polizas/crearEndoso.html");
	}
}
?>