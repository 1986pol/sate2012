<?php 
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));
//выводим количество пользователей
	$db = mysql_connect ("localhost","root","7766421");
    mysql_select_db ("test",$db);
	$q = mysql_query("SELECT COUNT(*) FROM users");
	$myrow = mysql_fetch_array($q);
	$total = $myrow[0];
	echo "Total Users: $total<br><br><br>";
	echo"<form action=eduser.php method=POST>
	<input type=submit style='width:120px; height:30px' name=eduser value='ed user'>
	</form>";
//выводим всех пользователей

	$db = mysql_connect ("localhost","root","7766421");
    mysql_select_db ("test",$db);
	$q = mysql_query('SELECT * FROM users');
	while($row = mysql_fetch_array($q))
	{
	echo "<table style=background-color:grey ><td><br>login: ".$row['login']."<br>email: ".$row['email']."<br>Data register: ".$row['data']."</td></table>";
	}
////кнопка завершающая сессию и кнопка возвращающая на предыдущую страницу
echo "<html>
	<br>
	<form action='' method=POST>
	<input type=submit style='width:120px; height:30px' name=exit value='Quit'>
	<input type=submit style='width:120px; height:30px' name=back value='Back'>
    </form><br>
	</html>";
	if(isset($_POST['back']))
	{
    header("Location: index.php");
	}
	if(isset($_POST['exit']))
	{
	$_SESSION['page'] = "b";
    session_destroy();
    header("Location: auth.php");
    die();
	}
?>