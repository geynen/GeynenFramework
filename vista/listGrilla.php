<?php
//Nombre y Codigo de la Clase a Ejecutar
$clase = $_POST["clase"];
$id_clase = $_POST["id_clase"];
$funcionlist = $_POST["funcionlist"];
if(!isset($funcionlist)) $funcionlist='';
if(isset($_POST['funcion'])){
	$funcion=$_POST['funcion'];
}else{
	$funcion='';
}
if(isset($_POST['funcionreporte'])){
	$funcionreporte=$_POST['funcionreporte'];
}else{
	$funcionreporte='';
}
if(isset($_POST['imprimir'])){
	$imprimir=$_POST['imprimir'];
}else{
	$imprimir='NO';
}
if(isset($_POST['tiporeporte'])){
	$tiporeporte=$_POST['tiporeporte'];
}else{
	$tiporeporte='Dinamico';
}
if(isset($_POST['titulo'])){
	$titulo=$_POST['titulo'];
}else{
	$titulo='';
}
if(isset($_POST['subtitulo'])){
	$subtitulo=$_POST['subtitulo'];
}else{
	$subtitulo='';
}
if(isset($_POST['origen'])){
	$origen=$_POST['origen'];
}else{
	$origen='';
}
if(isset($_POST['fechainicio'])){
	$fechainicio=$_POST['fechainicio'];
}else{
	$fechainicio='';
}
if(isset($_POST['fechafin'])){
	$fechafin=$_POST['fechafin'];
}else{
	$fechafin='';
}
$altoicono = $_POST["altoicono"];
if(!isset($altoicono)) $altoicono=16;

//Requiere para Ejecutar Clase
eval("require(\"../modelo/cls".$clase.".php\");");

//Nro de Hoja a mostrar en la Grilla
$nro_hoja = $_POST["nro_hoja"];
if(!$nro_hoja){//Si no se envia muestra Hoja Nro 1
	$nro_hoja = 1;
}
//Nro de Registros a mostrar en la Grilla
$nro_reg = $_POST["nro_reg"];
if($nro_reg==0){
$nro_reg = $_SESSION["NroFilaMostrar"];
}

//Para el Filtro
$filtro_str = utf8_encode($_POST["filtro"]);
$filtro = str_replace("\'", "'", $filtro_str);
if(!$filtro){//Si esta vacio cierra busqueda
	$filtro = ");";
}else{//Agrega filtro y cierra busqueda
	$filtro = ", ".$filtro.");";
}
?>
<HTML>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf8">
</head>
<body>
<?php
//Instancia la Clase
eval("\$objGrilla = new cls".$clase."(".$id_clase.", ".$_SESSION['IdCliente'].",\"".$_SESSION['NombreUsuario']."\",\"".$_SESSION['Clave']."\");");
//Para ver que es lo que consulta
//echo "\$rst = \$objGrilla->consultar".$clase."(".$nro_reg.",".$nro_hoja.$filtro;

//>>Inicio Obtiene Operaciones
$rstOperaciones = $objGrilla->obtenerOperacionesTipo('C');
if(is_string($rstOperaciones)){
	echo "<td colspan=100>Error al obtener Operaciones</td></tr><tr><td colspan=100>".$rstOperaciones."</td>";
	echo "</tr></table>";
	exit();
}
$datoOperaciones = $rstOperaciones->fetchAll();
$nro_ope = count($datoOperaciones);
//<<Fin

//>>Inicio Obtiene Alertas
$rstAlertas = $objGrilla->obtenerAlertas();
if(is_string($rstAlertas)){
	echo "<td colspan=100>Error al obtener Alertas</td></tr><tr><td colspan=100>".$rstAlertas."</td>";
	echo "</tr></table>";
	exit();
}
$datoAlertas = $rstAlertas->fetchAll();
//<<Fin

//>>Inicio Obtiene Operaciones para varios Registros
$rstOperacionesMultiple = $objGrilla->obtenerOperacionesMultiple();
if(is_string($rstOperacionesMultiple)){
	echo "<td colspan=100>Error al obtener Operaciones</td></tr><tr><td colspan=100>".$rstOperacionesMultiple."</td>";
	echo "</tr></table>";
	exit();
}
$datoOperacionesMultiple = $rstOperacionesMultiple->fetchAll();
$con_check=count($datoOperacionesMultiple);

