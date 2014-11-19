<?php
/**
* 
* Archivo encargado de manejar las plantillas de pago es decir lo que se muestra una vez finalizado el registro
* 
**/
if($_SESSION){
require_once("views/control.php");
extract($_SESSION["variables"]);


?>
<div class=" col-xs-12 col-lg-12 col-md-12" style="padding-top:50px; padding-bottom:50px; font-size:14px; background:#fff !important;">

<?php
$mensaje;
switch ($tipoPago) {
	case 'oxxo':
		echo getViewOxxo();
		break;
	case 'banorte':
		echo getViewbanorte();
		break;

	case 'tc':
		if($status == "paid"){
			echo getViewTCpaid();
		}else{
			echo getViewTC();
		}
		break;

	default:
		echo getViewDefault();
		break;
}

?>

</div>

<?php

}else{
?>
<script>
   setTimeout("location.href = 'index.php';",2000);
</script>

<?php
}
?>