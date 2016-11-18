<?php
session_start();

/* <!-- Optimizador de pagina --> */
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); 
	else ob_start(); 

include("access.php");
include("php_functions.php");

include("conn.php");
	//Obtengo todas las marcas de autos
	$queryMarcas = "SELECT * FROM marcas ORDER BY marca_titulo";
	$resultM = mysql_query($queryMarcas);
	
	//Obtengo todos los clientes
	//$queryClientes = "SELECT * FROM cliente ORDER BY apellido";
	//$resultC = mysql_query($queryClientes);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Servicios LubriExpress</title>
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="../themes/icon.css">
		<link rel="stylesheet" type="text/css" href="demo.css">
		<link rel="icon" href="img/le_icon.ico" type="image/x-icon" />
	<script type="text/javascript" src="../jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../jquery.easyui.min.js"></script>
	<script type="text/javascript" src="../funciones.js"></script>

	<script type="text/javascript" src="../js/accordion.js"></script>
	<script>

		
		$(function(){
		$('#win').window('close');
		$(document).bind('contextmenu',function(e){
				$('#mm').menu('show', {
					left: e.pageX,
					top: e.pageY
				});
				return false;
			});
			
		});
		
		function cerrarventana(){
		$('#win').window('close');
		}
		function editaauto(){
			var id_auto, patente, marca_id, modelo, motor;
			id_auto = document.forms["myForm"].id_auto.value;
			patente = document.forms["myForm"].patente1.value;
			marca_id = document.forms["myForm"].marca_id.value;
			modelo = document.forms["myForm"].modelo.value;
			motor = document.forms["myForm"].motor.value;
		
			if (id_auto){
				$('#dlgauto').dialog('open').dialog('setTitle','Editar Datos del Automotor');
				
				document.forms["fmauto"].patente11.value = patente;
				document.forms["fmauto"].marca11.value = marca_id;
				document.forms["fmauto"].modelo11.value = modelo;
				document.forms["fmauto"].motor11.value = motor;
				document.forms["fmauto"].id_auto11.value = id_auto;
			
			}
				
		};

		function editautoSubmit(){
		
				var xmlhttp;	
				var patente, id_auto, modelo, motor, marca_id;
				
				patente = document.forms["fmauto"].patente11.value;
				marca_id = document.forms["fmauto"].marca11.value;
				modelo = document.forms["fmauto"].modelo11.value;
				motor = document.forms["fmauto"].motor11.value;
				id_auto = document.forms["fmauto"].id_auto11.value;
						
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				xmlhttp.onreadystatechange=function()
				  {
				  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
					
					
						if(xmlhttp.responseText != "OK"){
							alert(xmlhttp.responseText);
						}else{	
							//alert("Datos del automotor fueron actualizados");
							//Tengo que cerrar el cuadro de dialogo
							$('#dlgauto').dialog('close');
						
							busca(patente, '');
					
						
						}
						
					}
				  }
				xmlhttp.open("GET","update_auto.php?id_auto11="+id_auto+"&patente11="+patente+"&marca11="+marca_id+"&modelo11="+modelo+"&motor11="+motor+'',true);
				xmlhttp.send();
	
		};
		
		
		
		//Transferir Autoooo
		// -----------------------------------------------------------------
		
		function transfiereauto(){
		
		var id_auto, patente;
		
		id_auto = document.forms["myForm"].id_auto.value;
		patente = document.forms["myForm"].patente1.value;
		
		if (id_auto){
		
				
				$('#dlgtxauto').dialog('open').dialog('setTitle','Transferir Automotor a otro Cliente');
				
				document.forms["fmtxauto"].patenteactual.value = patente;
				
			
		}
		
		
		
		
		
		};
		
		function txautoSubmit(){
		
		//Similar a editautoSubmit() 
		
		var xmlhttp;	
		var nuevo_cliente, anterior_cliente, id_auto, patente; 
		
		patente = document.forms["myForm"].patente1.value;
		nuevo_cliente = document.forms["fmtxauto"].nuevocliente11.value;
		anterior_cliente = document.forms["FormCliente"].id_cliente.value;

		id_auto = document.forms["myForm"].id_auto.value;
		
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
					if( xmlhttp.responseText == "OK"){
						$('#dlgtxauto').dialog('close');
						busca(patente);
						}else{
							alert(xmlhttp.responseText);
						}
				
			
				
			}
		  }
		xmlhttp.open("GET","transfer_auto.php?id_auto21="+id_auto+"&cliente_anterior="+anterior_cliente+"&cliente_nuevo="+nuevo_cliente+'',true);
		xmlhttp.send();
	
	
		};

		
				
		//Inactivar Autoooo
		// -----------------------------------------------------------------
		function BorraAuto(){
		
		//Similar a editautoSubmit() 
		
		var xmlhttp;	
		var id_auto, patente; 
		
		patente = document.forms["myForm"].patente1.value;
		id_auto = document.forms["myForm"].id_auto.value;
		
		if(confirm("Está seguro que desea desvincular al vehiculo: " + patente+" del cliente? \n Se mantendran los datos del auto.")){
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				
						alert(xmlhttp.responseText);
						window.location = "servicios.php";
				
				}
			  }
			xmlhttp.open("GET","delete_auto.php?id_auto31="+id_auto+'',true);
			xmlhttp.send();
		
		}
	
		};
		
		function borrarservicio(servicio){
		
		if(confirm("Está seguro que desea eliminar permanentemente este servicio?")){
		if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
						alert(xmlhttp.responseText);
						window.location = "servicios.php";
				}
			  }
			xmlhttp.open("GET","delete_servicio.php?id="+servicio+'',true);
			xmlhttp.send();
		
		}
		};
		function abrirform(servicio){
		
		if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
						 
						 document.getElementById("win").innerHTML=xmlhttp.responseText;
						$('#win').window('open');
						$('#win').window({  
								modal:true,
								collapsible: false,
								minimizable: false,
								maximizable: false
							}); 
						SumaTotal();
				}
			  }
			xmlhttp.open("GET","nuevoservicio_duplicado.php?id="+servicio+'',true);
			xmlhttp.send();
		};
		
		function abrirformvacio(autoid){
		
		if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
						 
						 document.getElementById("win").innerHTML=xmlhttp.responseText;
						$('#win').window('open');
						$('#win').window({  
								modal:true,
								collapsible: false,
								minimizable: false,
								maximizable: false
							}); 
						SumaTotal();
				}
			  }
			xmlhttp.open("GET","nuevoservicio_vacio.php?aid="+autoid+'',true);
			xmlhttp.send();
		};
		
		function ValidaPrecio(suma){

		if (!/^([0-9])*[.]?[0-9]*$/.test(document.getElementById(suma).value)){
		alert("El precio ingresado no es un precio valido.");
		document.getElementById(suma).value = '';		
		window.setTimeout(function ()
		{
		
        document.getElementById(suma).focus();
		}, 0);

		return false;
		}else{
			if (isNaN(parseFloat(document.getElementById(suma).value))){
			document.getElementById(suma).value = 0;
			}
			SumaTotal();		
		}
};

