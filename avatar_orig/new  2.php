�������� �� �������-�� ��������� ��� ������������ page.php! � ������� ������������ ���� ������������� �id�, ��� ����� ���� � ����� ��������� �������� ���������.  ��� ����� �������� ���: � ����� ������ ���������, �������� �� ������ � ������������, ����� �� ���� �� �� ��������� �����. ���� �� ����, �� ���������� ��� �������� ������ ������ � ������� ���������, ���� �� �����, �� ������� ����� ��� �������� ���������.
 
<?php
            //    ��� ��������� �������� �� �������. ������ � ��� �������� ������ ������������,    ���� �� ��������� �� �����. ����� ����� ��������� �� � ����� ������    ���������!!!
            session_start();
include ("bd.php");// ���� bd.php ������ ���� � ��� �� �����, ��� � ���    ���������, ���� ��� �� ���, �� ������ �������� ���� 
            if (isset($_GET['id'])) {$id =$_GET['id']; } //id "�������" ��������� 
            else
            { exit("�� ����� ��    �������� ��� ���������!");} //���� ��    ������� id, �� ������ ������
            if (!preg_match("|^[\d]+$|", $id))    {
            exit("<p>��������    ������ �������! ��������� URL</p>");//���� id �� �����, �� ������    ������
            }
if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //����    ���������� ����� � ������ � �������, �� ���������, ������������� �� ���
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))

               {
               //���� �� ������������� (����� �� �������    ����� ������������ �� ���� �� ������ ���������)

                exit("���� �� ��� �������� ��������    ������ ������������������ �������������!");
               }
            }
            else {
            //���������,    ��������������� �� ��������
            exit("���� �� ���    �������� �������� ������ ������������������ �������������!"); }
            $result = mysql_query("SELECT * FROM    users WHERE id='$id'",$db); 
            $myrow =    mysql_fetch_array($result);//��������� ��� ������    ������������ � ������ id
if (empty($myrow['login'])) {    exit("������������ �� ����������! �������� �� ��� ������.");} //���� ������ �� ����������
?>
            <html>
            <head>
            <title><?php echo $myrow['login'];    ?></title>
            </head>
            <body>

            <h2>������������ "<?php echo    $myrow['login']; ?>"</h2>
 
<?php
            print <<<HERE
            |<a href='page.php?id=$myrow2[id]'>��� ��������</a>|<a href='index.php'>������� ��������</a>|<a href='all_users.php'>������ �������������</a>|<a href='exit.php'>�����</a><br><br>
            HERE;
            //���� ������ ���� 
