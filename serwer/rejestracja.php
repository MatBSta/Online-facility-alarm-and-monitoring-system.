<?
if(session_status()!=PHP_SESSION_ACTIVE){
session_start();
}
require('expire.php');
include_once('tracking.php');
if (!(isset($_SESSION['zalogowany'])) or ($_SESSION['zalogowany']==false) or ($_SESSION['prawa']!=2)){
header('Location: /');
}
?>
<!DOCTYPE html>
<html>
<title>Rejestracja</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<body>
<div class="topnav">
  <a href="logowania">Logowania</a>
  <a href="odwiedziny">Odwiedziny</a>
  <a class="active" href="rejestracja">Rejestracja</a>
<div class="login-container">
    <form action="wyloguj.php" method="post">
     <button class="button button2" type="submit" name ="logout">Wyloguj</button>
    </form>
  </div>
 </div>
 <p></p><p></p>
     <form class="register" action="rejestracja" method="post">
     <p><input class="register_input" type="text" placeholder="Login" name="user" required></p>
      <p><input class="register_input" type="password" placeholder="Hasło" name="pasw" required></p>
	<select class="register_input" name="prawa">
    <option value="1">Użytkownik</option>
    <option value="2">Administrator</option>
  </select>
     <p>   <button class="register_input" id="button3" type="submit" name ="login">Utwórz</button></p>
    </form>
	<p></p>
<?
	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";

    // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_query($conn,'set names utf8');
 if (isset($_POST['login']) && !empty($_POST['user']) && !empty($_POST['pasw']) && !empty($_POST['prawa'])) {
             $user = $_POST['user'];
			 $prawa = $_POST['prawa'];
             $pasw = hash('sha256',  $_POST['pasw']);
			 $user = htmlentities($user, ENT_QUOTES, "UTF-8");
			 $pasw = htmlentities($pasw, ENT_QUOTES, "UTF-8");
			 $prawa = htmlentities($prawa, ENT_QUOTES, "UTF-8");
			 
$sql= "select * from users where login =?;";
//Prepare statement
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
}
// Parametry
mysqli_stmt_bind_param($stmt, "s", $user);
mysqli_stmt_execute($stmt);
$wynik = mysqli_stmt_get_result($stmt);
$sprawdzenie=$wynik->num_rows;
if($sprawdzenie==0){
$sql ="INSERT INTO users VALUES(null, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
echo "Błąd tworzenia prepare statement";	
}else
{
// Parametry
mysqli_stmt_free_result ($stmt);	
mysqli_stmt_bind_param($stmt, "ssi", $user, $pasw, $prawa);
mysqli_stmt_execute($stmt);	
mysqli_stmt_free_result ($stmt);	
}
echo 'Konto utworzono pomyślnie';				}else{
echo 'Takie konto już istnieje';
}
 }
$konta= mysqli_query($conn, "select login, prawa.Nazwa from users, prawa where users.prawa=prawa.ID");
?> 
<div class="container">
<div class="responsive">
<table class="tableregister striped bordered">
<thead>
<tr class="theme">
  <th>Login</th>
  <th>Prawa</th>
</tr>
</thead>
<tbody>
<?
                     while($row = mysqli_fetch_array($konta))  
                     {  
                     ?>  
                          <tr>  
                               <td><?php echo $row["login"]; ?></td>  
                               <td><?php echo $row["Nazwa"]; ?></td>   	
                          </tr>  
                     <?php  
                     }
					 ?>
					 </tbody>
					 </table>
					 </div>
					 </div>
					 <?
mysqli_free_result($konta);
mysqli_close($conn);					 
               ?> 

 

<?php include('footer.php'); ?>
</body>
</html>
