<?php

$strCodigo = $_REQUEST['codcliente'];  
      
    include 'conn.php';  
    
	$Query = "SELECT A.patente, A.modelo, A.motor, M.marca_titulo as marca FROM auto as A " .
				" INNER JOIN marcas AS M ON A.marca = M.marca_id " .
				" INNER JOIN cliente as C ON A.codcli = C.cod " .
				" WHERE C.cod = '$strCodigo' ORDER BY A.patente";
	  	  
    $rs = 	mysql_query($Query);  
		
    $items = array();  
    while($row = mysql_fetch_object($rs)){  
        array_push($items, $row);  
    }  
    echo json_encode($items);  

?>