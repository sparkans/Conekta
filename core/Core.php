<?php
/**

* 
* Clase principal de todo el sistema 
*
*
*
**/
class Core{

/**** variables ****/
private $totalPago;
private $usuarios;
private $conekta;
private $numeros;
private $origen;
private $efectivo = 0;
private $tarjeta  = 0; 
private $hoy;
/**** variables de usuario ****/
private $nombre;
private $aPaterno;
private $aMaterno;
private $correo; 
private $genero; 
private $fechaNac;
private $pais;
private $idTransaccion;
private $barCode = 'null';


/*** constructor ***/
public function __construct($usuarios = " ",$conekta = " ",$numeros="tbl_numeros"){
	$this->usuarios = $usuarios;
	$this->conekta  = $conekta;
	$this->numeros  = $numeros;
}
/**** sets *****/
public function setBarCode($value){
		$this->barCode = $value;	
}
public function setIdTransaccion($value){
		$this->idTransaccion = $value;	
}

public function setNombre($value){
		$this->nombre = $value;	
}

public function setApaterno($value){
		$this->aPaterno = $value;
}

public function setAmaterno($value){
		$this->aMaterno = $value;
}

public function setCorreo($value){
		$this->correo = $value;
}

public function setGenero($value){
		$this->genero = $value;
}

public function setFechaNac($value){
		$this->fechaNac = $value;
}

public function setPais($value){
		$this->pais = $value;
} 


public function setOrigen($origen){
	$this->origen = $origen;
	
}

public function setTipoPago($value){
	if($value == "tc"){
		$this->efectivo = 0;
		$this->tarjeta  = 1;
	}elseif($value == "efectivo"){
		$this->efectivo = 1;
		$this->tarjeta  = 0;
	}
}

public function setTotalPago($pago){

	if(is_numeric($pago)){
		$this->totalPago = $pago;
	}else{
		$this->totalPago = 0;
	}
}

/**** end sets ***/
public function getTotalPago(){
	return $this->totalPago;
}


/***** llenar select con fechas *******/
/****año *****/
public function getOptionYear(){
	for($i=2005; $i>=1930; $i--){
          if ($i == 2005)
            echo '<option value="'.$i.'">'.$i.'</option>';
          else
           echo '<option value="'.$i.'">'.$i.'</option>';
       }
}
/**** mes *****/
public function getOptionMonth(){
	for ($i=1; $i<=12; $i++) {
           if ($i == date('m'))
             echo '<option value="'.$i.'">'.$i.'</option>';
           else
            echo '<option value="'.$i.'">'.$i.'</option>';
        	}
}
/**** dia *****/
public function getOptionDay(){
	for ($i=1; $i<=31; $i++) {
               if ($i == date('j')){
                echo '<option value="'.$i.'">'.$i.'</option>';
              } else{
               echo '<option value="'.$i.'">'.$i.'</option>';
             }
           }
}

/*****insertar en una tabla ****/
public function insertarUsuario(){//"nombre","aMaterno","aPaterno","correo","sex","fechaNac","pais"
$hoy   = date('d-m-Y G:i:s');
				$sql = "INSERT INTO $this->usuarios(nombre,apellido_paterno,apellido_materno,correo, genero, fecha_nac, pais,fecha_registro) VALUES 
													('$this->nombre','$this->aPaterno','$this->aMaterno','$this->correo','$this->genero','$this->fechaNac','$this->pais','$hoy')";
					
					$resultado = $GLOBALS['conexion']->query($sql);
						if($resultado){
							return 1;
						}else{
							return 0;
						}

}

public function insertarConekta(){//"nombre","aMaterno","aPaterno","correo","sex","fechaNac","pais"
$hoy   = date('d-m-Y G:i:s');
				$sql = "INSERT INTO $this->conekta(correo,status,cantidad_pago,fecha_operacion,pais) VALUES 
															('$this->correo',0,$this->totalPago ,'$hoy','$this->pais')";

							$resultado = $GLOBALS['conexion']->query($sql);
								if($resultado){
									return 1;
								}else{
									return 0;
								}
}



public function actualizar(){//"nombre","aMaterno","aPaterno","correo","sex","fechaNac","pais"


						$sql = "UPDATE $this->usuarios SET tarjeta=$this->tarjeta, efectivo=$this->efectivo WHERE correo LIKE '$this->correo'";
						$resultado = $GLOBALS['conexion']->query($sql);

						if($resultado){
						 	$sql = "UPDATE $this->conekta SET id_transaccion='$this->idTransaccion',codigo_barras='$this->barCode',origen='$this->origen' WHERE correo LIKE '$this->correo'";
							$resultado = $GLOBALS['conexion']->query($sql);
										if($resultado){
											return 1;
										}else{
											return 0;
										}
							}else{
								return 0;
							}
		

}

/**

* asignar un numero o estatus
* en el caso de que requieras enviar un tipo de comprobante de pago una vez finalizado este
* o solo para diferenciarlos
**/

public function getNumero($correo){
	$usuarios = $this->usuarios;
 	$numeros  = $this->numeros;
 	$conekta  = $this->conekta;

	$sql    = "SELECT * FROM $usuarios WHERE correo LIKE '$correo' AND activo=0";//verifico que exista no este activo
	
	$result = $GLOBALS["conexion"]->query($sql);
	
	if ($result->num_rows==1) {

		
			$sql = "SELECT numero FROM $numeros WHERE disponible=0 ORDER BY id LIMIT 1";

			$resultado = $GLOBALS["conexion"]->query($sql);

			if($resultado->num_rows == 1){

				$folio  = $resultado->fetch_assoc();
				$numero = $folio['numero'];

				$sql = "UPDATE $usuarios SET activo=1, numero='$numero' WHERE correo LIKE ?";//cambio su estatus
				$result = $GLOBALS["conexion"]->prepare($sql);
				$result->bind_param("s", $correo);
    			$result->execute();

				if($result->affected_rows == 1){

					  	$result->close();

						$sql = "UPDATE $conekta SET status=1 WHERE correo LIKE ?";//cambio su estatus
						$result = $GLOBALS["conexion"]->prepare($sql);
						$result->bind_param("s", $correo);
    					$result->execute();	

							if($result->affected_rows == 1){

								$result->close();
								$sql = "UPDATE $numeros SET disponible=1 WHERE numero LIKE ? ";//cambio su estatus
								$result = $GLOBALS["conexion"]->prepare($sql);

								$result->bind_param("s", $numero);
		    					$result->execute();	
								if($result->affected_rows == 1){
									$result->close();
									return $numero;
								}else{//if($result->affected_rows == 1){
									return 0;
								}

						}else{//if($result->affected_rows == 1){ conekta
							return 0;
						}

					}else{//if($result->affected_rows == 1){ usuarios
							return 0;
						}


			}else{//if($resultado){
				return 0;
			}
			
	}else{ //if ($result->num_rows == 1)
		return 0;
	}
}
/**

* primer mail; el de inscripcion 
* este mail se envia una vez que el cliente genero el recibo de pago
**/
public function enviarMailInscripcion($variables){
	$hoy = date("d-m-Y G:i:s");
	extract($variables);
	

				$headers  ='MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: vendetta <contacto@vendetta.com>' . "\r\n";  /*Nombre Sitio <info@url del sitio>*/
				$asunto   ="Inscripcion";
			
				
					$escribirMensaje = '
				   
					<table width="600" border="0" align="center" cellpadding="1" cellspacing="0">
  <tbody><tr>
    <td bgcolor="#666666"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
      <tbody><tr>
        <td><img src="tuimagenheader" width="600" height="124"></td>
      </tr>
      <tr>
        <td align="center" bgcolor="#FFFFFF"><p>&nbsp;</p>
          <p>Mexico DF, a: <span class="titulo-ngo-01"><strong>'.$hoy.'</strong></span><br>
            Estimado: <span class="titulo-ngo-01"><strong>'.$nombre.'</strong></span></p>
          <p> Te has inscrito </p>
           <p>los datos con los que te registraste son:</p>
      </tr>

      <tr align="center">
        <td bgcolor="#FFFFFF">nombre: <strong>'.$nombre.'</strong></td>
      </tr>

      <tr align="center">
        <td bgcolor="#FFFFFF">Mail: <strong>'.$correo.'</strong></td>
      </tr>

      
      <tr align="center">
        <td bgcolor="#FFFFFF"><p>Ingresa aquí para cosultar tu numero una vez que hayas pagado: <strong><a href="turuta/consulta.php">consulta</a></strong></p></td>
        
      </tr>
      
      <br/>
      <tr>
        <td bgcolor="#99CA3C"><table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
          <tbody><tr>
            <td height="70" align="center"><strong>Copyright 2014</strong><br>
              Todos los derechos reservados. Este material no puede ser copiado o<br>
              reproducido sin autorización escrita.<br>
              <a href="http://www.tudominio.com" target="_blank">www.tudominio.com</a></td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
</tbody></table>
				
								
					';		

					
					return mail($correo, $asunto, utf8_decode($escribirMensaje), $headers);
}

/**

* mail que se envia una vez pagado el recibo de pago o si uso tarjeta se hace automaticamente
* solo aplica a pagos finalizados
**/

public function enviarMailPagado($nombre,$correo,$numero){
 
$hoy = date("d-m-Y");
        $headers ='MIME-Version: 1.0' . "\r\n";
        $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers.= 'From: Vendetta <contacto@vendetta.com>' . "\r\n";  /*Nombre Sitio <info@url del sitio>*/
        $asunto="Felicidades!!";
      
        
          $escribirMensaje = '
           
          <table width="600" border="0" align="center" cellpadding="1" cellspacing="0">
  <tbody><tr>
    <td bgcolor="#666666"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
      <tbody><tr>
        <td><img src="tuimagenheader" width="600" height="124"></td>
      </tr>
      <tr>
        <td align="center" bgcolor="#FFFFFF"><p>&nbsp;</p>
          <p>Mexico DF, a: <span class="titulo-ngo-01"><strong>'.$hoy.'</strong></span><br>
            Estimado: <span class="titulo-ngo-01"><strong>'.$nombre.'</strong></span><br>
            Tu numero es: <span class="titulo-ngo-01"><strong>'.$numero.'</strong></span>
            </p>
          <p> Tu registro ha sido completado.</p>
          <h4>GRACIAS!!</h4>
      </tr>

        <td bgcolor="#99CA3C"><table width="580" border="0" align="center" cellpadding="0" cellspacing="0">
          <tbody><tr>
            <td height="70" align="center"><strong>Copyright 2014</strong><br>
              Todos los derechos reservados. Este material no puede ser copiado o<br>
              reproducido sin autorización escrita.<br>
              <a href="http://www.tudominio.com" target="_blank">www.tudominio.com</a></td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table></td>
  </tr>
</tbody></table>
        
                
          ';    

          
          return mail($correo, $asunto, utf8_decode($escribirMensaje), $headers);
}

}
?>