<?
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));
//форма для отправки сообщений
	echo "<html>
	<center><h2>Guestbook</h2>
	<form action=gbadd.php method=POST>
	<table>
	<tr><td>Your name:</td>
	<td><input type=text name=username size=20></td></tr>
	<tr><td>Topic:</td>
	<td><input type=text name=title size=20></td></tr>
	<tr><td>Messages:<br>
	<td><textarea name=msg rows=4 cols=40></textarea><br></td>
	</table>
	<input type=submit name=addok value='Добавть запись'>
	<input type=reset name=reset value='Отмена'>
	</form>
	</center>
	</html>";
	if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $_POST['username']))
	{
		echo "<p style='color:red;text-align:center'> Entered an invalid character in the 'Your name'</p>";
	}
	else
	{
		if (empty($_POST['username']))
		{
		echo "<p style='color:red;text-align:center'>Field 'Your name' is empty</p>";
		}
		else
		{	
			if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $_POST['title']))
			{
				echo "<p style='color:red;text-align:center'> Entered an invalid character in the 'Topicа'</p>";
			}
			else
			{
				if (empty($_POST['title']))
				{
				echo "<p style='color:red;text-align:center'>Field 'Topic' is empty</p>";
				}
				else
				{
					if(preg_match("/<|>|#|\[|\]|\*/", $_POST['msg'])) 
					{
					echo "<p style='color:red;text-align:center'> Entered an invalid character in the message</p>";
					} 
					else 
					{
					if (empty($_POST['msg']))
					{
					echo "<p style='color:red;text-align:center'>Field 'Messages' is empty</p>";
					}
					else
					{
					$nik=($_POST['username']);
					$title=($_POST['title']);
					$msg=($_POST['msg']);
					$time=date("d.m.Y H:i");
					var_dump($msg);
					// Данные для mysql сервера
					$dbhost = "localhost"; 
					$dbuser = "root"; 
					$dbpassword = "7766421"; 
					$dbname = "test"; 
					// Подключаемся к mysql серверу
					$link = mysql_connect($dbhost, $dbuser, $dbpassword);
					// Выбираем нашу базу данных
					mysql_select_db($dbname, $link);
					// Добавляем запись в нашу таблицу 
					$query = "insert into messages values('$title', '$nik', '$msg', '$time')";
					mysql_query($query, $link);
					// Закрываем соединение
					mysql_close($link);
					header("Location: index.php");
					}
					}
				}
			}
		}
	}
?>