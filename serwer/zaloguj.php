<?
	if(session_status()!=PHP_SESSION_ACTIVE){
 session_start();
}
  if (isset($_POST['login']) && !empty($_POST['user']) && !empty($_POST['pasw'])) {
	  
               $user = $_POST['user'];
			   
             $pasw = hash('sha256',  $_POST['pasw']);
			 $user = htmlentities($user, ENT_QUOTES, "UTF-8");
			 $pasw = htmlentities($pasw, ENT_QUOTES, "UTF-8");
################################ LOGOWANIE BD ##########################################################
	$servername = "sql.server.nazwa.pl";
    $username = "server_inz";
    $password = "";
    $dbname = "server_inz";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
	
	if (!$conn) {
    exit;
}
#######################################################################################################
$sql= "select * from users where login =? and haslo =?;";
//Prepare statement
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
echo "Błąd tworzenia prepare statement";	
}else
{
// Parametry
mysqli_stmt_bind_param($stmt, "ss", $user, $pasw);
mysqli_stmt_execute($stmt);
$wynik = mysqli_stmt_get_result($stmt);
$sprawdzenie=$wynik->num_rows;
$ip = $_SERVER["REMOTE_ADDR"];


$sql2= "select * from users where login =?;";
$stmt2 = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt2, $sql2)){
echo "Błąd tworzenia prepare statement";	
}else
{
// Parametry
mysqli_stmt_bind_param($stmt2, "s", $user);
mysqli_stmt_execute($stmt2);
$wynik2 = mysqli_stmt_get_result($stmt2);
$sprawdzenie2=$wynik2->num_rows;

$wiersz2 = $wynik2->fetch_assoc();
$wid=$wiersz2['id'];
$proby = mysqli_query($conn,"SELECT logowania.ID from logowania, users WHERE users.ID=logowania.Login and users.ID = '$wid' and stan=1 and TIMESTAMP(logowania.Data,logowania.Godzina) >= NOW() - INTERVAL 10 MINUTE");
$proby=$proby->num_rows;
			if($sprawdzenie2>0)
			{
				if($proby>=5){
          //==================== brute force handling ==========================
$_SESSION['bruteforce']=true;		  
header('Location: /');
exit();			  
		  
		  
					}else{
if($sprawdzenie>0){
	$wiersz = $wynik->fetch_assoc();
								
				$_SESSION['zalogowany'] = true;
				$_SESSION['id'] = $wiersz['id'];
				$_SESSION['prawa'] = $wiersz['prawa'];
				$_SESSION['aktywnosc'] = time();
				$_SESSION['blad'] = false;
				mysqli_stmt_free_result ($stmt);	
				//unset($_SESSION['blad']);
				mysqli_query($conn, "INSERT INTO logowania VALUES(NULL,'$ip','{$_SESSION['id']}',CURDATE(),CURTIME(),'2')");
				mysqli_close($conn);
				if($_SESSION['prawa']==2){
				header('Location: logowania');
				exit();					
				}else if($_SESSION['prawa']==1){
				header('Location: wizualizacja');
				exit();	
				}
	}else{
				unset($_SESSION['id']);
				unset($_SESSION['prawa']);
				$_SESSION['zalogowany'] = false;
				$_SESSION['blad']= true;
				mysqli_query($conn,"INSERT INTO logowania VALUES(NULL,'$ip','$wid',CURDATE(),CURTIME(),'1')");
				mysqli_close($conn);
				header('Location: /');
				exit();	
	}
}
}else{
unset($_SESSION['id']);
unset($_SESSION['prawa']);
$_SESSION['zalogowany'] = false;
$_SESSION['blad']= true;
mysqli_query($conn,"INSERT INTO logowania VALUES(NULL,'$ip',null,CURDATE(),CURTIME(),'1')");
mysqli_close($conn);
header('Location: /');
exit();							
}		
}			
}
}
?>