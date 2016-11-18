<?php
session_start();
/* <!-- Optimizador de pagina --> */
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); 

//include("access.php");

include("conn.php");
include("php_functions.php");
	//Obtengo todas las estadisticas


//Cantidad de clientes nuevos en el dia
//SELECT COUNT(*) clientes_nuevos_dia_actual FROM cliente WHERE fecha_creacion = getdate()


// Cantidad de clientes nuevos en el mes
//SELECT COUNT(*) as autos_nuevos_mes_actual FROM auto WHERE MONTH(fecha_creacion) = MONTH(getdate()) AND YEAR(fecha_creacion) = YEAR(getdate()) 

//Cantidad de Autos total en el mes actual
//SELECT A.* FROM auto as A WHERE MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())

//Listado de Autos total en el mes actual
//SELECT COUNT(*) as autos_nuevos_mes_actual FROM auto as A WHERE MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())

//Cantidad de Autos total en el mes actual

//SELECT A.* FROM auto as A WHERE MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())

//Listado de Autos total en el mes actual
//SELECT COUNT(*) as autos_nuevos_mes_actual FROM auto as A WHERE MONTH(fecha_creacion) = MONTH(CURDATE()) AND YEAR(fecha_creacion) = YEAR(CURDATE())

//Cantidad de servicios nuevos realizados por mes:
//SELECT MONTH(fecha_creacion), YEAR(fecha_creacion), COUNT(A.patente) FROM auto as A 
//GROUP BY YEAR(fecha_creacion), MONTH(fecha_creacion) ORDER BY YEAR(fecha_creacion) DESC, MONTH(fecha_creacion) DESC

	$queryHistorialCantidadServicios = "SELECT YEAR(fecha) as anio, MONTH(fecha) as mes, COUNT(*) as total FROM servicio as S ".
		" GROUP BY YEAR(fecha), MONTH(fecha) ORDER BY YEAR(fecha) DESC, MONTH(fecha) DESC";
	$resultHistorialCantidadServicios = mysql_query($queryHistorialCantidadServicios);
	while($mm3 = mysql_fetch_array($resultHistorialCantidadServicios)){
	$autos1[$mm3['anio']][$mm3['mes']] = $mm3['total'];
	}
			
	mysql_free_result($resultHistorialCantidadServicios);
	
	//Guardo los anios
	$queryHistorialCantidadServiciosAnios = "SELECT DISTINCT YEAR(fecha) as anio, count(*) as total FROM servicio ".
		" GROUP BY YEAR(fecha) ORDER BY YEAR(fecha) DESC";
	$resultHistorialCantidadServiciosAnios = mysql_query($queryHistorialCantidadServiciosAnios);
	while($mm4 = mysql_fetch_array($resultHistorialCantidadServiciosAnios)){
	
		$anios1[] = $mm4['anio'];
		//Falta mostrar totales por anios
	}
			
	mysql_free_result($resultHistorialCantidadServiciosAnios);
	
	
	
