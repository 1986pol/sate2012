<?
session_start();
$login=$_SESSION['login'];
$db = mysql_connect ("localhost","root","7766421");
mysql_select_db ("test",$db);
	if($_SESSION['role']==2)
	{	
		echo"<br><h1> Your messages </h1><br>";
		$q=mysql_query("SELECT*FROM messages WHERE nik='$login'");
		while($row=mysql_fetch_array($q))
		{	
			$m=$row['message'];
			$t=$row['title'];
			$l=$row['nik'];
			$d=$row['data'];
			
			echo"<br><b>$l</b><br>$t....$d<br>$m<br><form action='' method=POST><input type=submit name=$t	 value=edit><hr>";
			if(isset($_POST["$t"]))
				{				
					 $_SESSION['mdata']=$d;
					 $_SESSION['tit']=$t;
					 $_SESSION['mes']=$m;
					 header("Location: edmes.php");
				}
		}
			echo"<br><h1> All messages</h1><br><hr>";
			$q = mysql_query("SELECT * FROM messages");
			while($row = mysql_fetch_array($q))
			{
				echo '<b>'.$row['title'].'</b><br><b>'.$row['message'].'</b><br>'.$row['nik'].'...'.$row['data'].'<hr>';
			}
	}
	else
	{
		if($_SESSION['role']==3)
		{
			$q=mysql_query("SELECT*FROM messages");
			while($row=mysql_fetch_array($q))
			{	
				$m=$row['message'];
				$t=$row['title'];
				$l=$row['nik'];
				$d=$row['data'];
				
				echo"<br><b>$l</b><br>$t....$d<br>$m<br><form action='' method=POST><input type=submit name=$t	 value=edit><hr>";
				if(isset($_POST["$t"]))
					{				
						 $_SESSION['mdata']=$d;
						 $_SESSION['tit']=$t;
						 $_SESSION['mes']=$m;
						 header("Location: edmes.php");
					}
			}
		}
		else
		{
			echo"<br><h1> All messages</h1><br><hr>";
			$q = mysql_query("SELECT * FROM messages");
			while($row = mysql_fetch_array($q))
			{
				echo '<b>'.$row['title'].'</b><br><b>'.$row['message'].'</b><br>'.$row['nik'].'...'.$row['data'].'<hr>';
			}
		}
		
	}
//кнопка возвращающая на предыдущую страницу
	echo "<html>
		<br>
		<form action='' method=POST>
		<input type=submit style='width:120px; height:30px' name=exit value='Quit'>
		<input type=submit style='width:120px; height:30px' name=back value='Back'>
		</form><br>
		</html>";
		if(isset($_POST['back']))
		{
		header("Location:index.php");
		}
		if(isset($_POST['exit']))
		{
		$_SESSION['page'] = "b";
		session_destroy();
		header("Location: auth.php");
		die();
		}
	
?>