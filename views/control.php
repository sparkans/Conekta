<?php
/**

* 
* Manejador de plantillas aqui se crea la funcion para cada plantilla se guarda en una variable para despues
* Mostrarla una vez que sea llamada la vista
**/
function getViewOxxo(){
	ob_start();
	require_once("oxxo.php");
	$data = ob_get_clean();
	return $data;
}

function getViewbanorte(){
	ob_start();
	require_once("banorte.php");
	$data = ob_get_clean();
	return $data;
}

function getViewTC(){
	ob_start();
	require_once("tc.php");
	$data = ob_get_clean();
	return $data;
}

function getViewTCpaid(){
	ob_start();
	require_once("tcPaid.php");
	$data = ob_get_clean();
	return $data;
}

function getViewDefault(){
	ob_start();
	require_once("default.php");
	$data = ob_get_clean();
	return $data;
}
			
?>


















