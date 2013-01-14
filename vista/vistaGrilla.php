<?php
require("../modelo/clsGeneral.php");
$idvista = $_GET["idvista"];
try{
	$objVista = new clsGeneral($idvista);
}catch(PDOException $e) {
    echo '<script>alert("Error :\n'.$e->getMessage().'");history.go(-1);</script>';
	exit();
}
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

//OBTENGO FILTROS
$rstFiltro=$objVista->getCampos($idvista,2);
while($datoFiltro=$rstFiltro->fetchObject()){
	if($_POST[$datoFiltro->nombre]!=''){
		$filtro[$datoFiltro->nombre]= array ('nombre' => $datoFiltro->nombre, 'valor' => $_POST[$datoFiltro->nombre], 'operador' => $datoFiltro->operador);
	}
}

//OBTENGO SENTENCIA SQL
$sqlVista=$objVista->getSQL($idvista,$filtro);
//echo $sqlVista;

//OBTENGO CAMPOS A MOSTRAR
$rstCampos = $objVista->getCampos($idvista,1);
if(is_string($rstCampos)){
	echo "<td colspan=100>Error al obtener campos a mostrar</td></tr><tr><td colspan=100>".$rstCampos."</td>";
	echo "</tr></table>";
	exit();
}
$dataCampos = $rstCampos->fetchAll();

//OBTENGO OPERACIONES
$rstOperaciones = $objVista->getOperaciones($idvista,1);
if(is_string($rstOperaciones)){
	echo "<td colspan=100>Error al obtener Operaciones</td></tr><tr><td colspan=100>".$rstOperaciones."</td>";
	echo "</tr></table>";
	exit();
}
$datoOperaciones = $rstOperaciones->fetchAll();
$nro_ope = count($datoOperaciones);
?>
<table id="miGrillaGeneral" class="jtable">
<tr>
<?php
foreach($dataCampos as $value){
	?>
	<th title="Ordenar por <?php echo $value['ayuda']?> (<?php if($_POST["by"]=="1"){echo "DESC";}else{echo "ASC";}?>)" onClick="javascript:ordenar('<?php echo $value['nombre'];?>');"><a href="#"><?php echo $value['etiqueta']?></a></th>
	<?php 
}
if($nro_ope>0){
?>
<th colspan="<?php echo $nro_ope;?>">Operaciones</th>
<?php }?>
</tr>
<?php
//OBTENGO DATOS
$rstVista=$objVista->getDataPaginacion(100,$nro_hoja,1,1,$sqlVista);
if(is_string($rstVista)){
	echo "<tr><td colspan=100>Error al ejecutar consulta</td></tr><tr><td colspan=100>".$rst."</td>";
	echo "</tr></table>";
	exit();
}
reset($dataCampos);
$nro_registros_total=0;
$odd=0;
while($dato = $rstVista->fetch()){
	$nro_registros_total = $dato["nrototal"];
	?>
	<tr title="<?php echo $msjalerta;?>" <?php echo $color;?> id="tr<?php echo $oddponer."_".$dato[1];?>" class="<?php if($res==1){echo "";}else{if($odd==0){echo "odd";$odd=1;}else{ echo "even";$odd=0;}}?>">
    
	<?php
	foreach($dataCampos as $value){
		if($value['idtipocontrol']==7){
		?>
				<td ><img src="<?php echo $dato[strtolower($value['nombre'])]?>"   height="50" /></td>
		<?php
				}else{
		?>
				<td <?php echo $with;?>  <?php echo $alin;?>><?php echo $dato[strtolower($value['nombre'])]?></td>
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
				$argumentos=explode(',',$operacion["argumentos"]);
				$arg="";
				foreach($argumentos as $indice => $value){
					$arg.="'".$dato[$value]."',";
				}
				$arg=substr($arg,0,-1);
		?>
<td class="ocultaBordeLateral" width="<?php echo $altoicono;?>px" style="max-width:<?php echo $altoicono;?>px"><a href="#frm" title="<?php echo $operacion["ayuda"];?>" onClick="javascript:<?php echo $operacion["nombre"].'_'.$idvista;?>(<?php echo $operacion["idvista_atributo"].",".$arg;?>);"><?php if($operacion["icono"]==""){echo $operacion["etiqueta"];}else{echo "<img src='img/".$operacion['icono']."' height=".$altoicono." border=0 />";}?></a></td>
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
</tr>
<?php
}
if($nro_registros_total==0){
	echo "<tr><td colspan=100 height='250px' align=center><center>Sin Informaci&oacute;n</center></td></tr>";
}
?>
</table>
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
</tr>
</table>
</td><td align="right">
<table><tr><td width="100%" align="right"><?php if($nro_registros_total==0){echo "No hay registros";}else{echo "Registros del $inicio al $fin (".$mostrar.") de ".$nro_registros_total;}?></td></tr></table>
</td></tr>
</table>
<script>
$().ready(function(){ 
 $(".jtable th").each(function(){
 
  $(this).addClass("ui-state-default");
 
  });
 $(".jtable td").each(function(){
 
  $(this).addClass("ui-widget-content");
 
  });
 $(".jtable tr").hover(
     function()
     {
      $(this).children("td").addClass("ui-state-hover");
     },
     function()
     {
      $(this).children("td").removeClass("ui-state-hover");
     }
    );
 $(".jtable tr").click(function(){
   
   $(this).children("td").toggleClass("ui-state-highlight");
  });
 
}); 
</script>