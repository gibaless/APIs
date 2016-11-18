<?php
include("php_functions.php");
include 'conn.php';		
	
$codid = GetParameter('id');

$apellido = strtoupper(GetParameter('apellido'));
$telefono = GetParameter('telefono');
$celular = GetParameter('celular');
$email = strtolower(GetParameter('email'));
$cuit = GetParameter('cuit');
$iva = GetParameter('iva');
$direccion = strtoupper(GetParameter('direccion'));
$localidad = strtoupper(GetParameter('localidad'));
$cp = GetParameter('cp');
$resp_compra = strtoupper(GetParameter('resp_compra'));
$resp_pago = strtoupper(GetParameter('resp_pago'));
$lugar_entrega = strtoupper(GetParameter('lugar_entrega'));


if ($apellido != '' && $codid !=''){
$sql = "update cliente set apellido=\"".$apellido."\",celular='$celular',telefono='$telefono',email='$email', " .
		" cuit='$cuit', iva='$iva', direccion=\"".$direccion."\", localidad='$localidad', cp='$cp', resp_compra=\"".
		$resp_compra."\", resp_pago=\"".$resp_pago ."\", lugar_entrega=\"". $lugar_entrega . "\" where cod=$codid";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Error al ingresar nuevo cliente. Intente Nuevamente.'));
}
}
?>