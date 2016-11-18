<?php
include "php_functions.php";
include 'conn.php';		
	
$marcaid = GetParameter('id');
$marcatitulo = GetParameter('marca_titulo');

$BoxMsg = "";


if ($marcaid != '' && $marcatitulo != ''){

	//se puede continuar
		$queryy = "UPDATE marcas SET marca_titulo = '{$marcatitulo}' where marca_id = {$marcaid} ";
		$resultInsertaEditaAuto = mysql_query($queryy);	
		
if ($resultInsertaEditaAuto){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Ocurrio un error al guardar el cliente.'));
}

}
?>