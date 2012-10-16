<?php
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));
	$in = $_SESSION['log'];
	$log = $_SESSION['login'];
	$db = mysql_connect ("localhost","root","7766421");
	mysql_select_db ("test",$db);
	$q = mysql_query ("SELECT*FROM users WHERE login = '$in'",$db);
	$row = mysql_fetch_array($q);
	$email=$row['email'];
	if($in==$log or $_SESSION['role']==3)
	{
		echo
			"<html>
				<table style=background-color:grey >
					<br><form action='' method=POST enctype=multipart/form-data>
					<td><br><img src=".$row['avatar']."></td>
					<tr><td>Your avatar:
					</td><td><input type=FILE  name=photo>
					</td><td><input type=submit style='width:100px; height:30px' name=enter value='Change'></td></tr>
					<br><form action='' method=POST>
					<tr><td>Your login: ".$row['login']."
					</td><td><input type=text name=log size=30>
					</td><td><input type=submit style='width:100px; height:30px' name=Clogin value='Change'></td></tr>
					<br><form action='' method=POST>
					<tr><td>Your email: ".$row['email']."
					</td><td><input type=text name=em size=30>
					</td><td><input type=submit style='width:100px; height:30px' name=Cemail value='Change'></td></tr>
					<br><form action='' method=POST>
					<tr><td>Your name: ".$row['name']."
					</td><td><input type=text name=name size=30>
					</td><td><input type=submit style='width:100px; height:30px' name=Cname value='Change'></td></tr>
					<br><form action='' method=POST>
					<tr><td>Your surname: ".$row['surname']."
					</td><td><input type=text name=surname size=30>
					</td><td><input type=submit style='width:100px; height:30px' name=Csurname value='Change'></td></tr>
					<tr><td>Your password: ".$row['password']."
					</td><td><input type=text name=password size=30>
					</td><td><input type=submit style='width:100px; height:30px' name=Cpassword value='Change'></td></tr>
					</td><td><input type=submit style='width:120px; height:30px' name=delete value='DELETE USER'></td></tr>
				</table>
			</html>";
		if($_SESSION['role']==3)
		{
			echo
				"<html>
					<table style=background-color:grey >
						<tr><td>Your role: ".$row['role']."
						</td><td><input type=text name=role size=30>
						</td><td><input type=submit style='width:100px; height:30px' name=Crole value='Change'></td></tr>
					</table>
				</html>";
		}
// меняем аватарку
		if (isset($_POST['enter']))
		{
			$photo=$_FILES['photo']['name'];		
			if (empty($photo))
			{
				$avatar = "avatar_orig/not.jpg";
			}
			else
			{
				$path_to_90_directory = "avatar/";
			}
			if(preg_match('/[.](jpg)|(JPG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['photo']['name']))
			{
				$filename = $_FILES['photo']['name'];
				$source = $_FILES['photo']['tmp_name'];
				$target = $path_to_90_directory.$filename;
				move_uploaded_file($source, $target);
				if(preg_match('/[.](gif)|(GIF)$/',$filename))
					{
						$im = imagecreatefromgif($path_to_90_directory.$filename);
					}
					if(preg_match('/[.](png)|(PNG)$/',$filename))
					{
						$im = imagecreatefrompng($path_to_90_directory.$filename);
					}
					if(preg_match('/[.](jpg)|(JPG)|(jpeg)|(JPEG)$/',$filename))
						{
							$im = imagecreatefromjpeg($path_to_90_directory.$filename);
						}
						$w = 150;
						$w_src = imagesx($im);
						$h_src = imagesx($im);
						$dest = imagecreatetruecolor($w,$w);
						if($w_src > $h_src)
						{
							imagecopyresampled($dest, $im, 0, 0,round((max($w_src,$h_src)-min($w_src,$h_src))/2),0, $w, $w,    min($w_src,$h_src), min($w_src,$h_src));
						}
						if($w_src<$h_src) 
						{
							 imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, min($w_src,$h_src), min($w_src,$h_src));  
						}
						if($w_src==$h_src)
							{
								imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src);
							}
						$data=time();
						imagejpeg($dest, $path_to_90_directory.$data.".jpg");
						$avatar = $path_to_90_directory.$data.".jpg";
						$delfull =$path_to_90_directory.$filename;
						unlink($delfull);
					}
				else
					{
						echo"an incorrect aspect ratio";
					}
				$db = mysql_connect ("localhost","root","7766421");
				mysql_select_db ("test",$db);
				$q = mysql_query("UPDATE users SET avatar='$avatar' WHERE login='$in'",$db);
		}
//менняем логн
		if(isset($_POST['Clogin']))
		{
			$login=$_POST['log'];
			if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $login))
			{
				echo "<p style='color:red'> An invalid character in the 'Login'</p>";
			}
			else
			{
				if (empty($login))
				{
					echo "<p style='color:red;text-align:center'>Field 'Login' is empty</p>";
				}
				else
				{
					$q = mysql_query ("SELECT*FROM users WHERE login = '$login'",$db);
					$row = mysql_fetch_array($q);
					if (!empty($row['data_reg']))
					{
						echo"username you entered is already registered";
					}
					else
					{
						$q = mysql_query("UPDATE users SET    login='$login' WHERE login='$in'",$db);
						$q = mysql_query("UPDATE messages SET    nik='$login' WHERE nik='$in'",$db);
						$_SESSION['log']=$login;
						$_SESSION['login']=$login;
					}
				}
			}	
		}
