<?
if(session_status()!=PHP_SESSION_ACTIVE){
session_start();
}
require('expire.php');
include_once('tracking.php');
if (!(isset($_SESSION['zalogowany'])) or ($_SESSION['zalogowany']==false) or ($_SESSION['prawa']!=2)){
header('Location: /');
}
	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";

    // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_query($conn,'set names utf8');
?>
<!DOCTYPE html>
<html>
<title>Logowania</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<body>
<div class="topnav">
  <a class="active" href="logowania">Logowania</a>
  <a href="odwiedziny">Odwiedziny</a>
  <a href="rejestracja">Rejestracja</a>
<div class="login-container">
    <form action="wyloguj.php" method="post">
     <button class="button button2" type="submit" name ="logout">Wyloguj</button>
    </form>
  </div>
 </div>
 
<p></p><p></p>
      <form class="logowania" action="logowania" method="post">
        <label class="logowania_label" for="startdate">Od: </label>
       <input class="logowania_input" type="date" name="startdate" min="2019-01-11" max="<?php echo date('Y-m-d');?>"  
              value="<?php echo date('Y-m-d'); ?>" required>
   <label class="logowania_label" for="stopdate">Do: </label>
<input class="logowania_input" type="date" name="stopdate" min="2019-01-11" max="<?php echo date('Y-m-d'); ?>"
   value="<?php echo date('Y-m-d'); ?>"required>
     &nbsp;
    <select name ="stan" class="logowania_input2" required>
    <option value="" disabled selected hidden>Wybierz stan:</option>
	<option value="4">Wszystkie</option>
    <option value="1">Niepomyślne</option>
	 <option value="2">Pomyślne</option>
	  <option value="3">Wylogowanie</option>
       </select>
&nbsp;
<select name="user" class="logowania_input2" required>
    <option value="" disabled selected hidden>Wybierz użytkownika:</option>
	<option value="-1">Wszyscy</option>
<?
$get=mysqli_query($conn,"SELECT id,users.login FROM users");
$option = '';
 while($row = mysqli_fetch_assoc($get))
{
  $option .= '<option value = "'.$row['id'].'">'.$row['login'].'</option>';
}
?>
<?php echo $option; ?>
</select>			 











&nbsp; <button class="logowania_button" id="button3" type="submit" name ="login">Sprawdź</button></p>
    </form>
<p></p>


<?
 if (isset($_POST['login']) && !empty($_POST['stan']) && !empty($_POST['user']) && !empty($_POST['startdate']) && !empty($_POST['stopdate'])) {
	 ?>
	 <div class="container">
<div class="responsive">
<table class="table striped bordered">
<thead>
<tr class="theme">
  <th>IP</th>
  <th>Login</th>
    <th>Data</th>
  <th>Godzina</th>
    <th>Stan</th>
</tr>
</thead>
<tbody>
<?
			$user = $_POST['user'];
			$startdate = $_POST['startdate'];
			$stopdate = $_POST['stopdate'];            
			if($_POST['stan']==4){
			$stan1 = "1";	
			$stan2 = "3";				
			 }else{
			$stan1 = $_POST['stan'];	 
			$stan2 = $_POST['stan'];
			 }
			 if($_POST['user']==-1){
$konta= mysqli_query($conn,"select logowania.IP, users.login, logowania.Data, logowania.Godzina, stany.Stan from users, logowania, stany where users.ID=logowania.login and stany.ID=logowania.stan 
and users.id between 0 and (
    SELECT max(id) FROM users
    )and logowania.stan BETWEEN ".$stan1." AND ".$stan2." and data BETWEEN '$startdate 'AND '$stopdate' order by Data desc, Godzina desc");
			 }else{
$konta= mysqli_query($conn, "select logowania.IP, users.login, logowania.Data, logowania.Godzina, stany.Stan from users, logowania, stany where users.ID=logowania.login and stany.ID=logowania.stan 
and users.id='$user' and logowania.stan BETWEEN ".$stan1." AND ".$stan2." and data BETWEEN '$startdate 'AND '$stopdate' order by Data desc, Godzina desc");
			 }


                     while($row = mysqli_fetch_array($konta))  
                     {  
                     ?>  
                          <tr>  
                               <td><?php echo $row["IP"]; ?></td>  
                               <td><?php echo $row["login"]; ?></td>   	
							   <td><?php echo $row["Data"]; ?></td>  
                               <td><?php echo $row["Godzina"]; ?></td>   	
							   <td><?php echo $row["Stan"]; ?></td> 
                          </tr>  
                     <?php  
                     }
					  
mysqli_free_result($get);				 
mysqli_free_result($konta);
mysqli_close($conn);	
}				 
               ?> 
			   
 </tbody>
					 </table>
					 </div>
					 </div>			   
<?php include('footer.php'); ?>
</body>
</html>


