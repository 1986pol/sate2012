<?php
session_start();
if ($_SESSION['page'] != "a")die(header("Location: auth.php"));
	echo 
	"<html>
	<form action='' method=POST enctype=multipart/form-data>
	<table style=background-color:grey>
	<tr><td><b>Enter login:  </b></td><td><input type=text  name=login size=30><br></td></tr>
	<tr><td><b>Enter the photo:  </b></td><td><input type=FILE  name=photo><br></td></tr>
	<td><input type=submit style='width:140px; height:30px' name=enter value='Enter'></td>
	</table>
    </form><br>
	</html>";
	//создаем аватар
	if (isset($_POST['enter']))
		{
			$photo=$_FILES['photo']['name'];
			var_dump($photo);
			$log=$_POST['login'];
		
		if (empty($photo))
			{
				$avatar = "avatar_orig/not.jpg";
				echo"ok";
			}
		else
			{
				$path_to_90_directory = "avatar/";
				echo"error";
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
				if ($w_src > $h_src)
					{
						imagecopyresampled($dest, $im, 0, 0,round((max($w_src,$h_src)-min($w_src,$h_src))/2),0, $w, $w,    min($w_src,$h_src), min($w_src,$h_src));
					}
				if    ($w_src<$h_src) 
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
				//unlink($delfull);
			}
		else
			{
				echo"an incorrect aspect ratio";
			}
	var_dump($avatar);
		$db = mysql_connect ("localhost","root","7766421");
		mysql_select_db ("test",$db);
		$q = mysql_query("UPDATE users SET avatar='$avatar' WHERE login='$log'",$db);
	}
//кнопка завершающая сессию и кнопка возвращающая на предыдущую страницу
	echo "<html>
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