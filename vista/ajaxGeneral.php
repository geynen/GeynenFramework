<?php
require("../modelo/clsGeneral.php");
//require("fun.php");
$accion = $_GET["accion"];
$idvista = $_GET["idview"];
$idvistaatributo = $_GET["idvistaatributo"];

if (!isset($accion)){
	echo "Error: Accion no encontrada.".$action;
	exit();
}
$objGeneral = new clsGeneral($idvista);


switch($accion){
	case "CONTROL" :
		if(ob_get_length()) ob_clean();
		//OBTENGO CAMPOS A MOSTRAR
		$rstCampos = $objGeneral->getCampos($idvista,0,$_GET['idtablacontrol'],$_GET['idcampocontrol']);
		if(is_string($rstCampos)){
			echo "Error al obtener campos a mostrar: ".$rstCampos."";
			exit();
		}
		$dataCampos = $rstCampos->fetchAll();
		
		foreach($dataCampos as $indice => $value){
			if($value['visible']=='S'){
				$renderCampos.=$objGeneral->renderControl($idvista,$value['idtabla'],$value['idcampo'],$value['nombre'],$value['etiqueta'],$value['css'],$value['idtipocontrol'],$value['idcombo'],$value['valor_opcional'],$value['valores'],$value['defecto'],$dataVista[$value['nombre']],$value['campos_ref'],'SI');
				$renderScriptJS.=$value['script_js'];
			}
		}
		echo $renderCampos;
		break;	
	default:
		echo "Error en el Servidor: Operacion no Implementada.";
		exit();
}
?>