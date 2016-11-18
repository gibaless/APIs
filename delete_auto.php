<?php
include "php_functions.php";
include 'conn.php';		
	
$autoid = GetParameter('id_auto31');

$BoxMsg = "";
if ($autoid != ''){

	$query ="UPDATE auto SET codcli = '00' WHERE id = '". $autoid . "'";
	$result = mysql_query($query);
	if ($result) $BoxMsg= "Se ha dado de baja el vehiculo exitosamente.";
		else $BoxMsg= "Error: No se ha dado de baja el vehiculo.";

}else {

	$BoxMsg= "Error: No se ha dado de baja el vehiculo.";

}
echo $BoxMsg;
?>