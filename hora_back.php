<div class="easyui-panel"   style="border: 0;text-align:center; padding:6px;">
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
var dayarray=new Array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","S&aacute;bado")
var montharray=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
document.getElementById('fechaactual').innerHTML = "<p style='font: 14px Calibri, Arial normal;'>" + dayarray[day]+"<br/> "+daym+" / "+(month+1)+" / "+year + "</p>";

</script>


		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100" height="100">
			      <param name="movie" value="http://www.respectsoft.com/onlineclock/analog.swf">
			      <param name=quality value=high>
				      <param name="wmode" value="transparent">
				      <embed src="http://www.respectsoft.com/onlineclock/analog.swf" width="100" height="100" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
				    </object>
		</div>
		
		&nbsp;&nbsp;<a href="logout.php" class="easyui-linkbutton" >Cerrar Sesi&oacute;n</a>
		<br/>

		