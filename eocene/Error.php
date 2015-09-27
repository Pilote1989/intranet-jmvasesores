<?php
/**
 * Class Error
 * Version 1.0.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modification history.
 * 10/01/2002 Initial creation.
 * Error class writes any error and abort the request.
 * Modify it according to your needs.
 *
 * PUBLIC METHODS
 *	Error(&$errorMessage)
*/

define("E_VALIDACION_INCORRECTO", 1);
define("E_VALIDACION_OBLIGATORIO", 2);

class Error{
	function __construct(&$errorMessage = null){
        if (!$errorMessage) {
            return;
        }

		$fc=&FrontController::instance();
		echo '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>eBase - JMVSeguros</title>
<base href="http://intranet.jmvasesores.com/">
<meta name="description" content="overview &amp; stats" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- basic styles -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/css/font-awesome.min.css" />
<!--[if IE 7]>
				  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
				<![endif]-->
<!-- page specific plugin styles -->
<link rel="stylesheet" href="assets/css/jquery-ui-1.10.3.full.min.css" />
<link rel="stylesheet" href="assets/css/datepicker.css" />
<link rel="stylesheet" href="assets/css/daterangepicker.css" />
<link rel="stylesheet" href="assets/css/ui.jqgrid.css" />
<!-- fonts -->
<link rel="stylesheet" href="assets/css/ace-fonts.css" />
<link rel="stylesheet" href="assets/css/chosen.css" />
<!-- ace styles -->
<link rel="stylesheet" href="assets/css/ace.min.css" />
<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="assets/css/animate.css" />
<!--[if lte IE 8]>
				  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
				<![endif]-->
<script src="assets/js/ace-extra.min.js"></script>
<style>
html {
	overflow: -moz-scrollbars-vertical;
	overflow: scroll;
}
</style>
<!-- Ajax -->
<script type="text/javascript" src="jScripts/ajax_core.js"></script>
<script type="text/javascript" src="jScripts/ajform.js"></script>

<!--Fin Ajax-->
</head>
<body>
<!-- basic scripts --> 
<!--[if !IE]> -->
						<script type="text/javascript">
							window.jQuery || document.write("<script src=\'assets/js/jquery-2.0.3.min.js\'>"+"<"+"/script>");
						</script>
						<!-- <![endif]--> 
<!--[if IE]>
						<script type="text/javascript">
						 window.jQuery || document.write("<script src=\'assets/js/jquery-1.10.2.min.js\'>"+"<"+"/script>");
						</script>
						<![endif]--> 
<script type="text/javascript">
							if("ontouchend" in document) document.write("<script src=\'assets/js/jquery.mobile.custom.min.js\'>"+"<"+"/script>");
						</script> 
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/typeahead-bs2.min.js"></script> 

<!-- page specific plugin scripts --> 
<script src="assets/js/jquery-ui-1.10.3.full.min.js"></script> 
<script src="assets/js/jquery.ui.touch-punch.min.js"></script> 
<script src="assets/js/jquery.dataTables.min.js"></script> 
<script src="assets/js/jquery.dataTables.bootstrap.js"></script> 
<script src="assets/js/fuelux/fuelux.wizard.min.js"></script> 
<script src="assets/js/additional-methods.min.js"></script> 
<script src="assets/js/bootbox.min.js"></script> 
<script src="assets/js/jquery.maskedinput.min.js"></script> 
<script src="assets/js/select2.min.js"></script> 
<script src="assets/js/date-time/bootstrap-datepicker.min.js"></script> 
<script src="assets/js/chosen.jquery.min.js"></script> 
<script src="assets/js/jquery.validate.min.js"></script> 
<script src="assets/js/date-time/moment.min.js"></script> 
<script src="assets/js/date-time/daterangepicker.min.js"></script> 

<!-- ace scripts --> 

<script src="assets/js/ace-elements.min.js"></script> 
<script src="assets/js/ace.min.js"></script>
<div class="navbar navbar-default" id="navbar">
  <script type="text/javascript">
						try{ace.settings.check("navbar" , "fixed")}catch(e){}
					</script> 
  <!-- /.container -->
</div>
<div class="main-container" id="main-container">
  <script type="text/javascript">
						try{ace.settings.check("main-container" , "fixed")}catch(e){}
					</script>
  <div class="main-container-inner">
    <a class="menu-toggler" id="menu-toggler" href="#"> <span class="menu-text"></span> </a>
    <div class="page-content">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          
          <div class="error-container">
            <div class="well">
              <h1 class="grey lighter smaller"> <span class="blue bigger-125"> <i class="icon-sitemap"></i> 404 </span> Pagina No Encontrada </h1>
              <hr>
              <h3 class="lighter smaller">¡Buscamos por todo el sitio pero no la encontramos!</h3>
              <div>
                <div class="space">
                </div>
                <h4 class="smaller">Intente lo siguiente:</h4>
                <ul class="list-unstyled spaced inline bigger-110 margin-15">
                  <li> <i class="icon-hand-right blue"></i> Revise la direccion. </li>
                  <li> <i class="icon-hand-right blue"></i> Regrese a la pagina anterior. </li>
                  <li> <i class="icon-hand-right blue"></i> Avisele al programador. </li>
                </ul>
              </div>
              <hr>
              <div class="space">
              </div>
              <div class="center">
                <a href="javascript:history.back()" class="btn btn-grey"> <i class="icon-arrow-left"></i> Regresar </a>
              </div>
            </div>
          </div>
          <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
      </div>
    </div>
  </div>
  <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse"> <i class="icon-double-angle-up icon-only bigger-110"></i> </a>
</div>
</body>
</html>		
		';
		exit;
	}

    function display($tipo, $informacion) {
        switch ($tipo) {
        
            case E_VALIDACION_INCORRECTO:
                $mensaje = "La información del campo \"$informacion\" es incorrecta.";
                break; 

            case E_VALIDACION_OBLIGATORIO:
                $mensaje = "El campo \"$informacion\" es obligatorio.";
                break;

        }

        header("HTTP/1.1 500 Internal Server Error");
        if ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
            echo json_encode(array("error" => $mensaje, "contenido" => array()));
        }
        else {
            $smarty = new Smarty();
            $smarty->compile_dir  = "templates_c";
            $smarty->template_dir = "templates";
            $smarty->assign("mensaje", $mensaje);
            $smarty->display("layouts/error.tpl");
        }

        exit();
    }
}
?>