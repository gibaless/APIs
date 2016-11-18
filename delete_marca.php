<?php

$id = intval($_REQUEST['id']);

include 'conn.php';

if (isset($id)){
	
	$sql = "DELETE FROM marcas WHERE marca_id=$id";
	$result = @mysql_query($sql);
	
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'No se ha borrado la marca porque ha ocurrido un error. Vuelva a intenrarlo.'));
}

}
?>