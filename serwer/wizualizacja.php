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
<title>Wizualizacja</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="style2.css">
<script src="jquery.js"></script>
<script type="text/javascript">
var old_count = 0;
setInterval(function(){    
    $.ajax({
        type : "POST",
        url : "bdstan.php",
        success : function(data){
            if (data > old_count) {
                //alert('new record on i_case'+ data+old_count);
                   $('#refresh' ).load(window.location.href + ' #refresh');
			   old_count = data;
           }
        }
    });
},1000);
  </script>
<body>
<div class="topnav">
  <a class="active" href="wizualizacja">Wizualizacja</a>
  <a href="wykresy">Wykresy</a>
  <a href="pomiary">Pomiary</a>
<div class="login-container">
    <form action="wyloguj.php" method="post">
     <button class="button button2" type="submit" name ="logout">Wyloguj</button>
    </form>
  </div>
 </div>
 
 
  
<body>
  <?	  	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";
$id=$_SESSION['id'];
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
	

?><div id="refresh" class= "refresh"><?
//select * from  online where TIMESTAMP(online.Date,online.Time) >= NOW() - INTERVAL 10 MINUTE and module ='A'
$zmianapir=mysqli_query($conn,"select TIMESTAMP(logs.Date,logs.Time)as Czas from logs where sensorID=5 order by czas desc limit 1,1");
$rowpir = mysqli_fetch_assoc($zmianapir);
$zmianagas=mysqli_query($conn,"select TIMESTAMP(logs.Date,logs.Time)as Czas from logs where (sensorID=1 or sensorID=2)  order by czas desc limit 1,1");
$rowgas = mysqli_fetch_assoc($zmianagas);
$zmianakon=mysqli_query($conn,"select TIMESTAMP(logs.Date,logs.Time)as Czas from logs where sensorID=4  order by czas desc limit 1,1");
$rowkon = mysqli_fetch_assoc($zmianakon);
$stanpir=mysqli_query($conn,"select TIMESTAMP(logs.Date,logs.Time)as Czas, value from logs where sensorID=5 order by czas desc limit 1");
$rowpirstan= mysqli_fetch_assoc($stanpir);
if($rowpirstan['value']==1){
$pirstan="Wykryto ruch!";	
}else if($rowpirstan['value']==0){
$pirstan="Brak aktywności!";	
}

$stangas=mysqli_query($conn,"select TIMESTAMP(logs.Date,logs.Time)as Czas, value from logs where (sensorID=1 or sensorID=2) order by czas desc limit 1");
$rowgasstan= mysqli_fetch_assoc($stangas);
if($rowgasstan['value']!=0){
$gasstan="Wykryto gaz/dym!";	
}else if($rowgasstan['value']==0){
$gasstan="Brak aktywności!";	
}

$stankon=mysqli_query($conn,"select TIMESTAMP(logs.Date,logs.Time)as Czas, value from logs where sensorID=4 order by czas desc limit 1");
$rowkonstan= mysqli_fetch_assoc($stankon);
if($rowkonstan['value']==1){
$konstan="Wykryto otwarcie!";	
}else if($rowkonstan['value']==0){
$konstan="Brak aktywności!";	
}
$onlinea=mysqli_query($conn,"select ID from online where TIMESTAMP(online.Date,online.Time) >= NOW() - INTERVAL 11 MINUTE and module ='A'");
$onlinez=mysqli_query($conn,"select ID from online where TIMESTAMP(online.Date,online.Time) >= NOW() - INTERVAL 11 MINUTE and module ='Z'");

$licza=mysqli_num_rows($onlinea);
$liczz=mysqli_num_rows($onlinez);

if($licza>0){
$aonline="Tak";	
}else{
$aonline="Nie";	
}

if($liczz>0){
$zonline="Tak";	
}else{
$zonline="Nie";	
}
?>

<div class="w3-row-padding w3-center w3-margin-top">
<div class="w3-third">
  <div class="w3-card w3-container" style="min-height:460px">
  <h3>PIR</h3>
   <h4>Czujnik ruchu</h4>
  <p>Online: <?echo $aonline;?></p>
  <p>Stan: <?echo $pirstan;?></p>
  <p>Ostatnia zmiana: <?echo $rowpir['Czas'];?></p>
      <?php 
if ($rowpirstan['value']== 1){
$pg="pir2.gif";
} else {
$pg="pir1.gif";
}
?>
       <img class="center" src="<?echo$pg ?>" alt="Alarm pir">
  </div>
</div>

<div class="w3-third">
  <div class="w3-card w3-container" style="min-height:460px">
  <h3>MQ-2</h3>
  <h4>Czujnik gazu/dymu</h4>
  <p>Online: <?echo $zonline;?></p>
  <p>Stan: <?echo $gasstan;?></p>
  <p>Ostatnia zmiana: <?echo $rowgas['Czas'];?></p>
    <?php 
if ($rowgasstan['value']== 1) {
$gg="alert2.gif";
} else {
$gg="alert1.gif";
}
?>
   <img class="center"  src="<?echo$gg ?>" alt="Alarm czujnik gazu">
  </div>
</div>


<div class="w3-third">
  <div class="w3-card w3-container" style="min-height:460px">
  <h3>Kontrakton</h3>
  <h4>Czujnik otwarcia</h4>
  <p>Online: <?echo $aonline;?></p>
  <p>Stan: <?echo $konstan;?></p>
  <p>Ostatnia zmiana: <?echo $rowkon['Czas'];?></p>
  <?php 
if ($rowkonstan['value'] == 1) {
$kg="kon2.gif";
} else {
$kg="kon1.gif";
}
?>
     <img class="center"  src="<?echo$kg ?>" alt="Alarm kontrakton">
  </div>
</div>
</div>
</div>

</body>
</html>
<?
mysqli_close($conn); 
 include('footer.php'); ?>