if ($myrow['login'] == $login) {
            //����    ��������� ����������� ���������, �� ���������� �������� ������ � �������    ������ ���������
print <<<HERE
<form action='update_user.php'    method='post'>
            ��� �����    <strong>$myrow[login]</strong>. �������� �����:<br>
            <input name='login' type='text'>
            <input type='submit' name='submit' value='��������'>
            </form>
            <br>
<form action='update_user.php'    method='post'>
            �������� ������:<br>
            <input name='password' type='password'>
            <input type='submit' name='submit' value='��������'>
            </form>
            <br>
<form action='update_user.php'    method='post' enctype='multipart/form-data'>
            ��� ������:<br>
            <img alt='������' src='$myrow[avatar]'><br>
            ����������� ������ ����    ������� jpg, gif ��� png. �������� ������:<br>
            <input type="FILE"    name="fupload">
            <input type='submit' name='submit' value='��������'>
            </form>
            <br>
<h2>������    ���������:</h2>
HERE;
$tmp = mysql_query("SELECT * FROM    messages WHERE poluchatel='$login' ORDER BY id DESC",$db); 
            $messages =    mysql_fetch_array($tmp);//��������� ���������    ������������, ��������� �� �������������� � �������� �������, �.�. �����    ����� ��������� ����� ������
if (!empty($messages['id'])) {
            do //�������    ��� ��������� � �����
              {
            $author = $messages['author'];
            $result4 = mysql_query("SELECT avatar,id    FROM users WHERE login='$author'",$db); //��������� ������ ������ 
            $myrow4 = mysql_fetch_array($result4);
if (!empty($myrow4['avatar']))    {//���� �������� ���, �� ������� ����������� (�����    ����� ������������ ��� ����� �������)
            $avatar = $myrow4['avatar'];
            }
            else {$avatar =    "avatars/net-avatara.jpg";}
     printf("
                 <table>
                 <tr>

                 <td><a href='page.php?id=%s'><img alt='������'    src='%s'></a></td>
              
                 <td>�����:    <a href='page.php?id=%s'>%s</a><br>
                  ����:    %s<br>
                                 ���������:<br>

                             %s<br>
                             <a href='drop_post.php?id=%s'>�������</a>

              
                 </td>  
                 </tr>
                 </table><br>
                 ",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
              //������� ���� ��������� 
              }
                 while($messages = mysql_fetch_array($tmp));
                    }
                                                                                          else    {
                                                                                          //���� ��������� �� �������
                                                                                          echo    "��������� ���";
                                                                                          }
                                                                                         

            }
else
            {
            //����    ��������� �����, �� ������� ������ �������� ������ � ����� ��� ��������    ������ ���������
print <<<HERE
            <img alt='������' src='$myrow[avatar]'><br>
            <form action='post.php' method='post'>
            <br>
            <h2>��������� ����    ���������:</h2>
            <textarea cols='43' rows='4'    name='text'></textarea><br>
            <input type='hidden' name='poluchatel'    value='$myrow[login]'>
            <input type='hidden' name='id'    value='$myrow[id]'>
            <input type='submit' name='submit' value='���������'>

            </form>
            HERE;
            }
?>
            </body>
            </html>

���� ������������ ������ �������� ���� ������ (�����, ������, ������), �� ������ ��� �� �������� page.php, ����� ��� ���������� ������ ������������ �� �������� update_user.php, ������� ����� ��������� �� � ����. ��� update_user.php:
<?php
          session_start();
          include ("bd.php");// ���� bd.php ������ ���� � ��� �� �����, ��� � ���    ���������, ���� ��� �� ���, �� �������� ���� 
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
            {
            //����    ���������� ����� � ������ � �������, �� ���������, ������������� �� ���
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))
               {
               //���� ��    �������������, �� ��������� ������
                exit("���� �� ��� �������� ��������    ������ ������������������ �������������!");
               }
            }
            else {
            //���������,    ��������������� �� ��������
            exit("���� �� ���    �������� �������� ������ ������������������ �������������!"); }
$old_login =    $_SESSION['login']; //������ ����� ���    �����������
            $id = $_SESSION['id'];//������������� ������������ ���� �����
            $ava =    "avatars/net-avatara.jpg";//�����������    ����������� ����� ������
////////////////////////
            ////////���������    ������
            ////////////////////////
if (isset($_POST['login']))//���� ���������� �����
                  {
            $login = $_POST['login'];
            $login = stripslashes($login); $login =    htmlspecialchars($login); $login = trim($login);//������� ��� ������ 
            if ($login == '') {    exit("�� �� ����� �����");} //����    ����� ������, �� ������������� 
if (strlen($login) < 3 or strlen($login)    > 15) {//���������    ���� 
            exit ("����� ������    �������� �� ����� ��� �� 3 �������� � �� ����� ��� �� 15."); //������������� ���������� ���������

            }
//    �������� �� ������������� ������������ � ����� �� �������
            $result = mysql_query("SELECT id FROM    users WHERE login='$login'",$db);
            $myrow = mysql_fetch_array($result);
            if (!empty($myrow['id'])) {
            exit ("��������,    �������� ���� ����� ��� ���������������. ������� ������ �����."); //������������� ���������� ���������

            }
$result4 = mysql_query("UPDATE users SET    login='$login' WHERE login='$old_login'",$db);//��������� � ���� ����� ������������ 

            if ($result4=='TRUE') {//���� ��������� �����, �� ��������� ��� ���������,    ������� ���������� ���
            mysql_query("UPDATE messages SET    author='$login' WHERE author='$old_login'",$db);
            $_SESSION['login'] = $login;//��������� ����� � ������ 
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$_SESSION['id']."'></head><body>��� ����� �������! ��    ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a    href='page.php?id=".$_SESSION['id']."'>�������    ����.</a></body></html>";}//����������    ������������ �����
      } 
