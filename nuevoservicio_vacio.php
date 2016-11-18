<?php
include "php_functions.php";
include "conn.php";		
	
$auto_id = GetParameter('aid');

$hint="	<form style='display: block; ' class='formservicio' name='serv' id='serv'  action='guardaservicio.php' method='post'  onSubmit='return ValidaForm();' > 
		<table>
		 <tr><td colspan=2 style='text-align:left;'><br/>
		 <input type='hidden' name='id_auto1' id='id_auto1' value='{$auto_id}' />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Km Actual: <input type='text' size='12' maxlength='7' tabindex='4' name='kmactual' id='kmactual'  style='text-align: center;'/>
			 <span>(Colocar NF si no funciona el cuenta KM.)</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						 Km Vuelta: <input type='text' size='12' maxlength='7' tabindex='5' name='kmvuelta' id='kmvuelta' style='text-align: center;'/>
						
						 </td></tr>
						 
						 <tr><td>
						 
						 <table class='formservicio'><THEAD><TH colspan='2'>Descripci&oacute;n</TH><TH>Precio</TH></THEAD><tbody>
						
						<tr><td>Aceite</td><td><input type='text' size='22' maxlength='24' tabindex='6' name='aceite' id='aceite' value='' style='text-transform: uppercase;'/></td>
						 <td>&nbsp;$ <input type='text' size='4' id='suma1' maxlength='8' tabindex='27' onKeyUp='ValidaPrecio(this.id);' name='paceite' value='0' onClick='this.select();'  /></td></tr>
						 
						 <tr><td>F. de Aceite</td><td><input type='text' size='22' maxlength='24' tabindex='7' name='filtroaceite' id='filtroaceite' value='' style='text-transform: uppercase;'/></td>
						 <td>&nbsp;$ <input type='text' size='4' id='suma2' maxlength='8' tabindex='27' onKeyUp='ValidaPrecio(this.id);' name='pfiltroaceite' value='0' onClick='this.select();' /></td></tr>
						
						<tr><td>Filtro de Aire</td><td><input type='text' size='22' maxlength='24' tabindex='9' name='filtroaire' id='filtroaire' value='' style='text-transform: uppercase;'/></td> 
						 <td>&nbsp;$ <input type='text' size='4' maxlength='8' onKeyUp='ValidaPrecio(this.id);' name='pfiltroaire' tabindex='28' id='suma3' value='0' onClick='this.select();' /></td></tr>
						 
						  <tr><td>Filtro Comb.</td><td><input type='text' size='22' maxlength='24' tabindex='10' name='filtrocomb' id='filtrocomb' value='' style='text-transform: uppercase;'/></td>
						  <td>&nbsp;$ <input type='text' size='4' maxlength='8' onKeyUp='ValidaPrecio(this.id);' tabindex='29' id='suma4' name='pfiltrocomb' value='0' onClick='this.select();' /></td></tr> 
						 
						 <tr><td>Filtro Habit.</td><td><input type='text' size='22' maxlength='24' tabindex='11' name='filtrohab' id='filtrohab' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$
						 <input type='text' size='4' maxlength='8' onKeyUp='ValidaPrecio(this.id);' name='pfiltrohab' tabindex='30' id='suma5' value='0' onClick='this.select();' /></td></tr>  
						 
						 <tr><td>Fluido Frenos</td><td><input type='text' size='22' maxlength='24' tabindex='12' name='fluidofrenos' id='fluidofrenos' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$ 
						 <input type='text' size='4' maxlength='8' name='pfluidofrenos' onKeyUp='ValidaPrecio(this.id);' tabindex='31' id='suma6' value='0' onClick='this.select();' /></td></tr>  
						 
						 <tr><td>Aceite Caja</td><td><input type='text' size='22' maxlength='24' tabindex='13' name='aceitecaja' id='aceitecaja' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$ 
						 <input type='text' size='4' maxlength='8'  name='paceitecaja' onKeyUp='ValidaPrecio(this.id);' tabindex='32' id='suma7' value='0' onClick='this.select();' /></td></tr> 
						 
						 <tr><td>Aceite Dif.</td><td><input type='text' size='22' maxlength='24' tabindex='14' name='aceitedif' id='aceitedif' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$ 
						<input type='text' size='4' maxlength='8' name='paceitedif' onKeyUp='ValidaPrecio(this.id);' tabindex='33' id='suma8' value='0' onClick='this.select();' /></td></tr>  
						 
						 <tr><td>Fluido Dir. HID</td><td><input type='text' size='22' maxlength='24' tabindex='15' name='fluidodir' id='fluidodir' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$
						 <input type='text' size='4' maxlength='8' name='pfluidodir' onKeyUp='ValidaPrecio(this.id);' tabindex='34' id='suma9' value='0' onClick='this.select();' /></td></tr>  
						 
						 <tr><td>Fluido Refrig.</td><td><input type='text' size='22' maxlength='24' tabindex='16' name='fluidoref' id='fluidoref' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$
						 <input type='text' size='4' maxlength='8' name='pfluidoref' onKeyUp='ValidaPrecio(this.id);' tabindex='35' id='suma10' value='0' onClick='this.select();' /></td></tr> 
						 
						

						 
						 </tbody></table>
						 
						 </td><td>
						 
								  <table class='formservicio'><thead><TH colspan='2'>Descripci&oacute;n</TH><TH>Precio</TH></thead><tbody>

								 <tr><td>Adit. Motor</td><td><input type='text' size='22' maxlength='24' tabindex='17' name='aditivomotor' id='aditivomotor' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$
								 <input type='text' size='4'   maxlength='8' name='paditivomotor' onKeyUp='ValidaPrecio(this.id);' tabindex='36' id='suma11' value='0' onClick='this.select();' /></td></tr>  
								 <tr><td>Adit. Comb.</td><td><input type='text' size='22' maxlength='24' tabindex='18' name='aditivocomb' id='aditivocomb' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$
								 <input type='text' size='4' value='0' onClick='this.select();'  maxlength='8'  name='paditivocomb' onKeyUp='ValidaPrecio(this.id);' tabindex='37' id='suma12'/></td></tr>  
								 <tr><td>Adit. Caja/Dif.</td><td><input type='text' size='22' maxlength='24' tabindex='19' name='aditivocajaydif' id='aditivocajaydif' value='' style='text-transform: uppercase;'/></td>
								 <td>&nbsp;$ <input type='text' size='4' value='0' onClick='this.select();'   maxlength='8' name='paditivocajaydif' onKeyUp='ValidaPrecio(this.id);' tabindex='38' id='suma13' /></td></tr>  
								 <tr><td>Chequeo Luces</td><td><input type='text' size='22' maxlength='24' tabindex='20' name='luces' id='luces' value='' style='text-transform: uppercase;'/></td>
								 <td>&nbsp;$ <input type='text' size='4' name='pluces'   maxlength='8' value='0' onClick='this.select();'   onKeyUp='ValidaPrecio(this.id);' tabindex='39' id='suma14'/></td></tr>  
								 <tr><td>Escobillas</td><td><input type='text' size='22' maxlength='24' tabindex='21' name='escobillas'  value='' id='escobillas' style='text-transform: uppercase;'/></td>
								 <td>&nbsp;$ <input type='text' size='4'  name='pescobillas' value='0' onClick='this.select();'   maxlength='8' onKeyUp='ValidaPrecio(this.id);' tabindex='40' id='suma15'/></td></tr>  
								 <tr><td>Presi&oacute;n Neum.</td><td><input type='text' size='22' maxlength='24' tabindex='22' name='presion' id='presion' value='' style='text-transform: uppercase;' /></td><td></td></tr>  
								 <tr><td>Past. de Freno</td><td><input type='text' size='22' maxlength='24' tabindex='23' name='pastillasfrenos' id='pastillasfrenos' value='' style='text-transform: uppercase;'/></td>
								 <td>&nbsp;$ <input type='text' size='4'   maxlength='8' name='ppastillasfrenos' value='0' onClick='this.select();'  onKeyUp='ValidaPrecio(this.id);' tabindex='41' id='suma16'/></td></tr>  
								 <tr><td>Otro</td><td><input type='text' size='22' maxlength='24' tabindex='24' name='otro' id='otro' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$ <input type='text' size='4' maxlength='8' name='p_otro' value='0' onClick='this.select();'  onKeyUp='ValidaPrecio(this.id);' tabindex='41' id='suma17'/></td></tr> 
								 <tr><td>Otro</td><td><input type='text' size='22' maxlength='24' tabindex='25' name='otro2' id='otro2' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$ <input type='text' size='4' maxlength='8' name='p_otro2' value='0' onClick='this.select();'   onKeyUp='ValidaPrecio(this.id);' tabindex='42' id='suma18'/></td></tr>  
								 <tr><td>Otro</td><td><input type='text' size='22' maxlength='24' tabindex='26' name='otro3' id='otro3' value='' style='text-transform: uppercase;'/></td><td>&nbsp;$ <input type='text' size='4' maxlength='8' name='p_otro3' value='0' onClick='this.select();'   onKeyUp='ValidaPrecio(this.id);' tabindex='43' id='suma19'/></td></tr>  
								
								 
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
	
	