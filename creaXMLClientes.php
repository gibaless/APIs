<?php
include "conn.php";

$query_rsImages = "SELECT * FROM cliente order by apellido";
$rsImages = mysql_query($query_rsImages);
$row_rsImages = mysql_fetch_assoc($rsImages);
$totalRows_rsImages = mysql_num_rows($rsImages);

// Send the headers
header('Content-type: text/xml');
header('Pragma: public');        
header('Cache-control: private');
header('Expires: -1');
?><?php echo('<?xml version="1.0" encoding="iso-8859-1"?>'); ?>
<clientes>
  <?php if ($totalRows_rsImages > 0) { // Show if recordset not empty ?>
  <?php do { ?>
	<cliente>
		<ID><?php echo $row_rsImages['cod']; ?></ID>
		<apellido><![CDATA[<?php echo $row_rsImages['apellido']; ?>]]></apellido>
	</cliente>
    <?php } while ($row_rsImages = mysql_fetch_assoc($rsImages)); ?>
	<?php } // Show if recordset not empty ?>
</clientes>
<?php
mysql_free_result($rsImages);
?>