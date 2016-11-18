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
            return '<div style="background: #ADD6FF; padding:0; margin:0;"><span style="line-height: 26px; padding: 4px 10px; font-weight: bold;">AUTOS</span><table id="ddv-' + index + '"></table></div><div style="background: #99E699;"><span style="line-height: 26px; padding: 4px 10px; font-weight: bold;">VENTAS ESPECIALES</span><table id="ddv2-' + index + '"></table></div>';  
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
				idField:'id', 				
				textfield:'codcliente', 	
                loadMsg:'',  
                height:'auto',  
                columns:[[  
                    {field:'fecha_venta',title:'Fecha',width:100, resizable: true},
					{field:'cod_producto',title:'Producto',width:160, resizable: true},  
                    {field:'desc_producto',title:'Descripci&oacute;n',width:220, resizable: true},  
                    {field:'cant',title:'Cantidad',width:80, resizable: true},  
                    {field:'precio_unitario',title:'Precio Unit ($)',width:120, resizable: true}
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