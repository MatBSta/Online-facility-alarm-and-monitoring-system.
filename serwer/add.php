<?php
//Creates new record as per request
    //Connect to database
	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
	
	if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

    //Get current date and time
    $d = date("Y-m-d");
    $t = date("H:i:s");
	
#!empty($_POST['value']) &&
    if(!empty($_POST['sensor']) && !empty($_POST['module']))
    {
     $value = $_POST['value'];
     $module = $_POST['module'];
	 $sensor = $_POST['sensor'];
if($sensor == 3){
	
	 $sql = "INSERT INTO online (module, Date, Time)
  
  VALUES ('".$module."', '".$d."', '".$t."')";	
}
else{
	
	 $sql = "INSERT INTO logs (value, Date, Time, sensorID)
  
  VALUES ('".$value."', '".$d."', '".$t."', '".$sensor."')";	
}
    

$conn->query($sql);
 }
mysqli_close($conn);
?>