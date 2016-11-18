<?php
include "php_functions.php";
include 'conn.php';		
	
$productoid = GetParameter('id');
$cod_producto = GetParameter('cod_producto');
$desc_producto = GetParameter('desc_producto');
$cant = GetParameter('cant');
$precio_unitario = GetParameter('precio_unitario');
$fecha_venta1 = explode("/",GetParameter('fecha_venta')); // formato dd/mm/yyyy
$fecha_venta = $fecha_venta1[2]."-".$fecha_venta1[1]."-".$fecha_venta1[0];
//if (!$fecha_venta) { $fecha_venta = " CURDATE() ";}

$BoxMsg = "";
if ($productoid != ''){

	$query ="UPDATE productos_cliente SET cod_producto = '$cod_producto', desc_producto = '$desc_producto', cant = '$cant', precio_unitario = '$precio_unitario', fecha_venta = '$fecha_venta' WHERE id = '". $productoid . "'";
	$result = mysql_query($query);
	if ($result) $BoxMsg= "Actualización exitosa.";
		else $BoxMsg= "Error al intentar actualizar.";

}else {

	$BoxMsg= "Error: No se ha actualizado la venta especial.";

}
echo $query;

?>