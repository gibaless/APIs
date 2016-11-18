<?php

$apellido = strtoupper($_REQUEST['apellido']);
$telefono = $_REQUEST['telefono'];
$celular = $_REQUEST['celular'];
$email = strtolower($_REQUEST['email']);
$cuit = $_REQUEST['cuit'];
$iva = $_REQUEST['iva'];
$direccion = strtoupper($_REQUEST['direccion']);
$localidad = strtoupper($_REQUEST['localidad']);
$cp = $_REQUEST['cp'];
$resp_compra = strtoupper($_REQUEST['resp_compra']);
$resp_pago = strtoupper($_REQUEST['resp_pago']);
$lugar_entrega = strtoupper($_REQUEST['lugar_entrega']);

if (isset($_REQUEST['apellido'])){			
			
include 'conn.php';

$sql = "insert into cliente(cod, apellido, telefono, celular, email, cuit, iva, direccion, localidad, cp, resp_compra, resp_pago, lugar_entrega, fecha_creacion) values('', 
'$apellido','$telefono','$celular','$email', '$cuit', $iva, '$direccion', '$localidad', '$cp', '$resp_compra', '$resp_pago', '$lugar_entrega', CURDATE() )";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Ocurrio un error al guardar el cliente.'));
}
}
?>