function SumaTotal(){		
		
  
 var total = 0;
 
 var aux;

var i;
for (i=1;i<=19;i++){

	aux = parseFloat(document.getElementById("suma"+i).value);
 
	 if( parseFloat(document.getElementById("suma"+i).value) != ''){
		total = total + aux;
	 }
} 
 document.getElementById("total").value = total;

};

function ValidaForm(){

	SumaTotal();
	
	//alert("Km Vuelta: " + document.getElementById('kmvuelta').value  + "  - Km Actual: " + document.getElementById('kmactual').value);
	
	
	if (document.getElementById('kmvuelta').value != '' && document.getElementById('kmactual').value != ''){
		if(  parseInt(document.getElementById('kmactual').value)  >= parseInt(document.getElementById('kmvuelta').value) ){

		alert("Error: El Kilometraje de Vuelta debe ser mayor al Kilometraje Actual.");
		document.getElementById('kmvuelta').focus();
		return false;
		}
	}
	
	if  (document.getElementById("total").value == '0')
	{
	var rta;
	rta = confirm('¿Este servicio no tiene costo. Desea confirmarlo de todas formas?');
	return rta;
	
	}
	
	return true;
};


//Funcion para bloquear la tecla enter
	function stopRKey(evt) {
	  var evt = (evt) ? evt : ((event) ? event : null);
	  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
	};

	document.onkeypress = stopRKey; 
	

	</script>

	<? $patente1 ='';
		$patente1 = GetParameter('id');
		$Codcliente = GetParameter('cid');
		
		
		if($patente1){
?>
 <script language="JavaScript" type="text/javascript">
 busca('<?=$patente1?>', '<?=$Codcliente?>');
  </script>
  <?
  }
  ?>
