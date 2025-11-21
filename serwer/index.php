<?
if(session_status()!=PHP_SESSION_ACTIVE){
 session_start();
}
if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true) && ($_SESSION['prawa']==2)){
				header('Location: logowania');
				exit();					
}
else if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true) &&  ($_SESSION['prawa']==1)){
				header('Location: wizualizacja');
				exit();	
}else if ((!isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==false))
	{
}


?>
<!DOCTYPE html>
<html lang="pl-PL">
<title>INZ</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<body>
<?
require('expire.php');
include_once('tracking.php');
		?>
<div class="topnav">
  <a class="active" href="">Strona główna</a>
<div class="login-container">
    <form action="zaloguj.php" method="post">
      <input type="text" placeholder="Login" name="user" required>
      <input type="password" placeholder="Hasło" name="pasw" autocomplete="off" required >
      <button class="button button1" type="submit" name ="login">Zaloguj</button>
    </form>
  </div>
 </div>
<?
			
		

if(isset($_SESSION['blad']) && ($_SESSION['blad']==true) && (!isset($_SESSION['bruteforce']))){
?>
<div class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
  <strong>Uwaga!</strong> Niepoprawne dane logowania.
</div>
<?		
}
if(isset($_SESSION['bruteforce']) && ($_SESSION['bruteforce']==true)){
?>
<div class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
  <strong>Uwaga!</strong> Zbyt wiele niepoprawnych prób logowania! Odczekaj 10 minut.
</div>
<?
}
	?>
	 <p>Admin:</p>
 L: grad H: grad
 <p>User:</p>
 L: grad2 H: grad2
	<?	

		

?>
<?php include('footer.php'); ?>

</body>
</html>
