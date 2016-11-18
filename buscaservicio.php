<?php
include "php_functions.php";
include "conn.php";		
	
$patente = strtoupper(GetParameter('id'));
$codcli = GetParameter('cid');

if($codcli){
		$queryCk1 = "SELECT id FROM auto WHERE patente = '$patente' AND codcli = '$codcli' LIMIT 1";

}else{
//De la patente tengo que obtener el id  del auto
		$queryCk1 = "SELECT id FROM auto WHERE patente = '$patente' LIMIT 1";
		}
		
		$resultCk1 = mysql_query($queryCk1);
		$msg_data2 = mysql_fetch_array($resultCk1);
		$autoid = $msg_data2['id'];

		//Obtengo todos los servicios
$queryMarcas = "SELECT DAY(S.fecha) as dia, MONTH(S.fecha) AS mes, YEAR(S.fecha) AS anio, S.* FROM servicio as S WHERE auto_id = '{$autoid}' order by fecha desc, km_actual desc";
$resultM = mysql_query($queryMarcas);
$cant = mysql_num_rows($resultM);

$hint="";
if($cant >0){
	$ii = 1;
	$hint.="<div id=\"AccordionContainer\" class=\"AccordionContainer\">";

	
	
	while($msg_data = mysql_fetch_array($resultM)){
	
		
		
			$id_servicio = $msg_data['id']; 
			$km_actual = $msg_data['km_actual']; 
			$aceite = $msg_data['aceite']; 
			$faceite = $msg_data['faceite']; 
			$faire = $msg_data['faire']; 
			$fcomb = $msg_data['fcomb']; 
			if(isset($msg_data['fhab']) && $msg_data['fhab']!=''){$fhab = $msg_data['fhab'];  }
			$frenos = $msg_data['frenos']; 
			$caja = $msg_data['caja']; 
			$dif = $msg_data['dif']; 
			$dirhid = $msg_data['dirhid']; 
			$ref = $msg_data['ref']; 
			$aditivo_motor = $msg_data['aditivo_motor']; 
			$aditivo_caja = $msg_data['aditivo_caja']; 	
		
			
			if(isset($msg_data['aditivo_comb']) && $msg_data['aditivo_comb']!=''){$aditivo_comb = $msg_data['aditivo_comb'];  }else {$aditivo_comb = '';} 
			if(isset($msg_data['chequeo_luces']) && $msg_data['chequeo_luces']!=''){$chequeo_luces = $msg_data['chequeo_luces'];  }else {$chequeo_luces = '';} 
			if(isset($msg_data['escobillas']) && $msg_data['escobillas']!=''){$escobillas = $msg_data['escobillas'];  }else {$escobillas = '';} 
			$otro = $msg_data['otro']; 
			$km_vuelta = $msg_data['km_vuelta']; 
			$total = $msg_data['total']; 
			
			if(isset($msg_data['p_aceite']) && $msg_data['p_aceite']!=''){$p_aceite = $msg_data['p_aceite'];  }else {$p_aceite = '';} 
			if(isset($msg_data['p_faceite']) && $msg_data['p_faceite']!=''){$p_faceite = $msg_data['p_faceite'];  }else {$p_faceite = '';} 
			if(isset($msg_data['p_faire']) && $msg_data['p_faire']!=''){$p_faire = $msg_data['p_faire'];  }else {$p_faire = '';} 
			if(isset($msg_data['p_fcomb']) && $msg_data['p_fcomb']!=''){$p_fcomb = $msg_data['p_fcomb'];  }else {$p_fcomb = '';} 
			if(isset($msg_data['p_fhab']) && $msg_data['p_fhab']!=''){$p_fhab = $msg_data['p_fhab'];  }else {$p_fhab = '';} 
			if(isset($msg_data['p_frenos']) && $msg_data['p_frenos']!=''){$p_frenos = $msg_data['p_frenos'];  }
			$p_caja = $msg_data['p_caja']; 
			$p_dif = $msg_data['p_dif']; 
			$p_dirhid = $msg_data['p_dirhid']; 
			$p_ref = $msg_data['p_ref']; 
			$p_ad_motor = $msg_data['p_ad_motor']; 
			$p_ad_caja = $msg_data['p_ad_caja']; 
			$p_ad_comb = $msg_data['p_aditivo_comb']; 
			$p_chequeo_luces = $msg_data['p_chequeo_luces']; 
			$p_escobillas = $msg_data['p_escobillas']; 
			$p_otro = $msg_data['p_otro']; 
			$presion_neum = $msg_data['presion_neum']; 
			$estado_past_freno = $msg_data['estado_past_freno']; 
			$p_estado_past_freno = $msg_data['p_estado_past_freno']; 
			$otro2 = $msg_data['otro2']; 
			$otro3 = $msg_data['otro3']; 
			$p_otro2 = $msg_data['p_otro2']; 
			$p_otro3 = $msg_data['p_otro3']; 
			
			//$fecha = $msg_data['fecha']; 
			//formateo la fecha
			$dia = $msg_data['dia']; 
			$mes = $msg_data['mes']; 
			$anio = $msg_data['anio']; 
			
						$hint .= "<div onclick=\"runAccordion($ii);\">  <div class=\"AccordionTitle\" > 
						&nbsp;&nbsp;$dia / $mes / $anio  -  Km: {$km_actual}  </div></div>
					<div id=\"Accordion{$ii}Content\" class=\"AccordionContent\">
					<div style='margin: 10px; background: transparent; padding-top: 5px; '>
					<span style='color: #222; font-size: 13px; margin-left: 10px; '>Km Vuelta: </span>
							<span style='color: #000; font-size: 14px;font-weight: bold;'>{$km_vuelta}  </span>
					<span style='font-weight: bold; color: #00376A; margin-left: 120px;'>TOTAL: <b>$ {$total}</b></span>
					 <a href='#' class='nuevo' onclick='javascript:abrirform($id_servicio)'>Nuevo Servicio</a>
					 <a href='#' class='nuevo2' onclick='javascript:borrarservicio($id_servicio)'>Eliminar</a>
										
					<table style='margin-top: 8px;'><tr><td>
				
						<table class='tableservicio' cellpadding=3 cellspacing=0 width=290px;>
						<tr class='simple2'><td style='text-align: right; width: 95px;' >Aceite: </td><td class='destaca'>{$aceite}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right; width: 45px; font-weight: bold; color: #00376A;'>{$p_aceite}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Filtro Aceite: </td><td class='destaca'>{$faceite}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_faceite}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Filtro Aire: </td><td class='destaca' >{$faire}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_faire}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Filtro Comb.: </td><td class='destaca' >{$fcomb}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_fcomb}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Filtro Habit.: </td><td class='destaca' >{$fhab}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_fhab}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Fluido Frenos: </td><td class='destaca' >{$frenos}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_frenos}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Fluido Caja: </td><td class='destaca'>{$caja}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_caja}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Fluido Dif.: </td><td class='destaca'>{$dif}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_dif}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Dir. Hidr.: </td><td class='destaca'>{$dirhid}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_dirhid}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Refrig.: </td><td class='destaca'>{$ref}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_ref}</td></tr>		  					
					</table>				
						
					</td><td style='width:6px;'></td><td>
						<table class='tableservicio' cellpadding=3 cellspacing=0 width=280px;>
						<tr class='odd2'><td style='text-align: right; width: 95px;'>Adit. Motor: </td><td class='destaca'>{$aditivo_motor}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right; width: 45px;font-weight: bold; color: #00376A;'>{$p_ad_motor}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Adit. Comb.: </td><td class='destaca'>{$aditivo_comb}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_ad_comb}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Adit. Caja/Dif.: </td><td class='destaca'>{$aditivo_caja}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_ad_caja}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Luces: </td><td class='destaca'>{$chequeo_luces}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_chequeo_luces}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Escobillas: </td><td class='destaca'>{$escobillas}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_escobillas}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Pres. Neum.: </td><td colspan=3 class='destaca'>{$presion_neum}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Past. Frenos:  </td><td class='destaca'>{$estado_past_freno}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_estado_past_freno}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Otro: </td><td class='destaca'>{$otro}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_otro}</td></tr>		  
						<tr class='odd2'><td style='text-align: right;'>Otro: </td><td class='destaca'>{$otro2}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_otro2}</td></tr>		  
						<tr class='simple2'><td style='text-align: right;'>Otro: </td><td class='destaca'> {$otro3}</td><td style='width:4px;text-align: right;color: #00376A; font-weight:bold;'>$</td><td style='text-align: right;font-weight: bold; color: #00376A;'>{$p_otro3}</td></tr>		  
					  
						</table>
					</td></tr></table>
			

				</div>
				</div>";
					
								
			$ii = $ii +1; //Accordion 

			}
		
		
		$hint .="</div>  ";
			
		}else{
			$hint="";
			}


// Set output to "no suggestion" if no hint were found
// or to the correct values
if ($hint=="")
  {
  $response="<p style='font-size: 13px; color: red;'> &nbsp;&nbsp;&nbsp;No hay servicios registrados para este vehiculo.</p>
   <a href='#' class='nuevo' onclick='javascript:abrirformvacio($autoid)' style='width: 200px; float: right; margin-right: 10px;'>Nuevo Servicio</a>";  
  }
else
  {
  $response=$hint;
  }

//output the response
echo $response;


?>