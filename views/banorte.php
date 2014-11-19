<?php
/**

* 
* Plantilla que se muestra si el usuario elige banorte
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
		<span class="col-md-6">Numero de servicio: </span><span class="col-md-6"><?= $serviceNumber;?></span>
	</div>
	<div class="col-md-12">
		<span><?= $error_code;?></span>
	</div>
	
</div>