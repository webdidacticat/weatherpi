<?php

require_once ('../jpgraph/src/jpgraph.php');
require_once ('../jpgraph/src/jpgraph_line.php');
require_once ('../jpgraph/src/jpgraph_date.php');
require_once '../admin/dates.php';
require '../admin/connect.php';

date_default_timezone_set('UTC+2');
$dia=date("Y-m-d");
$hora=date("H:i:s");
#echo $dia." _ ".$hora."<br/>";

if($hora>"00:00:00")
{
	$ayer=date("Y-m-d",strtotime($dia."-1 days"));
}

//echo "Dia ayer: ".$ayer."<br/>";

$conbd = conectar_base_datos($servidor,$usuario,$pass,$base_datos);
$con0="SELECT bmp.presion as presbmp, bmp.temperatura as tempbmp, hdc.humedad as humhdc, hdc.temperatura as temphdc, fecha.dia as diafecha, fecha.hora as horafecha, bat.volts as voltbat FROM bmp, fecha, hdc, bat WHERE ((bmp.key_fecha = fecha.key_fecha) AND (hdc.key_fecha = fecha.key_fecha) AND (bat.key_fecha = fecha.key_fecha) AND ((fecha.dia = '$ayer' AND fecha.hora >= '$hora') OR (fecha.dia = '$dia' AND fecha.hora <= '$hora')))";
$con1="SELECT MAX(bmp.temperatura) as tempbmp FROM bmp, fecha WHERE ((bmp.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con5="SELECT MIN(bmp.temperatura) as tempbmp FROM bmp, fecha WHERE ((bmp.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con2="SELECT MAX(bmp.presion) as presbmp FROM bmp, fecha WHERE ((bmp.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con6="SELECT MIN(bmp.presion) as presbmp FROM bmp, fecha WHERE ((bmp.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con3="SELECT MAX(hdc.humedad) as humhdc FROM hdc, fecha WHERE ((hdc.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con7="SELECT MIN(hdc.humedad) as humhdc FROM hdc, fecha WHERE ((hdc.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con4="SELECT MAX(hdc.temperatura) as temphdc FROM hdc, fecha WHERE ((hdc.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con8="SELECT MIN(hdc.temperatura) as temphdc FROM hdc, fecha WHERE ((hdc.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con9="SELECT bmp.presion as ultbmppres FROM bmp WHERE (SELECT MAX(fecha.key_fecha) FROM fecha WHERE (fecha.dia='$dia')) = bmp.key_fecha";
$con10="SELECT bmp.temperatura as ultbmptemp FROM bmp WHERE (SELECT MAX(fecha.key_fecha) FROM fecha WHERE (fecha.dia='$dia')) = bmp.key_fecha";
$con11="SELECT hdc.humedad as ulthdchum FROM hdc WHERE (SELECT MAX(fecha.key_fecha) FROM fecha WHERE (fecha.dia='$dia')) = hdc.key_fecha";
$con12="SELECT hdc.temperatura as ulthdctemp FROM hdc WHERE (SELECT MAX(fecha.key_fecha) FROM fecha WHERE (fecha.dia='$dia')) = hdc.key_fecha";
$con13="SELECT fecha.dia as ultimdia FROM fecha WHERE (SELECT MAX(key_fecha) FROM bmp) = fecha.key_fecha";
$con14="SELECT fecha.hora as ultimhora FROM fecha WHERE (SELECT MAX(key_fecha) FROM bmp) = fecha.key_fecha";
$con15="SELECT bat.volts as ultbat FROM bat WHERE (SELECT MAX(fecha.key_fecha) FROM fecha WHERE (fecha.dia='$dia')) = bat.key_fecha";
$con16="SELECT MIN(bat.volts) as minvolts FROM bat, fecha WHERE ((bat.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
$con17="SELECT MAX(bat.volts) as maxvolts FROM bat, fecha WHERE ((bat.key_fecha=fecha.key_fecha) AND (fecha.dia='$dia'))";
//echo $con1;
$query0 = mysqli_query($conbd,$con0)
	or die (mysqli_error($dbconnect));
$query1 = mysqli_query($conbd,$con1)
	or die (mysqli_error($dbconnect));
$query5 = mysqli_query($conbd,$con5)
	or die (mysqli_error($dbconnect));
$query2 = mysqli_query($conbd,$con2)
	or die (mysqli_error($dbconnect));
$query6 = mysqli_query($conbd,$con6)
	or die (mysqli_error($dbconnect));
$query3 = mysqli_query($conbd,$con3)
	or die (mysqli_error($dbconnect));
$query7 = mysqli_query($conbd,$con7)
	or die (mysqli_error($dbconnect));
$query4 = mysqli_query($conbd,$con4)
	or die (mysqli_error($dbconnect));
$query8 = mysqli_query($conbd,$con8)
	or die (mysqli_error($dbconnect));
$query9 = mysqli_query($conbd,$con9)
        or die (mysqli_error($dbconnect));
$query10 = mysqli_query($conbd,$con10)
        or die (mysqli_error($dbconnect));
$query11 = mysqli_query($conbd,$con11)
        or die (mysqli_error($dbconnect));
$query12 = mysqli_query($conbd,$con12)
        or die (mysqli_error($dbconnect));
$query13 = mysqli_query($conbd,$con13)
        or die (mysqli_error($dbconnect));
$query14 = mysqli_query($conbd,$con14)
        or die (mysqli_error($dbconnect));
$query15 = mysqli_query($conbd,$con15)
        or die (mysqli_error($dbconnect));
$query16 = mysqli_query($conbd,$con16)
        or die (mysqli_error($dbconnect));
$query17 = mysqli_query($conbd,$con17)
        or die (mysqli_error($dbconnect));

/*if($query1)
{
	echo "Datos<br/>";
}else{
	echo "NO Datos<br/>";
}*/

if ($row=mysqli_fetch_array($query1)){
	if(is_null($row["tempbmp"]))
		$maxbmptemp = 0;
	else
		$maxbmptemp = $row["tempbmp"];
}
if ($row=mysqli_fetch_array($query5)){
	if(is_null($row["tempbmp"]))
		$minbmptemp = 0;
	else
		$minbmptemp = $row["tempbmp"];
}
if ($row=mysqli_fetch_array($query2)){
	if(is_null($row["presbmp"]))
		$maxbmppres = 0;
	else
		$maxbmppres = $row["presbmp"];
}
if ($row=mysqli_fetch_array($query6)){
	if(is_null($row["presbmp"]))
		$minbmppres = 0;
	else
		$minbmppres = $row["presbmp"];
}
if ($row=mysqli_fetch_array($query3)){
	if(is_null($row["humhdc"]))
		$maxhdchum = 0;
	else
		$maxhdchum = $row["humhdc"];
}
if ($row=mysqli_fetch_array($query7)){
	if(is_null($row["humhdc"]))
		$minhdchum = 0;
	else
		$minhdchum = $row["humhdc"];
}
if ($row=mysqli_fetch_array($query4)){
	if(is_null($row["temphdc"]))
		$maxhdctemp = 0;
	else
		$maxhdctemp = $row["temphdc"];
}
if ($row=mysqli_fetch_array($query8)){
	if(is_null($row["temphdc"]))
		$minhdctemp = 0;
	else
		$minhdctemp = $row["temphdc"];
}
if ($row=mysqli_fetch_array($query9)){
        if(is_null($row["ultbmppres"]))
                $diabmppres = 0;
        else
                $diabmppres = $row["ultbmppres"];
}
if ($row=mysqli_fetch_array($query10)){
        if(is_null($row["ultbmptemp"]))
                $diabmptemp = 0;
        else
                $diabmptemp = $row["ultbmptemp"];
}
if ($row=mysqli_fetch_array($query11)){
        if(is_null($row["ulthdchum"]))
                $diahdchume = 0;
        else
                $diahdchume = $row["ulthdchum"];
}
if ($row=mysqli_fetch_array($query12)){
        if(is_null($row["ulthdctemp"]))
                $diahdctemp = 0;
        else
                $diahdctemp = $row["ulthdctemp"];
}
if ($row=mysqli_fetch_array($query13)){
        if(is_null($row["ultimdia"]))
                $ultimdia = "00-00-0000";
        else
                $ultimdia = $row["ultimdia"];
}
if ($row=mysqli_fetch_array($query14)){
	if(is_null($row["ultimhora"]))
		$ultimhora = "00:00:00";
	else
		$ultimhora = $row["ultimhora"];
}
if ($row=mysqli_fetch_array($query15)){
        if(is_null($row["ultbat"]))
                $ultbat = "0";
        else
                $ultbat = $row["ultbat"];
}
if ($row=mysqli_fetch_array($query16)){
        if(is_null($row["minvolts"]))
                $minvolts = "0";
        else
                $minvolts = $row["minvolts"];
}
if ($row=mysqli_fetch_array($query17)){
        if(is_null($row["maxvolts"]))
                $maxvolts = "0";
        else
                $maxvolts = $row["maxvolts"];
}
//Generación Gràficas
$dia=array();
$hora=array();
$tempbmp=array();
$temphdc=array();
$presbmp=array();
$humhdc=array();
$voltbat=array();
$i=0;

while($row=mysqli_fetch_array($query0)){
	//echo $row["diafecha"]." ".$row["horafecha"]." ".$row["presbmp"]." ".$row["tempbmp"]." ".$row["humhdc"]." ".$row["temphdc"]."<br/>";
	$dia[$i]=$row["diafecha"];
	$hora[$i]=$row["horafecha"];
	$tempbmp[$i]=$row["tempbmp"];
	$temphdc[$i]=$row["temphdc"];
	$presbmp[$i]=$row["presbmp"];
	$humhdc[$i]=$row["humhdc"];
	$voltbat[$i]=$row["voltbat"];
	$i++;
}
close($conbd);

//echo $i."<br/>";
$interval=round($i/24);
//echo $interval."<br/>";
$hora2 = array();
$diahora = array();
for($x=0;$x<$i;$x++){
        $diahora[$x] = $dia[$x]." ".$hora[$x];
        $hora2[$x] = intval(strtotime($diahora[$x]));
}
?>
<!DOCTYPE html>
<head>
    <TITLE>WeatherPi Gavà</TITLE>
    <meta http-equiv="Content-Type" content="text/html; ISO-8859-1">
    <META NAME="DC.Language" SCHEME="RFC1766" CONTENT="Spanish">
    <META NAME="AUTHOR" CONTENT="iorus">
    <!-- META-TAGS generadas por https://metatags.miarroba.com -->
    <link href="diseno.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="index.php"><img src="img/logo.png" height="85px" alt=""></a><h1>WeatherPi Gavà</h1>
            <ul class="menu top">
                <li><a href="#">GitHub</a></li>
                <li><a href="#">Thingiverse</a></li>
                <!--li><a href="#">Enlace 3</a></li>
                <li><a href="#">Enlace 4</a></li-->
            </ul>
        </div>
        <center><img src="img/portada.png" width="1100px" alt=""></center>
        <div class="row">
	    <div class="col">
                <h2>Resum dades</h2>
                <!--img src="img/img1.jpg" width="1000px" alt=""-->
		<table>
			<tr>
				<th></th>
				<th colspan="2">BMP280</th>
				<th colspan="2">HDC1080</th>
				<th>Bateria</th>
				<th>FECHA</th>
			</tr>
			<tr>
				<th></th>
				<th>Temperatura</th>
				<th>Pressió</th>
				<th>Temperatura</th>
				<th>Humitat</th>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<th>Dada Actual DIA</th>
				<td><?php echo "".$diabmptemp.""; ?></td>
				<td><?php echo "".$diabmppres.""; ?></td>
				<td><?php echo "".$diahdctemp.""; ?></td>
				<td><?php echo "".$diahdchume.""; ?></td>
				<td><?php echo "".$ultbat.""; ?></td>
				<td><?php echo "".$ultimdia." - ".$ultimhora."";?></td>
			</tr>
			<tr>
				<th>Dada Màxima DIA</th>
				<td><?php echo "".$maxbmptemp.""; ?></td>
				<td><?php echo "".$maxbmppres.""; ?></td>
				<td><?php echo "".$maxhdctemp.""; ?></td>
				<td><?php echo "".$maxhdchum.""; ?></td>
				<td><?php echo "".$maxvolts.""; ?></td>
				<td></td>
			</tr>
			<tr>
				<th>Dada Minima DIA</th>
				<td><?php echo "".$minbmptemp.""; ?></td>
				<td><?php echo "".$minbmppres.""; ?></td>
				<td><?php echo "".$minhdctemp.""; ?></td>
				<td><?php echo "".$minhdchum.""; ?></td>
				<td><?php echo "".$minvolts.""; ?></td>
				<td></td>
			</tr>
		</table>
		<?php
		//Valores Actuales
		/*echo "Valor actual BMPTemp = ".$diabmptemp."<br/>";
		echo "Valor actual BMPPres = ".$diabmppres."<br/>";
		echo "Valor actual HDCTemp = ".$diahdctemp."<br/>";
		echo "Valor actual HDCHume = ".$diahdchume."<br/>";
		//Valores MAX-MIN
		echo "Valor dia MAX bmptmp = ".$maxbmptemp."<br/>";
		echo "Valor dia MAX bmpprs = ".$maxbmppres,"<br/>";
		echo "Valor dia MIN bmptmp = ".$minbmptemp."<br/>";
		echo "Valor dia MIN bmpprs = ".$minbmppres,"<br/>";
		echo "Valor dia MAX hdctmp = ".$maxhdctemp."<br/>";
		echo "Valor dia MAX hdchum = ".$maxhdchum,"<br/>";
		echo "Valor dia MIN hdctmp = ".$minhdctemp."<br/>";
		echo "Valor dia MIN hdchum = ".$minhdchum."<br/>";*/
		?>
                <p></p>
            </div>
            <div class="col">
                <h2>Gràfic Temperatura BMP</h2>
                <!--img src="img/img1.jpg" width="1000px" alt=""-->
		<?php 
		if((empty($hora))&&(empty($tempbmp))){
			echo 'ERROR Dades';
		}else{
			$grafico = new Graph(1000, 400, 'auto');
			$grafico->img->SetMargin(80,40,40,80);
			$grafico->SetScale("datlin");
			$grafico->title->Set("Temperatura BMP");
			$grafico->xaxis->SetTitle("Hora","high");
			$grafico->xgrid->Show();
			$grafico->xaxis->SetTitleMargin(50);
			$grafico->xaxis->SetTitleSide(SIDE_BOTTOM);
			$grafico->xaxis->SetLabelAngle(50);
			$grafico->xaxis->SetTextLabelInterval(1);
			$grafico->xaxis->scale->ticks->Set(1*60*60);
                        $grafico->xaxis->scale->SetDateFormat('H:00');
			$grafico->yaxis->SetTitle("ºC");
			$grafico->yaxis->SetTitleSide("SIDE_TOP");
			$grafico->yaxis->SetTitleMargin(-50);

			$lineplot1 =new LinePlot($tempbmp,$hora2);
			$lineplot1->SetColor("blue");
			$grafico->Add($lineplot1);
			$img=$grafico->Stroke(_IMG_HANDLER);
			ob_start();
			imagepng($img);
			$img_data=ob_get_contents();
			ob_end_clean();

			echo '<img src="data:image/png;base64,'.base64_encode($img_data).'"/><br/>';
		}
		?>
                <p></p>
            </div>
            <div class="col">
                <h2>Gràfic Temperatura HDC</h2>
                <!--img src="img/img2.jpg"  width="1000px" alt=""-->
		<?php
		if((empty($hora))&&(empty($temphdc))){
                        echo 'ERROR Dades';
                }else{
			$grafico = new Graph(1000, 400, 'auto');
			$grafico->img->SetMargin(80,40,40,80);
			$grafico->SetScale("datlin");
			$grafico->title->Set("Temperatura HDC");
			$grafico->xaxis->SetTitle("Hora","high");
			$grafico->xgrid->Show();
			$grafico->xaxis->SetTitleMargin(50);
			$grafico->xaxis->SetTitleSide(SIDE_BOTTOM);
			$grafico->xaxis->SetLabelAngle(50);
			$grafico->xaxis->SetTextLabelInterval(1);
			$grafico->xaxis->scale->ticks->Set(1*60*60);
                        $grafico->xaxis->scale->SetDateFormat('H:00');
			$grafico->yaxis->SetTitle("ºC");
			$grafico->yaxis->SetTitleSide("SIDE_TOP");
			$grafico->yaxis->SetTitleMargin(-50);

			$lineplot1 =new LinePlot($temphdc,$hora2);
			$lineplot1->SetColor("blue");
			$grafico->Add($lineplot1);
			$img=$grafico->Stroke(_IMG_HANDLER);
			ob_start();
			imagepng($img);
			$img_data=ob_get_contents();
			ob_end_clean();

			echo '<img src="data:image/png;base64,'.base64_encode($img_data).'"/><br/>';
		}
		?>
                <p></p>
            </div>
            <div class="col">
                <h2>Gràfic Pressió BMP</h2>
                <!--img src="img/img3.jpg"  width="1000px" alt=""-->
		<?php 
		if((empty($hora))&&(empty($presbmp))){
                        echo 'ERROR Dades';
                }else{
			$grafico = new Graph(1000, 400, 'auto');
			$grafico->img->SetMargin(80,40,40,80);
			$grafico->SetScale("datlin");
			$grafico->title->Set("Pressio BMP");
			$grafico->xaxis->SetTitle("Hora","high");
			$grafico->xgrid->Show();
			$grafico->xaxis->SetTitleMargin(50);
			$grafico->xaxis->SetTitleSide(SIDE_BOTTOM);
			$grafico->xaxis->SetLabelAngle(50);
			$grafico->xaxis->SetTextLabelInterval(1);
			$grafico->xaxis->scale->ticks->Set(1*60*60);
                        $grafico->xaxis->scale->SetDateFormat('H:00');
			$grafico->yaxis->SetTitle("mbar");
			$grafico->yaxis->SetTitleSide("SIDE_TOP");
			$grafico->yaxis->SetTitleMargin(-50);

			$lineplot1 =new LinePlot($presbmp,$hora2);
			$lineplot1->SetColor("blue");
			$grafico->Add($lineplot1);
			$img=$grafico->Stroke(_IMG_HANDLER);
			ob_start();
			imagepng($img);
			$img_data=ob_get_contents();
			ob_end_clean();

			echo '<img src="data:image/png;base64,'.base64_encode($img_data).'"/><br/>';
		}
		?>
                <p></p>
            </div>
	    <div class="col">
                <h2>Gràfic Humitat HDC</h2>
                <!--img src="img/img4.jpg"  width="1000px" alt=""-->
		<?php 
		if((empty($hora))&&(empty($humhdc))){
                        echo 'ERROR Dades';
                }else{
			$grafico = new Graph(1000, 400, 'auto');
			$grafico->img->SetMargin(80,40,40,80);
			$grafico->SetScale("datlin",0,105);
			$grafico->title->Set("Humedad HDC");
			$grafico->xaxis->SetTitle("Hora","high");
			$grafico->xgrid->Show();
			$grafico->xaxis->SetTitleMargin(50);
			$grafico->xaxis->SetTitleSide(SIDE_BOTTOM);
			$grafico->xaxis->SetLabelAngle(50);
			$grafico->xaxis->SetTextLabelInterval(1);
			$grafico->xaxis->scale->ticks->Set(1*60*60);
                        $grafico->xaxis->scale->SetDateFormat('H:00');
			$grafico->yaxis->SetTitle("%");
			$grafico->yaxis->SetTitleSide("SIDE_TOP");
			$grafico->yaxis->SetTitleMargin(-50);

			$lineplot1 =new LinePlot($humhdc,$hora2);
			$lineplot1->SetColor("blue");
			$grafico->Add($lineplot1);
			$img=$grafico->Stroke(_IMG_HANDLER);
			ob_start();
			imagepng($img);
			$img_data=ob_get_contents();
			ob_end_clean();

			echo '<img src="data:image/png;base64,'.base64_encode($img_data).'"/><br/>';
		}
		?>
                <p></p>
            </div>
	    <div class="col">
                <h2>Gràfic Carrega Bateria</h2>
                <!--img src="img/img4.jpg"  width="1000px" alt=""-->
                <?php 
                if((empty($hora))&&(empty($voltbat))){
                        echo 'ERROR Dades';
                }else{
                        $grafico = new Graph(1000, 400, 'auto');
                        $grafico->img->SetMargin(80,40,40,80);
                        $grafico->SetScale("datlin",3,4.3);
                        $grafico->title->Set("Carrega Bateria");
                        $grafico->xaxis->SetTitle("Hora","high");
                        $grafico->xgrid->Show();
                        $grafico->xaxis->SetTitleMargin(50);
                        $grafico->xaxis->SetTitleSide(SIDE_BOTTOM);
                        $grafico->xaxis->SetLabelAngle(50);
                        $grafico->xaxis->SetTextLabelInterval(1);
                        $grafico->xaxis->scale->ticks->Set(1*60*60);
                        $grafico->xaxis->scale->SetDateFormat('H:00');
                        $grafico->yaxis->SetTitle("Volts");
                        $grafico->yaxis->SetTitleSide("SIDE_TOP");
                        $grafico->yaxis->SetTitleMargin(-50);

                        $lineplot1 =new LinePlot($voltbat,$hora2);
                        $lineplot1->SetColor("blue");
                        $grafico->Add($lineplot1);
                        $img=$grafico->Stroke(_IMG_HANDLER);
                        ob_start();
                        imagepng($img);
                        $img_data=ob_get_contents();
                        ob_end_clean();

                        echo '<img src="data:image/png;base64,'.base64_encode($img_data).'"/><br/>';
                }
                ?>
                <p></p>
            </div>
        </div>
        <div class="footer">
            <ul class="menu">
                <li><a href="#">Enlace 1</a></li>
                <li><a href="#">Enlace 2</a></li>
                <!--li><a href="#">Enlace 3</a></li>
                <li><a href="#">Enlace 4</a></li-->
            </ul>
        </div>
    </div>
</body>
</html>
