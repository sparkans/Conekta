<html>
<style>
body{
  font-family:Helvetica;
}
</style>

<?php
/**

* 
* Archivo encargado de insertar en base de datos a los nuevos usuarios
*
*
**/
if($_POST){

session_start();

ini_set("display_errors",1);
header("Content-type: text/html; charset = utf8");

require_once("conexion.php");
require_once("Core.php");

$data  =  new Core(tblUsers,tblConekta);

/***variables post***/
$nombre   = $_POST['nombre'];
$aMaterno = $_POST['aMaterno'];	
$aPaterno = $_POST['aPaterno'];	
$correo   = $_POST['email'];	
$sex      = $_POST['sex'];	
$fechaNac = $_POST['dia']."-".$_POST['mes']."-".$_POST['anio'];	
$pais     = $_POST['pais'];
/***variables generales**/
/***sets***/

$data->setNombre($nombre);
$data->setAmaterno($aMaterno);
$data->setApaterno($aPaterno);
$data->setCorreo($correo);
$data->setGenero($sex);
$data->setFechaNac($fechaNac);
$data->setPais($pais);

$pago      = $data->setTotalPago(precio);
$totalPago = $data->getTotalPago();	



		
		$resultado  = $data->insertarUsuario();
		$resultado2 = $data->insertarConekta();

				if($resultado && $resultado2){
					$send = compact("nombre","aPaterno","aMaterno","correo","totalPago");
					$_SESSION['variables'] = $send;

					?>
						<script>
							setTimeout("location.href  =  'pago.php';",2000);
						</script>
					<?php

				}else{

					?>
						<script>
							//setTimeout("location.href  =  'registro.php';",2000);
						</script>
					<?php

				}


	


?>
		<div align = "center"><img src = "images/loading.gif" width = "150" height = "150">
		<BR> Espere un momento por favor.  </div>
		<script>
			//setTimeout("location.href  =  'pago.php';",2000);
		</script>
		</html>
<?php
}else{
	?>
		<h2>Ha ocurrido un error.</h2>
		<script>
			setTimeout("location.href  =  'registro.php';",2000);
		</script>

	<?php
}
?>