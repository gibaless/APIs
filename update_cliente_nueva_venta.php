<?php
include("php_functions.php");
include 'conn.php';		
	
$codid = GetParameter('id');
$sinerrores = 0;
for($ii=1; $ii<6; $ii++)
{
	$p = "producto".$ii;
	$d = "descripcion".$ii;
	$c = "cantidad".$ii;
	$pp = "precio".$ii;
	
	$producto = strtoupper(GetParameter($p));
	$descripcion = strtoupper(GetParameter($d));
	$cant = GetParameter($c);
	$precio = GetParameter($pp);

	if ($producto != '' && $codid !=''){
	$sql = "INSERT INTO productos_cliente (codcliente, cod_producto, desc_producto, cant, precio_unitario, fecha_venta) " .
		" VALUES ('$codid', '$producto', '$descripcion', '$cant', '$precio', CURDATE())";
	$result = @mysql_query($sql);
	
	if ($result){
		$sinerrores = 1;
	} else {
		echo json_encode(array('msg'=>'Error en la carga de datos. Intentelo nuevamente.'));
	}
	}	
}
	
	if ($sinerrores == 1){
		echo json_encode(array('success'=>true));
	
	}else{
	echo json_encode(array('msg'=>'Error: Faltan datos.'));

	}
?>