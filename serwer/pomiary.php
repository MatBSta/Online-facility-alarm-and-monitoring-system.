<?
if(session_status()!=PHP_SESSION_ACTIVE){
session_start();
}
require('expire.php');
include_once('tracking.php');
if (!(isset($_SESSION['zalogowany'])) or ($_SESSION['zalogowany']==false) or ($_SESSION['prawa']!=1)){
header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<title>Pomiary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<meta charset="utf-8">
<body>

<div class="topnav">
  <a href="wizualizacja">Wizualizacja</a>
  <a href="wykresy">Wykresy</a>
  <a class="active" href="pomiary">Pomiary</a>
<div class="login-container">
    <form action="wyloguj.php" method="post">
     <button class="button button2" type="submit" name ="logout">Wyloguj</button>
    </form>
  </div>
 </div>
 
  <p></p><p></p>
	
	 <form class="pomiary" action="pomiary" method="post">
        <label class="pomiary_label" for="startdate">Od: </label>
       <input class="pomiary_input" type="date" name="startdate" min="2019-01-11" max="<?php echo date('Y-m-d');?>"  
              value="<?php echo date('Y-m-d'); ?>" required>
   <label class="pomiary_label" for="stopdate">Do: </label>
<input class="pomiary_input" type="date" name="stopdate" min="2019-01-11" max="<?php echo date('Y-m-d'); ?>"
   value="<?php echo date('Y-m-d'); ?>"required>

 <div class="pomiary_belka">
<p><label class="pomiary_label" for="Pir">Pir:</label><input type="checkbox" class="pomiary_checkbox" name="v1" value="5" checked> 
<label class="pomiary_label" for="Kontakton">Kontakton:</label><input type="checkbox" class="pomiary_checkbox" value="4"  name="v2" checked>
<label class="pomiary_label" for="Gaz">Gaz:</label><input type="checkbox" class="pomiary_checkbox" name="v3" value="1"  checked>
<label class="pomiary_label" for="Gaz">Stężenie:</label><input type="checkbox" class="pomiary_checkbox" name="v4" value="2"  checked>
 <button class="pomiary_button" id="button3" type="submit" name ="login">Sprawdz</button>
</form>
</div><p></p>
 
<? 
if (isset($_POST['login']) && !empty($_POST['startdate']) && !empty($_POST['stopdate'])) {
?>
	 <div class="container">
<div class="responsive">
<table class="table striped bordered">
<thead>
<tr class="theme">
  <th>Czujnik</th>
  <th>Stan</th>
    <th>Data</th>
  <th>Godzina</th>
</tr>
</thead>
<tbody>
<?
	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";

    // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_query($conn,'set names utf8');

			$startdate = $_POST['startdate'];
			$stopdate = $_POST['stopdate'];        
			$v1 = $_POST['v1'];         			
			$v2 = $_POST['v2'];    			
			$v3 = $_POST['v3'];    
			$v4 = $_POST['v4'];    
$kwerenda=mysqli_query($conn,"select sensors.Nazwa, value, Date, Time from logs ,sensors where logs.sensorID=sensors.ID and Date BETWEEN '$startdate' AND '$stopdate'  
and (sensorID='$v1' or sensorID='$v2' or sensorID='$v3' or sensorID='$v4')
order by Date desc, Time desc");

                     while($row = mysqli_fetch_array($kwerenda))  
                     {  
                     ?>  
                          <tr>  
                               <td><?php echo $row["Nazwa"]; ?></td>  
                               <td><?php echo $row["value"]; ?></td>   	
							   <td><?php echo $row["Date"]; ?></td>  
                               <td><?php echo $row["Time"]; ?></td>   	
                          </tr>  
                     <?php  
                     }
					  
				 
mysqli_free_result($kwerenda);
mysqli_close($conn);	
}				 
               ?> 
			   
 </tbody>
					 </table>
					 </div>
					 </div>		


}
<?include('footer.php'); ?>
</body>
</html>
