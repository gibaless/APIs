<?php
include("conn.php");

	//Obtengo todos los clientes
	$queryClientes = "SELECT * FROM cliente ORDER BY apellido";
	$resultC = mysql_query($queryClientes);
	
?>
	<!-- Formulario para transferir Auto Actual-->
		<div id="dlgtxauto" class="easyui-dialog" style="width:510px;height:160px;padding:8px 10px; background: #fdfdfd;" closed="true" buttons="#dlgtxauto-buttons">
		
		<form id="fmtxauto" name="fmtxauto" method="post" novalidate>
		<input type="hidden" id="id_auto11" />
			<div class="fitem">
				<label>Patente:</label>
				 <input type="text" id="patenteactual" readonly="readonly" style="width:120px;text-transform: uppercase;" class="easyui-validatebox"/>
			</div>
			<div class="fitem">
				<label> Transferir a: </label>
				
				<select name='nuevocliente11' id='nuevocliente11' class="easyui-validatebox" style="width:350px;" size="1">
					<option value=''>-- Seleccione Cliente --</option>
					<?	while($cc = mysql_fetch_array($resultC)){
					?>
						<option value="<?=$cc['cod']?>" ><?=$cc['apellido']?></option>
					<?
						}
						
					?>
			  </select>	
				
			</div>
	
		</form>
	</div>
		<div id="dlgtxauto-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="txautoSubmit();">Aceptar</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="$('#dlgtxauto').dialog('close')">Cancelar</a>
		</div>
	<!-- Fin de formulario para transferir Auto Actual-->