<?
if (!isset($_SESSION["UserLogged"]) ||  ($_SESSION["UserLogged"] == 0) )
{
	$_SESSION["UserLogged"] = 0;
	$_SESSION["UserId"] = 0;
	//header("Location: login.php");
/*
	?>
	<script>
	window.location.href = "login.php";
	</script>
	<?
*/
}


?>

