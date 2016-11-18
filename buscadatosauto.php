<?php
include("conn.php");
include "php_functions.php";
//get the q parameter from URL
$id=strtoupper(GetParameter('id'));
$codcli=GetParameter('cid');

//Obtengo todas las marcas de autos
$queryMarcas = "SELECT* FROM marcas ORDER BY marca_titulo";
$resultM = mysql_query($queryMarcas);
		

if($id){

		if($codcli){
		
		$query = "SELECT A.*, M.* FROM auto as A INNER JOIN marcas AS M ON A.marca = M.marca_id WHERE A.patente = '$id' AND A.codcli = '$codcli' LIMIT 1";
		}else{
		
		$query = "SELECT A.*, M.* FROM auto as A INNER JOIN marcas AS M ON A.marca = M.marca_id WHERE A.patente = '$id' LIMIT 1";
		}
		
		$resultS = mysql_query($query);
		$msg_data = mysql_fetch_array($resultS);
		$num_rows = mysql_num_rows($resultS);
		
		if($num_rows>0){
		$id_auto = $msg_data['id'];
		$patente = strtoupper($msg_data['patente']);
		$marca_id = $msg_data['marca'];
		$modelo = strtoupper($msg_data['modelo']);
		$motor = strtoupper($msg_data['motor']);


		$hint="
		<form name='myForm' id='myForm' > <input type='hidden' id='id_auto' value='$id_auto' />
		<input type='hidden' id='patente1' value='$patente' />
		<input type='hidden' id='marca_id' value='$marca_id' />
		<input type='hidden' id='modelo' value='$modelo' />
		<input type='hidden' id='motor' value='$motor' />
		
		<table class='muestradatosauto'>

 	<tr>
		<th scope='row' class='column1'>Patente</th>
		<td class='muestradatosauto'><b>$patente</b></td>
	</tr>	";
	
	
	while($mm = mysql_fetch_array($resultM)){
	
			if ($mm['marca_id'] == $marca_id) {   
			
			$hint .= "<tr><th scope='row' class='column1'>Marca</th><td class='muestradatosauto'>". $mm['marca_titulo'] . "</td></tr>";
			
			 }
		
		}
		
		
 	$hint .= "<tr><th scope='row' class='column1'>Modelo</th><td class='muestradatosauto'>$modelo</td></tr>	
 	<tr class='odd'><th scope='row' class='column1'>Motor</th><td class='muestradatosauto'>$motor</td></tr>
		</table></form>";
	
		}else{
			$hint="";
			}
		}


// Set output to "no suggestion" if no hint were found
// or to the correct values
if ($hint=="")
  {
  $response="<h4 style='color: red;'>&nbsp;&nbsp;&nbsp;Patente inexistente</h4>";  
  }
else
  {
  $response=$hint;
  }

//output the response
echo $response;
?>