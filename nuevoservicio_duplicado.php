<?php
include "php_functions.php";
include "conn.php";		
	
$id_servicio = GetParameter('id');
$auto_id = GetParameter('aid');

//Obtengo todos los servicios
$queryMarcas = "SELECT DAY(S.fecha) as dia, MONTH(S.fecha) AS mes, YEAR(S.fecha) AS anio, S.* FROM servicio as S WHERE id = '$id_servicio' LIMIT 1";
$resultM = mysql_query($queryMarcas);
$cant = mysql_num_rows($resultM);


$hint="";
if($cant >0){

//Obtengo los datos del servicio
$msg_data = mysql_fetch_array($resultM);
			$autoid = $msg_data['auto_id']; 
			$id_servicio = $msg_data['id']; 
			//$km_actual = $msg_data['km_actual']; 
			$aceite = $msg_data['aceite']; 
			$faceite = $msg_data['faceite']; 
			$faire = $msg_data['faire']; 
			$fcomb = $msg_data['fcomb']; 
			if(isset($msg_data['fhab']) && $msg_data['fhab']!=''){$fhab = $msg_data['fhab'];  }else { $fhab = "";} 
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
			if(isset($msg_data['presion_neum']) && $msg_data['presion_neum']!=''){$presion_neum = $msg_data['presion_neum'];  }else {$presion_neum = '';} 
			if(isset($msg_data['estado_past_freno']) && $msg_data['estado_past_freno']!=''){$estado_past_freno = $msg_data['estado_past_freno'];  }else {$estado_past_freno = '';} 
			if(isset($msg_data['otro']) && $msg_data['otro']!=''){$otro = $msg_data['otro'];  }else {$otro = '';} 
			if(isset($msg_data['otro2']) && $msg_data['otro2']!=''){$otro2 = $msg_data['otro2'];  }else {$otro2 = '';} 
			if(isset($msg_data['otro3']) && $msg_data['otro3']!=''){$otro3 = $msg_data['otro3'];  }else {$otro3 = '';} 
			
			$km_vuelta = $msg_data['km_vuelta']; 
			$total = $msg_data['total']; 
			$modopago = $msg_data['modopago']; 	
			
			
			if(isset($msg_data['p_aceite']) && $msg_data['p_aceite']!='' && $msg_data['p_aceite']!='0.00'){$p_aceite = $msg_data['p_aceite'];  }else {$p_aceite = "0";} 
			if(isset($msg_data['p_faceite']) && $msg_data['p_faceite']!='' && $msg_data['p_faceite']!='0.00'){$p_faceite = $msg_data['p_faceite'];  }else {$p_faceite = "0";} 
			if(isset($msg_data['p_faire']) && $msg_data['p_faire']!='' && $msg_data['p_faire']!='0.00'){$p_faire = $msg_data['p_faire'];  }else {$p_faire = "0";} 
			if(isset($msg_data['p_fcomb']) && $msg_data['p_fcomb']!='' && $msg_data['p_fcomb']!='0.00'){$p_fcomb = $msg_data['p_fcomb'];  }else {$p_fcomb = "0";} 
			if(isset($msg_data['p_fhab']) && $msg_data['p_fhab']!='' && $msg_data['p_fhab']!='0.00'){$p_fhab = $msg_data['p_fhab'];  }else {$p_fhab = "0";} 
			if(isset($msg_data['p_frenos']) && $msg_data['p_frenos']!='' && $msg_data['p_frenos']!='0.00'){$p_frenos = $msg_data['p_frenos'];  }else { $p_frenos = "0";}
			if(isset($msg_data['p_caja']) && $msg_data['p_caja']!='' && $msg_data['p_caja']!='0.00'){$p_caja = $msg_data['p_caja'];  }else { $p_caja = "0";}
			if(isset($msg_data['p_dif']) && $msg_data['p_dif']!='' && $msg_data['p_dif']!='0.00'){$p_dif = $msg_data['p_dif'];  }else { $p_dif = "0";}
			if(isset($msg_data['p_dirhid']) && $msg_data['p_dirhid']!='' && $msg_data['p_dirhid']!='0.00'){$p_dirhid = $msg_data['p_dirhid'];  }else { $p_dirhid = "0";}
			if(isset($msg_data['p_ref']) && $msg_data['p_ref']!='' && $msg_data['p_ref']!='0.00' ){$p_ref = $msg_data['p_ref'];  }else { $p_ref = "0";}
			if(isset($msg_data['p_ad_motor']) && $msg_data['p_ad_motor']!=''  && $msg_data['p_ad_motor']!='0.00'){$p_ad_motor = $msg_data['p_ad_motor'];  }else { $p_ad_motor = "0";}
			if(isset($msg_data['p_ad_caja']) && $msg_data['p_ad_caja']!=''  && $msg_data['p_ad_caja']!='0.00'){$p_ad_caja = $msg_data['p_ad_caja'];  }else { $p_ad_caja = "0";}
			if(isset($msg_data['p_aditivo_comb']) && $msg_data['p_aditivo_comb']!=''  && $msg_data['p_aditivo_comb']!='0.00'){$p_aditivo_comb = $msg_data['p_aditivo_comb'];  }else { $p_aditivo_comb = "0";}
		
			if(isset($msg_data['p_chequeo_luces']) && $msg_data['p_chequeo_luces']!='' && $msg_data['p_chequeo_luces']!='0.00'){$p_chequeo_luces = $msg_data['p_chequeo_luces'];  }else { $p_chequeo_luces = "0";}
			if(isset($msg_data['p_escobillas']) && $msg_data['p_escobillas']!='' && $msg_data['p_escobillas']!='0.00'){$p_escobillas = $msg_data['p_escobillas'];  }else { $p_escobillas = "0";}
			if(isset($msg_data['p_otro']) && $msg_data['p_otro']!='' && $msg_data['p_otro']!='0.00'){$p_otro = $msg_data['p_otro'];  }else { $p_otro = "0";}
			if(isset($msg_data['p_otro2']) && $msg_data['p_otro2']!='' && $msg_data['p_otro2']!='0.00'){$p_otro2 = $msg_data['p_otro2'];  }else { $p_otro2 = "0";}
			if(isset($msg_data['p_otro3']) && $msg_data['p_otro3']!='' && $msg_data['p_otro3']!='0.00'){$p_otro3 = $msg_data['p_otro3'];  }else { $p_otro3 = "0";}
			if(isset($msg_data['p_estado_past_freno']) && $msg_data['p_estado_past_freno']!='' && $msg_data['p_estado_past_freno']!='0.00'){$p_estado_past_freno = $msg_data['p_estado_past_freno'];  }else { $p_estado_past_freno = "0";}

								
}else{
	$autoid = $auto_id;
	$aceite = '';
	$faceite = '';
	$faire = '';
	$fcomb = '';
	$fhab = '';
	$frenos = '';
	$caja = '';
	$dif = '';
	$dirhid = '';
	$ref = '';
	$aditivo_motor = '';
	$aditivo_caja = '';			
	
	$aditivo_comb = '';
	$chequeo_luces = '';
	$escobillas = '';
	$presion_neum = '';
	$estado_past_freno = '';
	$otro = '';
	$otro2 = '';
	$otro3 = '';
		
}
$hint="	<form style='display: block; ' class='formservicio' name='serv' id='serv' action='guardaservicio.php' method='post'  onSubmit='return ValidaForm();' > 
		<table>
		 <tr><td colspan=2 style='text-align:left;'><br/>
		 <input type='hidden' name='id_auto1' id='id_auto1' value='{$autoid}' />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Km Actual: <input type='text' size='12' maxlength='7' tabindex='4' name='kmactual' id='kmactual'  style='text-align: center;'/>
			 <span>(Colocar NF si no funciona el cuenta KM.)</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						 Km Vuelta: <input type='text' size='12' maxlength='7' tabindex='5' name='kmvuelta' id='kmvuelta' style='text-align: center;'/>
						
						 </td></tr>
						 
						 <tr><td>
						 
						 <table class='formservicio'><THEAD><TH colspan='2'>Descripci&oacute;n</TH><TH>Precio</TH></THEAD><tbody>
						
						<tr><td>Aceite</td><td><input type='text' size='22' maxlength='24' tabindex='6' name='aceite' id='aceite' value='{$aceite}' style='text-transform: uppercase;'  /></td>
						 <td>&nbsp;$ <input type='text' size='4' id='suma1' maxlength='8' tabindex='27' onKeyUp='ValidaPrecio(this.id);' name='paceite' value='{$p_aceite}'  onClick='this.select();' /></td></tr>
						 
						 <tr><td>F. de Aceite</td><td><input type='text' size='22' maxlength='24' tabindex='7' name='filtroaceite' id='filtroaceite' value='{$faceite}' style='text-transform: uppercase;'  /></td>
						 <td>&nbsp;$ <input type='text' size='4' id='suma2' maxlength='8' tabindex='27' onKeyUp='ValidaPrecio(this.id);' name='pfiltroaceite' value='{$p_faceite}' onClick='this.select();' /></td></tr>
						
						<tr><td>Filtro de Aire</td><td><input type='text' size='22' maxlength='24' tabindex='9' name='filtroaire' id='filtroaire' value='{$faire}' style='text-transform: uppercase;'  /></td> 
						 <td>&nbsp;$ <input type='text' size='4' maxlength='8' onKeyUp='ValidaPrecio(this.id);' name='pfiltroaire' tabindex='27' id='suma3' value='{$p_faire}' onClick='this.select();' /></td></tr>
						 
						  <tr><td>Filtro Comb.</td><td><input type='text' size='22' maxlength='24' tabindex='10' name='filtrocomb' id='filtrocomb' value='{$fcomb}' style='text-transform: uppercase;'/></td>
						  <td>&nbsp;$ <input type='text' size='4' maxlength='8' onKeyUp='ValidaPrecio(this.id);' tabindex='28' id='suma4' name='pfiltrocomb' value='{$p_fcomb}' onClick='this.select();'/></td></tr> 
						 
						 <tr><td>Filtro Habit.</td><td><input type='text' size='22' maxlength='24' tabindex='11' name='filtrohab' id='filtrohab' value='{$fhab}' style='text-transform: uppercase;'/></td><td>&nbsp;$
						 <input type='text' size='4' maxlength='8' onKeyUp='ValidaPrecio(this.id);' name='pfiltrohab' tabindex='29' id='suma5' value='{$p_fhab}' onClick='this.select();'/></td></tr>  
						 
						 <tr><td>Fluido Frenos</td><td><input type='text' size='22' maxlength='24' tabindex='12' name='fluidofrenos' id='fluidofrenos' value='{$frenos}' style='text-transform: uppercase;'/></td><td>&nbsp;$ 
						 <input type='text' size='4' maxlength='8' name='pfluidofrenos' onKeyUp='ValidaPrecio(this.id);' tabindex='30' id='suma6' value='{$p_frenos}' onClick='this.select();'/></td></tr>  
						 
						 <tr><td>Aceite Caja</td><td><input type='text' size='22' maxlength='24' tabindex='13' name='aceitecaja' id='aceitecaja' value='{$caja}' style='text-transform: uppercase;'/></td><td>&nbsp;$ 
						 <input type='text' size='4' maxlength='8'  name='paceitecaja' onKeyUp='ValidaPrecio(this.id);' tabindex='31' id='suma7' value='{$p_caja}' onClick='this.select();'/></td></tr> 
						 
						 <tr><td>Aceite Dif.</td><td><input type='text' size='22' maxlength='24' tabindex='14' name='aceitedif' id='aceitedif' value='{$dif}' style='text-transform: uppercase;'/></td><td>&nbsp;$ 
						<input type='text' size='4' maxlength='8' name='paceitedif' onKeyUp='ValidaPrecio(this.id);' tabindex='32' id='suma8' value='{$p_dif}' onClick='this.select();'/></td></tr>  
						 
						 <tr><td>Fluido Dir. HID</td><td><input type='text' size='22' maxlength='24' tabindex='15' name='fluidodir' id='fluidodir' value='{$dirhid}' style='text-transform: uppercase;'/></td><td>&nbsp;$
						 <input type='text' size='4' maxlength='8' name='pfluidodir' onKeyUp='ValidaPrecio(this.id);' tabindex='33' id='suma9' value='{$p_dirhid}' onClick='this.select();'/></td></tr>  
						 
						 <tr><td>Fluido Refrig.</td><td><input type='text' size='22' maxlength='24' tabindex='16' name='fluidoref' id='fluidoref' value='{$ref}' style='text-transform: uppercase;'/></td><td>&nbsp;$
						 <input type='text' size='4' maxlength='8' name='pfluidoref' onKeyUp='ValidaPrecio(this.id);' tabindex='34' id='suma10' value='{$p_ref}' onClick='this.select();'/></td></tr> 
						 
						

						 
						 </tbody></table>
						 
						 </td><td>
						 
								  <table class='formservicio'><thead><TH colspan='2'>Descripci&oacute;n</TH><TH>Precio</TH></thead><tbody>

								 <tr><td>Adit. Motor</td><td><input type='text' size='22' maxlength='24' tabindex='17' name='aditivomotor' id='aditivomotor' value='{$aditivo_motor}' style='text-transform: uppercase;'  /></td><td>&nbsp;$
								 <input type='text' size='4'   maxlength='8' name='paditivomotor' onKeyUp='ValidaPrecio(this.id);' tabindex='35' id='suma11' value='{$p_ad_motor}'  onClick='this.select();' /></td></tr>  
								 <tr><td>Adit. Comb.</td><td><input type='text' size='22' maxlength='24' tabindex='18' name='aditivocomb' id='aditivocomb' value='{$aditivo_comb}' style='text-transform: uppercase;'  /></td><td>&nbsp;$
								 <input type='text' size='4' value='{$p_aditivo_comb}' maxlength='8'  name='paditivocomb' onKeyUp='ValidaPrecio(this.id);' tabindex='36' id='suma12'  onClick='this.select();' /></td></tr>  
								 <tr><td>Adit. Caja/Dif.</td><td><input type='text' size='22' maxlength='24' tabindex='19' name='aditivocajaydif' id='aditivocajaydif' value='{$aditivo_caja}' style='text-transform: uppercase;'  /></td>
								 <td>&nbsp;$ <input type='text' size='4' value='{$p_ad_caja}'  maxlength='8' name='paditivocajaydif' onKeyUp='ValidaPrecio(this.id);' tabindex='37' id='suma13'  onClick='this.select();'/></td></tr>  
								 <tr><td>Chequeo Luces</td><td><input type='text' size='22' maxlength='24' tabindex='20' name='luces' id='luces' value='{$chequeo_luces}' style='text-transform: uppercase;'/></td>
								 <td>&nbsp;$ <input type='text' size='4' name='pluces'   maxlength='8' value='{$p_chequeo_luces}'  onKeyUp='ValidaPrecio(this.id);' tabindex='38' id='suma14' onClick='this.select();'/></td></tr>  
								 <tr><td>Escobillas</td><td><input type='text' size='22' maxlength='24' tabindex='21' name='escobillas'  value='{$escobillas}' id='escobillas' style='text-transform: uppercase;'/></td>
								 <td>&nbsp;$ <input type='text' size='4'  name='pescobillas' value='{$p_escobillas}'  maxlength='8' onKeyUp='ValidaPrecio(this.id);' tabindex='39' id='suma15' onClick='this.select();'/></td></tr>  
								 <tr><td>Presi&oacute;n Neum.</td><td><input type='text' size='22' maxlength='24' tabindex='22' name='presion' id='presion' value='{$presion_neum}' style='text-transform: uppercase;'/></td><td></td></tr>  
								 <tr><td>Past. de Freno</td><td><input type='text' size='22' maxlength='24' tabindex='23' name='pastillasfrenos' id='pastillasfrenos' value='{$estado_past_freno}' style='text-transform: uppercase;'/></td>
								 <td>&nbsp;$ <input type='text' size='4'   maxlength='8' name='ppastillasfrenos' value='{$p_estado_past_freno}' onKeyUp='ValidaPrecio(this.id);' tabindex='40' id='suma16' onClick='this.select();'/></td></tr>  
								 <tr><td>Otro</td><td><input type='text' size='22' maxlength='24' tabindex='24' name='otro' id='otro' value='{$otro}' style='text-transform: uppercase;' /></td><td>&nbsp;$ <input type='text' size='4' maxlength='8' name='p_otro' value='{$p_otro}' onKeyUp='ValidaPrecio(this.id);' tabindex='41' id='suma17' onClick='this.select();'/></td></tr> 
								 <tr><td>Otro</td><td><input type='text' size='22' maxlength='24' tabindex='25' name='otro2' id='otro2' value='{$otro2}' style='text-transform: uppercase;'/></td><td>&nbsp;$ <input type='text' size='4' maxlength='8' name='p_otro2' value='{$p_otro2}'  onKeyUp='ValidaPrecio(this.id);' tabindex='42' id='suma18' onClick='this.select();'/></td></tr>  
								 <tr><td>Otro</td><td><input type='text' size='22' maxlength='24' tabindex='26' name='otro3' id='otro3' value='{$otro3}' style='text-transform: uppercase;'/></td><td>&nbsp;$ <input type='text' size='4' maxlength='8' name='p_otro3' value='{$p_otro3}'  onKeyUp='ValidaPrecio(this.id);' tabindex='43' id='suma19' onClick='this.select();'/></td></tr>  
								
								 
								 </tbody></table>
						 
						 </td></tr>
						 
						  <tr style='line-height: 40px;'><td style='text-align: left;'>
						 <b> &nbsp;&nbsp;&nbsp;&nbsp;Pago con:&nbsp;</b>
						 
						<br/>
						 <span style='font: 14px Calibri, Arial;'>
						 &nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='modopago' value='1' tabindex='45'"; 
									if($modopago=="1"){ $hint .= " checked"; } 
						 $hint .= "> Efectivo &nbsp;&nbsp;<input type='radio' name='modopago' value='2' tabindex='46' ";
								if($modopago=="2"){ $hint .= " checked"; } 
						 $hint .= "> D&eacute;bito &nbsp;&nbsp;<input type='radio' name='modopago' value='3' tabindex='47' ";
						 		if($modopago=="3"){ $hint .= " checked"; } 
						 $hint .= "> Cr&eacute;dito  &nbsp;&nbsp;<input type='radio' name='modopago' value='4' tabindex='48' ";
						 
						 $hint .= " > Cheque </span>
						 </td> 
						 <td><b>Total: $ </b> <input type='text' size='6' id='total' name='total' style='border: 2px solid #B40404;' tabindex='44'/></td></tr>
						 <tr><td colspan='2'><input type='submit' name='Aceptar' value='Aceptar' style='cursor:pointer; border-radius:18px;font: 14px Arial, Calibri, Verdana; padding-bottom:2px; border: 1px solid #CC5200;'/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' name='Restaurar' value='Restaurar' style='font: 14px Arial, Calibri, Verdana; cursor:pointer; border-radius:18px;padding-bottom:2px; border: 1px solid #CC5200;'/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type='button' name='Cancelar' value='Cancelar' style='font: 14px Arial, Calibri, Verdana; cursor:pointer; border-radius:18px;padding-bottom:2px; border: 1px solid #CC5200; margin-right: 110px;' onClick='cerrarventana()'/>
						</td></tr>
						 </table>
						 </form>	

    ";
	
	
	
	
	echo $hint;
	?>
	
	