<?php
/*dev mode*/
//ini_set('display_errors', '1');
/*dev mode*/
/**

* 
* Archivo encargado de instanciar y procesar pagos a la plataforma conekta
*
*
**/
require_once("conekta/lib/Conekta.php");
require_once("Core.php");
require_once("conexion.php");

Conekta::setApiKey(publicKey);//public key

$tipoPago = $_POST['tipoPago'];

if(!empty($tipoPago)){

$data  =  new Core(tblUsers,tblConekta,tblNumeros);

  /*variables generales*/
                $nombre = $_POST['nombre'];
                $correo = $_POST['correo'];
                $reference = str_replace(" ","-",$nombre)."-".date("Y-m-d-G:i:s");
                $description = "conekta";
                $pago = $_POST['pago']."00";

                $data->setCorreo($correo);
  /**
Pago con tarjeta
  **/
            if($tipoPago == 'tc'){

                $csv     = $_POST['cvc'];
                $mes     = $_POST['mes'];
                $anio    = $_POST['anio'];
                $tokenTC = $_POST['conektaTokenId'];
                $nombre  = $_POST['nombre-tc'];
                $data   ->setTipoPago("tc");
                $data   ->setOrigen("tc");
                try{
                 
                   /*conectar a "conekta"*/
                      $charge  =  Conekta_Charge::create(array(
                        "amount" => $pago,
                        "reference_id" =>$reference,
                        "currency" => "MXN",
                        "description" => $description,
                        "card" =>$tokenTC,
                        "details" =>array(
                                'name'  =>$nombre,
                                'email' =>$correo
                                )
                          ));
                         /*conectar a "conekta" end*/

                        /*variables de respuesta */
                        $id = $charge->id;
                        $status = $charge->status;
                        $reference = $charge->reference_id;
                        $error_code = $charge->failure_message;

                        $data->setIdTransaccion($id);
                        /*variables de respuesta end*/
                        
                        $value = $data->actualizar(compact("id","correo"));

                          if ($value){

                            $data->enviarMailInscripcion(compact("nombre","correo"));
                                 /*resultados*/
                              if($status == "paid"){
                                $numero = $data->getNumero($correo);
                                $data->enviarMailPagado($nombre,$correo,$numero);
                                $_SESSION['variables'] = compact("id","nombre","correo","status","reference","error_code",'tipoPago','numero');
                                  ?>
                                   <script>
                                      setTimeout("location.href  =  'success.php';",2000);
                                    </script>
                                 <?php
                              /*Resultados end*/
                             }else{
                                 $_SESSION['variables'] = compact("nombre","correo","status","reference","error_code",'tipoPago');
                                  ?>
                                   <script>
                                      setTimeout("location.href  =  'success.php';",2000);
                                    </script>
                                 <?php
                             }

                         }else{
                          echo "<h1>Ha ocurrido un error</h1>";
                          ?>
                              <script>
                                  setTimeout("location.href  =  'inscripcion.php';",2000);
                              </script>
                            <?php
                         }

                }catch (Conekta_Error $e){
                  echo $e->getMessage(); //Un error ocurrió al procesar el pago
                }

            }//end  if($tipoPago =  = 'tc')


/**
Pago OXXO
**/
            elseif($tipoPago == 'oxxo'){

              $data->setTipoPago("efectivo");
              $data->setOrigen("oxxo");
            /* en el momento en que se realice un pago*/
                  try{
                   
                    /*conectar a "conekta"*/
                          $charge  =  Conekta_Charge::create(array(
                            "amount" => $pago,
                            "reference_id" =>$reference,
                            "currency" => "MXN",
                            "description" => $description,
                            "cash" => array(
                              "type" =>"oxxo",
                              "expires_at" =>"2015-03-04"
                            ),
                            "details" =>array(
                              'name'  =>$nombre,
                              'email' =>$correo
                              )
                            ));
                           /*conectar a "conekta" end*/

                          /*variables de respuesta */
                          $id = $charge->id;
                          $status = $charge->status;
                          $reference = $charge->reference_id;
                          $error_code = $charge->failure_message;
                          $barcode = $charge->payment_method->barcode;
                          $barcode_url = $charge->payment_method->barcode_url;
                          /*variables de respuesta end*/
                          $data->setIdTransaccion($id);
                          $data->setBarCode($barcode);

                            $value = $data->actualizar(compact("id","correo","barcode"));

                          if ($value){
                             $data->enviarMailInscripcion(compact("nombre","correo"));
                          /*resultados*/
                            $_SESSION['variables'] = compact("id","nombre","correo","status","reference","error_code",'tipoPago',"barcode_url","barcode");
                              ?>
                                <script>
                                  setTimeout("location.href  =  'success.php';",2000);
                                </script>
                              <?php
                           /*Resultados end*/

                         }else{
                          echo "<h1>Ha ocurrido un error</h1>";
                          ?>
                              <script>
                                  setTimeout("location.href  =  'inscripcion.php';",2000);
                              </script>
                            <?php
                         }
                        

                   }catch (Conekta_Error $e){
                    echo $e->getMessage(); //Un error ocurrió al procesar el pago
                    }
            }//end elseif($tipoPago =  = 'oxxo')

/**
Pago Banorte
  **/
            elseif($tipoPago == 'banorte'){
                 $data->setTipoPago("efectivo");
                 $data->setOrigen("banorte");
                  try{
                  
                    /*conectar a "conekta"*/
                          $charge  =  Conekta_Charge::create(array(
                            "amount" => $pago,
                            "reference_id" =>$reference,
                            "currency" => "MXN",
                            "description" => $description,
                            "bank" => array(
                              "type" =>"banorte"
                            ),
                            "details" =>array(
                              'name'  =>$nombre,
                              'email' =>$correo
                              )
                            ));
                           /*conectar a "conekta" end*/
                          /*variables de respuesta */
                          $id = $charge->id;
                          $status = $charge->status;
                          $reference = $charge->payment_method->reference;
                          $serviceNumbre = $charge->payment_method->service_number;
                          $error_code = $charge->failure_message;
                         
                          $data->setIdTransaccion($id);
                         

                          $value = $data->actualizar(compact("id","correo"));

                          if ($value){
                             $data->enviarMailInscripcion(compact("nombre","correo"));
                          /*resultados*/
                            $_SESSION['variables'] = compact("id","nombre","serviceNumber","correo","status","reference","error_code","tipoPago");
                              ?>
                                <script>
                                  setTimeout("location.href  =  'success.php';",2000);
                                </script>
                              <?php
                           /*Resultados end*/

                         }else{
                          echo "<h1>Ha ocurrido un error</h1>";
                          ?>
                              <script>
                                  setTimeout("location.href  =  'inscripcion.php';",2000);
                              </script>
                            <?php
                         }

                  }catch (Conekta_Error $e){
                    echo $e->getMessage(); //Un error ocurrió al procesar el pago
                  }

            }//end elseif($tipoPago =  = 'banorte')

}//end if(!empty($tipoPago))


