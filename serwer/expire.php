<?
$servername = "sql.server.nazwa.pl";
$username = "server_inz";
$password = "";
$dbname = "server_inz";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (isset($_SESSION['aktywnosc'])){	
	if (time() - $_SESSION['aktywnosc'] > 600){


    // Create connection

$ip = $_SERVER["REMOTE_ADDR"];		
mysqli_query($conn,"INSERT INTO logowania VALUES(NULL,'$ip','{$_SESSION['id']}',CURDATE(),CURTIME(),'3')");
mysqli_close($conn);
	    session_unset();     // unset $_SESSION variable for the run-time 
	    session_destroy();   // destroy session data in storage
	}else{
		$_SESSION['aktywnosc'] = time(); // update last activity time stamp		
	}
} else{
	mysqli_query($conn,"INSERT INTO logowania(Login, Data, Godzina, Stan)
select logi.Login, curdate(), curtime(), 3 from logi logi 
,(SELECT logowania.Login as kapi
FROM logowania logowania
WHERE logowania.id = (SELECT users.id
                 FROM logowania users
                 WHERE users.login= logowania.Login          
                 ORDER BY users.id DESC
                 LIMIT 1 )
and Stan=2 
and TIMESTAMP(logowania.Data,logowania.Godzina) <= NOW() - INTERVAL 10 MINUTE
) as proba
WHERE logi.id = (SELECT users.id
                 FROM logi users
                 WHERE users.login= logi.Login          
                 ORDER BY users.id DESC
                 LIMIT 1 )
and TIMESTAMP(logi.Data,logi.Godzina) <= NOW() - INTERVAL 10 MINUTE
and proba.kapi=logi.Login");
}

?>