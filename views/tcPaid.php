<?php
/**

* 
* Plantilla que se muestra si el usuario elige tarjeta y ademas su pago se vio reflejado automaticamente
**/
extract($_SESSION["variables"]); 
?>
<div class="col-md-8 col-md-offset-2">

	<div class="col-md-12">
		<span class="col-md-6">ID verificacion: </span><span class="col-md-6"><?= $id;?></span>
	</div>
	<div class="col-md-12">
		<span class="col-md-6">Referencia: </span><span class="col-md-6"><?= $reference;?></span>
	</div>
	<div class="col-md-12">
		<span class="col-md-6">Numero: </span><span class="col-md-6"><?= $numero;?></span>
	</div>
	<div class="col-md-12">
		<span><?= $error_code;?></span>
	</div>
	<div class="col-md-12">
		<h6>Estos codigos no tienen otro fin que ser un comprobante</h6>
	</div>
	
</div>