////////////////////////
            ////////���������    ������
            ////////////////////////
else if    (isset($_POST['password'])) //���� ����������    ������
                  {
            $password = $_POST['password'];
            $password = stripslashes($password);$password    = htmlspecialchars($password);$password = trim($password);//������� ��� ������ 
            if ($password == '') {    exit("�� �� ����� ������");} //����    ������ �� ������, �� ������ ������
if (strlen($password) < 3    or strlen($password) > 15) {//�������� ��    ���������� ��������
            exit ("������ ������    �������� �� ����� ��� �� 3 �������� � �� ����� ��� �� 15."); //������������� ���������� ���������

            }
$password = md5($password);//������� ������
            $password = strrev($password);// ��� ���������� ������� ������
            $password = $password."b3p6f";
            //�����    �������� ��������� ����� �������� �� �����, ��������, ������    "b3p6f". ���� ���� ������ ����� ���������� ������� ������� � ����    �� ������� ���� �� md5,�� ���� ������ �������� �� ������. �� ������� �������    ������ �������, ����� � ������ ������ ��� � ��������.
            //���    ���� ���������� ��������� ����� ���� password � ����. ������������� ������    ����� ��������� ������� �������� �������.
 
$result4 = mysql_query("UPDATE users SET    password='$password' WHERE login='$old_login'",$db);//��������� ������ 

            if ($result4=='TRUE') {//���� �����, �� ��������� ��� � ������
            $_SESSION['password'] = $password;
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$_SESSION['id']."'></head><body>��� ������ �������! ��    ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a    href='page.php?id=".$_SESSION['id']."'>�������    ����.</a></body></html>";}//����������    ������� �� ��� ��������
                 } 
            ////////////////////////
            ////////���������    �������
            ////////////////////////
            else if    (isset($_FILES['fupload']['name'])) //������������    �� ����������
                  {
if (empty($_FILES['fupload']['name']))
            {
            //����    ���������� ������ (������������ �� �������� �����������),�� ����������� ���    ������� �������������� �������� � �������� "��� �������"
            $avatar =    "avatars/net-avatara.jpg"; //������    ���������� net-avatara.jpg ��� ����� � ����������
            $result7 = mysql_query("SELECT avatar    FROM users WHERE login='$old_login'",$db);//��������� ������� ������ 
            $myrow7 = mysql_fetch_array($result7);
            if ($myrow7['avatar'] == $ava)    {//���� ������ ��� �����������, �� �� �������    ���, ���� � �� ���� �������� �� ����.
            $ava = 1;
            }
            else {unlink    ($myrow7['avatar']);}//���� ������ ��� ����, ��    ������� ���, ����� �������� ��������
            }
else 
            {
            //�����    - ��������� ����������� ������������ ��� ����������
            $path_to_90_directory =    'avatars/';//�����, ���� ����� �����������    ��������� �������� � �� ������ �����
                
            if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name']))//�������� ������� ��������� �����������

                             {             
                                                            
                                           $filename    = $_FILES['fupload']['name'];
                                           $source    = $_FILES['fupload']['tmp_name'];        
                                           $target    = $path_to_90_directory . $filename;
                                           move_uploaded_file($source, $target);//�������� ��������� � ����� $path_to_90_directory 
                if(preg_match('/[.](GIF)|(gif)$/',    $filename)) {
                            $im    = imagecreatefromgif($path_to_90_directory.$filename) ; //���� �������� ��� � ������� gif, �� �������    ����������� � ���� �� �������. ���������� ��� ������������ ������
                            }
                            if(preg_match('/[.](PNG)|(png)$/', $filename)) {

                            $im =    imagecreatefrompng($path_to_90_directory.$filename) ;//����    �������� ��� � ������� png, �� ������� ����������� � ���� �� �������.    ���������� ��� ������������ ������
                            }
                            
                            if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',    $filename)) {
                                           $im =    imagecreatefromjpeg($path_to_90_directory.$filename); //���� �������� ��� � ������� jpg, �� ������� ����������� � ���� ��    �������. ���������� ��� ������������ ������
                            }
                            
            //��������    ����������� ����������� � ��� ����������� ������ ����� � ����� www.codenet.ru
//    �������� �������� 90x90
            //    dest - �������������� ����������� 
            //    w - ������ ����������� 
            //    ratio - ����������� ������������������ 
$w = 90;  // ����������    90x90. ����� ��������� � ������ ������.
//    ������ �������� ����������� �� ������ 
            //    ��������� ����� � ���������� ��� ������� 
            $w_src = imagesx($im); //��������� ������
            $h_src = imagesy($im); //��������� ������ �����������
         //    ������ ������ ���������� �������� 
                     // ����� ������ truecolor!, �����    ����� ����� 8-������ ��������� 
                     $dest = imagecreatetruecolor($w,$w); 
nbsp;        //    �������� ���������� ��������� �� x, ���� ���� �������������� 
                     if ($w_src>$h_src) 
                        imagecopyresampled($dest, $im, 0, 0,
                                         round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                                     0, $w, $w,    min($w_src,$h_src), min($w_src,$h_src)); 
            // �������� ���������� �������� ��    y, 
                     // ���� ���� ������������ (����    ����� ���� ���������) 
                     if ($w_src<$h_src) 
                        imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w,
                                      min($w_src,$h_src),    min($w_src,$h_src)); 
         //    ���������� �������� �������������� ��� ������� 
                     if ($w_src==$h_src) 
                     imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src); 
                                            