//Cantidad de autos nuevos creados por mes:
//SELECT MONTH(fecha_creacion), YEAR(fecha_creacion), COUNT(A.patente) FROM auto as A 
//GROUP BY YEAR(fecha_creacion), MONTH(fecha_creacion) ORDER BY YEAR(fecha_creacion) DESC, MONTH(fecha_creacion) DESC

	$queryHistorialCantidadAutos = "SELECT YEAR(fecha_creacion) as anio, MONTH(fecha_creacion) as mes, COUNT(A.patente) as total FROM auto as A ".
		" GROUP BY YEAR(fecha_creacion), MONTH(fecha_creacion) ORDER BY YEAR(fecha_creacion) DESC, MONTH(fecha_creacion) DESC";
	$resultHistorialCantidadAutos = mysql_query($queryHistorialCantidadAutos);
	while($mm1 = mysql_fetch_array($resultHistorialCantidadAutos)){
	$autos[$mm1['anio']][$mm1['mes']] = $mm1['total'];
	}
			
	mysql_free_result($resultHistorialCantidadAutos);
	
	//Guardo los anios
	$queryHistorialCantidadAutosAnios = "SELECT DISTINCT YEAR(fecha_creacion) as anio, count(*) as total FROM auto as A ".
		" GROUP BY YEAR(fecha_creacion) ORDER BY YEAR(fecha_creacion) DESC";
	$resultHistorialCantidadAutosAnios = mysql_query($queryHistorialCantidadAutosAnios);
	while($mm2 = mysql_fetch_array($resultHistorialCantidadAutosAnios)){
	
		$anios[] = $mm2['anio'];
		//Falta mostrar totales por anios
	}
			
	mysql_free_result($resultHistorialCantidadAutosAnios);
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Estadisticas LubriExpress</title>
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="../themes/icon.css">
		<link rel="stylesheet" type="text/css" href="demo.css">
		<link rel="icon" href="img/le_icon.ico" type="image/x-icon" />
	<script type="text/javascript" src="../jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../jquery.easyui.min.js"></script>
	<script type="text/javascript" src="../funciones.js"></script>
	<script type="text/javascript" src="../datagrid-detailview.js"></script> 
	<script>
		$(function(){
		$(document).bind('contextmenu',function(e){
				$('#mm').menu('show', {
					left: e.pageX,
					top: e.pageY
				});
				return false;
			});

		
		});
		
		$('#tt1').tabs({  
        border:false,  
        onSelect:function(title){  
            alert(title+' is selected');  
        }  
		});  
		  
	</script>

