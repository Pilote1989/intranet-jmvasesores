<?php
class corrigeMoneda extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		echo "aa<br/>";
		$liquidaciones = Fabrica::getAllFromDB("Liquidacion",array());
		$cDolares = 0;
		$cSoles = 0;
		$cEuros = 0;
		$cNN = 0;
		foreach($liquidaciones as $liquidacion){
			$cDolares = 0;
			$cSoles = 0;
			$cEuros = 0;
			$cNN = 0;
			$listaIndefinidos = " - ";
			echo "idLiquidacion :".$liquidacion->getId()." : <br/>";
			$cobros = Fabrica::getAllFromDB("Cobro",array("idLiquidacion = '" . $liquidacion->getId() . "'"));
			foreach($cobros as $cobro){
				$queryCobro = "SELECT `moneda` FROM `reporteTodoF` WHERE `idCobro` = " . $cobro->getId();
				$queryCobro=utf8_decode($queryCobro);		
				$link=&$this->fc->getLink();
				if($result=$link->query($queryCobro)){
					$row=$result->fetch_assoc();
					$monedaSQL=$row['moneda'];
					if($monedaSQL=="Dolares"){
						$cDolares++;
					}else if($monedaSQL=="Soles"){
						$cSoles++;
					}else if($monedaSQL=="Euros"){
						$cEuros++;
					}else{
						$listaIndefinidos .= $cobro->getId() . " - ";
						$cNN++;
					}
					//$this->addVar("moneda",$monedaSQL);
				}else{
					printf("Error: %s\n", $link->error);
					return null;
				}
			}
			echo "&nbsp;&nbsp;Dolares : " . $cDolares ."<br/>";
			echo "&nbsp;&nbsp;Soles : " . $cSoles ."<br/>";
			echo "&nbsp;&nbsp;Euros : " . $cEuros ."<br/>";
			echo "&nbsp;&nbsp;Indefinido : " . ($cNN > 0 ? "Alerta : " : "") . $cNN ."".$listaIndefinidos."<br/>";
			echo "------------<br/>";
		}
	}
}
?>