$date=time(); //��������� ����� � ��������� ������.
            imagejpeg($dest, $path_to_90_directory.$date.".jpg");//��������� ����������� ������� jpg � ������ �����,    ������ ����� ������� �����. �������, ����� � �������� �� ���� ����������    ����.
//������    ������ jpg? �� �������� ����� ���� ����� + ������������ ������������ gif    �����������, ������� ��������� ������������. �� ����� ������� ������ ���    �����������, ����� ����� ����� ��������� �����-�� ��������.
$avatar =    $path_to_90_directory.$date.".jpg";//������� � ���������� ���� �� �������.
$delfull = $path_to_90_directory.$filename; 
            unlink ($delfull);//������� �������� ������������ �����������, �� ���    ������ �� �����. ������� ���� - �������� ���������.
$result7 =    mysql_query("SELECT avatar FROM users WHERE    login='$old_login'",$db);//��������� ������� ������ ������������

            $myrow7 = mysql_fetch_array($result7);
if ($myrow7['avatar'] == $ava)    {//���� �� �����������, �� �� ������� ���, ���� �    ��� ���� �������� �� ����.
            $ava = 1;
            }
            else {unlink    ($myrow7['avatar']);}//���� ������ ��� ����, ��    ������� ���
 
}
            else 
                    {
                                          //�    ������ �������������� �������, ������ ��������������� ���������

                    exit ("������ ������ ���� �    ������� <strong>JPG,GIF ��� PNG</strong>");

                                          }
}
$result4 = mysql_query("UPDATE users SET    avatar='$avatar' WHERE login='$old_login'",$db);//��������� ������ � ���� 

            if ($result4=='TRUE') {//���� �����, �� ���������� �� ������ ���������
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$_SESSION['id']."'></head><body>���� �������� ��������! ��    ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a href='page.php?id=".$_SESSION['id']."'>�������    ����.</a></body></html>";}
      } 
            ?>

