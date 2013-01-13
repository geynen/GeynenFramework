function getFormData(objf) {
	var formObj =document.getElementById(objf);
	var elementos = document.getElementById(objf).elements.length;
	var dataForm = "";
	for (var i=0;i<elementos;i++){
		if (formObj.elements[i].type != undefined && formObj.elements[i].name != undefined){
			switch (formObj.elements[i].type)
			{
			case "checkbox":
				if(formObj.elements[i].checked==true){
					dataForm+=formObj.elements[i].name + ": S,";
			  		//g_ajaxGrabar.setParameter(formObj.elements[i].name, "S");
				}else{
					dataForm+=formObj.elements[i].name + ": N,";
					//g_ajaxGrabar.setParameter(formObj.elements[i].name, "N");
				}
			  	break;
			case "radio":
				var radios = document.getElementsByName(formObj.elements[i].name);
				var cant = radios.length;
				for (var j=0;j<cant;j++){
					var myOpt = radios[j];
					if(myOpt.checked){
						dataForm+=formObj.elements[i].name + ":" + myOpt.value + ",";
						//g_ajaxGrabar.setParameter(formObj.elements[i].name,myOpt.value);
					}
				}
			  	break;
			case "select-one":
				if(formObj.elements[i].name.substr(3,3)=="Mul"){
					var optiones = document.getElementById(formObj.elements[i].name);
					var cant = optiones.length;
					var Lista= new Array();
					var k=0;
					for (var j=0;j<cant;j++){
						var myOpt = optiones[j];
						Lista[k] = myOpt.value;
						k++;
					}
					dataForm+=formObj.elements[i].name+"[]" + ":" + Lista + ",";
					//g_ajaxGrabar.setParameter(formObj.elements[i].name+"[]", Lista);
				}else{
					dataForm+=formObj.elements[i].name + ":'" + formObj.elements[i].value + "',";
					//g_ajaxGrabar.setParameter(formObj.elements[i].name, formObj.elements[i].value);
				}
			  	break;
			case "select-multiple":
				var optiones = document.getElementById(formObj.elements[i].name);
				var cant = optiones.length;
				var Lista= new Array();
				var k=0;
				for (var j=0;j<cant;j++){
					var myOpt = optiones[j];
					if(myOpt.selected){
						Lista[k] = myOpt.value;
						k++;
					}
				}
				dataForm+=formObj.elements[i].name+"[]" + ":" + Lista + ",";
				//g_ajaxGrabar.setParameter(formObj.elements[i].name+"[]", Lista);
			  	break;
			default:
				vName = formObj.elements[i].name;
				vFin = vName.substr(vName.length-2,2);
				if(vFin=="[]"){
					var textos = document.getElementsByName(formObj.elements[i].name);
					var cant = textos.length;
					var valores= new Array();
					for (var j=0;j<cant;j++){
						var myTxt = textos[j];
						valores[j]=myTxt.value;
					}
					dataForm+=formObj.elements[i].name + ":" + valores + ",";
					//g_ajaxGrabar.setParameter(formObj.elements[i].name, valores);
				}else{
					dataForm+=formObj.elements[i].name + ":'" + formObj.elements[i].value + "',";
					//g_ajaxGrabar.setParameter(formObj.elements[i].name, formObj.elements[i].value);
				}
			  
			}
		}
	}
	return dataForm.substr(0,dataForm.length-5);
}

