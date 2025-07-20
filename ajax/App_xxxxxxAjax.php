<?php

$peticionAjax=true;
require_once "../core/configGeneral.php";
session_start(['name'=>SESION]);
if ($_SESSION['sesionactiva'] == true){
	if(isset($_POST['dni-reg'])){
		require_once "../controllers/App_xxxxxxxController.php";
		$xxxxxxxController = new xxxxxxxController();
		
	}else{
		echo '<script>window.location.href="'.SERVERURL.'home/" </script>';
	}
}else{
	session_start(['name'=>SESION]);
	session_destroy();
	echo '<script>window.location.href="'.SERVERURL.'" </script>';
}