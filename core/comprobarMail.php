
<?php
/**

* 
* Este archivo se encarga de comprobar si un mail ya existe de ser asi se oculta el formulario de registro 
* evitando duplicados en la base de datos
*
**/
session_start();
ini_set("display_errors",0);

header('Content-Type: application/json');
require_once("conexion.php");

 $tablaUsuarios = tblUsers;
 //obtener post
 $mail = $_POST['email'];
 
 
	$sql = "SELECT * FROM $tablaUsuarios WHERE correo='$mail' and (tarjeta=1 or efectivo=1)";
	//	echo $sql;
	//debe estar el proceso terminado de tarjeta o oxxo para bloquearlo si no quiere decir que no ha concluido su pago
	

	$consulta = $conexion->query($sql);
	
	if($consulta->num_rows>0){
		$arr = array("msg"=>1); 
		echo json_encode($arr);
		die();
		}
	else
	{
		$arr = array("msg"=>2);
		echo json_encode($arr);
		die();
		}

echo mysql_error();


?>


