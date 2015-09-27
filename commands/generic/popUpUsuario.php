<?php
class popUpUsuario extends sessionCommand
{
	function execute()
	{	
		$this->processTemplate("generic/popUpUsuario.html");
	}
		
}
?>