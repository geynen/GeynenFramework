<?php
require("../modelo/clsGeneral.php");
$idvista = $_GET["idview"];
$accion = isset($_GET["accion"])?$_GET["accion"]:'NUEVO';
try{
	$objVista = new clsGeneral($idvista);
}catch(PDOException $e) {
    echo '<script>alert("Error :\n'.$e->getMessage().'");history.go(-1);</script>';
	exit();
}
//OBTENGO DATOS DE LA VISTA
$rstVista=$objVista->getVista($idvista);
$datosvista=$rstVista->fetchObject();

if($accion=='ACTUALIZAR'){
	//OBTENGO DATOS DE LA OPERACION
	$rstOperacion = $objVista->getOperaciones($idvista,1,0,0,0,'',$_GET['idvistaatributo']);
	if(is_string($rstCampos)){
		echo "Error al obtener operaciones a mostrar ".$rstCampos."";
		exit();
	}
	$dataOperacion = $rstOperacion->fetchObject();
	//OBTENGO DATOS DE LOS CAMPOS ENVIADOS EN LA URL
	$argumentos=explode(',',$dataOperacion->argumentos);
	foreach($argumentos as $indice => $value){
		$filtro[$value]= array ('nombre' => $value, 'valor' => $_GET[$value], 'operador' => '=');
	}
	//OBTENGO SENTENCIA SQL
	$sqlVista=$objVista->getSQL($idvista,$filtro);
	//echo $sqlVista;
	//OBTENGO DATOS
	$rstVista=$objVista->getData($sqlVista);
	if(is_string($rstVista)){
		echo "Error al ejecutar consulta: ".$rst."";
		exit();
	}
	$dataVista=$rstVista->fetch();
}

//OBTENGO CAMPOS A MOSTRAR
$rstCampos = $objVista->getCampos($idvista,1);
if(is_string($rstCampos)){
	echo "Error al obtener campos a mostrar: ".$rstCampos."";
	exit();
}
$dataCampos = $rstCampos->fetchAll();
?>
<div class="title"><?php echo $datosvista->titulo;?></div>
<div id="form">
<form name="frm-<?php echo $idvista;?>" id="frm-<?php echo $idvista;?>">
<ul id="ul-form">
<?php 
foreach($dataCampos as $indice => $value){
	if($value['visible']=='S'){
		$renderCampos.=$objVista->renderControles($idvista,$value['idtabla'],$indice,$value['nombre'],$value['etiqueta'],$value['css'],$value['idtipocontrol'],$value['idcombo'],$value['valor_opcional'],$value['valores'],$value['defecto'],$dataVista[$value['nombre']]);
		$renderScriptJS.=$value['script_js'];
	}
}
echo $renderCampos;
?>
<li>
<button type="button" id="btn-aceptar-<?php echo $idvista;?>">Grabar</button>
<button type="button" id="btn-cancelar-<?php echo $idvista;?>">Cancelar</button>
</li>
</ul>
</form>
</div>
<script>
$('#btn-aceptar-<?php echo $idvista;?>').click(function(evt) {
	$.ajax({
		cache: false,
		async: false,
		url: "controlador/contGeneral.php?accion=<?php echo $accion;?>&idvista=<?php echo $idvista;?>&idvistaatributo=<?php echo $_GET['idvistaatributo'];?>",
		type: "POST",
		data: $("#frm-<?php echo $idvista;?>").serialize(),
		beforeSend: function(){
			$('#btn-aceptar-<?php echo $idvista;?>').attr('disabled', true);
		},
		success: function(data) {
			if(data=='Guardado correctamente'){
				//alert(data);
				$('#frm_mant').html(data);
				//EJECUTAMOS FUNCION DE BUSQUEDA
				buscar_<?php echo $idvista;?>();
			}else{
				alert(data);
				console.log(data);
				$('#btn-aceptar-<?php echo $idvista;?>').attr('disabled', false);
			}
		},
		error: function(msg){
			console.log(msg);
			$('#btn-aceptar-<?php echo $idvista;?>').attr('disabled', false);
		}
	});		
	evt.preventDefault();
});
$('#btn-cancelar-<?php echo $idvista;?>').click(function(evt) {
	$('#frm_mant').html('');
	//console.log(getFormData('frm-<?php //echo $idvista;?>'));
});
<?php echo $renderScriptJS;?>
</script>