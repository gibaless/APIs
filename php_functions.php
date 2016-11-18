<?php

function check_input($value)
{
// Stripslashes
if (get_magic_quotes_gpc())
  {
  $value = stripslashes($value);
  }
// Quote if not a number
if (!is_numeric($value))
  {
  $value = "'" . mysql_real_escape_string($value) . "'";
  }

return $value;
}

// Parámetros
//	- ParameterName: Nombre del parámetro del se quiere el valor.

// Nota: Si no lo encuentra, retorna vacio.

function GetParameter($ParameterName)
{

	if ( isset($_POST[$ParameterName]) && $_POST[$ParameterName] != null )
		return($_POST[$ParameterName]);
	else if ( isset($_GET[$ParameterName]) && $_GET[$ParameterName] != null )
		return($_GET[$ParameterName]);
	else
		return ("");

}
// Función la cual reemplaza la comilla simple por la doble comilla simple.
function SafeSql($text){
	return stripslashes(str_replace("'", "''", $text));
}

//Paginador
// Parámetros:
//	- SQLText: Consulta de la que se paginará.
//	- AdditionalParams: Parámetros adicionales que estarán en el QueryString.

// Clases del tipo CSS que pueden implementarse:
//	- PagItem: Style para los nros. que no fueron seleccionados, incluyendo Prev. y Next
//	- PagItemSelected: Style para el Link seleccionado

/***********************************************************************
// NOTA: La función Pagination debe llamarse antes que se haga
//		el Open del RecordSet.
  ***********************************************************************/

// Cantidad de filas por página a mostrar
$PagRowsPerPage=60;
// El AbsolutePosition del RecordSet se debe setear luego del Open del mismo (SIEMPRE MAYOR A CERO),
// Si es 0 indica que la consulta tiene 0 registros y da un ERROR
$PagAbsolutePosition=0;
// Registro por el cual se comienza a Loopear la consulta

// Registro por el cual se comienza a Loopear la consulta para distintas paginaciones en una misma pagina por ej: disciplinas.php
$PagCurrentRow=0;
// Cantidad de páginas máxima que se quiere mostrar (SIEMPRE MAYOR A CERO)
$PagMaxPages=30;

