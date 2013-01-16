<?php
require("../modelo/clsGeneral.php");
$idvista = $_GET["idview"];
//echo $idvista;
try{
$objGeneral = new clsGeneral($idvista);
}catch(PDOException $e) {
    echo '<script>alert("Error :\n'.$e->getMessage().'");history.go(-1);</script>';
	exit();
}
//OBTENGO DATOS DE LA VISTA
$rstVista=$objGeneral->getVista($idvista);
$datavista=$rstVista->fetchObject();

//RECIBIMOS DATOS ENVIADOS COMO PARAMETROS
if(isset($_GET['idtabla_redirect']) and isset($_GET['idoperacion_redirect'])){
	$rstOperaciones = $objGeneral->getOperaciones(0,0,$_GET['idtabla_redirect'],$_GET['idoperacion_redirect'],4);
	if(is_string($rstOperaciones)){
		echo "Error al obtener Script JS Operaciones: ".$rstOperaciones."";
		exit();
	}
	$dataOperacion=$rstOperaciones->fetchObject();
	$argumentos=explode(',',$dataOperacion->argumentos);
	$argGet="";
	foreach($argumentos as $indice => $value){
		$argGet.="&".$value."=".$_GET[$value];
	}//echo $argGet;
}
?>
<div class="title"><?php echo $datavista->titulo;?></div>
<div id="operaciones">
<?php 
//RENDERIZAMOS OPERACIONES
$rstOperaciones = $objGeneral->getOperaciones($idvista,3);
if(is_string($rstOperaciones)){
	echo "Error al obtener Script JS Operaciones: ".$rstOperaciones."";
	exit();
}
$datoOperaciones = $rstOperaciones->fetchAll();
reset($datoOperaciones);
$nro_ope=0;
foreach($datoOperaciones as $operacion){
	$argumentos=explode(',',$operacion["argumentos"]);
	$arg="";
	foreach($argumentos as $indice => $value){
		if($value!='') $arg.="'".$dato[$value]."',";
	}
	$arg=substr($arg,0,-1);
	$renderOperaciones.='<button onClick="javascript: '.$operacion["nombre"].'_'.$idvista.'('.$operacion["idvista_atributo"].($argGet!=''?',\''.$argGet.'\'':'').($arg!=''?','.$arg:'').');">'.$operacion["etiqueta"].'</button>';
}
echo $renderOperaciones;
?>
<!--<button onClick="javascript: setRun(3,'vistaForm','<?php echo $argGet;?>','frm_mant');">Nuevo</button>-->
</div>
<div id="frm_mant"></div>
<div id="busqueda">
<form id="frmBuscar">
<?php 
$filtro=$objGeneral->getFiltros($idvista);
if(isset($filtro)){
	foreach($filtro as $indice => $value){
		$renderCampos.=$value['etiqueta'].': <input type="text" id="'.$value['nombre'].'" name="'.$value['nombre'].'" value="'.$_GET[$value['defecto']].'">';	
	}
	echo $renderCampos;
?>
<button id="btn_buscar">Buscar</button>
<?php 
}
?>
<input name="nro_hoja" type="hidden" id="nro_hoja" value="<?php echo $nro_hoja;?>">
<input name="by" type="hidden" id="by" value="<?php echo $by;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
</form>
</div>
<div id="grilla"></div>
<?php 
echo $objGeneral->renderScript_JS_Operaciones($idvista,3);
echo $objGeneral->renderScript_JS_Operaciones($idvista,1);
?>
<script>
function buscar_<?php echo $idvista;?>(){
	$.ajax({
		cache: false,
		url: "vista/vistaGrilla.php?idvista=<?php echo $idvista;?>",
		type: "POST",
		data: $("#frmBuscar").serialize(),
		beforeSend: function(){
			$('#btn_aceptarCampo').attr('disabled', true);
		},
		success: function(data) {
			$('#grilla').html(data);
		},
		error: function(msg){
			$('#btn_aceptarCampo').attr('disabled', false);
		}
	});	
}
//RENDERIZAMOS OPERACIONES
<?php 
echo $objGeneral->renderScript_JS_Operaciones($idvista,3);
echo $objGeneral->renderScript_JS_Operaciones($idvista,1);
?>

$(document).ready(function() {
	//EJECUTAMOS FUNCION DE BUSQUEDA
	buscar_<?php echo $idvista;?>();
	
	$('#btn_buscar').click(function(evt) {
		buscar_<?php echo $idvista;?>();
		evt.preventDefault();
	});
});
</script>