���� ������������ �� ���������� ������ ���� ������, � ����� �������� ���������, ��, ������ �����, ������� ������� ������. ��� ����� � ��� ���� ���� drop_post.php, ������� ������� �� ���� ��������� � ������ id.
<?php
          session_start();//��������� ������
          include ("bd.php");//������������ � ����
if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //����    ���������� ����� � ������ � �������, �� ���������, ������������� �� ���
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))
               {
               //������    ������������ �������. 
                exit("���� �� ��� �������� ��������    ������ ������������������ �������������!");
               }
            }
            else {
            //���������,    ��������������� �� ��������
            exit("���� �� ���    �������� �������� ������ ������������������ �������������!"); }
            $id2 = $_SESSION['id']; //�������� ������������� ����� ��������
 
if (isset($_GET['id'])) { $id    = $_GET['id'];}//�������� ����� GET ������    ������������� ���������, ������� ����� �������
$result = mysql_query("SELECT poluchatel    FROM messages WHERE id='$id'",$db); 
            $myrow =    mysql_fetch_array($result); //����� ��������,    ���� ��������� ����������
            //����    ����� GET ������ ������������ ����� ������ ����� ������������� � ���    ��������� ������� ���������, ������� ���������� �� ���.
if ($login ==    $myrow['poluchatel']) {//���� ���������    ���������� ������� ������������, �� ��������� ��� �������
$result = mysql_query ("DELETE FROM    messages WHERE id = '$id' LIMIT 1");//������� ��������� 
            if ($result == 'true') {//���� ������� - �������������� �� ���������    ������������
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$id2."'></head><body>���� ��������� �������! ��    ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a    href='page.php?id=".$id2."'>�������    ����.</a></body></html>";
            }
            else {//����    �� �������, �� ��������������, �� ������ ��������� � �������
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$id2."'></head><body>������! ���� ��������� ��    �������. �� ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a    href='page.php?id=".$id2."'>�������    ����.</a></body></html>"; }
}
            else {exit("�� ���������    ������� ���������, ������������ �� ���!");} //����    ��������� ���������� �� ����� ������������. ������, �� ��������� ������� ���,    ����� � �������� ������ �����-�� ������ �������������
            ?>

���� �� ������������ ����� �� �� ���� ���������, �������������, �������� ������ �� ������, �� � ���� ���� ����������� ��������� ���������. ���� post.php ��������� � ���� ���������:
<?php
          session_start(); //��������� ������. ����������� � ������ ��������
          include ("bd.php"); // ����������� � �����, ������� ���� ����, ���� � ���    ��� ���� ����������
if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //����    ���������� ����� � ������ � �������, �� ���������, ������������� �� ���
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))

               {
               //���� �����    ��� ������ �� ������������
                exit("���� �� ��� �������� ��������    ������ ������������������ �������������!");
               }
            }
            else {

            //���������,    ��������������� �� ��������
            exit("���� �� ���    �������� �������� ������ ������������������ �������������!"); }
if (isset($_POST['id'])) { $id    = $_POST['id'];}//�������� ������������� ��������    ����������
            if (isset($_POST['text'])) { $text =    $_POST['text'];}//�������� ����� ��������� 
            if (isset($_POST['poluchatel'])) {    $poluchatel = $_POST['poluchatel'];}//����� ���������� 
            $author = $_SESSION['login'];//����� ������ 
            $date = date("Y-m-d");//���� ���������� 
if (empty($author) or empty($text) or    empty($poluchatel) or empty($date)) {//���� �� ��� �����������    ������? ���� ���, �� �������������
            exit ("�� ����� �� ���    ����������, ��������� ����� � ��������� ��� ����");}
