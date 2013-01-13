<?php
require("../modelo/clsTabla.php");
//require("fun.php");
$accion = $_POST["accion"];
$idvista = $_POST["idvista"];

if (!isset($accion)){
	echo "Error: Accion no encontrada.".$action;
	exit();
}
$objTabla = new clsTabla($idvista);


switch($accion){
	case "OPERACIONMULTIPLE" :
		if(ob_get_length()) ob_clean();
		echo umill(generaOperacionMultiple($objTabla, $_POST['check'], $operacion, "Procesado correctamente"));
		break;
	case "NUEVO" :
		if(ob_get_length()) ob_clean();
		echo $objTabla->insertarTabla($_POST["txtnombre"], $_POST["txtdescripcion"], $_POST["txtayuda"], $_POST["cbotipotabla"]);
		break;
		
	case "ACTUALIZAR" :
		if(ob_get_length()) ob_clean();
		echo $objTabla->actualizarTabla($_POST["txtId"], $_POST["txtDescripcion"], $_POST["txtComentario"], $multiple, $_POST["optTipo"], $_POST["txtDescripcionList"], $_POST["txtDescripcionMant"]);
		break;
		
	case "ELIMINAR" :
		if(ob_get_length()) ob_clean();
		echo $objTabla->eliminarTabla($_POST["txtId"]);
		break;
	default:
		echo "Error en el Servidor: Operacion no Implementada.";
		exit();
}
?>