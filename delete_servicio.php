<?php
include "php_functions.php";
include 'conn.php';		
	
$servicioid = GetParameter('id');

$BoxMsg = "";
if ($servicioid != ''){

	$query ="DELETE FROM servicio WHERE id = '". $servicioid . "'";
	$result = mysql_query($query);
	if ($result) $BoxMsg= "Se ha eliminado el servicio exitosamente.";
		else $BoxMsg= "Error: No se ha eliminado el servicio.";

}else {

	$BoxMsg= "Error: No se ha eliminado el servicio.";

}
echo $BoxMsg;
?>