$text = stripslashes($text);//������� �������� �����
            $text =    htmlspecialchars($text);//��������������    ������������ � �� HTML �����������

            $result2 = mysql_query("INSERT INTO    messages (author, poluchatel, date, text) VALUES    ('$author','$poluchatel','$date','$text')",$db);//������� � ���� ��������� 
echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$id."'></head><body>���� ��������� ��������! ��    ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a    href='page.php?id=".$id."'>�������    ����.</a></body></html>";//��������������    ������������
            ?>

��������� �� ������� ���� ������������� ������� ���� ������� �������. all_users.php:
<?php
          //    ��� ��������� �������� �� �������. ������ � ��� �������� ������ ������������,    ���� �� ��������� �� �����. ����� ����� ��������� �� � ����� ������    ���������!!!
          session_start();
 include ("bd.php");// ���� bd.php ������ ���� � ��� �� �����, ��� � ���    ���������, ���� ��� �� ���, �� ������ �������� ���� 
 if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //����    ���������� ����� � ������ � �������, �� ���������, ������������� �� ���

            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))
               {
               //���� ������    ������������ �� �����
                exit("���� �� ��� �������� ��������    ������ ������������������ �������������!");
               }
            }
            else {
            //���������,    ��������������� �� ��������
            exit("���� �� ���    �������� �������� ������ ������������������ �������������!"); }
            ?>
            <html>
            <head>
            <title>������ �������������</title>
            </head>
            <body>
            <h2>������    �������������</h2>
  
 <?php
            //�������    ����
            print <<<HERE
            |<a    href='page.php?id=$_SESSION[id]'>��� ��������</a>|<a    href='index.php'>������� ��������</a>|<a    href='all_users.php'>������ �������������</a>|<a    href='exit.php'>�����</a><br><br>
            HERE;
 $result = mysql_query("SELECT login,id    FROM users ORDER BY login",$db); //��������� ����� � ������������� ������������� 
            $myrow = mysql_fetch_array($result);
            do
            {
            //������� �� � ����� 
            printf("<a    href='page.php?id=%s'>%s</a><br>",$myrow['id'],$myrow['login']);
            }
            while($myrow = mysql_fetch_array($result));
 ?>
            </body>
            </html>

�������� ��������� �� ��������� index.php ���� �� ������������. ������� �� ����� �print <<<HERE� ����� �����������.
//���    ������� ����� ������������ �������� ���, ��� ����������� ���� �����    �����������.
          //************************************************************************************
  
 print <<<HERE

            |<a    href='page.php?id=$_SESSION[id]'>��� ��������</a>|<a    href='index.php'>������� ��������</a>|<a    href='all_users.php'>������ �������������</a>|<a    href='exit.php'>�����</a><br><br>

� ������ ������� ��������� � ���� � ���, ��� ���� ������ ����� ������������ �����������. ���� �� ������������� ����� �������� � ���������� ����� ��������� � ����� �������������. �� ��� ����� ���� �����, ��� � ���������� ����� ������, ������� �� ���������� �������� ������������ ��������� ����. ������� �� ����� ������������ ��������� ������ � ��������� ������ ��� ����, ����� ������������ �����������������. ���� � �������� ����, ��� ���� ����� ������, �� ����� ������� ���, ��� ��� ����, ��� ����� ������ ���� � ��� �� ����������, ��� ������ �������, ������ ��� ������ ������ �������. � ���� � �� ������, �� ������ �����������, ����� ������������� ����� �����, ����� ������������ ����������������? � ������ �������� ������ ��� �����: ����� ��������� ����������� ������������� ���, ����� ������ ���� ������. ��� �����, ������ ������ ���������� ���������� ������ �� ���� ������.

������� ����� ������������ �����������, ��� �� ���������� ����� ������������. �� ���� ��� �������� ����������� ��� �� �������� ������ ��� ������� ���� ���, �-����, �� ������ ��� �� �������. �������������������, �� ������ �������� � ������� �������������� �����, ��� ����� �������.