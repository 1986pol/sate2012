<?
	echo "<html>
	<br><br><br><br><br><br><br>
	<form action='' method=POST>
	<center><table style=background-color:#ff6f4f >
	<tr><th colspan='2'><h2>Registration</h2></th></tr>
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
			$time=date("d.m.Y H:i");
			$avatar = "avatar_orig/not.jpg";
			
			if ($Cpas != $pas)//проверяем правильность подтверждения пароля
			{
				echo"passwords do not match<br>";
			} 
			else
			{
				$db = mysql_connect ("localhost","root","7766421");
				mysql_select_db ("test",$db);
				$q = mysql_query("SELECT * FROM users WHERE login='$log'",$db); //извлекаем из базы все данные о пользователе с введенным логином
				$w = mysql_query("SELECT * FROM users WHERE email='$email'",$db);
				$myrow = mysql_fetch_array($q);
				$myr=mysql_fetch_array($w);
				if(empty($myrow['password']) && empty($myr['password']))
				{
					$result2 = mysql_query ("INSERT INTO users (login, email, password, data_reg, avatar, data_last, name, surname, role) VALUES('$log','$email','$pas', '$time', '$avatar', 1, 1, 1, 1)");
					echo "<p style='color:green;text-align:center'>registration is successful</p>";
				}
				else
				{
					echo "<p style='color:red;text-align:center'>a login or email already exists</p>";							
				}
			}					
		}
	}
?>