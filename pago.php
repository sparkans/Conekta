<?php include('header.php');
include('core/conexion.php');
/**

*
* Archivo en cargado del envio de datos a Conekta aqui puedes agregar metodos de pago
* Todos los campos son requeridos
* Sino estas seguro de como funciona la plataforma conekta no toques este archivo
**/

if($_SESSION){
extract($_SESSION['variables']);
?>

<!-- Inicia Registro -->
<div class="col-md-offset-2 col-md-6" id="pago" >

  <div class="form-group">
    <select name="elegir-pago" id="elegir-pago" class="col-md-12 form-control">
      <option value="-" selected>Elige una opcion</option>
      <option value="tc">Tarjeta de credito</option>
      <option value="efectivo">Efectivo</option>
    </select>
  </div>
<!-- tarjeta de credito-->
        
        <form action="pagar.php" method="POST" id="card-form" >
          <span class="card-errors"></span>

          <div class="form-row form-group">
            <label >
              <div class="col-md-6"><span >Nombre del tarjetahabiente</span></div>
              <div class="col-md-6"><input class="form-control" type="text"  name="nombre-tc" data-conekta="card[name]" placeholder="Nombre"/></div>
            </label>
          </div>

          <div class="form-row">
            <label>
              <div class="col-md-6"><span>Número de tarjeta de crédito</span></div>
              <div class="col-md-6"><input class="form-control" type="text" data-conekta="card[number]"/></div>
            </label>
          </div>

          <div class="form-row">
            <label>
              <div class="col-md-6"><span>CVC</span></div>
              <div class="col-md-6"><input class="form-control" type="text"  name="cvc" data-conekta="card[cvc]"/></div>
            </label>
          </div>

          <div class="form-row">
            <label>
              <div class="col-md-6"><span>Fecha de expiración (MM/AAAA)</span></div>
              <div class="col-md-3"><input class="form-control" type="text" name="mes" data-conekta="card[exp_month]"/></div>
              <div class="col-md-3"><input class="form-control" type="text"  name="anio" data-conekta="card[exp_year]"/></div>
            </label>
          </div>

          <button type="submit" class="btn btn-default">Enviar</button>
              <input type="hidden" name="tipoPago" value="tc">
              <input type="hidden" name="nombre" value="<?= $nombre." ".$aPaterno." ".$aMaterno; ?>">
              <input type="hidden" name="correo" value="<?= $correo; ?>">
              <input type="hidden" name="pago" value="<?= $totalPago; ?>">
        </form>
<!-- termina el form tarjeta e inicia el de pago en efectivo-->
          <form action="pagar.php" method="POST" id="cash-form">

              <select name="tipoPago" class="form-control">
                <option value="oxxo">OXXO</option>
                <option value="banorte">Banorte</option>
                  <input type="hidden" name="nombre" value="<?= $nombre." ".$aPaterno." ".$aMaterno; ?>">
                  <input type="hidden" name="correo" value="<?= $correo; ?>">
                  <input type="hidden" name="pago" value="<?= $totalPago; ?>">
              </select>

          <button type="submit" class="btn btn-default">Enviar</button>
          </form>




</div> <!-- Termina Registro -->


<?php 
}else{
  ?>
<script>
  setTimeout("location.href = 'registro.php';",2000);
</script>
  <?php
}
include('footer.php');?>
