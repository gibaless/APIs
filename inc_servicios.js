	
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
						//Tengo que cerrar el cuadro de dialogo
						$('#dlgauto').dialog('close');
						busca(patente);
						//alert("Datos del automotor fueron actualizados");
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