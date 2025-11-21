<?
if(session_status()!=PHP_SESSION_ACTIVE){
session_start();
}
require('expire.php');
include_once('tracking.php');
if (!(isset($_SESSION['zalogowany'])) or ($_SESSION['zalogowany']==false) or ($_SESSION['prawa']!=1)){
header('Location: /');
}
?>
<!DOCTYPE html>
<html>
<title>Wykresy</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<body>

<div class="topnav">
  <a href="wizualizacja">Wizualizacja</a>
  <a class="active" href="wykresy">Wykresy</a>
  <a href="pomiary">Pomiary</a>
<div class="login-container">
    <form action="wyloguj.php" method="post">
     <button class="button button2" type="submit" name ="logout">Wyloguj</button>
    </form>
  </div>
 </div>

   <p></p><p></p>
	
	 <form class="pomiary" action="wykresy" method="post">
        <label class="pomiary_label" for="startdate">Od: </label>
       <input class="pomiary_input" type="date" name="startdate" min="2019-01-11" max="<?php echo date('Y-m-d');?>"  
              value="<?php echo date('Y-m-d'); ?>" required>
   <label class="pomiary_label" for="stopdate">Do: </label>
<input class="pomiary_input" type="date" name="stopdate" min="2019-01-11" max="<?php echo date('Y-m-d'); ?>"
   value="<?php echo date('Y-m-d'); ?>"required>

 <div class="pomiary_belka">
<p><label class="pomiary_label" for="Pir">Pir:</label><input type="checkbox" class="pomiary_checkbox" name="v1" value="5" checked> 
<label class="pomiary_label" for="Kontakton">Kontakton:</label><input type="checkbox" class="pomiary_checkbox" name="v2" value="4" checked>
<label class="pomiary_label" for="Gaz">Gaz:</label><input type="checkbox" class="pomiary_checkbox" name="v3" value="1"  checked>
 <button class="pomiary_button" id="button3" type="submit" name ="login">Sprawdz</button>
