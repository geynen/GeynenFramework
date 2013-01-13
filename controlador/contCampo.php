<?php
require("../modelo/clsCampo.php");
//require("fun.php");
$accion = $_POST["accion"];
$idvista = $_POST["idvista"];

if (!isset($accion)){
	echo "Error: Accion no encontrada.".$action;
	exit();
}
$objCampo = new clsCampo($idvista);


switch($accion){
	case "OPERACIONMULTIPLE" :
		if(ob_get_length()) ob_clean();
		echo umill(generaOperacionMultiple($objCampo, $_POST['check'], $operacion, "Procesado correctamente"));
		break;
	case "NUEVO" :
		if(ob_get_length()) ob_clean();
		echo $objCampo->insertarCampo($_POST["idtabla"], $_POST["txtnombre"], $_POST["txtdescripcion"], $_POST["txtayuda"], ($_POST["txtlongitud"]!=''?$_POST["txtlongitud"]:0), $_POST["txtcss"], $_POST["cbotipocontrol"]);
		break;
		
	case "ACTUALIZAR" :
		if(ob_get_length()) ob_clean();
		echo $objCampo->actualizarCampo($_POST["txtId"], $_POST["txtDescripcion"], $_POST["txtComentario"], $multiple, $_POST["optTipo"], $_POST["txtDescripcionList"], $_POST["txtDescripcionMant"]);
		break;
		
	case "ELIMINAR" :
		if(ob_get_length()) ob_clean();
		echo $objCampo->eliminarCampo($_POST["txtId"]);
		break;
	default:
		echo "Error en el Servidor: Operacion no Implementada.";
		exit();
}
?>