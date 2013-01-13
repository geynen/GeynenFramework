<?php
require("../modelo/clsTabla.php");
$idvista = $_GET["idvista"];
//echo $idvista;
try{
$objMantenimiento = new clsTabla($idvista);
}catch(PDOException $e) {
    echo '<script>alert("Error :\n'.$e->getMessage().'");history.go(-1);</script>';
	exit();
}
?>
<div class="title">Tabla</div>
<button onClick="javascript: setRun(2,'mantTabla','','content');">Nuevo</button>
<?php
$rst=$objMantenimiento->listarTabla(0,'');
print_r($rst->fetchAll());
?>
