<?php
session_start();
require_once 'cado.php';
class clsGeneral extends clsAccesoDatos
{

	// Constructor de la clase
	function __construct($idvista){
		if($idvista>0){
			$this->gIdVista = $idvista;
		}else{
			$this->gIdVista = $idvista;
		}
		parent::__construct();
	}
	
	function operacionGeneral($data, $function_sql, $argumentos_sql){ 	
		//$argumentos_sql="nombre,descripcion,ayuda,tipotabla";
		$argumentos=explode(',',$argumentos_sql);
		foreach($argumentos as $indice => $value){
			$arg.="'".$data[$value]."',";
		}
		$arg=substr($arg,0,-1);
		
		$sql = "execute ".$function_sql." ".$arg;//echo $sql;
		$res = $this->ejecutarSP($sql);
		if($res==0){
			return "Guardado correctamente";
		}else{
			return $this->gError[2];
		}
 	}
	
	function insertarGeneral($idvista, $data){ 	
		
		//OBTENGO DATOS DE LA VISTA
		$rstVista=$this->getVista($idvista);
		$datavista=$rstVista->fetchObject();
		$idtabla=$datavista->idtabla;
		//OBTENGO DATOS DE LA TABLA
		$dataTabla=$this->getTabla($idtabla);
		$nombretabla=strtolower($dataTabla->nombre);
		//OBTENGO CAMPO PK
		$rstPK=$this->getCampoPK($idtabla);
		$dataPK=$rstPK->fetchObject();
		$PK=strtolower($dataPK->nombre);
		//OBTENGO CAMPOS PKD
		$rstPKD=$this->getCamposPKD($idtabla);
		while($dataPKD=$rstPKD->fetchObject()){
			$PKD.=strtolower($dataPKD->nombre)."='".$data[$dataPKD->nombre]."' and";
		}
		if(isset($PKD) and $PKD!=""){
			$PKD=" where ".substr($PKD,0,-3);
		}
		//FORMO CONSULTA PARA OBTENER ID
		$sql="select max(".$PK.") as id from ".$nombretabla.$PKD;
		//echo $sql;
		$rst=$this->obtenerDataSQL($sql);
		$datoID=$rst->fetchObject();
		$ID=$datoID->id + 1;
		
		//OBTENGO CAMPOS
		$rstCampos = $this->getCampos($idvista,1);
		while($dataCampos = $rstCampos->fetchObject()){
			$strCampos.=$dataCampos->nombre.",";
			if($dataCampos->nombre==$PK){
				$strCamposDato.="'".$ID."',";
			}else{
				$strCamposDato.="'".$data[$dataCampos->nombre]."',";
			}
		}
		$strCampos=strtolower(substr($strCampos,0,-1));
		$strCamposDato=substr($strCamposDato,0,-1);
		
		//FORMO CONSULTA PARA INSERT
		$sql="insert into ".$nombretabla." (".$strCampos.") values(".$strCamposDato.")";
		//echo $sql;		
		$this->iniciarTransaccion();
		$res = $this->ejecutarSQL($sql);
		
		if($res==0){
			$this->finalizarTransaccion();
			return "Guardado correctamente";
		}else{
			$this->abortarTransaccion();
			return $this->gError[2];
		}
 	}
	
