<?php
class verCorrespondencia extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			//$cartas = Fabrica::getAllFromDB("Cartas", array("idPoliza = " . $poliza->getId()), "fechaVencimiento ASC");
			$cartas = Fabrica::getAllFromDB("Carta", array("idPoliza = " . $poliza->getId()));
			$listaCartas = array();
			$i = 0;
			if(count($cartas)){
				$this->addBlock("bloqueResultados");	
			}else{
				$this->addBlock("bloqueNoResultados");				
			}
			foreach($cartas as $carta){
				//$listaCupones[$i]["fechaVencimiento"] = $cupon->getFechaVencimiento();
				$pieces = explode("-", $carta->getFecha());
				//print_r($pieces);
				//echo $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0] . "<br>";
				$listaCartas[$i]["fecha"] = $pieces[2] . "/" . $pieces[1] . "/" . $pieces[0];
				$listaCartas[$i]["numero"] = $fc->appSettings["siglasCompania"] . " - " . sprintf('%05d', $carta->getId()) . " - " . $pieces[0];
				$listaCartas[$i]["detalle"] = $carta->getDetalle();
    			$listaCartas[$i]["idCarta"] = $carta->getId();
    			
    			if(validateDate($carta->getDespacho("DATE"), 'd/m/Y')){
    				//valido, enviado
    				$listaCartas[$i]["despacho"] = '<span class="label label-success">Enviada el '.$carta->getDespacho("DATE").'</span>';
	    			$listaCartas[$i]["acciones"] = '
			            <a class="blue abreLink" x-link="/cartas/carta'.$carta->getId().'.pdf" href="#"> <i class="ace-icon fa fa-envelope bigger-130"></i> </a>  
			            <a class="blue abreLink" x-link="/?do=cartas.procesarCarta&idCarta='.$carta->getId().'" href="#"> <i class="ace-icon fa fa-print bigger-130"></i> </a>   			
	    			';
    			}else{
    				//no fecha
    				$listaCartas[$i]["despacho"] = '<span class="label">Pendiente</span>';
    				$listaCartas[$i]["acciones"] = '
			            <a class="blue abreLink" x-link="/?do=cartas.despacho&idCarta='.$carta->getId().'" href="#"> <i class="ace-icon fa fa-envelope bigger-130"></i> </a> 
			            <a class="blue abreLink" x-link="/?do=cartas.editar&idCarta='.$carta->getId().'" href="#"> <i class="ace-icon fa fa-search-plus bigger-130"></i> </a> 
			            <a class="blue abreLink" x-link="/?do=cartas.procesarCarta&idCarta='.$carta->getId().'" href="#"> <i class="ace-icon fa fa-print bigger-130"></i> </a> 
			            <a class="blue elimina" x-link="/?do=cartas.eliminarCarta&idCarta='.$carta->getId().'" href="#"> <i class="ace-icon fa fa-trash bigger-130"></i> </a>    			
	    			';
    			}
				$i++;
			}
			$this->addVar("idPoliza",$this->request->idPoliza);
			$this->addLoop("cartas", $listaCartas);
			$this->processTemplate("polizas/verCorrespondencia.html");
		}
	}
}
function validateDate($date, $format = 'Y-m-d H:i:s'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
?>