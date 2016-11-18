<?php
include "php_functions.php";
include 'conn.php';		
	
$autoid = GetParameter('id_auto11');
$nuevo_patente = strtoupper(GetParameter('patente11'));
$nuevo_marca_id = GetParameter('marca11');
$nuevo_modelo = strtoupper(GetParameter('modelo11'));
$nuevo_motor = strtoupper(GetParameter('motor11'));
$BoxMsg = "";


if ($nuevo_patente != '' && $autoid != ''){

	//Verifico que la patente haya cambiado
	$query2 = "SELECT A.patente FROM auto as A WHERE id = $autoid LIMIT 1";
	$result2 = mysql_query($query2);
	$msg_data2 = mysql_fetch_array($result2);

	if( $msg_data2['patente']!= '' && strtoupper($msg_data2['patente']) == $nuevo_patente ){
	//Si son iguales entonces no necesito cambiar ni verificar la patente por duplicados
	
	//se puede continuar
		$queryy = "UPDATE auto SET marca={$nuevo_marca_id} , modelo='{$nuevo_modelo}',
		motor = '{$nuevo_motor}'  where id = $autoid ";
		$resultInsertaEditaAuto = mysql_query($queryy);	
		
		if($resultInsertaEditaAuto){ $BoxMsg = "OK";  }

	}else{
	//Si no son iguales entonces debo primero verificar que la patente nueva elegida ya no exista en el sistema
	
	//Verifico que la patente no exista:
		$query = "SELECT A.* FROM auto as A WHERE patente = '$nuevo_patente' LIMIT 1";
		$resultS = mysql_query($query);
		$msg_data = mysql_fetch_array($resultS);
		$num_rows = mysql_num_rows($resultS);
	
			if($num_rows==0){
			
			//se puede continuar
			$queryy1 = "UPDATE auto SET patente= '{$nuevo_patente}', marca={$nuevo_marca_id} , modelo='{$nuevo_modelo}',
			motor = '{$nuevo_motor}'  where id = $autoid ";
			$resultInsertaEditaPatente = mysql_query($queryy1);	
			if($resultInsertaEditaPatente){ $BoxMsg = "OK";  }
		
		
			}else{
				//no debo continuar la patente ya existe y no le debo permitir a la persona registrar esa patente.
				$BoxMsg= "La Patente ingresada ya existe.";
				
				}
	
		}
		

}else {

	$BoxMsg= "Faltan datos para poder procesar. No se hicieron cambios.";

}
echo $BoxMsg;
?>