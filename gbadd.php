<?
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));
	$log = $_SESSION['login'];
	$db = mysql_connect ("localhost","root","7766421");
	mysql_select_db ("test",$db);
	$q = mysql_query ("SELECT*FROM users WHERE login = '$log'",$db);
	$row = mysql_fetch_array($q);
	
	//форма для отправки сообщений
		echo "<html>
		<center><h2>Messages</h2>
		<form action=gbadd.php method=POST>
		<table>
		<tr><td>Topic:</td>
		<td><input type=text name=title size=20></td></tr>
		<tr><td>Messages:<br>
		<td><textarea name=msg rows=4 cols=40></textarea><br></td>
		</table>
		<input type=submit style='width:120px; height:30px' name=addok value='Add message'>
		<input type=reset style='width:120px; height:30px' name=reset value='Reset'>
		<input type=submit style='width:120px; height:30px' name=back value='Back'>
		</form>
		</center>
		</html>";
		if(isset($_POST['back']))
		{
		header("Location: index.php");
		}
		if(isset($_POST['addok']))
		{
			if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $_POST['title']))
			{
				echo "<p style='color:red;text-align:center'> An invalid character in the 'Topicа'</p>";
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
					echo "<p style='color:red;text-align:center'> An invalid character in the message</p>";
					} 
					else 
					{
						if (empty($_POST['msg']))
						{
							echo "<p style='color:red;text-align:center'>Field 'Messages' is empty</p>";
						}
						else
						{
							$nik = $log;
							$title = ($_POST['title']);
							$msg = ($_POST['msg']);
							$time = date("d.m.Y H:i");
							$q = mysql_query ("INSERT INTO messages (title, nik, message, data) VALUES('$title','$nik','$msg', '$time')");
							header("Location: index.php");
						}
					}
				}
			}
		}
	
		
?>