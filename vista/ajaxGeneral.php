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
				$renderCampos.=$objGeneral->renderControl($idvista,$value['idtabla'],$value['idcampo'],$value['nombre'],$value['etiqueta'],$value['css'],$value['idtipocontrol'],$value['idcombo'],$value['valor_opcional'],$value['valores'],$value['defecto'],$_GET['datocampo'],$value['campos_ref'],'SI');
				//$renderScriptJS.=$value['script_js'];
			}
		}
		echo $renderCampos.$renderScriptJS;
		break;
	case "COMBO" :
		if(ob_get_length()) ob_clean();
		$renderCampos.=$objGeneral->renderControl($idvista,$_GET['idtabla'],$_GET['idcampo'],$_GET['nombre'],$_GET['etiqueta'],$_GET['css'],$_GET['idtipocontrol'],$_GET['idcombo'],$_GET['valor_opcional'],$_GET['valores'],$_GET['defecto'],$_GET['datocampo'],$_GET['campos_ref'],'SI');
		//$renderScriptJS.=$_GET['script_js'];
		//Forma de llamar desde js
		//setAjax(9,'ajaxGeneral','COMBO','idtablacontrol=5&idcampocontrol=8&nombre=idatributo&etiqueta=idatributo&css=&idtipocontrol=5&idcombo=2&valor_opcional=&valores=idcampo,nombre&defecto=&datocampo=&campos_ref=idtabla&ajax=SI&idtabla='+document.getElementById('idtabla').value,'li_idatributo');

		echo $renderCampos.$renderScriptJS;
		break;	
	default:
		echo "Error en el Servidor: Operacion no Implementada.";
		exit();
}
?>