<?
header("Buffer: 1"); //Mando todo junto (todo el Buffer)
header("cache-control: Private"); //Evito el proxy por HTML
header("pragma: no-cache"); //Evito la cache del navegador por HTML
//header("ExpiresAbsolute: " . time() -10); //Expira p�gina Absolutamente
header("Expires: -100000"); //Expira p�gina
session_start();

$_SESSION["UserLogged"] = 0;
$_SESSION["UserId"] = "";

session_unset();
session_destroy();

$URLRedirect = isset($_SESSION["UrlRedirect"]) && $_SESSION["UrlRedirect"] != "" ? $_SESSION["UrlRedirect"] : "login.php";
header("Location: $URLRedirect");
?>