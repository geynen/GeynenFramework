<?php
session_start();
require_once 'cado.php';
class clsTabla extends clsAccesoDatos
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

	function insertarTabla($nombre, $descripcion, $ayuda, $tipotabla)
 	{ 	
		$sql = "execute up_AgregarTabla '$nombre', '$descripcion', '$ayuda', '$tipotabla' ";
		$res = $this->ejecutarSP($sql);
		if($res==0){
			return "Guardado correctamente";
		}else{
			return $this->gError[2];
		}
 	}

	function actualizarTabla($id, $descripcion, $comentario, $multiple, $tipo, $deslis='', $desman = '')
 	{
   		$sql = "execute up_ModificarTabla $id, '".$this->mill($descripcion)."', '".$this->mill($comentario)."', '$multiple', '$tipo', '$deslis', '$desman' ";
		$res = $this->ejecutarSP($sql);
		if($res==0){
			return "Guardado correctamente";
		}else{
			return $this->gError[2];
		}
 	}

	function eliminarTabla($id)
 	{
   		$sql = "execute up_EliminarTabla $id";
		$res = $this->ejecutarSP($sql);
		if($res==0){
			return "Guardado correctamente";
		}else{
			return $this->gError[2];
		}
 	}
 
	function consultarTabla($nro_reg, $nro_hoja, $order, $by, $id, $descripcion)
 	{
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarTabla ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$this->mill($descripcion)."'";
			return $this->obtenerDataSP($sql);
		}else{
			if($by==1){
				$by="ASC";
			}else{
				$by="DESC";
			}
			$descripcion = "%".$descripcion."%";
			
			$sql = "SELECT Tabla.IdTabla, Tabla.Descripcion, Tabla.Comentario, Tabla.Multiple, Tabla.Tipo, Tabla.DescripcionList, Tabla.DescripcionMant
			FROM Tabla WHERE 1=1 ";
			$sql = $sql . " AND Tabla.Estado LIKE 'N' ";
			if($id>0){ $sql = $sql . " AND Tabla.IdTabla = " . $id;}
			if($descripcion <>"" ){$sql = $sql . " AND Tabla.Descripcion LIKE '" . $descripcion . "'";}
			
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
	
	function listarTabla($id, $descripcion)
 	{
		if(parent::getTipoBD()==1){
			$descripcion = "%".$descripcion."%";
			$sql = "execute up_BuscarTabla ".$nro_reg.", $nro_hoja, '$order', $by, $id, '".$descripcion."'";
			return $this->obtenerDataSP($sql);
		}else{
			if($by==1){
				$by="ASC";
			}else{
				$by="DESC";
			}
			$descripcion = "%".$descripcion."%";
			
			$sql = "SELECT idtabla, nombre, descripcion, ayuda, tipotabla, estado FROM tabla WHERE 1=1 ";
			$sql = $sql . " AND estado LIKE 'N' ";
			if($id>0){ $sql = $sql . " AND idtabla = " . $id;}
			if($descripcion <>"" ){$sql = $sql . " AND descripcion LIKE '" . $descripcion . "'";}
			
			return $this->obtenerDataSQL($sql);
		} 	 	
 	}
}
?>