<?php
include("php_functions.php");

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	
	//$itemid = isset($_POST['patente']) ? mysql_real_escape_string($_POST['patente']) : '';  
	$apellido1 = strtoupper(isset($_POST['apellidoynombre']) ? mysql_real_escape_string($_POST['apellidoynombre']) : '');  
    $apellido = strtoupper(GetParameter('apellidoynombre'));
	
	$offset = ($page-1)*$rows;
	$result = array();

	include 'conn.php';
	if($apellido != ''){
	$where = "where apellido like '%$apellido%' ";  
	}else
	$where='';
	
	$Consulta = "select count(*) from cliente  " . $where . " ORDER BY apellido";
	$rs = mysql_query($Consulta);

	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysql_query("select cod, apellido, telefono, celular, email, cuit, direccion, localidad, cp, resp_compra, resp_pago, lugar_entrega, iva, fecha_creacion from cliente " . $where. " ORDER BY apellido limit $offset,$rows");
	
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);
?>