</head>
<body class="easyui-layout">
	<div region="north" style="height: 80px;padding-top:8px; padding-left: 30px;background-image:url('../images/bg.jpg'); background-position:top left;">
		<img src="../images/logo.png" alt="" width="340px"/> 	
	</div>
	
	<div region="east" split="true" style="width:280px;background: #FAFAFA; ">
			
		<!-- Datos del Automotor -->
			<div class="easyui-panel" style="height:150px; padding:1px; border: 0; " 
					title="Datos Automotor" id="datosauto"  tools="#tt" >
			</div>
			<div id="tt">
				<a href="#" class="icon-edit" onclick="javascript:editaauto()" title="Editar auto..."></a>
				<a href="#" class="icon-transfer" onclick="javascript:transfiereauto()" title="Transferir o Asignar auto a cliente..."></a>
				<a href="#" class="icon-delete" onclick="javascript:BorraAuto()" style="padding-right: 16px;" title="Eliminar Auto..."></a>
			</div>
	
			<!-- Datos del cliente -->
			<div class="easyui-panel" style="min-height:300px; padding:10px; border: 0 ;"
					title="Datos Cliente" id="datoscliente">
			</div>
			
			

	</div>
	
	<div region="west" split="true" title="Menu" style="background: #F2F2F2; width:138px; padding:4px; "><? include("menu.php");
	include("hora.php");?>
	
	</div>
	
	
	<div region="center" title="Servicios"  style="height: 310px; background: #fff;">

			<div class="easyui-panel" style="padding:5px 10px 5px 0; border: 0; width:270px; background: #F2F2F2;border-right: 1px solid #dcdcdc; border-bottom: 1px solid #dcdcdc;">
			<label style="font:14px Arial; font-weight: bold;">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;PATENTE: &nbsp;</label>
				<input id="buscapatente" class="easyui-searchbox"  searcher="busca"  style="width:140px; text-transform:uppercase;"></input>  
 
			</div>
			
					<!-- Datos del Servicio -->
			<div class="easyui-panel" style="min-height:250px;padding:10px; border:0;"
					 id="historialdeservicios" >

					
						
			</div>

			
	<!-- Formulario para editar el auto -->
	
	<div id="dlgauto" class="easyui-dialog" style="width:450px;height:250px;padding:8px 10px; background: #fdfdfd;" closed="true" buttons="#dlgauto-buttons">
	
		<form id="fmauto" name="fmauto" action="update_auto.php" method="post" novalidate>
		<input type="hidden" id="id_auto11" />
			<div class="fitem">
		<label>Patente:</label>
		<input name="patente11" id="patente11" style="width:120px;" class="easyui-validatebox" required="true" maxlength="10" missingMessage="Este campo es requerido" >
			</div>
			
			<div class="fitem">
				<label>Marca:</label>
				<select name='marca11' id='marca11' class="easyui-validatebox" style="width:200px;" >
					<option value=''>-- Seleccione --</option>
					<?	while($mm = mysql_fetch_array($resultM)){
					?>
						<option value="<?=$mm['marca_id']?>" ><?=$mm['marca_titulo']?></option>
					<?
						}
					?>
			  </select>	
				
			</div>
			<div class="fitem">
				<label>Modelo:</label>
				<input name="modelo11" id="modelo11" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="30" >
			</div>
			<div class="fitem">
				<label>Motor:</label>
				<input name="motor11"  id="motor11" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="20">
			</div>
			
			
		</form>
	</div>
		<div id="dlgauto-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="editautoSubmit();">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="$('#dlgauto').dialog('close')">Cancelar</a>
		</div>
	
	
	<!-- FIn de formulario para editar el auto -->
	
	<? include "inc_form_tx_auto.php"; ?>
	
	
	
	
	
	
	<div id="win" class="easyui-window" title="  &nbsp;Nuevo Servicio" style="width:790px;height:520px; background: #f6f6f6; paading: 10px;" >  

</div>
	
	</div>
</body>
</html>