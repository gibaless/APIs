<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>LubriExpress - Software de Gesti&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="icon" href="images/le_icon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="style.css" />
</head>


<body>
<div style="width: 400px; margin:0 0;">
<?php
include("conn.php");
include("php_functions.php");
//get the q parameter from URL


/*

Aceite		 $
Filtro de Aceite		 $
Filtro de Aire		 $
Filtro Combustible		 $
Filtro Habitáculo		 $
Fluido Frenos		 $
Aceite Caja		 $
Aceite Diferencial		 $
Fluido Dirección HID		 $
Fluido Refrigerante		 $
Aditivo Motor		 $
Aditivo Combustible		 $
Aditivo Caja/Diferencial		 $
Chequeo Luces		 $
Escobillas Limpiaparabrisas		 $
Presión Neumáticos		
Estado Pastillas de Freno		 $
Otro		 $
Otro		 $
Otro		 $ 

*/
			$autoid= GetParameter('id_auto1');
			$kmactual=GetParameter('kmactual');
			$kmvuelta=GetParameter('kmvuelta');
			$aceite = GetParameter('aceite'); 
			$faceite = GetParameter('filtroaceite'); 
			$faire = GetParameter('filtroaire'); 
			$fcomb = GetParameter('filtrocomb'); 
			$fhab = GetParameter('filtrohab'); 
			$frenos = GetParameter('fluidofrenos'); 
			$caja = GetParameter('aceitecaja'); 
			$dif = GetParameter('aceitedif'); 
			$dirhid = GetParameter('fluidodir'); 
			$ref = GetParameter('fluidoref'); 
			$aditivo_motor = GetParameter('aditivomotor'); 
			$aditivo_comb = GetParameter('aditivocomb'); 
			$aditivo_caja = GetParameter('aditivocajaydif'); 
			
			$chequeo_luces = GetParameter('luces'); 
			$escobillas = GetParameter('escobillas'); 
			$presion = GetParameter('presion'); 
			$pastillasfrenos = GetParameter('pastillasfrenos'); 
			$otro1 = GetParameter('otro'); 
			$otro2 = GetParameter('otro2'); 
			$otro3 = GetParameter('otro3'); 
			
			$p_otro1 = GetParameter('p_otro'); 
			$p_otro2 = GetParameter('p_otro2'); 
			$p_otro3 = GetParameter('p_otro3'); 
			
			$p_aceite = GetParameter('paceite'); 
			$p_filtroaceite = GetParameter('pfiltroaceite'); 
			$p_faire = GetParameter('pfiltroaire'); 
			$p_fcomb = GetParameter('pfiltrocomb'); 
			$p_fhab = GetParameter('pfiltrohab'); 
			$p_frenos = GetParameter('pfluidofrenos'); 
			$p_caja = GetParameter('paceitecaja'); 
			$p_dif = GetParameter('paceitedif'); 
			$p_dirhid = GetParameter('pfluidodir'); 
			$p_ref = GetParameter('pfluidoref'); 
			$p_ad_motor = GetParameter('paditivomotor'); 	
			$p_ad_comb = GetParameter('paditivocomb'); 
			$p_ad_caja = GetParameter('paditivocajaydif'); 
			$p_chequeo_luces = GetParameter('pluces'); 
			$p_escobillas = GetParameter('pescobillas'); 
			
			$p_pastillasfrenos = GetParameter('ppastillasfrenos'); 
			$modo_de_pago = GetParameter('modopago');
			
				$total = GetParameter('total'); 


if (isset($autoid)){

	//Guardo este servicio para la patente en cuestion
	
		$query = "SELECT A.* FROM auto as A WHERE id = '$autoid' LIMIT 1";
		$resultS = mysql_query($query);
		$msg_data = mysql_fetch_array($resultS);
		$num_rows = mysql_num_rows($resultS);
		
		if($num_rows==1){
		
		//se puede continuar guardando el servicio
		$queryy = "INSERT INTO servicio (`auto_id`, `km_actual`, `aceite`, `faceite`, `faire`, `fcomb`, `fhab`, `frenos`, `caja`, `dif`, " .
				" `dirhid`, `ref`, `aditivo_motor`, `aditivo_comb`, `aditivo_caja`, `chequeo_luces`, `escobillas`, `presion_neum` ,`estado_past_freno`,`otro`, `otro2`, `otro3`, " .
				" `km_vuelta`, `total`, `fecha`, " . 
				" `p_aceite`, `p_faceite`, `p_faire`, `p_fcomb`, `p_fhab`, `p_frenos`, `p_caja`, `p_dif`, `p_dirhid`, `p_ref`, `p_ad_motor`, `p_aditivo_comb`, `p_ad_caja`, " .
				" `p_chequeo_luces`, `p_escobillas`, `p_estado_past_freno`, `p_otro`, `p_otro2`, `p_otro3`, `modopago`) ".
				" VALUES ('$autoid', '$kmactual', '$aceite', '$faceite', '$faire', '$fcomb', '$fhab',  '$frenos', '$caja', '$dif', '$dirhid', '$ref', " .
				" '$aditivo_motor', '$aditivo_comb', '$aditivo_caja', '$chequeo_luces', '$escobillas', '$presion', '$pastillasfrenos', '$otro1', '$otro2', '$otro3', ".
				" '$kmvuelta', '$total', CURDATE(), '$p_aceite', '$p_filtroaceite', '$p_faire', '$p_fcomb', '$p_fhab', '$p_frenos', '$p_caja', '$p_dif', '$p_dirhid', '$p_ref', ".
				" '$p_ad_motor', '$p_ad_comb', '$p_ad_caja', '$p_chequeo_luces', '$p_escobillas', '$p_pastillasfrenos', '$p_otro1', '$p_otro2', '$p_otro3', '$modo_de_pago' ) ";
		$resultInsertaNuevaPatente = mysql_query($queryy);	
		$num_exito = mysql_affected_rows();
		
		//echo "El insert dio:" . $num_exito . "<br/> query:  " . $queryy;
		
	
			}
			
			$msg = "<script>
					alert('El servicio fue guardado exitosamente.');
					window.location = 'servicios.php'</script>";
		}else{
		
		 //$msg = "<p align='center' style='color: RED; font-size: 14px;'>La nueva patente elegida NO existe en el sistema.</P>";
		
		
		}


?>
<div class="column" style="width: 440px;">

<?=$msg?>

</div>
</div>
</body>
</html>