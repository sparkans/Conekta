<?php
/**

* 
* Plantilla que se muestra si el usuario elige oxxo
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
		<span class="col-md-6">Codigo de barras: </span><span class="col-md-6"><img src="<?= $barcode_url;?>"></span>
		<span class="col-md-6"></span><span class="col-md-6"><?= $barcode;?></span>
	</div>

	<div class="col-md-12">
		<span><?= $error_code;?></span>
	</div>

</div>