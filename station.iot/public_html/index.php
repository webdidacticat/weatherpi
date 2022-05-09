<?php
if($_GET)
{
	require_once '../admin/dates.php';
	require '../admin/connect.php';

	date_default_timezone_set('UTC+2');
	$dia=date("Y-m-d");
	$hora=date("H:i:s");

	$tempbmp=$_GET['tb'];
	$presbmp=$_GET['pb'];
	$temphdc=$_GET['th'];
	$humehdc=$_GET['hh'];
	$batery=$_GET['bat'];
	$iden=$_GET['ii'];
	echo $dia." _ ".$hora." _ ".$tempbmp." _ ".$presbmp." _ ".$temphdc." _ ".$humehdc." _ ".$batery." _ ".$iden."<br/>";

	$conbd = conectar_base_datos($servidor,$usuario,$pass,$base_datos);
	insert($conbd,$dia,$hora,$tempbmp,$presbmp,$temphdc,$humehdc,$batery,$iden);
	close($conbd);
}else{
	echo "Params error \n";
}
?>