?>
<?php /*?><?php
if($con_check>0){
	?>
<select id="accionmultiple" name="accionmultiple" onChange="accionMultiple(this.value)">
  <option value="0">Seleccione una Operaci&oacute;n</option>
  <?php
	reset($datoOperacionesMultiple);
	foreach($datoOperacionesMultiple as $operacionMultiple){
	?>
  <option value="<?php echo umill($operacionMultiple["accion"]);?>();"><?php echo umill($operacionMultiple["descripcion"]);?></option>
  <?php
	}
	?>
</select>
<?php
}
?><?php */?>
<div class="blur" style="">
<div class="shadow"><div class="contentGlobal">
<div style="overflow-x: hidden; overflow-y: hidden;">
<table id="miGrillaGeneral" class="tablaLista">
<tr>
<?php
if($con_check>0){
?>
<th><label><input type="checkbox" name="chkTodos" onClick="chekearTodo(this.checked)"></label></th>
<?php
}
//<<Fin
//>>Inicio Obtiene Campos a mostrar
$rstCampos = $objGrilla->obtenerCamposMostrar("G");
if(is_string($rstCampos)){
	echo "<td colspan=100>Error al obtener campos a mostrar</td></tr><tr><td colspan=100>".$rstCampos."</td>";
	echo "</tr></table>";
	exit();
}
$dataCampos = $rstCampos->fetchAll();
$nro_cam=count($dataCampos);
//$nro_td = $nro_cam + $nro_ope;
$totalancho=0;
foreach($dataCampos as $value){
	$with = "";
		if($value['ancho']){
			$with = "width = '".$value['ancho']."px' style='max-width::".$value['ancho']."'";		
		}
		$alin = "";
		if($value['alineacion']){
			$alin = "style='text-align:".$value['alineacion']."'";
		}
		if($value['ancho']>0){
		$totalancho  = $totalancho + $value['ancho'];
		}
?>
<th <?php echo $with;?>  <?php echo $alin;?> title="Ordenar por <?php echo umill($value['diccionario'])?> (<?php if($_POST["by"]=="1"){echo "DESC";}else{echo "ASC";}?>)" onClick="javascript:ordenar('<?php echo umill($value['descripcion']);?>');"><a href="#carga"><?php echo umill($value['comentario'])?></a></th>
<?php 
}
if($totalancho==0){
	$ponerrestoancho="";
}else{
	$poner = 810 - $nro_ope*16 - $totalancho;
	$ponerrestoancho = "width = '".$poner."px'" ;
}
//<<Fin
?>
<th colspan="<?php echo $nro_ope;?>" class="ocultaBordeLateral"></th><th <?php echo $ponerrestoancho;?>></th>
</tr>
<?php
//>>Inicio Ejecutando la consulta
//echo "\$rst = \$objGrilla->consultar".$clase.$funcion."(".$nro_reg.",".$nro_hoja.$filtro;
eval("\$rst = \$objGrilla->consultar".$clase.$funcion."(".$nro_reg.",".$nro_hoja.$filtro);
if(is_string($rst)){
	echo "<td colspan=100>Error al ejecutar consulta</td></tr><tr><td colspan=100>".$rst."</td>";
	echo "</tr></table>";
	exit();
}
$nro_registros_total=0;
$odd=0;
while($dato = $rst->fetch()){
	$nro_registros_total = $dato["nrototal"];
	
	$color="";
	$msjalerta="";
	reset($datoAlertas);
	$res=0;
	foreach($datoAlertas as $alerta){
		$res=0;
		if($alerta["codigoevaluar"]!=''){
			eval("\$res = (".$alerta["codigoevaluar"].") ? 1 : 0;");
		}else{
			$res=0;
		}
		if($res==1){
			$color = 'bgcolor="'.$alerta["color"].'"';
			$msjalerta = $alerta["descripcion"];
			break 1;
		}
	}
	
	reset($dataCampos);
	$oddponer=$odd;
?>
<tr title="<?php echo $msjalerta;?>" <?php echo $color;?> id="tr<?php echo $oddponer."_".$dato[1];?>" class="<?php if($res==1){echo "";}else{if($odd==0){echo "odd";$odd=1;}else{ echo "even";$odd=0;}}?>">

<?php
if($con_check>0){
?>
<td><input onClick="javascript:vObj=this.parentNode.parentNode;if(this.checked){document.getElementById(vObj.id).className = 'sombra';}else{vDato=vObj.id.substr(2,1);if(vDato=='0'){document.getElementById(vObj.id).className='odd';}else{document.getElementById(vObj.id).className='even';}}" type="checkbox" name="chkSeleccion[]" id="chkSeleccion[]" value="<?php echo $dato[1];?>"></td>
<?php
}
	foreach($dataCampos as $value){
		$with = "";
		if($value['ancho']){
			$with = "width = ".$value['ancho']."px style='max-width::".$value['ancho']."'";
		}
		$alin = "";
		if($value['alineacion']){
			$alin = "style='text-align:".$value['alineacion']."'";
		}
if($value['idtipocontrol']==7){
?>
		<td ><img src="<?php echo $dato[strtolower($value['descripcion'])]?>"   height="50" /></td>
<?php
		}else{
?>
		<td <?php echo $with;?>  <?php echo $alin;?>><?php echo umill($dato[strtolower($value['descripcion'])])?></td>
<?php 
		}
?>
<?php
	}
	reset($datoOperaciones);
	$nro_ope=0;
	foreach($datoOperaciones as $operacion){
		if($operacion["tipo"]=="C"){
			$res=1;
			if(!$operacion["codigoevaluar"] or $operacion["codigoevaluar"]==''){
				$res=1;
			}else{
				eval("\$res = (".$operacion["codigoevaluar"].") ? 1 : 0;");
			}
			if($res==1){
		?>
<td class="ocultaBordeLateral" width="<?php echo $altoicono;?>px" style="max-width:<?php echo $altoicono;?>px"><a href="#frm" title="<?php echo umill($operacion["comentario"]);?>" onClick="javascript:<?php echo umill($operacion["accion"]);?>('<?php echo $dato[1];?>');"><?php if($operacion["imagen"]==""){echo umill($operacion["descripcion"]);}else{echo "<img src='img/".$operacion['imagen']."' height=".$altoicono." border=0 />";}?></a></td>
		<?php
				$nro_ope = $nro_ope + 1;
			}else{
			?>
			<td class="ocultaBordeLateral"></td>			
			<?php
			}
		}
	}
?>
<td <?php echo $ponerrestoancho;?>></td>
</tr>
<tr id="trfila<?php echo $dato[1];?>" class="oculta"><td colspan="<?php echo ($nro_cam+$nro_ope+1);?>" id="tdfila<?php echo $dato[1];?>"></td></tr>
<?php
}
if($nro_registros_total==0){
	echo "<tr><td colspan=100 height='250px' align=center><center>Sin Informaci&oacute;n</center></td></tr>";
}
?>
</table>
</div>
<div style="width:810px" >
<?php
if($nro_reg==0){
$nro_reg = $nro_registros_total;
}
if($nro_registros_total>0){
if($nro_registros_total % $nro_reg == 0){
	$nro_hojas = (int)($nro_registros_total/$nro_reg);
}else{
	$nro_hojas = (int)($nro_registros_total/$nro_reg) + 1;
}
}
if($nro_hojas<$nro_hoja){
	$nro_hoja=1;
}
if($nro_hoja==$nro_hojas){
	$mostrar = $nro_registros_total % $nro_reg;
}else{
	if($nro_registros_total==0){
		$mostrar=0;
	}else{
		$mostrar  = $nro_reg;
	}
}
$inicio=($nro_hoja - 1)*$nro_reg + 1;
if($nro_reg==$nro_registros_total){
$fin=$nro_registros_total;
$mostrar=$nro_registros_total;	
}else{
$fin=($nro_hoja - 1)*$nro_reg + $mostrar;
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>
<table class="tablaPaginacion">
<tr>
<?php
$ini = "<td class='paginacion' width=24px onClick=\"buscarGrilla".$funcionlist."(";
$medio=")\"><a href=\"javascript:;\">";
if($nro_hojas>11){
	for($i=1;$i<=3;$i++){
		if($nro_hoja!=$i){echo $ini.$i.$medio.$i."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
	}
	if($nro_hojas % 2 == 0){
		$mitad = (int)($nro_hojas/2);
	}else{
		$mitad = (int)($nro_hojas/2) + 1;
	}
	if($nro_hoja>3 && $nro_hoja <= $nro_hojas-3){
		if($nro_hoja > 6 && $nro_hoja < $nro_hojas - 5){
			if($nro_hoja!=4){echo $ini.'4'.$medio."-></a></td>";}else{ echo "<td class='paginacionOn' width=24px>-></td>";}
			for($i=$nro_hoja-2;$i<$nro_hoja;$i++){
				if($nro_hoja!=$i){echo $ini.$i.$medio.$i."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
			}	
			for($i=$nro_hoja;$i<=$nro_hoja+2;$i++){
				if($nro_hoja!=$i){echo $ini.$i.$medio.$i."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
			}	
			if($nro_hoja!=($nro_hojas-3)){echo $ini.($nro_hojas-3).$medio."<-</a></td>";}else{ echo "<td class='paginacionOn' width=24px><-</td>";}
		}else{
			if($nro_hoja>=4 && $nro_hoja<=6){
				for($i=4;$i<=8;$i++){
					if($nro_hoja!=$i){echo $ini.$i.$medio.$i."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
				}
				if($nro_hoja!=($nro_hojas-3)){echo $ini.($nro_hojas-3).$medio."<-</a></td>";}else{ echo "<td class='paginacionOn' width=24px><-</td>";}
			}else{
				if($nro_hoja!=4){echo $ini.'4'.$medio."-></a></td>";}else{ echo "<td class='paginacionOn' width=24px>-></td>";}
				for($i=$nro_hojas-7;$i<=$nro_hojas-3;$i++){
					if($nro_hoja!=$i){echo $ini.$i.$medio.$i."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
				}
			}
		}
	}else{
		if($nro_hoja!=4){echo $ini.'4'.$medio."-></a></td>";}else{ echo "<td class='paginacionOn' width=24px>-></td>";}
		for($i=(int)$mitad-2;$i<=(int)$mitad+2;$i++){
			if($nro_hoja!=$i){echo $ini.$i.$medio.$i."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
		}
		if($nro_hoja!=($nro_hojas-3)){echo $ini.($nro_hojas-3).$medio."<-</a></td>";}else{ echo "<td class='paginacionOn' width=24px><-</td>";}
	}
	for($i=(int)$nro_hojas-2;$i<=(int)$nro_hojas;$i++){
		if($nro_hoja!=$i){echo $ini.$i.$medio.$i."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
	}
}else{
	for($i=1;$i<=$nro_hojas;$i++){
		if($nro_hoja!=$i){echo $ini.$i.$medio.$i.""."</a></td>";}else{ echo "<td class='paginacionOn' width=24px>".$i."</td>";}
	}
}
?>
<td><div id="cargando"></div></td>
</tr>
</table>
</td><td align="right">
<table><tr><td width="100%" align="right"><?php if($nro_registros_total==0){echo "No hay registros";}else{echo "Registros del $inicio al $fin (".$mostrar.") de ".$nro_registros_total;}?></td></tr></table>
</td></tr>
<?php if($imprimir=='SI'){?><tr><td align="center" valign="middle" colspan="2"><form id="frmDatosReporte<?php echo $clase;?>" method="post" target="_blank" action="vista/reportes/Reporte<?php echo $tiporeporte;?>.php">
<input id="txtOrigenREPORTE" name="txtOrigenREPORTE" type="hidden" value="<?php echo $origen;?>">
<input id="txtTituloREPORTE" name="txtTituloREPORTE" type="hidden" value="<?php echo $titulo;?>">
<input id="txtSubTituloREPORTE" name="txtSubTituloREPORTE" type="hidden" value="<?php echo $subtitulo;?>">
<input id="txtClaseREPORTE" name="txtClaseREPORTE" type="hidden" value="<?php echo $clase;?>">
<input id="txtIdClaseREPORTE" name="txtIdClaseREPORTE" type="hidden" value="<?php echo $id_clase;?>">
<input id="txtFuncionREPORTE" name="txtFuncionREPORTE" type="hidden" value="<?php echo $funcionreporte;?>">
<input id="txtFiltroREPORTE" name="txtFiltroREPORTE" type="hidden" value="<?php echo $filtro;?>">
<input id="txtNroRegistrosTotalREPORTE" name="txtNroRegistrosTotalREPORTE" type="hidden" value="<?php echo $nro_registros_total;?>">
<input id="txtNroHojaREPORTE" name="txtNroHojaREPORTE" type="hidden" value="<?php echo $nro_hoja;?>">
<input id="txtFechaInicioREPORTE" name="txtFechaInicioREPORTE" type="hidden" value="<?php echo $fechainicio;?>">
<input id="txtFechaFinREPORTE" name="txtFechaFinREPORTE" type="hidden" value="<?php echo $fechafin;?>">
<a href="#" onClick="javascript: document.getElementById('frmDatosReporte<?php echo $clase;?>').submit();"><img src="img/print_f2.png" width="20" height="20" border="0">Imprimir</a>
</form></td></tr><?php }?>
</table>
</div>
</div></div></div>
</body>
</HTML>
<script>
function buscarGrilla<?php echo $funcionlist;?>(nro_hoja){
	if(document.getElementById("nro_hoj<?php echo $funcionlist;?>")){
		document.getElementById("nro_hoj<?php echo $funcionlist;?>").value = nro_hoja;
	}
		<?php
	if(isset($funcionlist) and $funcionlist!=''){
		echo "buscar".$funcionlist."();";
	}else{?>
		buscar();
	<?php }?>
}
</script>