<?php
require("../modelo/clsCampo.php");
$idvista = $_GET["idvista"];
$idtabla = $_GET["idtabla"];
//echo $idvista;
try{
$objMantenimiento = new clsCampo($idvista);
}catch(PDOException $e) {
    echo '<script>alert("Error :\n'.$e->getMessage().'");history.go(-1);</script>';
	exit();
}
?>
<div class="title">Campo</div>
<form name="frmCampo" id="frmCampo">
<input type="hidden" id="accion" name="accion" value="NUEVO">
<input type="hidden" id="idtabla" name="idtabla" value="1">
<ul>
<li>
Nombre
<input type="text" id="txtnombre" name="txtnombre">
</li>
<li>
Descripción
<input type="text" id="txtdescripcion" name="txtdescripcion">
</li>
<li>
Ayuda
<input type="text" id="txtayuda" name="txtayuda">
</li>
<li>
class CSS
<input type="text" id="txtcss" name="txtcss">
</li>
<li>
Longitud
<input type="text" id="txtlongitud" name="txtlongitud">
</li>
<li>
Tipo Control
<select id="cbotipocontrol" name="cbotipocontrol">
<option value="1">Caja de Texto</option>
</select>
</li>
<li>
<button type="button" id="btn_aceptarCampo">Grabar</button>
<button type="button" id="btn_cancelarCampo">Cancelar</button>
</li>
</ul>
</form>
<script>
$('#btn_aceptarCampo').click(function(evt) {
	$.ajax({
		cache: false,
		async: false,
		url: "controlador/contCampo.php",
		type: "POST",
		data: $("#frmCampo").serialize(),
		beforeSend: function(){
			$('#btn_aceptarCampo').attr('disabled', true);
		},
		success: function(data) {
			$('#content').html(data);
			$( "button" ).button();
			alert('Load was performed.');
		},
		error: function(msg){
			$('#btn_aceptarCampo').attr('disabled', false);
		}
	});		
	evt.preventDefault();
});
$('#btn_cancelarCampo').click(function(evt) {
	console.log(getFormData('frmCampo'));
});
</script>