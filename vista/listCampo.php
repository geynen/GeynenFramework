<?php
require("../modelo/clsCampo.php");
$idvista = $_GET["idvista"];
//echo $idvista;
try{
$objMantenimiento = new clsCampo($idvista);
}catch(PDOException $e) {
    echo '<script>alert("Error :\n'.$e->getMessage().'");history.go(-1);</script>';
	exit();
}
?>
<div class="title">Campo</div>
<button onClick="javascript: setRun(2,'mantCampo','','content');">Nuevo</button>
<?php
$rst=$objMantenimiento->listarCampo(0,'');
print_r($rst->fetchAll());
?>