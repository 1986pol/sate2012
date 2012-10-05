<?php 
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));

//выводим количество пользователей
	$db = mysql_connect ("localhost","root","7766421");
    mysql_select_db ("test",$db);
	$q = mysql_query("SELECT COUNT(*) FROM users");
	$myrow = mysql_fetch_array($q);
	$total = $myrow[0];
	echo "Total Users: $total";
//кнопка для созданя новых сообщенй
	echo"<h1>Guestbook</h1>";
	echo"<form action=gbadd.php method=POST>
	<input type=submit name=addrecord value='Add record'>
	</form>";
//отображение сообщений из бд
$sdd_db_host='localhost';// ваш адрес где находится, хостится ваша база данных
$sdd_db_name='test';// Имя базы данных с которой вы хотите работать, так как их может быть множество
$sdd_db_user='root';// логин доступ к базе данных
$sdd_db_pass='7766421';// пароль доступа к базе данных
@mysql_connect($sdd_db_host,$sdd_db_user,$sdd_db_pass);// устанавливаем связь с сервером
@mysql_select_db($sdd_db_name);// переключаемся на нужную нам базу данных
$q=mysql_query('SELECT * FROM `messages`');// делаем выборку из таблицы
while($row=mysql_fetch_array($q))// берем результаты из каждой строки
{
//$r=($row['message']);
//var_dump($r);
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
echo "<html>
	<br>
	<form action='' method=POST>
	<td><input type=submit style='width:140px; height:30px' name=exit value='Quit'></td>
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