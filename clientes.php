<?php
session_start();
/* <!-- Optimizador de pagina --> */
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 

include("access.php");
include("php_functions.php");
	//Obtengo todas las marcas de autos
	
	include("conn.php");
	$queryMarcas = "SELECT* FROM marcas ORDER BY marca_titulo";
	$resultM = mysql_query($queryMarcas);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex">
<title>Clientes LubriExpress</title>
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="../themes/icon.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="demo.css">

		<link rel="icon" href="img/le_icon.ico" type="image/x-icon" />
	<script type="text/javascript" src="../jquery-1.7.2.min.js"></script>


	<script type="text/javascript" src="../jquery.easyui.min.js"></script>
	<script type="text/javascript" src="../funciones.js"></script>

	<script type="text/javascript" src="../datagrid-detailview.js"></script>
	
	<script>
	$(function(){
		$(document).bind('contextmenu',function(e){
				$('#mm').menu('show', {
					left: e.pageX,
					top: e.pageY
				});
				return false;
			});
		
		$('#dg').datagrid({  
        view: detailview,  
        detailFormatter:function(index,row){  
            return '<div style="padding:0; margin:0;"><p style="background: #ADD6FF; line-height: 28px; padding: 1px 10px; margin:0;font-weight: bold; width: 100%;">AUTOS</p><table style="background: #fefefe;" id="ddv-' + index + '"></table></div><div style="background: #fefefe;"><p style="background: #99E699; line-height: 28px; padding: 1px 10px; margin: 0;font-weight: bold;">VENTAS ESPECIALES</p><table" id="ddv2-' + index + '"></table></div>';  
        },  
        onExpandRow: function(index,row){  
            $('#ddv-'+index).datagrid({  
                url:'datagrid_getautosdecliente.php?codcliente='+row.cod,  
				fitColumns:false,  
				frozenColumns: true,
				showHeader: false,
                singleSelect:true, 
				idField:'patente', 				
				textfield:'patente', 	
                loadMsg:'',  
                height:'auto',  
                columns:[[  
                    {field:'patente',title:'Patente',width:100, resizable: true, 
						formatter:function(value,rec){
							return "<a href='servicios.php?id="+value+"&cid="+row.cod+"' style=' margin-left: 10px; color:#000; text-decoration: none; font-size:18px; font-weight: bold;' >"+value+"</a>";
						}},  
                    {field:'marca',title:'Marca',width:160, resizable: true},    
                    {field:'modelo',title:'Modelo',width:220, resizable: true},  
                    {field:'motor',title:'Motor',width:200, resizable: true}
					
                ]],  
                onResize:function(){  
                    $('#dg').datagrid('fixDetailRowHeight',index);  
                },  
                onLoadSuccess:function(){  
                    setTimeout(function(){  
                        $('#dg').datagrid('fixDetailRowHeight',index);  
                    },0);  
                }, 
				
            }); 



			  $('#ddv2-'+index).datagrid({  
			  	url:'datagrid_getproductosdecliente.php?codcliente='+row.cod,  
                singleSelect:true, 
				fitColumns:false,  
				frozenColumns: true,
				showHeader: true,
				idField:row.id, 				
				textfield:'codcliente', 	
                loadMsg:'',  
                height:'auto',  
               // updateUrl: 'update_productos_de_cliente.php?id='+row.id,
                columns:[[  
                    {field:'fecha_venta',title:'<b>Fecha</b>',width:120, resizable: true, editor:{type:'datebox'}},
					{field:'cod_producto',title:'<b>Producto</b>',width:160, resizable: true, editor:'text'},  
                    {field:'desc_producto',title:'<b>Descripci&oacute;n</b>',width:240, resizable: true, editor:'text'},  
                    {field:'cant',title:'<b>Cantidad</b>',width:80, resizable: true, align:'right',editor:{type:'numberbox',options:{precision:2}}},  
                    {field:'precio_unitario',title:'<b>Precio Unit ($)</b>',width:120, resizable: true, align:'right',
                    		editor:{type:'numberbox',options:{precision:2}}},
                    {field:'action',title:'<b>Acciones</b>',width:200,align:'center',
		                formatter:function(value,row,index){
		                    if (row.editing){
		                        var s = "<a class='btn' href='javascript:void(0)' onclick='saverow(this, "+index+")'><i class='fa fa-floppy-o' aria-hidden='true' style='color: green;'></i> Guardar</a> ";

		                        var c = " &nbsp;&nbsp;<a class='btn' href='javascript:void(0)' onclick='cancelrow(this, "+index+")'><i class='fa fa-times-circle-o' aria-hidden='true' style='color: red;'></i> Cancelar</a>";
		                        return s+c;
		                    } else {
		                        var e = "<a class='btn' href='javascript:void(0)' onclick='editrow(this, "+index+")'><i class='fa fa-pencil-square-o' aria-hidden='true' style='color: green;'></i> Editar</a> ";
		                        var d = " &nbsp;&nbsp;<a class='btn' href='javascript:void(0)' onclick='deleterow(this,"+row.id+")'><i class='fa fa-trash' aria-hidden='true' style='color: red;'></i> Eliminar</a>";

		                        return e+d;
		                    }
		                }
            		}
				 ]],  
                onResize:function(){  
                    $('#dg').datagrid('fixDetailRowHeight',index);  
                },  
                onLoadSuccess:function(){  
                    setTimeout(function(){  
                        $('#dg').datagrid('fixDetailRowHeight',index);  
                    },0);  
                }, 
            
		        onEndEdit:function(index,row){
		        	//
		        },
		        onBeforeEdit:function(index,row){
		        	row.editing = true;
		            $(this).datagrid('refreshRow', index);
		        },
		        onAfterEdit:function(index,row){
		            row.editing = false;
		            $(this).datagrid('refreshRow', index);
		        	//console.log("Editing");
		            //console.log(row);
	
					$.ajax({
					    type: "POST",
					    url: 'update_productos_de_cliente.php?id='+row.id+'&cod_producto='+row.cod_producto+'&desc_producto='+row.desc_producto+'&cant='+row.cant+'&precio_unitario='+row.precio_unitario+'&fecha_venta='+row.fecha_venta,
					    data: {
					       // 'myString': "blahblah",
					    },
					    success: function(data) {
					       // alert('worked' + data);
					    },
					    error: function(){
					       // alert('failed');
					    }
					});

		        },
		        onCancelEdit:function(index,row){
		            row.editing = false;
		            $(this).datagrid('refreshRow', index);
		        }


				
            });  
            $('#dg').datagrid('fixDetailRowHeight',index);  
	    }  
    });  

		
		});
		
		


		function getRowIndex(target){
		    var tr = $(target).closest('tr.datagrid-row');
		   // console.log("Current: " + parseInt(tr.attr('datagrid-row-index')));
		    return parseInt(tr.attr('datagrid-row-index'));

		}

		function getRowParent(target){
			var tr1 = $(target).closest('tr.datagrid-row').parents().eq(11).children().closest('tr.datagrid-row');
			//console.log(tr1);
		    //console.log("Parent is: " + parseInt(tr1.attr('datagrid-row-index')));
		    return parseInt(tr1.attr('datagrid-row-index'));

		}
		function editrow(target, index){
		    //console.log("index: " + index);
		    var ind = getRowParent(target);
		    //$('#ddv2-'+index).datagrid('beginEdit', getRowIndex(target));
		    $('#ddv2-'+ind).datagrid('beginEdit', getRowIndex(target));
		    


		}
		function deleterow(target, id1){
		    $.messager.confirm('Confirm','¿Confirma que desea BORRAR esta venta especial?',function(r){
		        if (r){
		            $.ajax({
					    type: "POST",
					    url: 'delete_productos_de_cliente.php?id='+id1,
					    data: {
					       // 'myString': "blahblah",
					    },
					    success: function(data) {
					        alert('worked' + data);
					    },
					    error: function(){
					        alert('failed');
					    }
					});
		        }
		    });
		}
		function saverow(target, index){
			var ind = getRowParent(target);
		     $('#ddv2-'+ind).datagrid('endEdit', getRowIndex(target));

		}
		function cancelrow(target, index){
			var ind = getRowParent(target);
		    $('#ddv2-'+ind).datagrid('cancelEdit', getRowIndex(target));
		    console.log("Canceling");
		}




		var url;
		
		function newUser(){
			$('#dlg').dialog('open').dialog('setTitle','Nuevo Cliente');
			$('#fm').form('clear');
			url = 'save_cliente.php';
		}
		
		function editUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Editar Cliente');
				$('#fm').form('load',row);
				url = 'update_cliente.php?id='+row.cod;
			}
		}
		function saveUser(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){

					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.success){
						$('#dlg').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');	// reload the user data
					} else {
						$.messager.show({
							title: 'Error',
							msg: result.msg
						});
					}
				}
			});
		}
		function removeUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirmación',"Confirma que desea BORRAR este cliente?",function(r){
					if (r){
						$.post('delete_cliente.php',{id:row.cod},function(result){
							if (result.success){
								$('#dg').datagrid('reload');	// reload the user data
							} else {
								$.messager.show({	// show error message
									title: 'Error',
									msg: result.msg
								});
							}
						},'json');
					}
				});
			}
		}

		function agregarAutodeCliente(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
			document.getElementById("clientenuevoauto").innerHTML = "<p> &nbsp;&nbsp;&nbsp;Codigo Cliente: &nbsp;&nbsp;<b>" + row.cod + "</b></p>";
				$('#dlgauto').dialog('open').dialog('setTitle','Nuevo Auto');
				$('#fmauto').form('load',row);
				url = 'update_cliente_nuevo_auto.php?id='+row.cod;
			}
		}
		
		function saveAuto(){
			$('#fmauto').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.success){
						$('#dlgauto').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');	// reload the user data
					} else {
						$.messager.show({
							title: 'Error',
							msg: result.msg
						});
					}
				}
			});
		}
		
		
		function agregarVentaEspecialdeCliente(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
							
				document.getElementById("clientevtaespecial").innerHTML = "<p> &nbsp;&nbsp;&nbsp;Codigo Cliente: &nbsp;&nbsp;<b>" + row.cod + "</b></p>";
				$('#dlgvtaespecial').dialog('open').dialog('setTitle','Nueva Venta Especial');
				$('#fmvtaespecial').form('load',row);
				url = 'update_cliente_nueva_venta.php?id='+row.cod;
			}
		}
		
		function saveVentaEspecial(){
			$('#fmvtaespecial').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.success){
						$('#dlgvtaespecial').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');	// reload the user data
					} else {
						$.messager.show({
							title: 'Error',
							msg: result.msg
						});
					}
				}
			});
		}
		
    function doSearch(){  
	
	var varSzukaj =  document.getElementById("apellidoynombre").value; 
	//alert(varSzukaj.length);
        if (varSzukaj.length >= 3) {

	    $('#dg').datagrid('load',{  
            apellidoynombre: $('#apellidoynombre').val()  
        });  
    }

	};	
	
	

	function calculatotal(){
	oFormObject = document.forms['fmvtaespecial'];
	var p1, p2, p3, p4, p5;
	var c1, c2, c3, c4, c5;
	var a, b, c, d, e;
	p1 = oFormObject.elements["precio1"].value;
	p2 = oFormObject.elements["precio2"].value;
	p3 = oFormObject.elements["precio3"].value;
	p4 = oFormObject.elements["precio4"].value;
	p5 = oFormObject.elements["precio5"].value;
	
	c1 = oFormObject.elements["cantidad1"].value;
	c2 = oFormObject.elements["cantidad2"].value;
	c3 = oFormObject.elements["cantidad3"].value;
	c4 = oFormObject.elements["cantidad4"].value;
	c5 = oFormObject.elements["cantidad5"].value;
	
	a = oFormObject.elements["subtotal1"].value = p1 * c1;
	b = oFormObject.elements["subtotal2"].value = p2 * c2;
	c = oFormObject.elements["subtotal3"].value = p3 * c3;
	d = oFormObject.elements["subtotal4"].value = p4 * c4;
	e = oFormObject.elements["subtotal5"].value = p5 * c5;
	
	oFormObject.elements["total"].value = a+b+c+d+e;
	}
	
	
	
	function validalineas(){
	
	var p1, p2, p3, p4, p5;
	var c1, c2, c3, c4, c5;

	p1 = oFormObject.elements["precio1"].value;
	p2 = oFormObject.elements["precio2"].value;
	p3 = oFormObject.elements["precio3"].value;
	p4 = oFormObject.elements["precio4"].value;
	p5 = oFormObject.elements["precio5"].value;
	
	c1 = oFormObject.elements["cantidad1"].value;
	c2 = oFormObject.elements["cantidad2"].value;
	c3 = oFormObject.elements["cantidad3"].value;
	c4 = oFormObject.elements["cantidad4"].value;
	c5 = oFormObject.elements["cantidad5"].value;
	
	//Valido que si inserto un producto no me olvide de la cantidad ni del precio
	
	if(oFormObject.elements["producto2"].value != '' || oFormObject.elements["descripcion2"].value != '' ||
	oFormObject.elements["cantidad2"].value != '' || oFormObject.elements["precio2"].value != ''	){
		if(p2 =='' || c2 ==''){
			alert("Debes ingresar Precio y Cantidad para el Producto en la línea 2");
			return false;
		}
	}
	if(oFormObject.elements["producto3"].value != '' || oFormObject.elements["descripcion3"].value != '' ||
	oFormObject.elements["cantidad3"].value != '' || oFormObject.elements["precio3"].value != ''	 ){
		if(p3 =='' || c3 ==''){
			alert("Debes ingresar Precio y Cantidad para el Producto en la línea 3");
			return false;
		}
	}
	if(oFormObject.elements["producto4"].value != '' || oFormObject.elements["descripcion4"].value != '' ||
	oFormObject.elements["cantidad4"].value != '' || oFormObject.elements["precio4"].value != ''){
		if(p4 =='' || c4 ==''){
			alert("Debes ingresar Precio y Cantidad para el Producto en la línea 4");
			return false;
		}
	}
	if(oFormObject.elements["producto5"].value != '' || oFormObject.elements["descripcion5"].value != '' ||
	oFormObject.elements["cantidad5"].value != '' || oFormObject.elements["precio5"].value != ''){
		if(p5 =='' || c5 ==''){
			alert("Debes ingresar Precio y Cantidad para el Producto en la línea 5");
			return false;
		}
	}
	return true;
	}
	
	function validar_iva(){
	
	if( document.getElementById("iva").value != "1" && document.getElementById("iva").value != "2"){
		document.getElementById("iva").value = 2;
		alert("Valores válidos para IVA:\n\n 1 - Responsable Inscripto \n 2 - Consumidor Final");
		}
	
	};	
	
	//Funcion para bloquear la tecla enter
		function stopRKey(evt) {
		  var evt = (evt) ? evt : ((event) ? event : null);
		  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
		};

		document.onkeypress = stopRKey; 


	

	</script>

