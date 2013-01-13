<?php
require("../modelo/clsTabla.php");
$idvista = $_GET["idvista"];
//echo $idvista;
try{
$objMantenimiento = new clsTabla($idvista);
}catch(PDOException $e) {
    echo '<script>alert("Error :\n'.$e->getMessage().'");history.go(-1);</script>';
	exit();
}
?>
<div class="title">Tabla</div>
<form name="frmTabla" id="frmTabla">
<input type="hidden" id="accion" name="accion" value="NUEVO">
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
Tipo Tabla
<select id="cbotipotabla" name="cbotipotabla">
<option value="B">Base de datos</option>
<option value="V">Virtual</option>
</select>
</li>
<li>
<button type="button" id="btn_aceptarTabla">Grabar</button>
<button type="button" id="btn_cancelarTabla">Cancelar</button>
</li>
</ul>
</form>
<script>
$('#btn_aceptarTabla').click(function(evt) {
	$.ajax({
		cache: false,
		async: false,
		url: "controlador/contTabla.php",
		type: "POST",
		data: $("#frmTabla").serialize(),
		beforeSend: function(){
			$('#btn_aceptarTabla').attr('disabled', true);
		},
		success: function(data) {
			$('#content').html(data);
			$( "button" ).button();
			alert('Load was performed.');
		},
		error: function(msg){
			$('#btn_aceptarTabla').attr('disabled', false);
		}
	});		
	evt.preventDefault();
});
$('#btn_cancelarTabla').click(function(evt) {
	console.log(getFormData('frmTabla'));
});
</script>