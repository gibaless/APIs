<?php
session_start();
include("conn.php");
include("php_functions.php");
	//Obtengo todas las marcas de autos
	$queryMarcas = "SELECT* FROM marcas ORDER BY marca_titulo";
	$resultM = mysql_query($queryMarcas);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Clientes LubriExpress</title>
	<link rel="stylesheet" type="text/css" href="../themes/pepper-grinder/easyui.css">
	<link rel="stylesheet" type="text/css" href="../themes/icon.css">
		<link rel="stylesheet" type="text/css" href="demo.css">
		<link rel="icon" href="img/le_icon.ico" type="image/x-icon" />
	
	<script type="text/javascript" src="../jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../jquery.easyui.min.js"></script>
	<script type="text/javascript" src="../datagrid-detailview.js"></script>
	<script type="text/javascript" src="../funciones.js"></script>
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
            return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';  
        },  
        onExpandRow: function(index,row){  
            $('#ddv-'+index).datagrid({  
                url:'datagrid_getautosdecliente.php?codcliente='+row.cod,  
				fitColumns:true,  
                singleSelect:true, 
				idField:'patente', 				
				textfield:'patente', 	
                loadMsg:'',  
                height:'auto',  
                columns:[[  
                    {field:'patente',title:'Patente',width:50, resizable: true, 
						formatter:function(value,rec){
							//FUnca OK     return "<a onClick=alert('"+value+ "') class=patente>"+value+"</a>";
							return "<a href='servicios.php?id="+value+"' class=patente >"+value+"</a>";
						}},  
                    {field:'marca',title:'Marca',width:80, resizable: true},  
                    {field:'modelo',title:'Modelo',width:80, resizable: true},  
                    {field:'motor',title:'Motor',width:80, resizable: true}
					
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
            $('#dg').datagrid('fixDetailRowHeight',index);  
	    }  
    });  

		
		});
		
		
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
				$('#dlgauto').dialog('open').dialog('setTitle','Agregar Nuevo Auto');
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
		
    function doSearch(){  
        $('#dg').datagrid('load',{  
            apellidoynombre: $('#apellidoynombre').val()  
        });  
    } 


	
	</script>
		<style type="text/css">
				
		#fm{
			margin:0;
			padding:10px;
		}
		.ftitle{
			font-size:20px;
			font-weight:bold;
			color:#333;
			padding:5px 0;
			margin-bottom:10px;
			border-bottom:1px solid #444;
		}
		.fitem{
			margin-bottom:5px;
		}
		.fitem label{
			display:inline-block;
			width:180px;
			font-size: 19px;
			line-height: 28px;
			text-align: right;
		}
		.fitem input, .fitem select{
			
			width:390px;
			font-size: 20px;
			color: #913D26;
			font-weight: bold;
			padding: 2px 4px;
			height: 28px;
			line-height: 26px;
			
		}
		.fitem input:focus{
			
		border: 2px groove #F0AB51;
			
		}
		.patente{
		
		color: #913D26;
		font-weight: bold;
		font-size: 16px;
		cursor: pointer;
		
		}
		#apellidoynombre{
		border:1px solid #ccc; 
		width:200px;
			font-size: 19px;
			color: #913D26;
			font-weight: bold;
			padding: 1px 2px;
			height: 22px;
			line-height: 19px;
		}
	</style>
