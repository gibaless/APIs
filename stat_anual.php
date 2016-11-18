<?
			
	require_once 'charts/lib/GoogleChart.php';
	require_once 'charts/lib/markers/GoogleChartShapeMarker.php';
	require_once 'charts/lib/markers/GoogleChartTextMarker.php';

	include("conn.php");
	
	//Cantidad de Servicio Realizados por Año
	
	//Guardo los anios
	$queryHistorialCantidadServiciosAnios = "SELECT DISTINCT YEAR(fecha) as anio, count(*) as total FROM servicio ".
		" GROUP BY YEAR(fecha) ORDER BY anio ASC";
	$resultHistorialCantidadServiciosAnios = mysql_query($queryHistorialCantidadServiciosAnios);
	$ii =0;
	while($mm4 = mysql_fetch_array($resultHistorialCantidadServiciosAnios)){
	
		$anios2[$ii] = $mm4['anio'];  //eje X
		$totales2[$ii] = $mm4['total'];  //data dentro del chart
		//Falta mostrar totales por anios
	$ii++;
	}
			
	mysql_free_result($resultHistorialCantidadServiciosAnios);
	
		
	
	$chart1 = new GoogleChart('lc', 680, 320);

	// manually forcing the scale to [0,100]
	$chart1->setScale(0,3000);
	//$chart->setAutoscale(true);


	//Seteo el titulo
	
	
	// add one line - Incluyo los totales
	$data = new GoogleChartData($totales2);
	$data->setColor('B4B4B4');
	$data->setThickness(3);
	//$data->setDash(3,0);
	$data->setFill('CFE7E2');
	
	$chart1->addData($data);
	
	


	$chart1->setTitle("Total Anual de Servicios Realizados", '000', 20);
	
	$chart1->setMargin(40, 40, 40, 40);	
	// customize y axis
	$y_axis = new GoogleChartAxis('y');
	$y_axis->setDrawTickMarks(true)->setLabels(array(0, 500,1000,1500,2000,2500,3000));
	$y_axis->setFontSize(12);
	$chart1->addAxis($y_axis);




	// customize x axis
	$x_axis = new GoogleChartAxis('x');
	//$x_axis->setTickMarks(5);
	$x_axis->setFontSize(12);
	$x_axis->setDrawTickMarks(true)->setLabels($anios2);
	$chart1->addAxis($x_axis);

	// add a shape marker with a border
	$shape_marker = new GoogleChartShapeMarker(GoogleChartShapeMarker::CIRCLE);
	$shape_marker->setSize(12);
	$shape_marker->setBorder(3);
	$shape_marker->setData($data);
	$chart1->addMarker($shape_marker);

	// add a value marker
	$value_marker = new GoogleChartTextMarker(GoogleChartTextMarker::VALUE);
	$value_marker->setSize(16);
	$value_marker->setColor('222222');
	$value_marker->setData($data);
	$chart1->addMarker($value_marker);

	//header('Content-Type: image/png');
	//echo $chart;
	//echo $chart;
	echo $chart1->toHtml();
		

?>