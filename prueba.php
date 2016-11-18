<?
			
	require_once 'charts/lib/GoogleChart.php';
	require_once 'charts/lib/markers/GoogleChartShapeMarker.php';
	require_once 'charts/lib/markers/GoogleChartTextMarker.php';

	include("conn.php");
	
	//Cantidad de Autos Nuevos por Año
	//Guardo los anios
	$queryHistorialCantidadAutosAnios = "SELECT DISTINCT YEAR(fecha_creacion) as anio, count(*) as total FROM auto as A ".
		" GROUP BY YEAR(fecha_creacion) ORDER BY anio ASC";
	$resultHistorialCantidadAutosAnios = mysql_query($queryHistorialCantidadAutosAnios);
	$ii =0;
	while($mm2 = mysql_fetch_array($resultHistorialCantidadAutosAnios)){
	
		$anios[$ii] = $mm2['anio'];  //eje X
		$totales[$ii] = $mm2['total'];  //data dentro del chart
		//Mismo $ii se encuentra anio y el total
	
	$ii++;
	}
			
	mysql_free_result($resultHistorialCantidadAutosAnios);
	
	
	
	$chart = new GoogleChart('lc', 680, 320);

	// manually forcing the scale to [0,100]
	$chart->setScale(0,1000);
	//$chart->setAutoscale(true);


	//Seteo el titulo
	
	
	// add one line - Incluyo los totales
	$data = new GoogleChartData($totales);
	$data->setColor('B4B4B4');
	$data->setThickness(3);
	//$data->setDash(2,1);
	$data->setFill('EBF5FF');
	
	$chart->addData($data);
	
	


	$chart->setTitle("Total Anual de Nuevos Autos ", '111', 20);
	
	$chart->setMargin(40, 40, 40, 40);	
	// customize y axis
	$y_axis = new GoogleChartAxis('y');
	$y_axis->setDrawTickMarks(true)->setLabels(array(0,100, 200,400,600,800,1000));
	$y_axis->setFontSize(12);
	$chart->addAxis($y_axis);




	// customize x axis
	$x_axis = new GoogleChartAxis('x');
	//$x_axis->setTickMarks(5);
	$x_axis->setFontSize(12);
	$x_axis->setDrawTickMarks(true)->setLabels($anios);
	$chart->addAxis($x_axis);

	// add a shape marker with a border
	$shape_marker = new GoogleChartShapeMarker(GoogleChartShapeMarker::CIRCLE);
	$shape_marker->setSize(12);
	$shape_marker->setBorder(3);
	$shape_marker->setData($data);
	$chart->addMarker($shape_marker);

	// add a value marker
	$value_marker = new GoogleChartTextMarker(GoogleChartTextMarker::VALUE);
	$value_marker->setSize(16);
	$value_marker->setColor('2966B8');
	$value_marker->setData($data);
	$chart->addMarker($value_marker);

	//header('Content-Type: image/png');
	//echo $chart;
	//echo $chart;
	echo $chart->toHtml();
		

?>