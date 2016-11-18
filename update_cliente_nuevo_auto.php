<?php
include("php_functions.php");
include 'conn.php';		
	
$codid = GetParameter('id');
$patente = strtoupper(GetParameter('patente'));
$marca = GetParameter('marca');
$modelo = strtoupper(GetParameter('modelo'));
$motor = strtoupper(GetParameter('motor'));

//Verifico que la patente no exista previamente en el sistema.

$sql1 = "SELECT patente FROM auto where patente='$patente' LIMIT 1";
$result1 = @mysql_query($sql1);
$numero_filas = mysql_num_rows($result1);


if ($numero_filas != 1 && $patente != '' && $codid !=''){
$sql = "INSERT INTO auto (patente, marca, modelo, motor, fecha_creacion, codcli) " .
		" VALUES ('$patente', '$marca', '$modelo', '$motor', CURDATE(), $codid)";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Error en la carga del nuevo auto. Intentelo nuevamente.'));
}
}else{
echo json_encode(array('msg'=>'Error: La patente ingresada ya existe en el sistema.'));

}
?>