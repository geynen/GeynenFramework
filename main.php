<!DOCTYPE HTML>
<html lang="es">
<head>	
    <meta charset="utf-8" />
    <title>Main</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
    <style>
    .ui-menu {
        width: 200px;
    }
    </style>
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="lib/js/fun.js"></script>
    <script>
    function setRun(idview,action,argument,div){
		$.ajax({
		  url: "vista/"+action+".php?idview="+idview+"&"+argument,
		  success: function(data) {
			$('#'+div).html(data);
			$( "button" ).button();
			//alert('Load was performed.');
		  }
		});	
	}
	</script>
</head>

<body>
<div id="menu-left" style="display:inline-block; vertical-align:top">
<ul id="menu">
<li><a href="#" onClick="javascript: setRun(1,'listTabla','','content');">Tablas</a></li>
<li><a href="#" onClick="javascript: setRun(3,'listCampo','','content');">Campos</a></li>
<li><a href="#" onClick="javascript: setRun(1,'listGeneral','','content');">Tablas</a></li>
<li><a href="#" onClick="javascript: setRun(5,'listGeneral','','content');">Vista</a></li>
</ul>
</div>
<div id="content" style="display:inline-block"></div>
<script>
$( "#menu" ).menu();
</script>
</body>
</html>