function PaginationImg( $SQLText, $AdditionalParams )
{
	global $PagRowsPerPage;
	global $PagAbsolutePosition;
	global $PagAbsolutePosition2;
	global $PagCurrentRow;
	global $PagMaxPages;

    $iNumPerPage = 0;
    $iTtlNumItems = 0;
    $iDBLoc = 0;
    $sSqlTemp = "";
    $iTtlTemp = 0;
    $iDBLocTemp = 0;
    $sURLBeg = "";
    $iA = 0;
    $iB = 0;
    $x = 0;
    $iTemp = 0;
    
	if ( GetParameter("rowsperpage") != "" )
	{
		$iNumPerPage = (int)GetParameter("rowsperpage");
	}
	else 
	{
		$iNumPerPage = $PagRowsPerPage;
	}
	
	// Seteo de la cantidad de filas por página
    $PagRowsPerPage = $iNumPerPage;
    // Seteo del la posición absoluta del RecordSet
    ////$PagAbsolutePosition = GetParameter("iDBLoc") + 1;
    $PagAbsolutePosition = GetParameter("iDBLoc");
    if ( $PagAbsolutePosition == "" )
		$PagAbsolutePosition = 0;
    
    $iDBLoc = GetParameter("iDBLoc");
    $iTtlNumItems = GetParameter("ittlnumitems");
    // Get ttl num of items from the database if its Not already In the QueryString
    if ($iTtlNumItems == "")
    {
		$iPosFROM = 0;
    	
		$Result = mysql_query($SQLText);
    	
    	$iTtlNumItems = mysql_num_rows($Result);
    	
    	mysql_free_result($Result);
    }
    $iTtlTemp = (int)($iTtlNumItems / $iNumPerPage); // this is the number of numbers overall (use the "\" To return int)
    $iDBLocTemp = (int)($iDBLoc / $iNumPerPage);	// this is which number we are currently On (use the "\" To return int)
    
	
	$PrepParams = "";
	$sURLBeg = "";
	$Params = "ittlnumitems=" . $iTtlNumItems . "&rowsperpage=" . $iNumPerPage . "&iDBLoc=";
	$sURLBeg = "<a  class='paginador' href='" . $_SERVER["SCRIPT_NAME"] . "?" . $Params;

	if ($AdditionalParams != "") 
    	$PrepParams = "||@Params";
    
	
	
    //***** BEGIN DISPLAY *****//
    
	// Print the numbers in between. Print them out in sets of 10.
    $iA = ( (int)($iDBLocTemp / $iNumPerPage) ) * $iNumPerPage;

	// Seteo la cantidad de páginas a mostrar    
    if ( $PagMaxPages > 0 && $PagMaxPages > $iA ) 
    	$iB = $PagMaxPages - 1;
	else
		$iB = ( (int)($iDBLocTemp / $iNumPerPage) ) * $iNumPerPage + $iNumPerPage;
    
	$i = 0;
	$iCantItems = 0;
    for ($x=$iA; $x <= $iB; $x++)
    {
    	$iTemp = ($x * $iNumPerPage);
    	if ($iTemp < $iTtlNumItems) // takes care of extra numbers after the overall final number
    	{
			$iCantItems++;
    	}
    	else
    		break;
    }
	
	$strResultPagination = '';//"<table cellpadding='0' cellspacing='0' border='1' style='margin:0;'><tr><td width='60px' style='padding:0; margin:0;'>";
	
    if ($iDBLoc != 0) 
    {
    	$strResultPagination = $strResultPagination . $sURLBeg . ($iDBLoc - $iNumPerPage) . $PrepParams . "')\" class=\"next\">< Ant</a>";
    }else{
		$strResultPagination = $strResultPagination . "";
	}
		
	$strResultPagination =  $strResultPagination ;// . '</td><td align=\"center\" style=\"text-align: center;\">';
	
	$i = 0;
    for ($x=$iA; $x <= $iB; $x++)
    {
    	$iTemp = ($x * $iNumPerPage);
    	if ($iTemp < $iTtlNumItems) // takes care of extra numbers after the overall final number
    	{
			
    		$iProxValor = $x + 1;
    		if ($iDBLoc == $iTemp)
    		{
    			$strResultPagination = $strResultPagination . " <b class='actual'>" . $iProxValor . "</b>";
    		}
    		else
    		{
    			$strResultPagination = $strResultPagination .  " " . $sURLBeg . ($x * $iNumPerPage) . $PrepParams . "')\">" . $iProxValor . "</a>";
    		}


		}
    	else
    		break;
    }
	
	
	$strResultPagination =  $strResultPagination ; //. '</td><td style=\"padding:0; margin:0;\">';

    // Print the "Next"
    if (($iDBLoc + $iNumPerPage) < $iTtlNumItems)
    {
    	$strResultPagination = $strResultPagination . "  " . $sURLBeg . ($iDBLoc + $iNumPerPage) . $PrepParams . "')\" class=\"next\"> Sig > </a>";
    }else{
		$strResultPagination = $strResultPagination . "";
    }

	$strResultPagination =  $strResultPagination ;//. '</td></tr></table>';
    
    // Print the <<
    if ($iDBLocTemp >= $iNumPerPage) 
    {
		/*
		$dVal = (( (int)($iDBLocTemp / $iNumPerPage) ) * $iNumPerPage ^ 2) - ($iNumPerPage * 9);
		$strResultPagination = $strResultPagination . $sURLBeg . $dVal . "'><<</a> ";
		*/
    }
    
    
    
    // Print the >>
    if ($iTtlTemp > $iDBLocTemp) 
    {
    	if (($iDBLocTemp + $iNumPerPage) <= $iTtlTemp)
    	{
    		// $strResultPagination = $strResultPagination .  " " . $sURLBeg . (( (int)($iDBLocTemp / $iNumPerPage) ) * $iNumPerPage + $iNumPerPage ) * $iNumPerPage . "' class='paginador'>>></A> ";
    	}
    }
    
	
	if($PrepParams != "")
		$strResultPagination = str_replace($PrepParams, '&' . $AdditionalParams, $strResultPagination);
	//***** End DISPLAY *****//
    
    if ( $iTtlNumItems == 0 )
		$PagAbsolutePosition = 0;

	return $strResultPagination;
}

function encrypt($string)
{
   	if(!$string || $string == ""){return "";}
	
	$key = '123456789';
	$result = '';

	for($i=0; $i<strlen($string); $i++)
	{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	
	return urlencode(base64_encode($result));
}

// Funcion que decripta un valor.
function decrypt($string)
{
	if(!$string || $string == ""){return "";}
	
	$key = '123456789';
	$result = '';
	$string = base64_decode(urldecode($string));
	for($i=0; $i<strlen($string); $i++)
	{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}
	
	return $result;
}

?>