	function actualizarGeneral($idvista, $data){ 	
		
		//OBTENGO DATOS DE LA VISTA
		$rstVista=$this->getVista($idvista);
		$datavista=$rstVista->fetchObject();
		$idtabla=$datavista->idtabla;
		//OBTENGO DATOS DE LA TABLA
		$dataTabla=$this->getTabla($idtabla);
		$nombretabla=strtolower($dataTabla->nombre);
		//OBTENGO CAMPO PK
		$rstPK=$this->getCampoPK($idtabla);
		$dataPK=$rstPK->fetchObject();
		$PK=strtolower($dataPK->nombre)."='".$data[$dataPK->nombre]."'";
		//OBTENGO CAMPOS PKD
		$rstPKD=$this->getCamposPKD($idtabla);
		while($dataPKD=$rstPKD->fetchObject()){
			$PKD.=strtolower($dataPKD->nombre)."='".$data[$dataPKD->nombre]."' and";
		}
		if(isset($PKD) and $PKD!=""){
			$PKD=substr($PKD,0,-3);
		}
		
		//OBTENGO CAMPOS
		$rstCampos = $this->getCampos($idvista,1);
		while($dataCampos = $rstCampos->fetchObject()){
			if($dataCampos->llave!='PK' and $dataCampos->llave!='PKD'){
				$strCamposDato.=strtolower($dataCampos->nombre)."='".$data[$dataCampos->nombre]."',";
			}
		}
		$strCamposDato=substr($strCamposDato,0,-1);
		
		//FORMO CONSULTA PARA UPDATE
		$sql="update ".$nombretabla." set ".$strCamposDato." where ".$PK.($PKD!=''?" and ".$PKD:"");
		//echo $sql;		
		$this->iniciarTransaccion();
		$res = $this->ejecutarSQL($sql);
		
		if($res==0){
			$this->finalizarTransaccion();
			return "Guardado correctamente";
		}else{
			$this->abortarTransaccion();
			return $this->gError[2];
		}
 	}
	
	function eliminarGeneral($idvista, $data){ 	
		
		//OBTENGO DATOS DE LA VISTA
		$rstVista=$this->getVista($idvista);
		$datavista=$rstVista->fetchObject();
		$idtabla=$datavista->idtabla;
		//OBTENGO DATOS DE LA TABLA
		$dataTabla=$this->getTabla($idtabla);
		$nombretabla=strtolower($dataTabla->nombre);
		//OBTENGO CAMPO PK
		$rstPK=$this->getCampoPK($idtabla);
		$dataPK=$rstPK->fetchObject();
		$PK=strtolower($dataPK->nombre)."='".$data[$dataPK->nombre]."'";
		//OBTENGO CAMPOS PKD
		$rstPKD=$this->getCamposPKD($idtabla);
		while($dataPKD=$rstPKD->fetchObject()){
			$PKD.=strtolower($dataPKD->nombre)."='".$data[$dataPKD->nombre]."' and";
		}
		if(isset($PKD) and $PKD!=""){
			$PKD=substr($PKD,0,-3);
		}
		
		//FORMO CONSULTA PARA DELETE
		$sql="delete from ".$nombretabla." where ".$PK.($PKD!=''?" and ".$PKD:"");
		echo $sql;		
		$this->iniciarTransaccion();
		//$res = $this->ejecutarSQL($sql);
		
		if($res==0){
			$this->finalizarTransaccion();
			return "Guardado correctamente";
		}else{
			$this->abortarTransaccion();
			return $this->gError[2];
		}
 	}

