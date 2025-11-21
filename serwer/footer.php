<footer>
<div class="footer">
  <?
  if (!(isset($_SESSION['zalogowany'])) or ($_SESSION['zalogowany']==false)){
?>
  <p>Niezalogowany</p>	
<?	 	 
  }else{
	  ?><p><?
	  	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";
$id=$_SESSION['id'];
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
	$usersql = mysqli_query($conn,"select users.login from users where id ='$id'");
		$logowaniesql = mysqli_query($conn,"select Data, Godzina from logowania where Login ='$id' and stan=2 order by Data desc, Godzina desc limit 1,1");
$row = mysqli_fetch_array($usersql);
$user = $row['login'];
$row2 =  mysqli_fetch_array($logowaniesql);
$data = $row2['Data'];
$godzina = $row2['Godzina'];
mysqli_free_result ($usersql);	
mysqli_free_result ($logowaniesql);	
?><span class="rwd-line"><?
echo "Zalogowano jako: ".$user.", ";
?></span><span class="rwd-line"><?
echo "ostatnie logowanie: ".$data.", ".$godzina."";
  ?></span></p><?
   mysqli_close($conn);
 }
?>
</div>
</footer>

