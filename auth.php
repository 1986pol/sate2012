<?
session_start();
	echo "<html>
	<br><br><br><br><br><br>
	<form action='' method=POST>
	<center><table style=background-color:grey >
	<tr>
	<th colspan='2'><h2>Authorization</h2></th>
	</tr>
	<tr><td>Login:</td>
	<td><input type=text name=login size=30></td></tr>
	<tr><td>Password:</td>
	<td><input type=password name=password size=30></td></tr>
	</table><center>
	<td><input type=submit style='width:140px; height:30px' name=enter value='Enter'></td>
	<td><input type=reset style='width:140px; height:30px' name=reset value='Reset'></td>
	</form>
	</html>";
	echo "<html>
	<br>
	<form action='reg.php' method=POST>
	<td><input type=submit style='width:140px; height:30px' name=reg value='Registration'></td>
    </form><br>
	</html>";
if(isset($_POST['enter']))
{
	if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);}} //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
		if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
		//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
	if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
		{
		 echo "You entered all the information, go back and fill in all fields!<br>";
		}
	   $db = mysql_connect ("localhost","root","7766421");
	   mysql_select_db ("test",$db);
	   $q = mysql_query("SELECT * FROM users WHERE login='$login'",$db); //извлекаем из базы все данные о пользователе с введенным логином
	   $myrow = mysql_fetch_array($q);
	   if(empty($myrow['password']))
		{
		//если пользователя с введенным логином не существует
		echo "Sorry, you entered an incorrect username or password.";
		}
		else 
		{
		//если существует, то сверяем пароли
		if ($myrow['password']==$password) 
		{	
			$log = $myrow['login'];
			$_SESSION['login'] = $myrow['login'];
			$_SESSION['page'] = "a";
			$_SESSION['role'] = $myrow['role'];
			$time=date("d.M.Y H:i");
			$db = mysql_connect ("localhost","root","7766421");
			mysql_select_db ("test",$db);
			$q = mysql_query("UPDATE users SET data_last='$time' WHERE login='$log'",$db);
			header("Location: index.php");
			die();
		}
		else 
		{
		//если пароли не сошлись
		echo "Sorry, you entered an incorrect username or password.";
		}
		}
	}
?>