<?php

$id = intval($_REQUEST['id']);

include 'conn.php';

if (isset($id)){
//Buscamos todas las patentes que tenga ese cliente y se las desligamos
		$query = "SELECT patente FROM auto WHERE cod = '$id' LIMIT 1";
		$resultS = mysql_query($query);
			
		while ($msg_data = mysql_fetch_array($resultS)){
			$query1 = "UPDATE auto SET cod = NULL WHERE patente = '".$msg_data['patente'] . "';";
			$resultS1 = mysql_query($query1);
		}
	
	$sql = "DELETE FROM cliente WHERE cod=$id";
	$result = @mysql_query($sql);
	
	$sql2 = "DELETE FROM productos_cliente WHERE codcliente=$id";
	$result2 = @mysql_query($sql2);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'No se ha borrado el cliente porque ha ocurrido un error. Vuelva a intenrarlo.'));
}

}
?>