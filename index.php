﻿<?php 
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));
if($_SESSION['role']==0)
	{ 
		echo "Your username is blocked";
	}
else
	{
	//выводим количество пользователей
		$db = mysql_connect ("localhost","root","7766421");
		mysql_select_db ("test",$db);
		$q = mysql_query("SELECT COUNT(*) FROM users");
		$myrow = mysql_fetch_array($q);
		$total = $myrow[0];
		echo "Total Users: $total";
	//кнопка для созданя новых сообщенй
	if ($_SESSION['role']==2 or $_SESSION['role']==3)
		{
			echo"<h1>Guestbook</h1>";
			echo"<form action='' method=POST>
			<input type=submit style='width:120px; height:30px' name=addrecord value='Add record'>
			<input type=submit style='width:120px; height:30px' name=users value='Users'>
			<input type=submit style='width:120px; height:30px' name=brows value='brows message'>
			</form>";
		}
		else
		{
			echo"<h1>Guestbook</h1>";
			echo"<form action='' method=POST>
			<input type=submit style='width:120px; height:30px' name=users value='Users'>
			<input type=submit style='width:120px; height:30px' name=brows value='brows message'>
			</form>";
		}
		if(isset($_POST['addrecord']))
			{
				header("Location: gbadd.php");
			}
			if(isset($_POST['users']))
			{
				header("Location: users.php");
			}
			if(isset($_POST['brows']))
			{
				header("Location: test.php");
			}
		//отображение сообщений из бд
		$db = mysql_connect ("localhost","root","7766421");
		mysql_select_db ("test",$db);
		$qt=mysql_query('SELECT * FROM messages');
		while($row=mysql_fetch_array($qt))
		{
			if(strlen($row['message'])>70)
			{
				echo '<b>'.$row['title'].'</b><br>';
				echo '<p><i>'.cutstr($row['message'],40).'</b><form action=test.php method=POST><input type=submit name=test value=read></form></i></p><hr>';
			}
			else 
			{
				echo '<b>'.$row['title'].'</b><br>';
				echo '<p><i>'.$row['message'].'</i></p><hr>';
			}
		}
	}
	function cutStr($str, $lenght = 100, $end = '&nbsp;&hellip;', $charset = 'UTF-8', $token = '~') 
	{
		$str = strip_tags($str);
		if (mb_strlen($str, $charset) >= $lenght) 
		{
		$wrap = wordwrap($str, $lenght, $token);
		$str_cut = mb_substr($wrap, 0, mb_strpos($wrap, $token, 0, $charset), $charset); 	
		return $str_cut .= $end;
		}
		else 
		{
		return $str;	    
		}	
	}
//кнопка завершающая сессию 
echo "<html>
	<br>
	<form action='' method=POST>
	<input type=submit style='width:120px; height:30px' name=exit value='Quit'>
    </form><br>
	</html>";
	if(isset($_POST['exit']))
	{
	$_SESSION['page'] = "b";
    session_destroy();
    header("Location: auth.php");
    die();
	}
?>