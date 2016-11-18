<?php
include "conn.php";
include "php_functions.php";
//get the q parameter from URL recibe la patente
$id=GetParameter('id');
$codcli=GetParameter('cid');

$num_autos_del_cliente ='';
if($id){

		//Verifico no sea un auto inactivo
		$queryCk1 = "SELECT codcli FROM auto WHERE patente = '$id' LIMIT 1";
		$resultCk1 = mysql_query($queryCk1);
		$msg_dataCk1 = mysql_fetch_array($resultCk1);
		$cod_cliente1 = $msg_dataCk1['codcli'];
		
		
		if($cod_cliente1 != "0"){
		
				//OBTENGO DATOS DEL CLIENTE desde la patente
				$query = "SELECT C.* FROM auto as A INNER JOIN cliente as C ON A.codcli = C.cod WHERE A.patente = '$id' LIMIT 1";
				$resultS = mysql_query($query);
				$msg_data2 = mysql_fetch_array($resultS);
				$num_rows = mysql_num_rows($resultS);
				
				if($num_rows > 0) $cod_cliente = $msg_data2['cod'];
				
				if($codcli){
						$query2 = "SELECT C.*, A.id, A.patente, A.modelo, A.motor, M.marca_titulo FROM auto as A " .
								" INNER JOIN marcas AS M ON A.marca = M.marca_id " .
								" INNER JOIN cliente as C ON A.codcli = C.cod " .
								" WHERE A.codcli = '$codcli' ORDER BY A.patente";
				}else{
				
								$query2 = "SELECT C.*, A.id, A.patente, A.modelo, A.motor, M.marca_titulo FROM auto as A " .
								" INNER JOIN marcas AS M ON A.marca = M.marca_id " .
								" INNER JOIN cliente as C ON A.codcli = C.cod " .
								" WHERE A.codcli = '$cod_cliente' ORDER BY A.patente";
				
								$codcli=$cod_cliente1;
				}
		
							
				$resultS2 = mysql_query($query2);
				
				//Paginacion
				$cant_per_page = 4;
				$num_autos_del_cliente = mysql_num_rows($resultS);
				$pages = $num_autos_del_cliente / $cant_per_page;
				// FIN Paginacion
				

				$i = 0;
				$hint="";
				while ($msg_data = mysql_fetch_array($resultS2)){
				
				if($i == 0){
					$i=2;
					$patente = $msg_data['patente'];
					$cliente = $msg_data['apellido'];
					$marca = $msg_data['marca_titulo'];
					$modelo = $msg_data['modelo'];
					$motor = $msg_data['motor'];
					$tel = $msg_data['telefono'];
					$cel = $msg_data['celular'];
					$cuit = $msg_data['cuit'];
					$email = $msg_data['email'];
					$direccion = $msg_data['direccion'];
					$localidad = $msg_data['localidad'];
					$cp = $msg_data['cp'];
					$resp_compra = $msg_data['resp_compra'];
					$resp_pago = $msg_data['resp_pago'];
					$lugar_entrega = $msg_data['lugar_entrega'];
					
					
					$hint.="<form name='FormCliente' id='FormCliente' class='stnform'>			
						<input type='hidden' id='id_cliente' value='$cod_cliente' />" . 		
					"<span class='patente'><b>" . $cliente . 
					"</b></span> <span class='patente'>(". $codcli.")</span><br/>";
					if($cuit!=''){ 
							$hint.= "<div><label>CUIT: </label> <b>" . $cuit . "</b> &nbsp;&nbsp;&nbsp;</div>";
					}
					if($tel!=''){ 
							$hint.= "<div><label>Tel: </label> <b>" . $tel . "</b> &nbsp;&nbsp;&nbsp;</div>";
					}
					if($cel!=''){ 
							$hint.= "<div><label>Cel: </label> <b>" . $cel . "</b> &nbsp;&nbsp;&nbsp;</div>";
					}
					if($email!=''){ 
							$hint.= "<div><label>E-mail: </label> <b>" . $email . "</b> &nbsp;&nbsp;&nbsp;</div>";
					}
					if($direccion!=''){ 
							$hint.= "<div><label>Direcci&oacute;n: </label> <b>" . $direccion . " ". $localidad;
									if($cp!='') { $hint.= " CP: " . $cp; }
							
							$hint.= "</b></div>";
					}
					if($resp_compra!=''){ 
							$hint.= "<div><label>Resp Compra: </label> <b>" . $resp_compra . " &nbsp;&nbsp;</b></div>";
					}
					if($resp_pago!=''){ 
							$hint.= "<div><label>Resp Pago: </label><b> " . $resp_pago . " &nbsp;&nbsp;</b></div>";
					}
					if($lugar_entrega!=''){ 
							$hint.= "<div><label>Lugar de Entrega: </label><b> " . $lugar_entrega . " &nbsp;&nbsp;</b></div>";
					}
					$hint.="<p style='width: 90%; background-color: #F5D0A9; padding: 4px 8px; margin-bottom: -2px;'><b>Listado de Autos</b></p>
					<table style='width:99%;'>
					<tr><td><b>" . strtoupper($patente) . "</b><br/>" . $marca ." ". strtoupper($modelo) .", ". strtoupper($motor) .
					"<hr/></td><td>
					<a href=\"javascript:busca('{$patente}', '{$codcli}');\"  ><img src='../images/arrow.png' style='width:26px;' title='Ver Auto'/></a>
					</td></tr>" ;
				
				}else{
					$patente = $msg_data['patente'];
					$marca = $msg_data['marca_titulo'];
					$modelo = $msg_data['modelo'];
					$motor = $msg_data['motor'];
					$hint .= "<tr><td><b>" . strtoupper($patente) . "</b><br/>" . $marca ." ". strtoupper($modelo) .", ". strtoupper($motor) ."
					<hr style='padding:0; margin-bottom:2px;'/></td><td>
					
					<a href=\"javascript:busca('{$patente}', '{$codcli}');\" ><img src='../images/arrow.png' style='width:26px; margin-bottom:8px;' title='Ver Auto'/></a>
					
					</td></tr>";
				
				}
				
				}
		
	
				
					$hint .="</table>";
				
					
				} //Auto desligado
				else{
					$hint="<form name='FormCliente' id='FormCliente'>			
						<input type='hidden' id='id_cliente' value='$codcli' /></form>
						<p style='color: red; font-weight: bold;'>&nbsp;&nbsp;&nbsp; Auto desligado.<br/> <br/></p>";
				}

				
		}else{
			$hint="";
			}


// Set output to "no suggestion" if no hint were found
// or to the correct values
if ($hint=="")
  {
			if($num_autos_del_cliente > 0){
			$response="<p style='color: green; font-weight: bold;'>&nbsp;&nbsp;&nbsp;El cliente no tiene autos registrados.<br/><br/></p>";  
			}else{
			$response="<p style='color: red; font-weight: bold;'>&nbsp;&nbsp;&nbsp; Cliente inexistente.<br/> <br/></p>";  
			}
  }
else
  {

			$response=$hint;

  
  }

//output the response
echo $response;
?>