</head>
<body class="easyui-layout">
	<div region="north" style="height: 80px;padding-top:8px; padding-left: 30px;background-image:url('../images/bg.jpg'); background-position:top left;">
		<img src="../images/logo.png" alt="" width="340px"/> 	
	</div>
	<div region="west" split="true" title="Menu" style="background: #F2F2F2; width:138px; padding:4px; "><?php include("menu.php");
	include("hora.php");?>
	
	</div>
	<div region="center" title="Estadísticas" >

	    

			<?php	


			$anio_actual = date("Y");
			$mes_anterior = date("n")-1;
			if($mes_anterior == 0 ){ $mes_anterior =12; $anio_actual = date("Y")-1;}
			//echo $mes_anterior;
									
			//Listado de Autos dados de baja
			
			//Listado de Cantidad de Autos Nuevos HISTORIAL
			?>
			
			<h3 style='background: #ddd; padding: 10px; margin-bottom: 0; '> Históricos </h3>
			<div style="padding-left: 6px; padding-top:6px; border: 2px solid #ddd;">
			
			<div id="tt1" class="easyui-tabs" style="width:980px;height:270px;">
			
			<?php
			
			foreach ($anios as $anio) {
			
			if($anio!=0){
				?>
				
				<div title="<?=$anio?>" style="padding-left:20px;">
				
				<h4>Cantidad de Autos Nuevos x Mes</h4>
				<table id="test" class="estadisticas" style="margin-left: 20px;">
				<thead>
					<tr>
					
				<?php
				for ($i = 1; $i <= 12; $i++) {
				switch ($i) {
					case 1: $mes = "Ene"; break;
					case 2: $mes = "Feb"; break;
					case 3:  $mes = "Mar"; break;					
					case 4: $mes = "Abr"; break;
					case 5: $mes = "May"; break;
					case 6:  $mes = "Jun"; break;
					case 7: $mes = "Jul"; break;
					case 8: $mes = "Ago"; break;
					case 9:  $mes = "Sep"; break;					
					case 10: $mes = "Oct"; break;
					case 11: $mes = "Nov"; break;
					case 12:  $mes = "Dic"; break;					
				
				}
					if(isset($autos[$anio][$i])){
					?>
						<th width="60"><?=$mes?></th>
							
					<?php
					}
				}
				?>
				</tr>
				</thead>
				<tbody>
					<tr>
					<?php
				//Busco el maximoy el minimo
				
						if(isset($autos[$anio][12])){$max = $autos[$anio][12]; } else $max = 0;
						if(isset($autos[$anio][12])){$min = $autos[$anio][12]; } else $min = 300;
						
						for ($jji = 1; $jji <= 12; $jji++) {
													
							if(isset($autos[$anio][$jji]) && $max <= $autos[$anio][$jji]){ $max = $autos[$anio][$jji];
							}
							if(isset($autos[$anio][$jji]) && $min >= $autos[$anio][$jji]){ $min = $autos[$anio][$jji];
							}
						}
				
				
				for ($ji = 1; $ji <= 12; $ji++) {
				
				if(isset($autos[$anio][$ji])){
				
				
				
				?>
						<td style='text-align:center;'><?if($autos[$anio][$ji] == $max){echo "<b style='color:#3300CC'>".$autos[$anio][$ji]."</b>"; } 
														 else if($autos[$anio][$ji] == $min){echo "<b style='color:red'>".$autos[$anio][$ji]."</b>"; }
														 else echo $autos[$anio][$ji]; ?></td>
				<?php
					
					
					}
				}
				?>
					
					</tr>
					
				</tbody>
					</table>
		
			<h4>Cantidad de Servicios Realizados x Mes</h4>
				<table id="test1" class="estadisticas2" style="margin-left: 20px;">
				<thead>
					<tr>
					
				<?php
				for ($i = 1; $i <= 12; $i++) {
				switch ($i) {
					case 1: $mes = "Ene"; break;
					case 2: $mes = "Feb"; break;
					case 3:  $mes = "Mar"; break;					
					case 4: $mes = "Abr"; break;
					case 5: $mes = "May"; break;
					case 6:  $mes = "Jun"; break;
					case 7: $mes = "Jul"; break;
					case 8: $mes = "Ago"; break;
					case 9:  $mes = "Sep"; break;					
					case 10: $mes = "Oct"; break;
					case 11: $mes = "Nov"; break;
					case 12:  $mes = "Dic"; break;					
				
				}
					if(isset($autos1[$anio][$i])){
					?>
						<th width="60"><?=$mes?></th>
							
					<?php
					}
				}
				?>
				</tr>
				</thead>
				<tbody>
					<tr>
					<?php
					//Busco el maximoy el minimo
				
						if(isset($autos1[$anio][12])){$max = $autos1[$anio][12]; } else $max = 0;
						if(isset($autos1[$anio][12])){$min = $autos1[$anio][12]; } else $min = 300;
						
						for ($jji = 1; $jji <= 12; $jji++) {
													
							if(isset($autos1[$anio][$jji]) && $max <= $autos1[$anio][$jji]){ $max = $autos1[$anio][$jji];
							}
							if(isset($autos1[$anio][$jji]) && $min >= $autos1[$anio][$jji]){ $min = $autos1[$anio][$jji];
							}
						}
						
						
				for ($ji = 12; $ji >= 1; $ji--) {
				
				if(isset($autos1[$anio][$ji])){
				?>
						<td style='text-align:center;'><?if($autos1[$anio][$ji] == $max){echo "<b style='color:#3300CC'>".$autos1[$anio][$ji]."</b>"; } 
														 else if($autos1[$anio][$ji] == $min){echo "<b style='color:red'>".$autos1[$anio][$ji]."</b>"; }
														 else echo $autos1[$anio][$ji]; ?>
						
						</td>
							
				<?php
					}
				}
				?>
					
					</tr>				
				</tbody>
					</table>
				 </div>
							 
			<?php
				
			}
			
			}
			 ?>
			
		 
			</div>  
			
			</div>
			
			
			
			<h3 style='background: #ddd; padding:10px; margin-bottom: 0; '> Gráficos </h3>
			<div style="padding: 8px; border: 2px solid #ddd; min-height: 360px; width:100%">
			
				<div style="margin-left: 10px; float: left; width: 48%">
				<?php //Insertamos Gráficos HTML
					include "prueba.php"; 
					?>
				</div>
				<div style="float: left; width:48%">
			<?php
			include "stat_anual.php";
			
			?>
				</div>
			 </div>
			
	
	</div>
</body>
</html>