<?php
if(session_status()!=PHP_SESSION_ACTIVE){
  session_start();
}
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
$ip = $_SERVER["REMOTE_ADDR"];

mysqli_query($conn,"INSERT INTO logowania VALUES(NULL,'$ip','{$_SESSION['id']}',CURDATE(),CURTIME(),'3')");
mysqli_close($conn);
session_destroy();
header('Location: /');
?>