function setValidar(objf) {
	var formComplete=true;
	var iSet = 0
	var formObj =document.getElementById(objf);
	var elementos = document.getElementById(objf).elements.length;
	for (var i=0;i<elementos && formComplete == true;i++){
		//alert(formObj.elements[i].name);
		//alert(formObj.elements[i].tabindex);
		if(formObj.elements[i].type!="button"){
			var elemValLength = formObj.elements[i].value;

			switch (formObj.elements[i].type)
			{
			case "select-one":
				if (formObj.elements[i].title != null && formObj.elements[i].title != "") {
					
					if(elemValLength==0 | elemValLength=='0'){
						alertMsg = formObj.elements[i].title;
						iSet = i;
						formComplete = false;
						break;
					}else{
						if(validacionForm(objf,formObj.elements[i].name)){	
							iSet = i;
							formComplete = false;
							break;
						}
					}
				}
				//break;
			case "select":
				if (formObj.elements[i].title != null && formObj.elements[i].title != "") {
					
					if(elemValLength==0 | elemValLength=='0'){
						alertMsg = formObj.elements[i].title;
						iSet = i;
						formComplete = false;
						break;
					}else{
						if(validacionForm(objf,formObj.elements[i].name)){	
							iSet = i;
							formComplete = false;
							break;
						}
					}
				}
				//break;
			case "select-multiple":
				if (formObj.elements[i].title != null && formObj.elements[i].title != "") {
					
					if(elemValLength==0 | elemValLength=='0'){
						alertMsg = formObj.elements[i].title;
						iSet = i;
						formComplete = false;
						break;
					}else{
						if(validacionForm(objf,formObj.elements[i].name)){	
							iSet = i;
							formComplete = false;
							break;
						}
					}
				}
				//break;
			default:
			  if (formObj.elements[i].title != null && formObj.elements[i].title != "") {
					
					if(elemValLength.length < 1 ){
						alertMsg = formObj.elements[i].title;
						iSet = i;
						formComplete = false;
						break;
					}else{
						if(validacionForm(objf,formObj.elements[i].name)){
							iSet = i;
							formComplete = false;
							break;
						}
					}
				}
			}
		}
	}
	if (!formComplete){
		alert(alertMsg);
		if(formObj.elements[iSet].parent){
			formObj.elements[iSet].parent.focus();
		}
		//alert(formObj.elements[iSet].id);
		//document.getElementById(formObj.elements[iSet].id).focus();
		formObj.elements[iSet].focus();	
			
		return false;

	} else {
		return true;
	}
}

function setValidarNew(objf) {
//	jQuery(function($){
// simple jQuery validation script
	//$('#login').submit(function(){
		var valid = true;
		var errormsg = 'Este campo es requerido!';
		var errorcn = 'error';
		var colorborde = '#2473CF';
		
		$('.' + errorcn, $('#'+objf)).remove();			
		
		$('.required', $('#'+objf)).each(function(){
			if( $.trim($(this).val()) == '' || ($(this).val()<=0 && $(this).is('select'))){
				var parent = $(this).parent();
				if(parent.is('span')==false) $(this).wrap('<span class="span" style="padding:5px 0px; border:1px solid '+colorborde+';">');
				var parent = $(this).parent();
				parent.css('border','1px solid '+colorborde+'');
				parent.css('position', 'relative');
				if($(this).is('select')){
					parent.click(function(){ $('.' + errorcn, $(this)).remove();$(this).css('border','0'); });
				}else{
					parent.keypress(function(){ $('.' + errorcn, $(this)).remove();$(this).css('border','0'); });
				}
				
				var msg = $(this).attr('title');
				msg = (msg != '') ? msg : errormsg;
				$('<span class="'+ errorcn +'">'+ msg +'</span>')
					.appendTo(parent)
					.fadeIn('fast')
					.click(function(){ $(this).remove(); })
				valid = false;
			};
		});
		
		$('.mail', $('#'+objf)).each(function(){
			if(valEmail($(this).val())==false){
				var parent = $(this).parent();
				if(parent.is('span')==false) $(this).wrap('<span class="span" style="padding:5px 0px; border:1px solid '+colorborde+';">');
				var parent = $(this).parent();
				parent.css('border','1px solid '+colorborde+'');
				parent.css('position', 'relative');
				parent.keypress(function(){ $('.' + errorcn, $(this)).remove();$(this).css('border','0'); });
				
				var msg = $(this).attr('title');
				msg = (msg != '') ? msg : errormsg;
				$('<span class="'+ errorcn +'">'+ msg +'</span>')
					.appendTo(parent)
					.fadeIn('fast')
					.click(function(){ $(this).remove(); })
				valid = false;
			};
		});
		return valid;
	//});
//	})	
}

function accionMultiple(accion){
	eval(accion);
}

function chekearTodo(valor){
	var radios = document.getElementsByName("chkSeleccion[]");
	var cant = radios.length;
	for (var j=0;j<cant;j++){
		var myOpt = radios[j];
		myOpt.checked = valor;
		vObj=myOpt.parentNode.parentNode;
		if(myOpt.checked){
			document.getElementById(vObj.id).className = 'sombra';
		}else{
			vDato=vObj.id.substr(2,1);
			if(vDato=='0'){
				document.getElementById(vObj.id).className='odd';
			}else{
				document.getElementById(vObj.id).className='even';
			}
		}
	}
}

