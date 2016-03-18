<?php
class meses extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$usuario=$this->getUsuario();/*
		setlocale(LC_ALL,"es_ES");
		$codMes = array("ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic");
		$nombreMes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre");
		$nomMeses = array("Ene.","Feb.","Mar.","Abr.","May.","Jun.","Jul.","Ago.","Sep.","Oct.","Nov.","Dic.");

		$lista = array();
		$meses = Fabrica::getAllFromDB("Mes",array(),"anio, mes","",false);
		$anioActual = 0;
		$i=0;
		foreach($meses as $mes){
			if($mes->getAnio() == $anioActual){
				//sigo en el mismo anio
				$lista[$i]["mesesA"][$j]["id"] = $j + 1;
				$lista[$i]["mesesA"][$j]["mes"] = $nomMeses[$j];
				$lista[$i]["mesesA"][$j]["codMes"] = $codMes[$j] . $mes->getAnio();
				$lista[$i]["mesesA"][$j]["numMes"] = $mes->getId();
				$lista[$i]["mesesA"][$j]["mesNum"] = $mes->getMes();
				$lista[$i]["mesesA"][$j]["nombreMes"] = $nombreMes[$j];
				$lista[$i]["mesesA"][$j]["color"] = $mes->estadoColor();
				$lista[$i]["mesesA"][$j]["colorBot"] = $mes->codBoot();
				$j++;
			}else{
				$j=0;
				//nuevo anio
				$i++;
				$lista[$i]["idLista"] = $i;
				$lista[$i]["anio"] = $mes->getAnio();
				$anioActual = $mes->getAnio();
				$lista[$i]["mesesA"][$j]["id"] = $j + 1;
				$lista[$i]["mesesA"][$j]["mes"] = $nomMeses[$j];
				$lista[$i]["mesesA"][$j]["codMes"] = $codMes[$j] . $mes->getAnio();
				$lista[$i]["mesesA"][$j]["numMes"] = $mes->getId();
				$lista[$i]["mesesA"][$j]["mesNum"] = $mes->getMes();
				$lista[$i]["mesesA"][$j]["nombreMes"] = $nombreMes[$j];
				$lista[$i]["mesesA"][$j]["color"] = $mes->estadoColor();
				$lista[$i]["mesesA"][$j]["colorBot"] = $mes->codBoot();
				$j++;
			}	
		}
		//print_r($lista);
		$this->addLoop("meses", $lista);*/
    	
		$this->addLayout("ace");
		$this->processTemplate("contabilidad/meses.html");
	}
}
?>