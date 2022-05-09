<?php

function conectar_base_datos($server,$user,$pass,$bd)
{
	//echo $server." ".$user." ".$pass." ".$bd;

	$descriptor = mysqli_connect($server, $user, $pass);

	if(! $descriptor) {
		die('<br/>Could not connect: '.mysqli_connect_error().PHP_EOL);
	}else{  //echo "<br/>OK\n"; 
	}
	
	if(mysqli_select_db($descriptor,$bd) == TRUE)
	{
		//echo "Exist\n";
	}else{
		$sql = 'CREATE DATABASE '.$bd;
		if(mysqli_query($descriptor,$sql))
		{
			//echo "Database created succesfully\n";
		}else{
			die('Error creating database: '.mysqli_error($descriptor)."\n");
		}
		mysqli_select_db($descriptor,$bd);
		instalar_BD($descriptor);
	}

	return $descriptor;
}

function instalar_BD($descriptor)
{
	$sql = "CREATE TABLE fecha (key_fecha INT NOT NULL AUTO_INCREMENT,".
	" dia DATE NOT NULL, hora TIME NOT NULL, PRIMARY KEY (key_fecha));";
	if(mysqli_query($descriptor,$sql)){
		//echo "Table created ";
	}else{
		die('Error created table fecha'.mysqli_error($descriptor)." ");
	}
	$sql = "CREATE TABLE bmp (key_bmp INT NOT NULL AUTO_INCREMENT, ".
	"presion FLOAT(6,2) NOT NULL, temperatura FLOAT(4,2) NOT NULL, ".
	"key_fecha INT NOT NULL, PRIMARY KEY (key_bmp), FOREIGN KEY (key_fecha) ".
	"REFERENCES fecha(key_fecha) ON UPDATE CASCADE ON DELETE CASCADE);";
	if(mysqli_query($descriptor,$sql)){
		//echo "Table created ";
	}else{
		die('Error created table bmp'.mysqli_error($descriptor)." ");
	}
	$sql = "CREATE TABLE hdc (key_hdc INT NOT NULL AUTO_INCREMENT, ".
        "humedad FLOAT(4,2) NOT NULL, temperatura FLOAT(4,2) NOT NULL, ".
        "key_fecha INT NOT NULL, PRIMARY KEY (key_hdc), FOREIGN KEY (key_fecha) ".
        "REFERENCES fecha(key_fecha) ON UPDATE CASCADE ON DELETE CASCADE);";
        if(mysqli_query($descriptor,$sql)){
                //echo "Table created ";
        }else{
                die('Error created table hdc'.mysqli_error($descriptor)." ");
        }
	$sql = "CREATE TABLE bat (key_bat INT NOT NULL AUTO_INCREMENT, ".
        "volts FLOAT(4,2) NOT NULL, ".
        "key_fecha INT NOT NULL, PRIMARY KEY (key_bat), FOREIGN KEY (key_fecha) ".
        "REFERENCES fecha(key_fecha) ON UPDATE CASCADE ON DELETE CASCADE);";
        if(mysqli_query($descriptor,$sql)){
                //echo "Table created ";
        }else{
                die('Error created table bat'.mysqli_error($descriptor)." ");
        }
	$sql = "CREATE TABLE lugar (key_id INT NOT NULL AUTO_INCREMENT, ".
        "id VARCHAR(5) NOT NULL, ".
        "key_fecha INT NOT NULL, PRIMARY KEY (key_id), FOREIGN KEY (key_fecha) ".
        "REFERENCES fecha(key_fecha) ON UPDATE CASCADE ON DELETE CASCADE);";
        if(mysqli_query($descriptor,$sql)){
                //echo "Table created ";
        }else{
                die('Error created table lugar'.mysqli_error($descriptor)." ");
        }
}

function insert($descriptor,$dia,$hora,$tempbmp,$presbmp,$temphdc,$humehdc,$bat,$iden)
{
	$sql = "INSERT INTO fecha (dia, hora) VALUES ('".$dia."','".$hora."');";
	if(mysqli_query($descriptor,$sql)){
                //echo "OK Select insert ";
        }else{
                die('Error select insert'.mysqli_error($descriptor)." ");
        }
	$sql = "SELECT `key_fecha` FROM `fecha` WHERE 1 ORDER BY key_fecha DESC LIMIT 0, 1;";
        if($fecha=mysqli_query($descriptor,$sql)){
                //echo "OK Select fecha ";
        }else{
                die('Error select fecha '.mysqli_error($descriptor)." ");
        }
	while($row=mysqli_fetch_array($fecha)){
		//$sql = "INSERT INTO bmp (temperatura, presion, key_fecha) VALUES ('25.20','1005.23','".$row["key_fecha"]."');";
		//echo "<br/>".$sql;
		$key=$row["key_fecha"];
	}
	$sql1 = "INSERT INTO bmp (temperatura, presion, key_fecha) VALUES ('".$tempbmp."','".$presbmp."','".$key."');";
	$sql2 = "INSERT INTO hdc (temperatura, humedad, key_fecha) VALUES ('".$temphdc."','".$humehdc."','".$key."');";
	$sql3 = "INSERT INTO lugar (id, key_fecha) VALUES ('".$iden."','".$key."');";
	$sql4 = "INSERT INTO bat (volts, key_fecha) VALUES ('".$bat."','".$key."');";
        if(mysqli_query($descriptor,$sql1)){
                //echo "OK Select insert ";
		if(mysqli_query($descriptor,$sql2)){
			/*if(mysqli_query($descriptor,$sql3)){
				if(mysqli_query($descriptor,$sql4)){}else{
                                die('Error select insert4'.mysqli_error($descriptor)." "); }
			}else{
                                die('Error select insert3'.mysqli_error($descriptor)." "); }*/
			if(mysqli_query($descriptor,$sql4)){}else{
                                die('Error select insert4'.mysqli_error($descriptor)." "); }
		}else{
			die('Error select insert2'.mysqli_error($descriptor)." "); }
        }else{
                die('Error select insert1'.mysqli_error($descriptor)." "); }

}

function close($descriptor)
{
	mysqli_close($descriptor);
}

?>
