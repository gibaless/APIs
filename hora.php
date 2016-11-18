<div class="easyui-panel"   style="border: 0;text-align:center; padding:6px; font-family: 'Dosis', sans-serif; font-weight: 300;">
<div id="fechaactual" style="padding-top: 2px;text-shadow: #ccc 1px 1px 1px;"></div>
<script languaje="JavaScript">

var mydate=new Date()
var year=mydate.getYear()
if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
var dayarray=new Array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado")
var montharray=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
document.getElementById('fechaactual').innerHTML = "<p style='font-size: 20px; font-family: 'Dosis'; font-weight: 800;  '><span style='margin-bottom: 20px;'> " + dayarray[day]+" </span> <br/> "+daym+"/"+(month+1)+"/"+year + "</p>";

</script>

</div>
<!-- 
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100" height="100">
			      <param name="movie" value="http://www.respectsoft.com/onlineclock/analog.swf">
			      <param name=quality value=high>
				      <param name="wmode" value="transparent">
				      <embed src="http://www.respectsoft.com/onlineclock/analog.swf" width="100" height="100" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
				    </object>
		
	-->
		<div style="margin-left: 24px; font-size:30px; font-weight: 800; font-family: 'Dosis'; ">
		<span>
		
    <a href="http://24timezones.com/es_husohorario/buenos_aires_hora_actual.php" style="text-decoration: none" target="_BLANK" title="hora en Buenos Aires"></a> 
	<span id="tzTimeSpan_ee526c49cbe5364"></span>
    <script type="text/javascript" src="http://24timezones.com/js/es/time_24_0_0.js"></script>
    <script src="http://24timezones.com/timescript/gettime.js.php?city=51&hourtype=24&showdate=0&showseconds=0&id=1515238&elem=ee526c49cbe5364" language="javascript"></script>
	</span>
		</div>
		
		<br/><br/>
		&nbsp;&nbsp;<a href="logout.php" class="easyui-linkbutton" >Cerrar Sesi&oacute;n</a>
		<br/>

		