</form>
</div>
 
   <?
 if (isset($_POST['login']) && !empty($_POST['startdate']) && !empty($_POST['stopdate'])) {
	 			$startdate = $_POST['startdate'];
			$stopdate = $_POST['stopdate'];        
			$v1 = $_POST['v1'];         			
			$v2 = $_POST['v2'];    			
			$v3 = $_POST['v3'];    
$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";

    $con = new mysqli($servername, $username, $password, $dbname);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
mysqli_query($con, "insert into test (id,czas, Gas, Kontakton,Pir)
values (1,0,0,0,0)");
	
mysqli_query($con, "insert into test (czas, Gas, Kontakton,Pir)
SELECT TIMESTAMP(logs.Date,logs.Time) as czas,
IF(sensorID=1,value, NULL) as Gas,
IF(sensorID=4,value, NULL) as Kontakton,
IF(sensorID=5,value, NULL) as Pir
FROM logs where (sensorID!=2 and sensorID!=3) and Date BETWEEN '$startdate' AND '$stopdate'  
and (sensorID='$v1' or sensorID='$v2' or sensorID='$v3') GROUP BY czas order by czas ");

while(mysqli_num_rows(mysqli_query($con,"select * from test where 
Gas is null or 
Kontakton is null or 
Pir is null"))>0){
mysqli_query($con,"UPDATE test t
JOIN test tt ON (t.id - 1) = tt.id
SET t.Gas =
  CASE WHEN t.Gas IS NULL THEN
    tt.Gas
  ELSE
    t.Gas
  END,
  t.Kontakton = 
  CASE WHEN t.Kontakton IS NULL THEN
    tt.Kontakton
  ELSE 
    t.Kontakton
  END,
	t.Pir =
  CASE WHEN t.Pir IS NULL THEN
    tt.Pir
  ELSE
    t.Pir
  END;");
};

if($v1==5 && $v2==4 && $v3==1){
$query="SELECT czas, Gas, Kontakton, Pir FROM test where id>1";	
    $result = $con->query($query);
while ($row = mysqli_fetch_array($result)) {
$entry .= "['".$row{0}."',".$row{1}.",".$row{2}.",".$row{3}."],";
}
mysqli_query($con,"TRUNCATE TABLE test");	
	?>
	
	  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {		  
        var data = google.visualization.arrayToDataTable([
          ['czas', 'Gas', 'Kontakton', 'Pir'],
		  <?php echo $entry ?>
        ]);
        var options = {
			     vAxis: {
            minValue: 0,
            ticks: [0, 1]
          },
               legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head> 	

 <?php
mysqli_free_result($result);
 }
 else if($v1==5 && $v2==4 && $v3!=1){
$query="SELECT czas, Kontakton, Pir FROM test where id>1";	
    $result = $con->query($query);
while ($row = mysqli_fetch_array($result)) {
$entry .= "['".$row{0}."',".$row{1}.",".$row{2}."],";
}
mysqli_query($con,"TRUNCATE TABLE test");	
	?>
	
	  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {		  
        var data = google.visualization.arrayToDataTable([
          ['czas', 'Kontakton', 'Pir'],
		  <?php echo $entry ?>
        ]);
        var options = {
               legend: {position: 'bottom', maxLines: 3},
			        vAxis: {
            minValue: 0,
            ticks: [0, 1]
          }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head> 	

 <?php
mysqli_free_result($result);
 }	 
 else if($v1==5 && $v2!=4  && $v3==1){
$query="SELECT czas, Gas, Pir FROM test where id>1";	
    $result = $con->query($query);
while ($row = mysqli_fetch_array($result)) {
$entry .= "['".$row{0}."',".$row{1}.",".$row{2}."],";
}
mysqli_query($con,"TRUNCATE TABLE test");	
	?>
	
	  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {		  
        var data = google.visualization.arrayToDataTable([
          ['czas', 'Gas', 'Pir'],
		  <?php echo $entry ?>
        ]);
        var options = {
               legend: {position: 'bottom', maxLines: 3},
			        vAxis: {
            minValue: 0,
            ticks: [0, 1]
          }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head> 	

 <?php
mysqli_free_result($result);	 	  
 }
  else if($v1!=5 && $v2==4 && $v3==1){
$query="SELECT czas, Gas, Kontakton FROM test where id>1";	
    $result = $con->query($query);
while ($row = mysqli_fetch_array($result)) {
$entry .= "['".$row{0}."',".$row{1}.",".$row{2}."],";
}
mysqli_query($con,"TRUNCATE TABLE test");	
	?>
	
	  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {		  
        var data = google.visualization.arrayToDataTable([
          ['czas', 'Gas', 'Kontakton'],
		  <?php echo $entry ?>
        ]);
        var options = {
               legend: {position: 'bottom', maxLines: 3},
			        vAxis: {
            minValue: 0,
            ticks: [0, 1]
          }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head> 	

 <?php
mysqli_free_result($result);	 	  
 }



  else if($v1==5 && $v2!=4 && $v3!=1){
$query="SELECT czas, Pir FROM test where id>1";	
    $result = $con->query($query);
while ($row = mysqli_fetch_array($result)) {
$entry .= "['".$row{0}."',".$row{1}."],";
}
mysqli_query($con,"TRUNCATE TABLE test");	
	?>
	
	  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {		  
        var data = google.visualization.arrayToDataTable([
          ['czas', 'Pir'],
		  <?php echo $entry ?>
        ]);
        var options = {
               legend: {position: 'bottom', maxLines: 3},
			        vAxis: {
            minValue: 0,
            ticks: [0, 1]
          }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head> 	

 <?php
mysqli_free_result($result);	 	  
 }
  else if($v1!=5 && $v2==4 && $v3!=1){
$query="SELECT czas, Kontakton FROM test where id>1";	
    $result = $con->query($query);
while ($row = mysqli_fetch_array($result)) {
$entry .= "['".$row{0}."',".$row{1}."],";
}
mysqli_query($con,"TRUNCATE TABLE test");	
	?>
	
	  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {		  
        var data = google.visualization.arrayToDataTable([
          ['czas', 'Kontakton'],
		  <?php echo $entry ?>
        ]);
        var options = {
               legend: {position: 'bottom', maxLines: 3},
			        vAxis: {
            minValue: 0,
            ticks: [0, 1]
          }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head>

 <?php
mysqli_free_result($result);	 	  
 }
   else if($v1!=5 && $v2!=4 && $v3==1){
$query="SELECT czas, Gas FROM test where id>1";	
    $result = $con->query($query);
while ($row = mysqli_fetch_array($result)) {
$entry .= "['".$row{0}."',".$row{1}."],";
}
mysqli_query($con,"TRUNCATE TABLE test");	
	?>
	
	  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {		  
        var data = google.visualization.arrayToDataTable([
          ['czas', 'Gas'],
		  <?php echo $entry ?>
        ]);
        var options = {
               legend: {position: 'bottom', maxLines: 3},
			        vAxis: {
            minValue: 0,
            ticks: [0, 1]
          }
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head>


 <?php
mysqli_free_result($result);	 	  
 }

mysqli_close($con);	
 ?>
 <div id="curve_chart" style="width: 100%; height: 500px;"></div>
</body>
</html>
 
 <?
 }
  include('footer.php'); 
 ?>