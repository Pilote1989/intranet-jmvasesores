<?php
class free extends BaseCommand{
	function execute(){
		// -> Banner
		$cobros = Fabrica::getAllFromDB("Cobro");
		foreach($cobros as $cobro){
			if($cobro->tipo() == "POL"){
				$poliza = Fabrica::getAllFromDB("Poliza", array("idCobro = '" . $cobro->getId() . "'"));
				echo "POL : " . $cobro->getId() . " : " . $poliza[0]->getIdPersona();
				echo "<br>";				
				//echo $poliza[0]->getIdCompania() . "<br/>";
				$cobro->setIdPersona($poliza[0]->getIdPersona());
				$cobro->storeIntoDB();
			}else if($cobro->tipo() == "END"){
				$endoso = Fabrica::getAllFromDB("Endoso", array("idCobro = '" . $cobro->getId() . "'"));
				$poliza = Fabrica::getFromDB("Poliza", $endoso[0]->getIdPoliza());
				echo "END : " . $cobro->getId() . " : " . $poliza->getIdPersona() . " : " . $endoso[0]->getId();
				echo "<br>";
				//echo $poliza[0]->getIdCompania() . "<br/>";
				$cobro->setIdPersona($poliza->getIdPersona());
				$cobro->storeIntoDB();
			}else{
				echo "ALERTA";	
				echo "<br>";
			}
		}
	}
}
?>