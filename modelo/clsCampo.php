<?php
session_start();
require_once 'cado.php';
class clsCampo extends clsAccesoDatos
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

	function insertarCampo($idtabla, $nombre, $descripcion, $ayuda, $longitud, $css, $idtipocontrol)
 	{ 	
		$sql = "execute up_AgregarCampo $idtabla, '$nombre', '$descripcion', '$ayuda', $longitud, '$css', $idtipocontrol ";
		$res = $this->ejecutarSP($sql);
		if($res==0){
			return "Guardado correctamente";
		}else{
			return $this->gError[2];
		}
 	}

	function actualizarCampo($id, $descripcion, $comentario, $multiple, $tipo, $deslis='', $desman = '')
 	{
   		$sql = "execute up_ModificarCampo $id, '".$this->mill($descripcion)."', '".$this->mill($comentario)."', '$multiple', '$tipo', '$deslis', '$desman' ";
		$res = $this->ejecutarSP($sql);
		if($res==0){
			return "Guardado correctamente";
		}else{
			return $this->gError[2];
		}
 	}

	function eliminarCampo($id)
 	{
   		$sql = "execute up_EliminarCampo $id";
		$res = $this->ejecutarSP($sql);
		if($res==0){
			return "Guardado correctamente";
		}else{
			return $this->gError[2];
		}
 	}
 
	function consultarCampo($nro_reg, $nro_hoja, $order, $by, $id, $descripcion)
 	{
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarCampo ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$this->mill($descripcion)."'";
			return $this->obtenerDataSP($sql);
		}else{
			if($by==1){
				$by="ASC";
			}else{
				$by="DESC";
			}
			$descripcion = "%".$descripcion."%";
			
			$sql = "SELECT Campo.IdCampo, Campo.Descripcion, Campo.Comentario, Campo.Multiple, Campo.Tipo, Campo.DescripcionList, Campo.DescripcionMant
			FROM Campo WHERE 1=1 ";
			$sql = $sql . " AND Campo.Estado LIKE 'N' ";
			if($id>0){ $sql = $sql . " AND Campo.IdCampo = " . $id;}
			if($descripcion <>"" ){$sql = $sql . " AND Campo.Descripcion LIKE '" . $descripcion . "'";}
			
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
	
	function listarCampo($id, $descripcion)
 	{
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarCampo ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$descripcion."'";
			return $this->obtenerDataSP($sql);
		}else{
			if($by==1){
				$by="ASC";
			}else{
				$by="DESC";
			}
			$descripcion = "%".$descripcion."%";
			
			$sql = "SELECT idcampo, idtabla, nombre, descripcion, ayuda, longitud, css, idtipocontrol, estado FROM campo WHERE 1=1 ";
			$sql = $sql . " AND estado LIKE 'N' ";
			if($id>0){ $sql = $sql . " AND idcampo = " . $id;}
			if($descripcion <>"" ){$sql = $sql . " AND descripcion LIKE '" . $descripcion . "'";}
			
			return $this->obtenerDataSQL($sql);
		} 	 	
 	}
}
?>