</head>
<body class="easyui-layout">
	<div region="north"
	style="height:90px;padding:10px;background-image:url('../images/bg.jpg');
	background-position:top left;">
		<img src="../images/logo.png" alt="" width="350px"/> 	
	</div>

	<div region="west" split="true" title="Menu Principal" style="background: #FAF8F5; width:170px;padding1:1px;"><? include("menu.php"); include("hora.php");?></div>
	<div region="center" title="Clientes" >

	<table id="dg" class="easyui-datagrid" style="height: 580px;"
			url="get_clientes.php"
			toolbar="#toolbar" pagination="true" 
			fitColumns="true" singleSelect="true"
			pageList="[100, 200, 300]"
			>
		<thead>
			<tr>
				<th field="cod" width="30">Codigo</th>
				<th field="apellido" width="100" editor="text">Nombre</th>
				<th field="telefono" width="50" editor="text">Tel&eacute;fono</th>
				<th field="celular" width="50" editor="text">Celular</th>
				<th field="email" width="50" editor="text">Email</th>
				<th field="cuit" width="40" editor="text">CUIT</th>
		<!--		<th field="direccion" width="50" editor="text">Direccion</th>
				<th field="localidad" width="50" editor="text">Localidad</th>
				<th field="cp" width="50" editor="text">CP</th>
			<th field="resp_compra" width="50" editor="text">Resp. Compra</th>
				<th field="resp_pago" width="50" editor="text">Resp. Pago</th>
				-->
			</tr>
		
		</thead>
	</table>
	<div id="toolbar"  style="padding:10px;height:auto; font-size: 15px; background: #fefefe;">
		
		<span>&nbsp; Apellido / Nombre:</span>  
		<input id="apellidoynombre">  
		<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Buscar</a>&nbsp;&nbsp;&nbsp;		
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()" style="padding: 2px;">Nuevo Cliente &nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()" style="padding: 2px;" >Editar Cliente &nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser()" style="padding: 2px;">Eliminar Cliente &nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="agregarAutodeCliente()" style="padding: 2px;">Nuevo Auto &nbsp;</a>
		
	
		
	</div>
	<!-- Crea un nuevo Cliente -->
	<div id="dlg" class="easyui-dialog" style="width:880px;height:580px;padding:8px 10px; background: #fdfdfd;"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">Datos del Cliente</div>
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<label>Apellido y Nombre:</label>
				<input name="apellido" class="easyui-validatebox" required="true" maxlength="80" missingMessage="Este campo es requerido">
			</div>
			<div class="fitem">
				<label>Telefono:</label>
				<input name="telefono" class="easyui-validatebox" maxlength="50">
			</div>
			<div class="fitem">
				<label>Celular:</label>
				<input name="celular" class="easyui-validatebox" maxlength="50">
			</div>
			<div class="fitem">
				<label>Email:</label>
				<input name="email" class="easyui-validatebox" validType="email" maxlength="40">
			</div>
			<div class="fitem">
				<label>CUIT:</label>
				<input name="cuit" class="easyui-validatebox" maxlength="13" style="width: 180px;">
				<span> (Sólo números)</span>
			</div>			
			<div class="fitem">
				<label>Direccion:</label>
				<input name="direccion" class="easyui-validatebox" maxlength="40">
			</div>			
			<div class="fitem">
				<label>Localidad:</label>
				<input name="localidad" class="easyui-validatebox" maxlength="40">
			</div>			
			<div class="fitem">
				<label>CP:</label>
				<input name="cp" class="easyui-validatebox" maxlength="10" style="width: 150px;">
			</div>			
			<div class="fitem">
				<label>Resp. de Compra:</label>
				<input name="resp_compra" class="easyui-validatebox" maxlength="150">
			</div>			
			<div class="fitem">
				<label>Resp. de Pago:</label>
				<input name="resp_pago" class="easyui-validatebox"  maxlength="150">
			</div>			
			
		</form>
	</div>
		<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveUser()">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:$('#dlg').dialog('close')">Cancelar</a>
		</div>
	
	
	<!-- Crea un nuevo Auto para un cliente existente -->
	
		<div id="dlgauto" class="easyui-dialog" style="width:700px;height:300px;padding:8px 10px; background: #fdfdfd;"
			closed="true" buttons="#dlgauto-buttons">

		
		<div class="ftitle">Datos del Automovil</div>
		<form id="fmauto" method="post" novalidate>
			<div class="fitem">
				<label>Patente:</label>
				<input name="patente" style="width:280px;" class="easyui-validatebox" required="true" maxlength="80" missingMessage="Este campo es requerido" tabindex='0'>
			</div>
			
			<div class="fitem">
				<label>Marca:</label>
				<select name='marca' id='marca' class="easyui-validatebox" style="width:280px;" tabindex='1'>
										<option value=''>-- Seleccione --</option>
										<?
								
								while($mm = mysql_fetch_array($resultM)){
								?>
									<option value="<?=$mm['marca_id']?>" ><?=$mm['marca_titulo']?>
								<?
								}
								?>
			  </select>	
				
			</div>
			<div class="fitem">
				<label>Modelo:</label>
				<input name="modelo" class="easyui-validatebox" style="width:280px;" maxlength="50" tabindex='2'>
			</div>
			<div class="fitem">
				<label>Motor:</label>
				<input name="motor" class="easyui-validatebox" style="width:280px;" maxlength="40" tabindex='3'>
			</div>
			
			
		</form>
	</div>
		<div id="dlgauto-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveAuto()">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:$('#dlgauto').dialog('close')">Cancelar</a>
		</div>
		


		<!-- Fin de Form para nuevo Auto -->
	
	
	</div>
</body>
</html>