function chekearTodoArbol(valor){
	var radios = document.getElementsByName("chkSeleccion[]");
	var cant = radios.length;
	for (var j=0;j<cant;j++){
		var myOpt = radios[j];
		myOpt.checked = valor;
		vObj=myOpt.parentNode.parentNode;
		if(valor){
			vDato=vObj.id.substr(6,1);
			if(vDato=='0'){
				vObj.className='muestra odd';
			}else{
				vObj.className='muestra even';
			}
		}else{
			vDato=vObj.id.substr(6,1);
			if(vDato=='0'){
				vObj.className='oculta odd';
			}else{
				vObj.className='oculta even';
			}
		}
	}
}

function operacionmultiple(control, accion, idclase, opeclase){
	var radios = document.getElementsByName("chkSeleccion[]");
	var cant = radios.length;
	var Lista= new Array();
	//alert(cant);
	var k=0;
	for (var j=0;j<cant;j++){
		var myOpt = radios[j];
		if(myOpt.checked){
			Lista[k] = myOpt.value;
			k++;
			//alert(myOpt.value);
		}
	}
	//if(setValidarMultiple()){
		g_ajaxGrabar.setURL("controlador/" + control + ".php?ajax=true");
		g_ajaxGrabar.setRequestMethod("POST");
		g_ajaxGrabar.setParameter("accion", accion);
		g_ajaxGrabar.setParameter("operacion", opeclase);
		g_ajaxGrabar.setParameter("clase", idclase);		
		g_ajaxGrabar.setParameter("check[]", Lista );      	
		g_ajaxGrabar.response = function(text){
			eval(text);
			loading(false, "loading");		
			if(vError == 0){
				buscar();
			}else{
				alert(vMsg);
			}
		};
		g_ajaxGrabar.request();
		loading(true, "loading", "grilla", "linea.gif",true);
	//}
	
}

function operacionmultipletodo(control, accion, idclase, opeclase){
	var radios = document.getElementsByName("chkSeleccion[]");
	var cant = radios.length;
	var Lista= new Array();
	//alert(cant);
	var k=0;
	for (var j=0;j<cant;j++){
		var myOpt = radios[j];
		if(myOpt.checked){
			vchk='1-';
		}else{
			vchk='0-';	
		}
		Lista[k] = vchk + myOpt.value;
		k++;
	}
	//if(setValidarMultiple()){
		g_ajaxGrabar.setURL("controlador/" + control + ".php?ajax=true");
		g_ajaxGrabar.setRequestMethod("POST");
		g_ajaxGrabar.setParameter("accion", accion);
		g_ajaxGrabar.setParameter("operacion", opeclase);
		g_ajaxGrabar.setParameter("clase", idclase);		
		g_ajaxGrabar.setParameter("check[]", Lista );      	
		g_ajaxGrabar.setParameter("todo", "SI" );      	
		g_ajaxGrabar.response = function(text){
			eval(text);
			loading(false, "loading");		
			if(vError == 0){
				buscar();
			}else{
				alert(vMsj);
			}		
		};
		g_ajaxGrabar.request();
		loading(true, "loading", "grilla", "linea.gif",true);
	//}
	
}

function cancelar(div){
	if(document.getElementById(div)){
		document.getElementById(div).getElementsByTagName('td')[0].innerHTML = "";
		document.getElementById(div).className = "oculta";
	}
	if(div.substr(0,9)=='cargamant' && document.getElementById(div)){
		document.getElementById(div).className = "oculta";
		document.getElementById(div).innerHTML = "";
	}
}

function mostrar(div){
	if(document.getElementById("cargamant")){
		document.getElementById("cargamant").className = "oculta";
		document.getElementById("cargamant").innerHTML = "";
	}
	if(document.getElementById('miGrillaGeneral')){
		vFilas = document.getElementById('miGrillaGeneral').getElementsByTagName('tr');
		for(i=0;i<vFilas.length;i++){
			if(vFilas[i].id.substr(0,6) == 'trfila'){
				if(vFilas[i].id !=div){
					vFilas[i].getElementsByTagName('td')[0].innerHTML = "";
					vFilas[i].className = "oculta";
				}
			}
		}
	}
}

