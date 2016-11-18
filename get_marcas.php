<?php
include("php_functions.php");

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	
	//$itemid = isset($_POST['patente']) ? mysql_real_escape_string($_POST['patente']) : '';  
	//$apellido1 = strtoupper(isset($_POST['marca']) ? mysql_real_escape_string($_POST['marca']) : '');  
    $apellido = strtoupper(GetParameter('marca'));
	
	$offset = ($page-1)*$rows;
	$result = array();

	include 'conn.php';
	if($apellido != ''){
	$where = "where marca_titulo like '%$apellido%' ";  
	}else
	$where='';
	
	$Consulta = "select count(*) from marcas  " . $where . " ORDER BY marca_titulo";
	$rs = mysql_query($Consulta);

	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysql_query("select marca_id, marca_titulo from marcas " . $where. " ORDER BY marca_titulo limit $offset,$rows");
	
	$items = array();
	while($row = mysql_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);
?>