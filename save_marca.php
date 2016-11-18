<?php

$marca_titulo = strtoupper($_REQUEST['marca_titulo']);

if (isset($_REQUEST['marca_titulo'])){			
			
include 'conn.php';

$sql = "insert into marcas(marca_titulo) values('$marca_titulo')";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Ocurrio un error al guardar la nueva marca.'));
}
}
?>