	function consultarGeneral($nro_reg, $nro_hoja, $order, $by, $id, $descripcion){//PENDIENTE
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarGeneral ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$this->mill($descripcion)."'";
			return $this->obtenerDataSP($sql);
		}else{
			if($by==1){
				$by="ASC";
			}else{
				$by="DESC";
			}
			$descripcion = "%".$descripcion."%";
			
			$sql = "SELECT General.IdGeneral, General.Descripcion, General.Comentario, General.Multiple, General.Tipo, General.DescripcionList, General.DescripcionMant
			FROM General WHERE 1=1 ";
			$sql = $sql . " AND General.Estado LIKE 'N' ";
			if($id>0){ $sql = $sql . " AND General.IdGeneral = " . $id;}
			if($descripcion <>"" ){$sql = $sql . " AND General.Descripcion LIKE '" . $descripcion . "'";}
			
			$rst = $this->obtenerDataSQL($sql.chr(13)."	ORDER BY " . $order . " " . $by . chr(13));
			$cuenta = $rst->fetchAll();
			$total = COUNT($cuenta);
			if($nro_reg==0){
				$limit="";
			}else{
				if($total%$nro_reg==0){$total_hojas=(int)($total/$nro_reg);}else{$total_hojas=(int)($total/$nro_reg) + 1;}
				if($total_hojas < $nro_hoja){$nro_hoja=1;}
				$limit = " LIMIT ".$nro_reg." OFFSET ".($nro_reg*$nro_hoja - $nro_reg);
			}
			return $this->obtenerDataSQL("SELECT ".$total." as NroTotal, ".substr($sql,7,strlen($sql)-7)." ".chr(13)."	ORDER BY " . ($order)." ".$by.chr(13).$limit);
		} 	 	
 	}
	
	function getVista($id, $nombre=''){
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarGeneral ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$descripcion."'";
			return $this->obtenerDataSP($sql);
		}else{
			if($by==1){
				$by="ASC";
			}else{
				$by="DESC";
			}
			
			$sql = "SELECT * FROM vista WHERE 1=1 ";
			//$sql = $sql . " AND estado = 'N' ";
			if($id>0){ $sql = $sql . " AND idvista = ".$id;}
			if($nombre <>"" ){$sql = $sql . " AND nombre LIKE '%".$nombre."%'";}
			
			return $this->obtenerDataSQL($sql);
		} 	 	
 	}
	
	function getTabla($idtabla){
		
		$sql="select * from tabla where idtabla=".$idtabla;
		$rst=$this->obtenerDataSQL($sql);
		return $rst->fetchObject();
	}
	
	function getFiltros($idvista){
		$rstFiltro=$this->getCampos($idvista,2);
		while($datoFiltro=$rstFiltro->fetchObject()){
				$filtro[$datoFiltro->nombre]= array ('nombre' => $datoFiltro->nombre, 'etiqueta' => $datoFiltro->etiqueta, 'operador' => $datoFiltro->operador, 'defecto' => $datoFiltro->defecto);
		}
		return $filtro;
	}
	
	function getCampos($idvista, $idregionvista=0, $idtabla=0, $idcampo=0){
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarGeneral ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$descripcion."'";
			return $this->obtenerDataSP($sql);
		}else{
			$sql="select c.idtabla, c.idcampo, c.nombre as nombre, c.idtipocontrol, c.idtipodato, c.idcombo, c.valores, c.idhandler, c.script_js, c.llave, c.valor_opcional, c.campos_ref, va.etiqueta, va.operador, va.defecto, va.css, va.visible from vista_atributo va inner join campo c on va.idatributo=c.idcampo and va.idtabla=c.idtabla and tipoatributo='C' inner join tabla on tabla.idtabla=c.idtabla where 1=1 ";
			
			if($idvista>0){ $sql.=" AND va.idvista = ".$idvista;}
			if($idregionvista>0){ $sql.=" AND idregionvista = ".$idregionvista;}
			if($idtabla>0){ $sql.=" AND c.idtabla = ".$idtabla;}
			if($idcampo>0){ $sql.=" AND c.idcampo = ".$idcampo;}
			
			$sql.=" order by va.orden asc";
			
			return $this->obtenerDataSQL($sql);
		} 	
	}
	
	function getCampoPK($idtabla){
		$sql="select c.idcampo, c.nombre as nombre, c.idtipocontrol, c.idtipodato, c.idcombo, c.valores, c.idhandler, c.script_js, c.llave, c.valor_opcional from campo c where llave='PK' ";
		
		if($idtabla>0){ $sql.=" AND c.idtabla = ".$idtabla;}
		
		return $this->obtenerDataSQL($sql);
	}
	
	function getCamposPKD($idtabla){
		$sql="select c.idcampo, c.nombre as nombre, c.idtipocontrol, c.idtipodato, c.idcombo, c.valores, c.idhandler, c.script_js, c.llave, c.valor_opcional from campo c where llave='PKD' ";
		
		if($idtabla>0){ $sql.=" AND c.idtabla = ".$idtabla;}
		
		return $this->obtenerDataSQL($sql);
	} 	
	
	function getAtributosCampo($idtabla,$idcampo){
		$sql="select ca.valor, ac.nombre from campo_atributo ca inner join atributo_control ac on ac.idatributo_control=ca.idatributo_control and ac.idtipocontrol=ca.idtipocontrol where 1=1 ";
		
		if($idtabla>0){ $sql.=" AND ca.idtabla = ".$idtabla;}
		if($idcampo>0){ $sql.=" AND ca.idcampo = ".$idcampo;}
		
		return $this->obtenerDataSQL($sql);
	}
	
	function getAtributosControl($idtipocontrol){
		$sql="select * from atributo_control ac where 1=1 ";
		
		if($idtipocontrol>0){ $sql.=" AND ac.idtipocontrol = ".$idtipocontrol;}
		
		return $this->obtenerDataSQL($sql);
	}
	
	function getSQL($idvista,$filtro=''){		
		$sql="select * from vista where idvista=".$idvista;
		$rst=$this->obtenerDataSQL($sql);
		$datoVista=$rst->fetchObject();
		$idtablabase=$datoVista->idtabla;
		$sql="select * from tabla where idtabla=".$idtablabase;
		$rst=$this->obtenerDataSQL($sql);
		$datoTabla=$rst->fetchObject();
		$nombretablabase=$datoTabla->nombre;
		$sql="select va.nombre as alias,c.nombre as nombre, tabla.nombre as nombretabla from vista_atributo va inner join campo c on va.idatributo=c.idcampo and va.idtabla=c.idtabla and tipoatributo='C' inner join tabla on tabla.idtabla=c.idtabla where idvista=".$idvista;
		$rst=$this->obtenerDataSQL($sql);
		while($datoAtributo=$rst->fetchObject()){
			$atributos.=$datoAtributo->nombretabla.".".$datoAtributo->nombre." as ".$datoAtributo->alias.",";
		}
		$atributos=substr($atributos,0,-1);
		
		if(isset($filtro) and $filtro!=''){
			foreach($filtro as $indice => $value){
				if(strtolower($value['operador'])=='like'){
					$strFiltro.=" and ".$indice." ".$value['operador']." '%".$value['valor']."%'";
				}else{
					$strFiltro.=" and ".$indice." ".$value['operador']." '".$value['valor']."'";
				}
			}
		}
		
		$sql="select ".$atributos." from ".$nombretablabase." where 1=1 ".$strFiltro;
		return strtolower($sql);
 	}
	
	function getDataPaginacion($nro_reg, $nro_hoja, $order, $by, $sql){
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarGeneral ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$this->mill($descripcion)."'";
			return $this->obtenerDataSP($sql);
		}else{
			if($by==1){
				$by="ASC";
			}else{
				$by="DESC";
			}
			$descripcion = "%".$descripcion."%";
			
			//echo $sql;
			$rst = $this->obtenerDataSQL($sql.chr(13)."	ORDER BY " . $order . " " . $by . chr(13));
			$cuenta = $rst->fetchAll();
			$total = COUNT($cuenta);
			if($nro_reg==0){
				$limit="";
			}else{
				if($total%$nro_reg==0){$total_hojas=(int)($total/$nro_reg);}else{$total_hojas=(int)($total/$nro_reg) + 1;}
				if($total_hojas < $nro_hoja){$nro_hoja=1;}
				$limit = " LIMIT ".$nro_reg." OFFSET ".($nro_reg*$nro_hoja - $nro_reg);
			}
			return $this->obtenerDataSQL("SELECT ".$total." as NroTotal, ".substr($sql,7,strlen($sql)-7)." ".chr(13)."	ORDER BY " . ($order)." ".$by.chr(13).$limit);
		} 	 	
 	}
	
	function getData($sql){
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarGeneral ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$this->mill($descripcion)."'";
			return $this->obtenerDataSP($sql);
		}else{
			//echo $sql;
			return $this->obtenerDataSQL($sql);
		} 	 	
 	}
	
	function getOperaciones($idvista, $idregionvista=0, $idtabla=0, $idoperacion=0, $idhandler=0, $tipo='', $idvista_atributo=0){
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarGeneral ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$descripcion."'";
			return $this->obtenerDataSP($sql);
		}else{
			$sql="select va.idvista_atributo, o.idoperacion, o.nombre as nombre, o.tipo, va.etiqueta, o.idhandler, o.accion, o.argumentos, o.script_js, o.function_sql, o.argumentos_sql, o.idvista_redirect, o.contenedor_redirect, va.idtabla from vista_atributo va inner join operacion o on va.idatributo=o.idoperacion and va.idtabla=o.idtabla and tipoatributo='O' where 1=1 ";
			
			if($idvista>0){ $sql.=" AND va.idvista = ".$idvista;}
			if($idregionvista>0){ $sql.=" AND idregionvista = ".$idregionvista;}
			if($idtabla>0){ $sql.=" AND va.idtabla = ".$idtabla;}
			if($idoperacion>0){ $sql.=" AND o.idoperacion = ".$idoperacion;}
			if($idhandler>0){ $sql.=" AND idhandler = ".$idhandler;}
			if($tipo!=''){ $sql.=" AND o.tipo = '".$tipo."'";}
			if($idvista_atributo>0){ $sql.=" AND va.idvista_atributo = ".$idvista_atributo;}
			
			$sql.=" order by va.orden asc";
			//echo $sql;
			return $this->obtenerDataSQL($sql);
		} 	
	}
	
	function renderScript_JS_Operaciones($idvista,$idregionvista=1){
		//OBTENGO OPERACIONES
		$rstOperaciones = $this->getOperaciones($idvista,$idregionvista);
		if(is_string($rstOperaciones)){
			echo "Error al obtener Script JS Operaciones: ".$rstOperaciones."";
			exit();
		}
		$datoOperaciones = $rstOperaciones->fetchAll();
		$nro_ope = count($datoOperaciones);
		reset($datoOperaciones);
		$nro_ope=0;
		foreach($datoOperaciones as $operacion){
			$arg=NULL;
			if($operacion["idhandler"]==1){//NUEVO
				$argumentos=explode(',',$operacion["argumentos"]);
				foreach($argumentos as $indice => $value){
					if($value!='') $arg.="+'&".$value."='+".$value;
				}
				$script_js="setRun(".$operacion["idvista_redirect"].",'vistaForm','&accion=NUEVO&idtabla_redirect=".$operacion["idtabla"]."&idoperacion_redirect=".$operacion["idoperacion"]."".(isset($arg)?substr($arg,2):"'")."+argumentos_get,'".$operacion["contenedor_redirect"]."');";
			}elseif($operacion["idhandler"]==2){//ACTUALIZAR
				$argumentos=explode(',',$operacion["argumentos"]);
				foreach($argumentos as $indice => $value){
					if($value!='') $arg.="+'&".$value."='+".$value;
				}
				$script_js="setRun(".$operacion["idvista_redirect"].",'vistaForm','&accion=ACTUALIZAR&idvistaatributo='+idvistaatributo".(isset($arg)?$arg:"'").",'".$operacion["contenedor_redirect"]."');";
			}elseif($operacion["idhandler"]==3){//ELIMINAR
				$argumentos=explode(',',$operacion["argumentos"]);
				$arg="";
				foreach($argumentos as $indice => $value){
					if($value!='') $arg.="+'&".$value."='+".$value;
				}
				$script_js="
				$.ajax({
					cache: false,
					async: false,
					url: 'controlador/contGeneral.php?accion=ELIMINAR&idvista=".$idvista."&idvistaatributo='+idvistaatributo".$arg.",
					success: function(data) {
						alert(data);
					},
				});	";
			}elseif($operacion["idhandler"]==4){//REDIRECCIONAR
				$argumentos=explode(',',$operacion["argumentos"]);
				foreach($argumentos as $indice => $value){
					if($value!='') $arg.="+'&".$value."='+".$value;
				}
				$script_js="setRun(".$operacion["idvista_redirect"].",'".$operacion["accion"]."','&idtabla_redirect=".$operacion["idtabla"]."&idoperacion_redirect=".$operacion["idoperacion"]."".(isset($arg)?substr($arg,2):"'").",'".$operacion["contenedor_redirect"]."');";
			}elseif($operacion["idhandler"]==5){//OTRO
				$argumentos=explode(',',$operacion["argumentos"]);
				$arg="";
				foreach($argumentos as $indice => $value){
					if($value!='') $arg.="+'&".$value."='+".$value;
				}
				$script_js="
				$.ajax({
					cache: false,
					async: false,
					url: 'controlador/contGeneral.php?accion=OPERACION&idvista=".$idvista."&idvistaatributo='+idvistaatributo".$arg.",
					success: function(data) {
						alert(data);
					},
				});	";
			}else{
				$script_js=$operacion['script_js'];
			}
			
			if($idregionvista==1){//CAMPOS
				$renderScripJS_Operaciones.=chr(13)."function ".$operacion['nombre']."_".$idvista."(idvistaatributo".(isset($operacion['argumentos'])?($operacion['argumentos']!=''?",".$operacion['argumentos']:""):"")."){".$operacion['script_js_pref'].$script_js.$operacion['script_js_suf']."}";
			}elseif($idregionvista==3){//ENCABEZADO
				$renderScripJS_Operaciones.=chr(13)."function ".$operacion['nombre']."_".$idvista."(idvistaatributo,argumentos_get".(isset($operacion['argumentos'])?($operacion['argumentos']!=''?",".$operacion['argumentos']:""):"")."){".$operacion['script_js_pref'].$script_js.$operacion['script_js_suf']."}";
			}
		}
		return $renderScripJS_Operaciones;
	}
	
	function renderControl($idvista,$idtabla,$idcampo,$nombre,$etiqueta,$css,$idtipocontrol,$idcombo,$valor_opcional,$valores,$defecto,$datocampo,$campos_ref,$ajax='NO'){
		
		//OBTENGO ATRIBUTOS DEL CAMPO
		$rstAtributoCampo=$this->getAtributosCampo($idtabla,$idcampo);
		while($dataAtributoCampo=$rstAtributoCampo->fetchObject()){
				$datoAtributoCampo[$dataAtributoCampo->nombre]=array ("valor"=>$dataAtributoCampo->valor);
		}		
		//OBTENGO ATRIBUTOS DEL CONTROL
		$rstAtributoControl=$this->getAtributosControl($idtipocontrol);
		while($dataAtributoControl=$rstAtributoControl->fetchObject()){
			//Verifico si el atributo del campo tiene un valor, para asignarlo como valor por defecto
			if(isset($datoAtributoCampo[$dataAtributoControl->nombre]['valor']) and $datoAtributoCampo[$dataAtributoControl->nombre]['valor']!=''){
				$datoAtributoControl[$dataAtributoControl->nombre]=array ("valores"=>$dataAtributoControl->valores,"defecto"=>$datoAtributoCampo[$dataAtributoControl->nombre]['valor']);
			}else{
				$datoAtributoControl[$dataAtributoControl->nombre]=array ("valores"=>$dataAtributoControl->valores,"defecto"=>$dataAtributoControl->defecto);			
			}
		}
		
		$etiquetainicio='<li id="li_'.$nombre.'" class="'.$css.'"><label for="'.$nombre.'">'.$etiqueta.': </label>';
		$etiquetafin='</li>';
		
		switch ($idtipocontrol){
			case 2://CAJA DE OCULTA
				$etiquetainicio='';
				$etiquetafin='';
				$renderControl='<input type="hidden" id="'.$nombre.'" name="'.$nombre.'" value="'.(isset($datocampo)?$datocampo:$_GET[$nombre]).'">';
				break;
			case 3://TEXT AREA
				$renderControl='<textarea id="'.$nombre.'" name="'.$nombre.'" rows="'.$datoAtributoControl['rows']['defecto'].'"  cols="'.$datoAtributoControl['cols']['defecto'].'">'.(isset($datocampo)?$datocampo:$_GET[$nombre]).'</textarea>';
				break;
			case 4://SELECT SIMPLE
				$renderControl='<select id="'.$nombre.'" name="'.$nombre.'">';
				//Separamos cadena de valores por cada salto de linea
				$arrayValores=explode("\x0a",$valores);
				foreach($arrayValores as $indice => $value){
					$arrayValor=explode('|',$value);
					$valor=trim($arrayValor[0]);
					$descripcion=trim($arrayValor[1]);
					$seleccionado=(isset($datocampo)?$datocampo:(isset($_GET[$nombre])?$_GET[$nombre]:$defecto));
					$renderControl.='<option value="'.$valor.'" '.($seleccionado==$valor?'selected':'').' >'.$descripcion.'</option>';
				}
				$renderControl.='</select>';
				break;
			case 5://SELECT SIMPLE ORIGEN TABLA
				//Verifico si es un campo dependiente
				if(isset($campos_ref) and $campos_ref!=''){
					$filtroCombo=explode(",",$campos_ref);
					foreach($filtroCombo as $indice => $value){
						//obtengo filtros de los campos del cual depende
						$filtroComboJS.="&".$value."='+document.getElementById('".$value."').value + '";
						$filtro.=$value."='".$_GET[$value]."' and ";
						//creo handlers js
						$handlerJS.="$('#".$value."').click(function(evt) { exec_".$nombre."(); });";
					}
					if($ajax=='NO'){//Si la renderización es sin ajax, creo y ejecuto el script js para la rendreización por vez primera
						$renderControlJS.="<script>";
						$renderControlInit="setAjax(".$idvista.",'ajaxGeneral','CONTROL','idtablacontrol=".$idtabla."&idcampocontrol=".$idcampo."&datocampo=".(isset($datocampo)?$datocampo:(isset($_GET[$nombre])?$_GET[$nombre]:$defecto)).(isset($filtroComboJS)?$filtroComboJS:"")."','li_".$nombre."');";
						$renderControlJS.=$renderControlInit;
						$renderControlJS.="function exec_".$nombre."(){".$renderControlInit."}";
						$renderControlJS.=$handlerJS;
						$renderControlJS.="</script>";
						break;
					}
				}
				$filtro=substr($filtro,0,-4);	
				
				//Inicio renderizacion del combo			
				$renderControl='<select id="'.$nombre.'" name="'.$nombre.'">';
				//Obtengo datos del valor opcional
				if($valor_opcional!=''){
					$arrayValor_opcional=explode('|',$valor_opcional);
					$valor_opcional=trim($arrayValor_opcional[0]);
					$descripcion_opcional=trim($arrayValor_opcional[1]);
					$renderControl.='<option value="'.$valor_opcional.'" selected>'.$descripcion_opcional.'</option>';
				}
				//Obtengo datos de la tabla
				$datosTabla=$this->getTabla($idcombo);
				$sql="select * from ".strtolower($datosTabla->nombre).($filtro!=''?" where ".$filtro:"")." order by 2 asc";
				//echo $sql;
				$rstTabla=$this->obtenerDataSQL($sql);
				while($datoTabla=$rstTabla->fetchObject()){
					//Separamos cadena de valores a mostrar
					$arrayValores=explode(",",$valores);//solo se acepta 2 valores
					$valor=trim($datoTabla->$arrayValores[0]);
					$descripcion=trim($datoTabla->$arrayValores[1]);
					$seleccionado=(isset($datocampo)?$datocampo:(isset($_GET[$nombre])?$_GET[$nombre]:$defecto));
					$renderControl.='<option value="'.$valor.'" '.($seleccionado==$valor?'selected':'').' >'.$descripcion.'</option>';
				}
				$renderControl.='</select>';
				break;
			case 6://SELECT SIMPLE ORIGEN VISTA (PENDIENTE)
				$renderControl='<select id="'.$nombre.'" name="'.$nombre.'">';
				//Obtengo datos del valor opcional
				if($valor_opcional!=''){
					$arrayValor_opcional=explode('|',$valor_opcional);
					$valor_opcional=trim($arrayValor_opcional[0]);
					$descripcion_opcional=trim($arrayValor_opcional[1]);
					$renderControl.='<option value="'.$valor_opcional.'" selected>'.$descripcion_opcional.'</option>';
				}
				//OBTENGO SENTENCIA SQL
				$sqlVista=$this->getSQL($idcombo);
				//OBTENGO DATOS
				$rstVista=$this->getData($sqlVista);
				while($datoVista=$rstVista->fetchObject()){
					//Separamos cadena de valores a mostrar
					$arrayValores=explode(",",$valores);//solo se acepta 2 valores
					$valor=trim($datoVista->$arrayValores[0]);
					$descripcion=trim($datoVista->$arrayValores[1]);
					$seleccionado=(isset($datocampo)?$datocampo:(isset($_GET[$nombre])?$_GET[$nombre]:$defecto));
					$renderControl.='<option value="'.$valor.'" '.($seleccionado==$valor?'selected':'').' >'.$descripcion.'</option>';
				}
				$renderControl.='</select>';
				break;
			default://CAJA DE TEXTO
				$renderControl='<input type="text" id="'.$nombre.'" name="'.$nombre.'" value="'.(isset($datocampo)?$datocampo:$_GET[$nombre]).'">';
				break;
		}
		return $etiquetainicio.$renderControl.$etiquetafin.$renderControlJS;	
	}
}
?>