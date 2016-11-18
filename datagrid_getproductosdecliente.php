<?php

$strCodigo = $_REQUEST['codcliente'];  
      
    include 'conn.php';  
    
	$Query = "SELECT id, DATE_FORMAT( fecha_venta, '%d/%m/%Y' ) AS fecha_venta, cod_producto, desc_producto, cant, precio_unitario FROM productos_cliente WHERE codcliente = '$strCodigo' ORDER BY cod_producto, desc_producto, fecha_venta DESC";
	  	  
    $rs = 	mysql_query($Query);  
	
	
    $items = array();  
    while($row = mysql_fetch_object($rs)){  
        array_push($items, $row);  
    }  

    echo json_encode($items);  
	
?>