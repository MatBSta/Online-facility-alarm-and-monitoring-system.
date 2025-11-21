<?php
$ipaddress = $_SERVER["REMOTE_ADDR"];
    $servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
	if (!$conn) {
    exit;
}
$sql1 = "SET NAMES utf8";
$sql2 ="SET CHARACTER SET utf8";
$sql3 ="SET collation_connection = utf8_polish_ci";
mysqli_query($conn,$sql1);
mysqli_query($conn,$sql2);
mysqli_query($conn,$sql3);
$ref=$_SERVER['HTTP_REFERER'];
$Nazwa_strony= "$_SERVER[REQUEST_URI]";
$agent=$_SERVER['HTTP_USER_AGENT'];
$host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);	
if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
	
$id=$_SESSION['id'];
$sql="INSERT INTO logi(IP,Login, Data, Godzina,Przekierowanie, Aplikacja_kliencka, Nazwa_hosta, Nazwa_strony)    VALUES('$ipaddress','$id',CURDATE(),CURTIME(),'$ref','$agent','$host_name','$Nazwa_strony')";
	}else
	{
$sql="INSERT INTO logi(IP,Login, Data, Godzina,Przekierowanie, Aplikacja_kliencka, Nazwa_hosta, Nazwa_strony)    VALUES('$ipaddress',NULL,CURDATE(),CURTIME(),'$ref','$agent','$host_name','$Nazwa_strony')";
	}
mysqli_query($conn,$sql);
mysqli_close($conn);
?>