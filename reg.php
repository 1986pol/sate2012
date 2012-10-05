<?
	echo "<html>
	<br><br><br><br><br><br><br>
	<form action='' method=POST>
	<center><table style=background-color:#ff6f4f >
	<tr>
	<th colspan='2'><h2>Registration</h2></th>
	</tr>
	<tr><td>E-mail:</td>
	<td><input type=text name=email size=30></td></tr>
	<tr><td>Password:</td>
	<td><input type=text name=password size=30></td></tr>
	</table><center>
	<input type=submit style='width:140px; height:30px' name=adduser value='Sign up'>
	</form>
	<form action='auth.php' method=POST>
	<input type=submit style='width:140px; height:30px' name=reg value='Quit'>
	</form>
	</html>";
	  if (empty($_POST['email']) or empty($_POST['password']))
    {
    // Если пусты, то мы не выводим ссылку
    echo "one of the fields is not filled";
    }
    else
    {
		$log = ($_POST['email']);
		$pas = ($_POST['password']);						
// Данные для mysql сервера
$dbhost = "localhost"; // Хост
$dbuser = "root"; // Имя пользователя
$dbpassword = "7766421"; // Пароль
$dbname = "test"; // Имя базы данных
// Подключаемся к mysql серверу
$link = mysql_connect($dbhost, $dbuser, $dbpassword);
// Выбираем нашу базу данных
mysql_select_db($dbname, $link);
// Добавляем запись в нашу таблицу customer
// т.е. делаем sql запрос
$query = "insert into users values('$log', '$pas')";
mysql_query($query, $link);
// Закрываем соединение
mysql_close($link);
}
?>