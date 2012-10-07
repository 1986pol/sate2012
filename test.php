<?
$sdd_db_host='localhost';// ваш адрес где находится, хостится ваша база данных
$sdd_db_name='test';// Имя базы данных с которой вы хотите работать, так как их может быть множество
$sdd_db_user='root';// логин доступ к базе данных
$sdd_db_pass='7766421';// пароль доступа к базе данных
@mysql_connect($sdd_db_host,$sdd_db_user,$sdd_db_pass);// устанавливаем связь с сервером
@mysql_select_db($sdd_db_name);// переключаемся на нужную нам базу данных
$q=mysql_query('SELECT * FROM `messages`');// делаем выборку из таблицы
while($row=mysql_fetch_array($q))// берем результаты из каждой строки
	echo '<b>'.$row['title'].'</b><br><b>'.$row['message'].'</b><br>'.$row['nik'].'...'.$row['data'].'<hr>';
	
?>