<?php
class home extends BaseCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->redirect("?do=login");
		
		//$this->addLayout("public");


		//$this->processTemplate("home.html");
	}
}
?>