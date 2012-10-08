<?
	echo "<html>
	<br><br><br><br><br><br><br>
	<form action='' method=POST>
	<center><table style=background-color:#ff6f4f >
	<tr>
	<th colspan='2'><h2>Registration</h2></th>
	</tr>
	<tr><td>Login:</td>
	<td><input type=text name=login size=30></td></tr>
	<tr><td>Password:</td>
	<td><input type=text name=password size=30></td></tr>
	<tr><td>Confirm password:</td>
	<td><input type=text name=Cpassword size=30></td></tr>
	<tr><td>E-mail:</td>
	<td><input type=text name=email size=30></td></tr>
	</table><center>
	<input type=submit style='width:140px; height:30px' name=adduser value='Sign up'>
	</form>
	<form action='auth.php' method=POST>
	<input type=submit style='width:140px; height:30px' name=reg value='Quit'>
	</form>
	</html>";
	if (empty($_POST['email']) or empty($_POST['password']) or empty($_POST['Cpassword']) or empty($_POST['login']))
	{
	echo "one of the fields is not filled";// Если пусты, то мы не выводим ссылку
	}
	else
	{
		if(preg_match("/<|>|#|\[|\]|\*/", $_POST['email']) or preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $_POST['login'])or preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $_POST['password']) or preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $_POST['Cpassword']))
		{
		echo "<p style='color:red;text-align:center'>An invalid character</p>";//отсеваем ненужные символы
		}
		else
		{
			$email = ($_POST['email']);
			$pas = ($_POST['password']);
			$Cpas = ($_POST['Cpassword']);
			$log =($_POST['login']);
			if ($Cpas != $pas)//проверяем правильность подтверждения пароля
			{
			echo"passwords do not match<br>";
			} 
			else
			{
				$dbhost = "localhost"; // Хост
				$dbuser = "root"; // Имя пользователя
				$dbpassword = "7766421"; // Пароль
				$dbname = "test"; // Имя базы данных
				$link = mysql_connect($dbhost, $dbuser, $dbpassword);// Подключаемся к mysql серверу
				mysql_select_db($dbname, $link);// Выбираем нашу базу данных
				$q=mysql_query('SELECT*FROM `users`');
				while($row=mysql_fetch_array($q))
				{
					if($log != $row['login'])
					{
					mysql_select_db($dbname, $link);
					$query = "insert into users values('$log', '$email', '$pas')";// Добавляем запись в нашу таблицу customer
					mysql_query($query, $link);
					mysql_close($link);// Закрываем соединение
					echo "ok";
					}
					else
					{
					echo "error";
					}
				}
			}
		}
	}
?>