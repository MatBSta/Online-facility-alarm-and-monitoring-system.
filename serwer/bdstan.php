<?	
  $servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
$result3 = mysqli_query($conn,"select MAX(logs.id) from logs");
while($row3 = mysqli_fetch_array($result3))
{
    $logs = $row3[0];
} 
$result4 = mysqli_query($conn,"select MAX(online.id) from online");
while($row4 = mysqli_fetch_array($result4))
{
    $online = $row4[0];
} 
$suma = $logs+$online;
mysqli_free_result ($result3);	
mysqli_free_result ($result4);	
mysqli_close($conn);
echo "$suma";
?>