</head>
<body class="easyui-layout">
	<div region="north" style="height: 80px;padding-top:8px; padding-left: 30px;background-image:url('../images/bg.jpg'); background-position:top left;">
		<img src="../images/logo.png" alt="" width="340px"/> 	
	</div>
	<div region="west" split="true" title="Menu" style="background: #F2F2F2; width:138px; padding:4px; "><? include("menu.php");
	include("hora.php");?>
	
	</div>
	<div region="center" title="Clientes" style="border: 0;">
	<table id="dg" class="easyui-datagrid" style="height: 560px; border: 0;" url="get_clientes.php" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true"	pageList="[20, 40]">
		<thead >
			<tr>
				<th field="cod" width="20"><b>Codigo</b></th>
				<th field="apellido" width="100" editor="text"><b>Nombre</b></th>
				<th field="telefono" width="45" editor="text"><b>Tel&eacute;fono</b></th>
				<th field="celular" width="45" editor="text"><b>Celular</b></th>
				<th field="email" width="60" editor="text"><b>Email</b></th>
				<th field="cuit" width="40" editor="text"><b>CUIT</b></th>
			</tr>
		
		</thead>
	</table>
	<div id="toolbar" style="padding:8px 0 2px 6px;height:auto; background: #F4DECB;">
		
		<span style="font:14px Calibri, Arial;">&nbsp; Apellido / Nombre:</span>  
		<input id="apellidoynombre" name="apellidoynombre" maxlength="80" style="padding: 2px; border:1px solid #A4BED4; text-transform: uppercase; color: #111;" onKeyDown="doSearch()"/>  

		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()" style="padding: 2px;">Nuevo Cliente &nbsp;</a>
		<br/>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()" style="padding: 2px; margin-right: 15px;" title="Seleccione un cliente y edite sus datos">Editar Cliente &nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser()" style="padding: 2px; margin-right: 15px;"  title="Seleccione un cliente y eliminelo del sistema">Eliminar Cliente &nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="agregarAutodeCliente()" style="padding: 2px; margin-right: 15px;" title="Seleccione un cliente y asignele un nuevo auto">Nuevo Auto &nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="agregarVentaEspecialdeCliente()" style="padding: 2px; margin-right: 15px;" title="Seleccione un cliente y agregue una nueva venta especial">Nueva Venta Especial &nbsp;</a>
	</div>
	<!-- Crea un nuevo Cliente -->
	<div id="dlg" class="easyui-dialog" style="width:670px;height:500px;padding:4px 5px; background: #f2f2f2;" closed="true" buttons="#dlg-buttons">
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<label>Apellido y Nombre:</label>
				<input name="apellido" class="easyui-validatebox" required="true" maxlength="80" style="text-transform: uppercase;" missingMessage="Este campo es requerido">
			</div>
			<div class="fitem">
				<label>Telefono:</label>
				<input name="telefono" class="easyui-validatebox" maxlength="50" style="text-transform: uppercase;">
			</div>
			<div class="fitem">
				<label>Celular:</label>
				<input name="celular" class="easyui-validatebox" maxlength="50" style="text-transform: uppercase;">
			</div>
			<div class="fitem">
				<label>Email:</label>
				<input name="email" class="easyui-validatebox" validType="email" maxlength="40">
			</div>
			<div class="fitem">
				<label>IVA:</label>
				<input name="iva" id="iva" class="easyui-validatebox" maxlength="1" style="width: 20px;" onChange="validar_iva();">
				<span> (  <b>1</b>  Responsable Inscripto / <b>2</b> Consumidor Final</span>  )
				
			</div>	
			<div class="fitem">
				<label>CUIT:</label>
				<input name="cuit" id="cuit" maxlength="13" style="width: 190px;text-transform: uppercase;">
				<span> (Sólo números)</span>
			</div>			
			<div class="fitem">
				<label>Direccion:</label>
				<input name="direccion" class="easyui-validatebox" maxlength="40" style="text-transform: uppercase;">
			</div>			
			<div class="fitem">
				<label>Localidad:</label>
				<input name="localidad" class="easyui-validatebox" maxlength="40" style="width: 190px; text-transform: uppercase;">
				<span style="font-size: 19px;">&nbsp;&nbsp;  CP:</span>
				<input name="cp" class="easyui-validatebox" maxlength="10" style="width: 130px;text-transform: uppercase;">
			</div>			
			<div class="fitem">
				
			</div>			
			<div class="fitem">
				<label>Resp. de Compra:</label>
				<input name="resp_compra" class="easyui-validatebox" maxlength="150" style="text-transform: uppercase;">
			</div>			
			<div class="fitem">
				<label>Resp. de Pago:</label>
				<input name="resp_pago" class="easyui-validatebox"  maxlength="150" style="text-transform: uppercase;">
			</div>		
			<div class="fitem">
				<label>Lugar de Entrega:</label>
				<input name="lugar_entrega" class="easyui-validatebox"  maxlength="100" style="text-transform: uppercase;">
			</div>				
			
		</form>
	</div>
		<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveUser(); document.getElementById('fm').reset();">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:document.getElementById('fm').reset();$('#dlg').dialog('close')">Cancelar</a>
		</div>
	
	
	<!-- Crea un nuevo Auto para un cliente existente -->
	
		<div id="dlgauto" class="easyui-dialog" style="width:450px;height:280px;padding:8px 10px; background: #f2f2f2;"
			closed="true" buttons="#dlgauto-buttons">
		<form id="fmauto" method="post" novalidate>
			<div class="fitem" id="clientenuevoauto">
				Cliente:
			</div>		
		<div class="fitem">
		<label>Patente:</label>
		<input name="patente" style="width:120px;text-transform: uppercase;" class="easyui-validatebox" required="true" maxlength="10" missingMessage="Este campo es requerido" >
			</div>
			
			<div class="fitem">
				<label>Marca:</label>
				<select name='marca' id='marca' class="easyui-validatebox" style="width:200px;" >
					<option value=''>-- Seleccione --</option>
					<?	while($mm = mysql_fetch_array($resultM)){
					?>
						<option value="<?=$mm['marca_id']?>" ><?=$mm['marca_titulo']?>
					<?
						}
					?>
			  </select>	
				
			</div>
			<div class="fitem">
				<label>Modelo:</label>
				<input name="modelo" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="30" >
			</div>
			<div class="fitem">
				<label>Motor:</label>
				<input name="motor" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="20">
			</div>
			
			
		</form>
	</div>
		<div id="dlgauto-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveAuto(); document.getElementById('fmauto').reset();">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:document.getElementById('fmauto').reset();$('#dlgauto').dialog('close')">Cancelar</a>
		</div>
		


		<!-- Fin de Form para nuevo Auto -->
	
	<!-- Crea una nueva Venta Especial para un cliente existente -->
	
		<div id="dlgvtaespecial" class="easyui-dialog" style="width:760px;height:400px;padding:8px 10px; background: #fdfdfd;"
			closed="true" buttons="#dlgvtaespecial-buttons">

		
		
		<form id="fmvtaespecial" method="post"    novalidate>
			<div class="fitem" id="clientevtaespecial">
				Cliente:
			</div>
			
			<div class="fitem2">
			<table><tr><td> </td><td><label>Producto</label></td>
				<td><label>Descripci&oacute;n</label></td>
				<td><label>Cantidad</label></td>
				<td><label>Precio Unit.</label></td>
				<td><label>Subtotal</label></td></tr>
			<tr><td>1-</td> 
				<td><input name="producto1" class="easyui-validatebox" style="width:120px;text-transform: uppercase;" maxlength="50" tabindex='1' required="true"></td>
				<td><input name="descripcion1" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="50" tabindex='2'></td>
			<td><input name="cantidad1" class="easyui-validatebox" style="width:90px;" maxlength="50" tabindex='3' required="true" missingMessage="Campo requerido. (ej: 25.50)" onChange="if(isNaN(this.value)){ alert('Linea 1: Cantidad Invalida.')};calculatotal();"></td>
				<td><input name="precio1" class="easyui-validatebox" style="width:100px;" maxlength="50" tabindex='4' onChange="if(isNaN(this.value)){ alert('Linea 1: Precio Invalido.')}; calculatotal();"  required="true" missingMessage="Campo requerido. (ej: 25.50)"></td>
				<td><input name="subtotal1" class="easyui-validatebox" style="width:100px;" readonly="readonly"></td>
			</tr>
			
			<tr><td>2-</td> 
				<td><input name="producto2" class="easyui-validatebox" style="width:120px;text-transform: uppercase;" maxlength="50" tabindex='5'></td>
				<td><input name="descripcion2" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="50" tabindex='6'></td>
			<td><input name="cantidad2" class="easyui-validatebox" style="width:90px;" maxlength="50" tabindex='7' ></td>
				<td><input name="precio2" class="easyui-validatebox" style="width:100px;" maxlength="50" tabindex='8' onChange="calculatotal()"></td>
				<td><input name="subtotal2" class="easyui-validatebox" style="width:100px;" readonly="readonly"></td>
			</tr>
			<tr><td>3-</td> 
				<td><input name="producto3" class="easyui-validatebox" style="width:120px;text-transform: uppercase;" maxlength="50" tabindex='9'></td>
				<td><input name="descripcion3" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="50" tabindex='10'></td>
			<td><input name="cantidad3" class="easyui-validatebox" style="width:90px;" maxlength="50" tabindex='11' onChange="calculatotal()"></td>
				<td><input name="precio3" class="easyui-validatebox" style="width:100px;" maxlength="50" tabindex='12' onChange="calculatotal()"></td>
				<td><input name="subtotal3" class="easyui-validatebox" style="width:100px;" readonly="readonly"></td>
			</tr>
			<tr><td>4-</td> 
				<td><input name="producto4" class="easyui-validatebox" style="width:120px;text-transform: uppercase;" maxlength="50" tabindex='13'></td>
				<td><input name="descripcion4" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="50" tabindex='14'></td>
			<td><input name="cantidad4" class="easyui-validatebox" style="width:90px;" maxlength="50" tabindex='15' onChange="calculatotal()"></td>
				<td><input name="precio4" class="easyui-validatebox" style="width:100px;" maxlength="50" tabindex='16' onChange="calculatotal()"></td>
				<td><input name="subtotal4" class="easyui-validatebox" style="width:100px;" readonly="readonly"></td>
			</tr>
			<tr><td>5-</td> 
				<td><input name="producto5" class="easyui-validatebox" style="width:120px;text-transform: uppercase;" maxlength="50" tabindex='17'></td>
				<td><input name="descripcion5" class="easyui-validatebox" style="width:200px;text-transform: uppercase;" maxlength="50" tabindex='18'></td>
			<td><input name="cantidad5" class="easyui-validatebox" style="width:90px;text-transform: uppercase;" maxlength="50" tabindex='19' onChange="calculatotal()"></td>
				<td><input name="precio5" class="easyui-validatebox" style="width:100px;" maxlength="50" tabindex='20' onChange="calculatotal()"></td>
				<td><input name="subtotal5" class="easyui-validatebox" style="width:100px;" readonly="readonly"></td>
			</tr>
			<tr><td colspan="5" style="text-align: right;">Total</td>
			<td><input name="total" id="total" class="easyui-validatebox" style="width:100px;" readonly="readonly"></td>
			</tr>
			</table>
			</div>
		
		</form>
	</div>
		<div id="dlgvtaespecial-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="if(validalineas()){saveVentaEspecial();} document.getElementById('fmvtaespecial').reset();">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:document.getElementById('fmvtaespecial').reset();$('#dlgvtaespecial').dialog('close'); ">Cancelar</a>
		</div>
		


		<!-- Fin de Form para nueva Venta Especial -->
	</div>
</body>
</html>
