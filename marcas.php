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
<title>Marcas LubriExpress</title>
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="../themes/icon.css">
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
		});
		var url;
		function newMarca(){
			$('#dlg').dialog('open').dialog('setTitle','Nueva Marca');
			$('#fm').form('clear');
			url = 'save_marca.php';
		};
		function editMarca(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Edita Marca');
				$('#fm').form('load',row);
				
				url = 'update_marca.php?id='+row.marca_id;
			}
		};
		function saveMarca(){
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
		};
		function removeMarca(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirma','Confirmas que queres eliminar permanentemente a esta marca?',function(r){
					if (r){
						$.post('delete_marca.php',{id:row.marca_id},function(result){
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
		};
	
	   function doSearch(){  
	
	var varSzukaj =  document.getElementById("marca").value; 
	//alert(varSzukaj.length);
        

	    $('#dg').datagrid('load',{  
            marca: $('#marca').val()  
        });  
    

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
	<div region="center" title="Marcas" style="border: 0;">
	
	<p>&nbsp; &nbsp; Acá podes configurar el listado de sus marcas cargadas en el sistema.</p>
	
	<table id="dg" class="easyui-datagrid" style="height: 500px; border: 0; width:650px;" url="get_marcas.php" toolbar="#toolbar" pagination="true" fitColumns="true" singleSelect="true" pageList="[80, 160]">
		<thead>
			<tr>
				<th field="marca_id" width="30" >Código</th>
				<th field="marca_titulo" width="100" >Marca</th>
				
			</tr>
		
		</thead>
	</table>
	<div id="toolbar" style="padding:8px 0 2px 6px;height:auto; background: #F0E0B2;">
		
		<span style="font:14px Calibri, Arial;">&nbsp; Marca:</span>  
		<input id="marca" name="marca" maxlength="15" style="padding: 2px; border:1px solid #A4BED4; text-transform: uppercase; color: #111;" onKeyDown="doSearch()"/>  

		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMarca()" style="padding: 2px;">Nueva Marca&nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMarca()" style="padding: 2px; margin-right: 15px;" title="Seleccione una marca y edite sus datos">Editar Marca &nbsp;</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeMarca()" style="padding: 2px; margin-right: 15px;"  title="Seleccione una marca y eliminelo del sistema">Eliminar Marca &nbsp;</a>
		
	</div>
	<!-- Crea un nuevo Cliente -->
	<div id="dlg" class="easyui-dialog" style="width:670px;height:200px;padding:4px 5px; background: #f2f2f2;" closed="true" buttons="#dlg-buttons">
		<form id="fm" method="post" novalidate>
			<br/><br/>
			<div class="fitem">
				<label>Marca:</label>
				<input name="marca_titulo" class="easyui-validatebox" required="true" maxlength="15" style="text-transform: uppercase;" missingMessage="Este campo es requerido">
				<br/><small style="padding-left: 130px;">(Máximo 15 caracteres)</small>
			</div>
						
		</form>
	</div>
		<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveMarca(); document.getElementById('fm').reset();">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:document.getElementById('fm').reset();$('#dlg').dialog('close')">Cancelar</a>
		</div>
	
	
	
	</div>
</body>
</html>
