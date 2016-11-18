<?php
include "php_functions.php";
include 'conn.php';		
	
$autoid = GetParameter('id_auto21');
$cliente_anterior = GetParameter('cliente_anterior');
$cliente_nuevo = GetParameter('cliente_nuevo');

$BoxMsg = "";
if ($autoid != '' && $cliente_nuevo != ''){
		

		//VERIFICO EL CLIENTE NUEVO EXISTE
		$query = "SELECT * FROM cliente WHERE cod = '$cliente_nuevo' LIMIT 1";
		$resultS = mysql_query($query);
		$msg_data2 = mysql_fetch_array($resultS);
		$num_rows2 = mysql_num_rows($resultS);
		
		if($num_rows2>0){
					
			$queryNuevo = "UPDATE auto SET codcli = '{$cliente_nuevo}'	WHERE id = '{$autoid}'";
			$resultNuevoCliente = mysql_query($queryNuevo);	
			
			$queryNuevo2 = "INSERT INTO auto_cliente (id_auto, cod_cliente_desde, cod_cliente_hacia, fecha) VALUES('{$autoid}', '{$cliente_anterior}', '{$cliente_nuevo}', CURDATE())";
			$resultNuevoCliente2 = mysql_query($queryNuevo2);	
			
			if ($resultNuevoCliente2 || $resultNuevoCliente) {
								//El auto fue transferido exitosamente.
								$BoxMsg = "OK";
								
								}
			
			
		}else{
		
		$BoxMsg = "El cliente no es un cliente valido en el sistema.";
		
		}
	

		

}else {

	$BoxMsg = "Faltan datos para poder procesar. No se hicieron cambios.";

}
echo $BoxMsg;

?>