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
<title>Odwiedziny</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<body>

<div class="topnav">
  <a href="logowania">Logowania</a>
  <a class="active" href="odwiedziny">Odwiedziny</a>
  <a href="rejestracja">Rejestracja</a>
<div class="login-container">
    <form action="wyloguj.php" method="post">
     <button class="button button2" type="submit" name ="logout">Wyloguj</button>
    </form>
  </div>
 </div>
 <p></p><p></p>
       <form class="odwiedziny" action="odwiedziny" method="post">
        <label class="odwiedziny_label" for="startdate">Od: </label>
       <input class="odwiedziny_input" type="date" name="startdate" min="2019-01-11" max="<?php echo date('Y-m-d');?>"  
              value="<?php echo date('Y-m-d'); ?>"required>
   <label class="odwiedziny_label" for="stopdate">Do: </label>
<input class="odwiedziny_input" type="date" name="stopdate" min="2019-01-11" max="<?php echo date('Y-m-d'); ?>"
   value="<?php echo date('Y-m-d'); ?>"required>
     &nbsp;
<button class="odwiedziny_button" id="button3" type="submit" name ="login">Sprawdz</button></p>
    </form>
<p></p>
<?
 if (isset($_POST['login']) && !empty($_POST['startdate']) && !empty($_POST['stopdate'])) {
?><div class="container">
<div class="responsive">
<table class="table striped bordered">
<thead>
<tr class="theme">
  <th>Login</th>
  <th>IP</th>
    <th>Data</th>
  <th>Godzina</th>
    <th>Przekierowanie</th>
	  <th>Aplikacja kliencka</th>
    <th>Nazwa hosta</th>
  <th>Nazwa strony</th>
</tr>
</thead>
<tbody><?	 
	 
	 	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";

    // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_query($conn,'set names utf8');

				$startdate = $_POST['startdate'];
			$stopdate = $_POST['stopdate'];     



$odwiedziny= mysqli_query($conn, "select users.Login, IP, Data, Godzina, Przekierowanie, Aplikacja_kliencka, Nazwa_hosta , Nazwa_strony  from logi LEFT JOIN users ON logi.Login=users.ID and data BETWEEN '$startdate 'AND '$stopdate' order by Data desc, Godzina desc");
			


                     while($row = mysqli_fetch_array($odwiedziny))  
                     {  
                     ?>  
                          <tr>  
                               <td><?php echo $row["Login"]; ?></td>  
								<td><?php echo $row["IP"]; ?></td> 							   
							   <td><?php echo $row["Data"]; ?></td>  
                               <td><?php echo $row["Godzina"]; ?></td>   	
							   <td><?php echo $row["Przekierowanie"]; ?></td> 
							   <td><?php echo $row["Aplikacja_kliencka"]; ?></td> 
							   <td><?php echo $row["Nazwa_hosta"]; ?></td> 
							   <td><?php echo $row["Nazwa_strony"]; ?></td> 							   
                          </tr>  
                     <?php  
                     }
					  
					 ?>
					 </tbody>
					 </table>
					 </div>
					 </div>
					 
					 
<? 			 
mysqli_free_result($odwiedziny);
mysqli_close($conn);	
 }
			
 
?>	
	
<?php include('footer.php'); ?>
</body>
</html>
