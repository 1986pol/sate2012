<?
$sdd_db_host='localhost';// ��� ����� ��� ���������, �������� ���� ���� ������
$sdd_db_name='test';// ��� ���� ������ � ������� �� ������ ��������, ��� ��� �� ����� ���� ���������
$sdd_db_user='root';// ����� ������ � ���� ������
$sdd_db_pass='7766421';// ������ ������� � ���� ������
@mysql_connect($sdd_db_host,$sdd_db_user,$sdd_db_pass);// ������������� ����� � ��������
@mysql_select_db($sdd_db_name);// ������������� �� ������ ��� ���� ������
$q=mysql_query('SELECT * FROM `messages`');// ������ ������� �� �������
while($row=mysql_fetch_array($q))// ����� ���������� �� ������ ������
echo '<b>'.$row['title'].'</b><br><b>'.$row['message'].'</b><br>'.$row['nik'].'...'.$row['data'].'<hr>';
//������ ������������ �� ���������� ��������
echo "<html>
	<br>
	<form action='' method=POST>
	<input type=submit style='width:120px; height:30px' name=back value='Back'>
    </form><br>
	</html>";
	if(isset($_POST['back']))
	{
    header("Location: index.php");
	}
	
?>