<?php
require $_SERVER['DOCUMENT_ROOT'].'/html2pdf/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

class pdfCarta extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		if($this->request->idCarta){
			$carta = Fabrica::getFromDB("Carta", $this->request->idCarta);
			$this->addVar("carta", $carta->getCarta("CARTA"));
			$pieces = explode("-", $carta->getFecha());	
			$this->addVar("numero", $fc->appSettings["siglasCompania"] . " - " . sprintf('%05d', $this->request->idCarta) . " - " . $pieces[0]);
			$response=$fc->executeCommand("cartas.cartaLista?idCarta=".$this->request->idCarta);
			$plugContents=$response->getContent();
			$html2pdf = new Html2Pdf('P','A4','es','true','UTF-8',array(20,40,20,0));
			//$plugContents = str_replace("90%", "100%", $plugContents);
			$plugContents = str_replace('"cellpadding="4"', '"cellpadding="44"', $plugContents);
			$html2pdf->setDefaultFont('Arial');
			$html2pdf->writeHTML($plugContents);
			$image_file = $_SERVER['DOCUMENT_ROOT'].'/assets/logo.jpg';
			$html2pdf->pdf->Image($image_file, 8, 8, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			$html2pdf->pdf->SetY(281);
			$html2pdf->pdf->SetX(119);
			$html2pdf->pdf->SetFont('helvetica', 'I', 7.5);
			$html2pdf->pdf->SetTextColor(71,34,0);
			$html2pdf->pdf->Cell(0, 10, 'Ca. Ricardo Angulo N 840 - Corpac - San Isidro - t:+511 611 3604 / c:992 710 108', 0, false, 'C', 0, '', 0, false, 'T', 'M');
			$html2pdf->pdf->SetY(284);
			$html2pdf->pdf->SetX(152);
	        $html2pdf->pdf->Cell(0, 10, 'jmartinez@jmvasesores.com - www.jmvasesores.com', 0, false, 'C', 0, '', 0, false, 'T', 'M');	        
	        $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(71, 34, 0));
			$html2pdf->pdf->Line(105, 292, 206, 292, $style);
			$html2pdf->pdf->Line(206, 250, 206, 292, $style);
			if($this->request->sologenera){
				$html2pdf->Output($_SERVER['DOCUMENT_ROOT'].'/cartas/carta'.$this->request->idCarta.'.pdf', 'F');	
			}else{
				$html2pdf->output();	
			}
		}
	}
}
?>