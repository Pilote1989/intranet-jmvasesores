<?php
class Cupon extends DBObject{
	var $tableName="Cupon";
	function estado(){
		//revisar si esta anulada!!
		$todays_date = date("Y-m-d"); 
		$today = strtotime("+4 days");
		$envio = $this->getFechaVencimiento();
		if($this->getProgramado()==0){
			return "No Programado";
		}
		if($today<strtotime($envio)){
			return "Por Enviar";
		}elseif($today==strtotime($envio)){
			return "Enviado Hoy";
		}elseif($today>strtotime($envio)){
			return "Enviado";
		}else{
			return "No Encontrada";
		}
	}
	function estadoCommand(){
		//revisar si esta anulada!!
		$todays_date = date("Y-m-d"); 
		$today = strtotime("+4 days");
		$envio = $this->getFechaVencimiento();
		/*
		if($this->getProgramado()==0){
			return "No Programado";
		}*/
		if($today<strtotime($envio)){
			return "porEnviar";
		}elseif($today==strtotime($envio)){
			return "enviadoHoy";
		}elseif($today>strtotime($envio)){
			return "enviado";
		}else{
			return "noEncontrada";
		}
	}
	function monto(){
		$poliza = Fabrica::getFromDB("Poliza",$this->getIdPoliza());
		if($poliza->getMoneda()=="Soles"){
			$simbolo = "S/. ";
		}else if($poliza->getMoneda()=="Dolares"){
			$simbolo = "US$ ";
		}else if($poliza->getMoneda()=="Euros"){
			$simbolo = "&euro; ";
		} 
		return $simbolo . " " . $this->getMonto();
	}
	function estadoColor(){
		//revisar si esta anulada!!
		$todays_date = date("Y-m-d"); 
		$today = strtotime("+4 days");
		$envio = $this->getFechaVencimiento();
		if($this->getProgramado()==0){
			return "<span class='label'>No Programado</span>";
		}
		if($today<strtotime($envio)){
			return "<span class='label label-yellow'>Por Enviar</span>";
		}elseif($today==strtotime($envio)){
			return "<span class='label label-success'>Enviado Hoy</span>";
		}elseif($today>strtotime($envio)){
			return "<span class='label label-info'>Enviado</span>";
		}else{
			return "<span class='label label-warning'>No Encontrada</span>";
		}
	}
}
?>