//меняем email
		if(isset($_POST['Cemail']))
		{
			$email=$_POST['em'];
			if(preg_match("/<|>|#|\[|\]|\*/", $email))
			{
				echo "<p style='color:red'> An invalid character in the 'Email'</p>";
			}
			else
			{
				if (empty($email))
				{
					echo "<p style='color:red'>Field 'email' is empty</p>";
				}
				else
				{
					$q = mysql_query ("SELECT*FROM users WHERE email = '$email'",$db);
					$row = mysql_fetch_array($q);
					if (!empty($row['data_reg']))
					{
						echo"email you entered is already registered";
					}
					else
					{
						$q = mysql_query("UPDATE users SET    email='$email' WHERE login='$in'",$db);
					}
				}
			}	
		}
//менняем имя
		if(isset($_POST['Cname']))
		{
			$Cname=$_POST['name'];
			if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $Cname))
			{
					echo "<p style='color:red'> An invalid character in the 'name'</p>";
			}
			else
			{
				if (empty($Cname))
				{
					echo "<p style='color:red'>Field 'name' is empty</p>";
				}
				else
				{
					$q = mysql_query("UPDATE users SET    name='$Cname' WHERE login='$in'",$db);
				}
			}	
		}
//меняем фамилию
		if(isset($_POST['Csurname']))
		{
			$Csurname=$_POST['surname'];
			if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $Csurname))
			{
					echo "<p style='color:red'> An invalid character in the 'surname'</p>";
			}
			else
			{
				if (empty($Csurname))
				{
					echo "<p style='color:red'>Field 'surname' is empty</p>";
				}
				else
				{
					$q = mysql_query("UPDATE users SET    surname='$Csurname' WHERE login='$in'",$db);
				}
			}	
		}
//меняем пароль
		if(isset($_POST['Cpassword']))
		{
			$Cpassword=$_POST['password'];
			if(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $Cpassword))
			{
					echo "<p style='color:red'> An invalid character in the 'password'</p>";
			}
			else
			{
				if (empty($Cpassword))
				{
					echo "<p style='color:red'>Field 'password' is empty</p>";
				}
				else
				{
					$q = mysql_query("UPDATE users SET    password='$Cpassword' WHERE login='$in'",$db);
				}
			}	
		}
				//удалене пользователя
		if(isset($_POST['delete']))
		{
			$q = mysql_query("DELETE FROM users WHERE login='$in'",$db);
			$_SESSION['page'] = "b";
			session_destroy();
			header("Location: auth.php");
			die();
		}
		if(isset($_POST['Crole']))
		{			
			$Crole=$_POST['role'];
			$Crole = stripslashes($Crole); $Crole = htmlspecialchars($Crole); $Crole = trim($Crole);
			if($Crole=='')
			{
				echo"You have not entered role";
			}
			else
			{
				$q = mysql_query("UPDATE users SET role='$Crole' WHERE login='$in'",$db);
			}
		}
	}
	else
	{
		$q = mysql_query ("SELECT*FROM users WHERE login = '$in'",$db);
		$row1 = mysql_fetch_array($q);
		echo
			"<html>
				<table> 
					<td><br><img src=".$row1['avatar']."></td></tr>
					<tr><td>User login:<b> ".$row1['login']."</b></td></tr>
					<tr><td>User email:<b> ".$row1['email']."</b></td></tr>
					<tr><td>User surname:<b> ".$row1['surname']."</b></td></tr>
					<tr><td>User name:<b> ".$row1['name']."</b></td></tr>
					<tr><td>User last authorization:<b> ".$row1['data_last']."</b></td></tr>
				</table>
			</html>";
	}
//кнопка завершающая сессию и кнопка возвращающая на предыдущую страницу
echo 
	"<html>
		<br>
		<form action='' method=POST>
			<input type=submit style='width:120px; height:30px' name=exit value='Quit'>
			<input type=submit style='width:120px; height:30px' name=back value='Back'>
		</form><br>
	</html>";
	if(isset($_POST['back']))
	{
		header("Location: users.php");
	}
	if(isset($_POST['exit']))
	{
		$_SESSION['page'] = "b";
		session_destroy();
		header("Location: auth.php");
		die();
	}
?>