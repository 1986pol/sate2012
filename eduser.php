<?php 
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));
echo 
	"<html>
	<form action='' method=POST>
	<table style=background-color:grey>
	<tr><td><b>Enter the name:  </b></td><td><input type=text  name=name size=30></td></tr>
	<tr><td><b>Enter the surname:  </b></td><td><input type=text  name=surname size=30><br></td></tr>
	<tr><td><b>Enter the email:  </b></td><td><input type=text  name=email size=30><br></td></tr>
	</table>
    </form><br>
	</html>";

	$log = $_SESSION['login'];
	$db = mysql_connect ("localhost","root","7766421");
	mysql_select_db ("test",$db);
	$q = mysql_query("SELECT * FROM users WHERE login='$log'",$db);
	$row = mysql_fetch_array($q);
	echo "<table style=background-color:grey ><td><br>login: ".$row['login']."<br>email: ".$row['email']."<br>Data register: ".$row['data']."</td></table>";
//кнопка завершающая сессию и кнопка возвращающая на предыдущую страницу
echo "<html>
	<br>
	<form action='' method=POST>
	<input type=submit style='width:120px; height:30px' name=exit value='Quit'>
	<input type=submit style='width:120px; height:30px' name=back value='Back'>
    </form><br>
	</html>";
	if(isset($_POST['back']))
	{
    header("Location: users.php");
	}
	if(isset($_POST['exit']))
	{
	$_SESSION['page'] = "b";
    session_destroy();
    header("Location: auth.php");
    die();
	}
?>