function chekearOpt(name, valor){
	var radios = document.getElementsByName(name);
	var cant = radios.length;
	for (var j=0;j<cant;j++){
		var myOpt = radios[j];
		if(myOpt.value == valor){
			myOpt.checked=true;
			return;
		}
	}	
}

function validarFormato(vValor){
	vText = vValor.value;
	var vCadena = vText.split('-');
	eval("vSerie = 1000 + " + vCadena[0] + ";")
	vSerie = String(vSerie).substr(1,3);
	eval("vNumero = 1000000 + " + vCadena[1] + ";")
	vNumero = String(vNumero).substr(1,6);
	vValor.value = 	vSerie  + "-" + vNumero;
}

function valEmail(valor){
	if(valor=='') return true;
	re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/
	if(!re.exec(valor))    {
	 return false;
	}else{
	 return true;
	}
}

function defecto(valor, defecto, quiero_null){
    quiero_null = quiero_null || false;   
    if (quiero_null){
        valor = (typeof valor == 'undefined') ? defecto : valor;
    }else{
        valor = valor || defecto;
    }   
    return valor;
}


function miAlert(msg,tipo,position){
	//top-left, top-center, top-right, middle-left, middle-center, middle-right
	switch(tipo){
		case 'N':
			//$().toastmessage('showNoticeToast', msg);
			type='notice';
			break;
		case 'S':
			//$().toastmessage('showSuccessToast', msg);
			type='success';
			break;
		case 'W':
			//$().toastmessage('showWarningToast', msg);
			type='warning';
			break;
		case 'E':
			//$().toastmessage('showErrorToast', msg);
			type='error';
			break;
		default:
			//$().toastmessage('showNoticeToast', msg);
			type='notice';
	}
	position=defecto(position,'button-right',true);
	$().toastmessage('showToast', {
		text     : msg,
		sticky   : false,
		position : position,
		type     : type
	});
}

function validarsololetras(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[A-Za-z\s]/; // 4
    //patron =/\d/;
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

function validarsolonumeros(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    //patron =/[A-Za-z\s]/; // 4
    patron =/\d/;
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

function validarsololetrasynumeros(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[A-Za-z\d\s]/; // 4
    //patron =/\d/;
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

function validarsolonumerosdecimales(e,valor) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron = /[0-9\,\.]/;
    te = String.fromCharCode(tecla); // 5
    r = patron.test(te); // 6
	if(r){
		formato = /^[0-9]*(\.[0-9]{0,2})?$/;
		if(!formato.exec(valor+te))    {
		 return false;
		}else{
		 return true;
		}
	}else{
		return false;
	}
	
}

function validarsolonumerosdecimales4(e,valor) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron = /[0-9\,\.]/;
    te = String.fromCharCode(tecla); // 5
    r = patron.test(te); // 6
	if(r){
		formato = /^[0-9]*(\.[0-9]{0,4})?$/;
		if(!formato.exec(valor+te))    {
		 return false;
		}else{
		 return true;
		}
	}else{
		return false;
	}
	
}

function validarnumeroconserie(valor) {
    patron =/^(\d){3}-(\d){6}-(\d){4}/;
    if(!patron.exec(valor))    {
         return false;
     }else{
         return true;
     }
}

function valFecha(valor){
     re=/^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$/

     if(!re.exec(valor))    {
         return false;
     }else{
         return true;
     }
 }
 
function valHora(valor){
     re=/^(0[1-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d)$/
     if(!re.exec(valor))    {
         return false;
     }else{
         return true;
     }
 }
 
function valFechaHora(valor){
	 fecha=valor.substr(0,10);
	 separador=valor.substr(10,1);
	 hora=valor.substr(11,8);
    /*alert(fecha);
	alert(separador);
	alert(hora);
	alert(valFecha(fecha));*/
	if(separador==' '){
		 if(valFecha(fecha)){//alert("fecha correcta");
			 if(valHora(hora)){//alert("hora correcta");
				 return true;
			 }else{
				 return false;
			 }
		 }else{
			 //alert("fecha incorrecta");
			 return false;
		 }
	}else{
		return false;
	}	 
 }
 
//FUENTE: http://tunait.com/javascript/?s=mascara#codigo 
//var patron = new Array(2,2,4)
//var patron2 = new Array(1,3,3,3,3)
//onkeyup="mascara(this,'/',patron,true)" 
function mascara(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	if(val.substr(-1)==sep) return;
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}