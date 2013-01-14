<?php
require("../modelo/clsGeneral.php");
//require("fun.php");
$accion = $_GET["accion"];
$idvista = $_GET["idvista"];
$idvistaatributo = $_GET["idvistaatributo"];

if (!isset($accion)){
	echo "Error: Accion no encontrada.".$action;
	exit();
}
$objGeneral = new clsGeneral($idvista);


switch($accion){
	case "OPERACIONMULTIPLE" :
		if(ob_get_length()) ob_clean();
		echo umill(generaOperacionMultiple($objGeneral, $_POST['check'], $operacion, "Procesado correctamente"));
		break;
	case "NUEVO" :
		if(ob_get_length()) ob_clean();
		//OBTENGO DATOS DE LOS CAMPOS ENVIADOS DESDE EL FORM
		$rstCampos = $objGeneral->getCampos($idvista,1);
		if(is_string($rstCampos)){
			echo "Error al obtener campos a mostrar ".$rstCampos."";
			exit();
		}
		$dataCampos = $rstCampos->fetchAll();
		foreach($dataCampos as $indice => $value){
			if($value['idtipodato']==2){//NUMERICO O ENTERO				
				$data[$value['nombre']]=((isset($_POST[$value['nombre']]) and $_POST[$value['nombre']]!='')?$_POST[$value['nombre']]:0);
			}else{
				$data[$value['nombre']]=$_POST[$value['nombre']];
			}
		}

		//OBTENGO DATOS DE LA OPERACION
		$rstOperacion = $objGeneral->getOperaciones($idvista,3,0,1,1);
		if(is_string($rstCampos)){
			echo "Error al obtener operaciones a mostrar ".$rstCampos."";
			exit();
		}
		$dataOperacion = $rstOperacion->fetchObject();
		$function_sql=$dataOperacion->function_sql;
		$argumentos_sql=$dataOperacion->argumentos_sql;
		
		//echo $objGeneral->operacionGeneral($data,$function_sql,$argumentos_sql);
		echo $objGeneral->insertarGeneral($idvista,$data);
		break;
				
	case "ACTUALIZAR" :
		if(ob_get_length()) ob_clean();
		//OBTENGO DATOS DE LOS CAMPOS ENVIADOS DESDE EL FORM
		$rstCampos = $objGeneral->getCampos($idvista,1);
		if(is_string($rstCampos)){
			echo "Error al obtener campos a mostrar ".$rstCampos."";
			exit();
		}
		$dataCampos = $rstCampos->fetchAll();
		foreach($dataCampos as $indice => $value){
			if($value['idtipodato']==2){//NUMERICO O ENTERO				
				$data[$value['nombre']]=((isset($_POST[$value['nombre']]) and $_POST[$value['nombre']]!='')?$_POST[$value['nombre']]:0);
			}else{
				$data[$value['nombre']]=$_POST[$value['nombre']];
			}
		}
		
		//OBTENGO DATOS DE LA OPERACION
		$rstOperacion = $objGeneral->getOperaciones($idvista,1,0,0,0,'',$idvistaatributo);
		if(is_string($rstCampos)){
			echo "Error al obtener operaciones a mostrar ".$rstCampos."";
			exit();
		}
		$dataOperacion = $rstOperacion->fetchObject();
		$function_sql=$dataOperacion->function_sql;
		$argumentos_sql=$dataOperacion->argumentos_sql;

		//echo $objGeneral->operacionGeneral($data,$function_sql,$argumentos_sql);
		echo $objGeneral->actualizarGeneral($idvista,$data);
		break;
		
	case "OPERACION" :
		if(ob_get_length()) ob_clean();
		//OBTENGO DATOS DE LA OPERACION
		$rstOperacion = $objGeneral->getOperaciones($idvista,1,1,3,3);
		if(is_string($rstCampos)){
			echo "Error al obtener operaciones a mostrar ".$rstCampos."";
			exit();
		}
		$dataOperacion = $rstOperacion->fetchObject();
		//OBTENGO DATOS DE LOS CAMPOS ENVIADOS EN LA URL
		$argumentos=explode(',',$dataOperacion->argumentos);
		foreach($argumentos as $indice => $value){
			$data[$value]=$_GET[$value];
		}
		$function_sql=$dataOperacion->function_sql;
		$argumentos_sql=$dataOperacion->argumentos_sql;
		
		echo $objGeneral->operacionGeneral($data,$function_sql,$argumentos_sql);
		break;	
	default:
		echo "Error en el Servidor: Operacion no Implementada.";
		exit();
}
?>