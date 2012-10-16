<?php
session_start();
$tit=$_SESSION['tit'];
$mes=$_SESSION['mes'];
$data=$_SESSION['mdata'];
$db = mysql_connect ("localhost","root","7766421");
mysql_select_db ("test",$db);
echo
			"<html>
			<br><form action='' method=POST>
				Your Title: $tit<br>
				<input type=text name=tit size=''>
				<input type=submit style='width:100px; height:30px' name=Ctit value='Change'><br>
				<form action='' method=POST>
				Your messages: $mes<br>
				<input type=text name=mes size=''>
				<input type=submit style='width:100px; height:30px' name=Cmes value='Change'><br>
			</html>";
			
if(isset($_POST['Ctit']))
	{	
		$title=$_POST['tit'];
		$title = stripslashes($title); $title = htmlspecialchars($title); $title = trim($title);
			if($title=='')
				{
					echo"You have not entered title";
				}
				else
				{
					$q = mysql_query("UPDATE messages SET  title='$title' WHERE data='$data'",$db);
					$_SESSION['tit']=$title;
				}
	}
	if(isset($_POST['Cmes']))
	{	
		$message=$_POST['mes'];
		$message = stripslashes($message); $message = htmlspecialchars($message); $message = trim($message);
			if($message=='')
				{
					echo"You have not entered message";
				}
				else
				{
					$q = mysql_query("UPDATE messages SET  message='$message' WHERE data='$data'",$db);
					$_SESSION['mes']=$message;
				}
	}
	
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
		header("Location: test.php");
		}
		if(isset($_POST['exit']))
		{
		$_SESSION['page'] = "b";
		session_destroy();
		header("Location: auth.php");
		die();
		}
?>