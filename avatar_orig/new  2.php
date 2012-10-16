Создадим же наконец-то страничку для пользователя page.php! У каждого пользователя есть идентификатор “id”, вот через него и будем указывать «хозяина» странички.  Она будет устроена так: в самом начале проверяем, запущена ли сессия у пользователя, затем на свою ли он страничку зашел. Если на свою, то предлагаем ему изменить личные данные и выводим сообщения, если на чужую, то выводим форму для отправки сообщений.
 
<?php
            //    вся процедура работает на сессиях. Именно в ней хранятся данные пользователя,    пока он находится на сайте. Очень важно запустить их в самом начале    странички!!!
            session_start();
include ("bd.php");// файл bd.php должен быть в той же папке, что и все    остальные, если это не так, то просто измените путь 
            if (isset($_GET['id'])) {$id =$_GET['id']; } //id "хозяина" странички 
            else
            { exit("Вы зашли на    страницу без параметра!");} //если не    указали id, то выдаем ошибку
            if (!preg_match("|^[\d]+$|", $id))    {
            exit("<p>Неверный    формат запроса! Проверьте URL</p>");//если id не число, то выдаем    ошибку
            }
if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //если    существует логин и пароль в сессиях, то проверяем, действительны ли они
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))

               {
               //Если не действительны (может мы удалили    этого пользователя из базы за плохое поведение)

                exit("Вход на эту страницу разрешен    только зарегистрированным пользователям!");
               }
            }
            else {
            //Проверяем,    зарегистрирован ли вошедший
            exit("Вход на эту    страницу разрешен только зарегистрированным пользователям!"); }
            $result = mysql_query("SELECT * FROM    users WHERE id='$id'",$db); 
            $myrow =    mysql_fetch_array($result);//Извлекаем все данные    пользователя с данным id
if (empty($myrow['login'])) {    exit("Пользователя не существует! Возможно он был удален.");} //если такого не существует
?>
            <html>
            <head>
            <title><?php echo $myrow['login'];    ?></title>
            </head>
            <body>

            <h2>Пользователь "<?php echo    $myrow['login']; ?>"</h2>
 
<?php
            print <<<HERE
            |<a href='page.php?id=$myrow2[id]'>Моя страница</a>|<a href='index.php'>Главная страница</a>|<a href='all_users.php'>Список пользователей</a>|<a href='exit.php'>Выход</a><br><br>
            HERE;
            //выше вывели меню 
if ($myrow['login'] == $login) {
            //Если    страничка принадлежит вошедшему, то предлагаем изменить данные и выводим    личные сообщения
print <<<HERE
<form action='update_user.php'    method='post'>
            Ваш логин    <strong>$myrow[login]</strong>. Изменить логин:<br>
            <input name='login' type='text'>
            <input type='submit' name='submit' value='изменить'>
            </form>
            <br>
<form action='update_user.php'    method='post'>
            Изменить пароль:<br>
            <input name='password' type='password'>
            <input type='submit' name='submit' value='изменить'>
            </form>
            <br>
<form action='update_user.php'    method='post' enctype='multipart/form-data'>
            Ваш аватар:<br>
            <img alt='аватар' src='$myrow[avatar]'><br>
            Изображение должно быть    формата jpg, gif или png. Изменить аватар:<br>
            <input type="FILE"    name="fupload">
            <input type='submit' name='submit' value='изменить'>
            </form>
            <br>
<h2>Личные    сообщения:</h2>
HERE;
$tmp = mysql_query("SELECT * FROM    messages WHERE poluchatel='$login' ORDER BY id DESC",$db); 
            $messages =    mysql_fetch_array($tmp);//извлекаем сообщения    пользователя, сортируем по идентификатору в обратном порядке, т.е. самые    новые сообщения будут вверху
if (!empty($messages['id'])) {
            do //выводим    все сообщения в цикле
              {
            $author = $messages['author'];
            $result4 = mysql_query("SELECT avatar,id    FROM users WHERE login='$author'",$db); //извлекаем аватар автора 
            $myrow4 = mysql_fetch_array($result4);
if (!empty($myrow4['avatar']))    {//если такового нет, то выводим стандартный (может    этого пользователя уже давно удалили)
            $avatar = $myrow4['avatar'];
            }
            else {$avatar =    "avatars/net-avatara.jpg";}
     printf("
                 <table>
                 <tr>

                 <td><a href='page.php?id=%s'><img alt='аватар'    src='%s'></a></td>
              
                 <td>Автор:    <a href='page.php?id=%s'>%s</a><br>
                  Дата:    %s<br>
                                 Сообщение:<br>

                             %s<br>
                             <a href='drop_post.php?id=%s'>Удалить</a>

              
                 </td>  
                 </tr>
                 </table><br>
                 ",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
              //выводим само сообщение 
              }
                 while($messages = mysql_fetch_array($tmp));
                    }
                                                                                          else    {
                                                                                          //если сообщений не найдено
                                                                                          echo    "Сообщений нет";
                                                                                          }
                                                                                         

            }
else
            {
            //если    страничка чужая, то выводим только некторые данные и форму для отправки    личных сообщений
print <<<HERE
            <img alt='аватар' src='$myrow[avatar]'><br>
            <form action='post.php' method='post'>
            <br>
            <h2>Отправить Ваше    сообщение:</h2>
            <textarea cols='43' rows='4'    name='text'></textarea><br>
            <input type='hidden' name='poluchatel'    value='$myrow[login]'>
            <input type='hidden' name='id'    value='$myrow[id]'>
            <input type='submit' name='submit' value='Отправить'>

            </form>
            HERE;
            }
?>
            </body>
            </html>

Если пользователь желает изменить свои данные (логин, пароль, аватар), то делает это на странице page.php, затем уже измененные данные отправляются на страницу update_user.php, которая будет обновлять их в базе. Код update_user.php:
<?php
          session_start();
          include ("bd.php");// файл bd.php должен быть в той же папке, что и все    остальные, если это не так, то измените путь 
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
            {
            //если    существует логин и пароль в сессиях, то проверяем, действительны ли они
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))
               {
               //Если не    действительны, то закрываем доступ
                exit("Вход на эту страницу разрешен    только зарегистрированным пользователям!");
               }
            }
            else {
            //Проверяем,    зарегистрирован ли вошедший
            exit("Вход на эту    страницу разрешен только зарегистрированным пользователям!"); }
$old_login =    $_SESSION['login']; //Старый логин нам    пригодиться
            $id = $_SESSION['id'];//идентификатор пользователя тоже нужен
            $ava =    "avatars/net-avatara.jpg";//стандартное    изображение будет кстати
////////////////////////
            ////////ИЗМЕНЕНИЕ    ЛОГИНА
            ////////////////////////
if (isset($_POST['login']))//Если существует логин
                  {
            $login = $_POST['login'];
            $login = stripslashes($login); $login =    htmlspecialchars($login); $login = trim($login);//удаляем все лишнее 
            if ($login == '') {    exit("Вы не ввели логин");} //Если    логин пустой, то останавливаем 
if (strlen($login) < 3 or strlen($login)    > 15) {//проверяем    дину 
            exit ("Логин должен    состоять не менее чем из 3 символов и не более чем из 15."); //останавливаем выполнение сценариев

            }
//    проверка на существование пользователя с таким же логином
            $result = mysql_query("SELECT id FROM    users WHERE login='$login'",$db);
            $myrow = mysql_fetch_array($result);
            if (!empty($myrow['id'])) {
            exit ("Извините,    введённый вами логин уже зарегистрирован. Введите другой логин."); //останавливаем выполнение сценариев

            }
$result4 = mysql_query("UPDATE users SET    login='$login' WHERE login='$old_login'",$db);//обновляем в базе логин пользователя 

            if ($result4=='TRUE') {//если выполнено верно, то обновляем все сообщения,    которые отправлены ему
            mysql_query("UPDATE messages SET    author='$login' WHERE author='$old_login'",$db);
            $_SESSION['login'] = $login;//Обновляем логин в сессии 
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$_SESSION['id']."'></head><body>Ваш логин изменен! Вы    будете перемещены через 5 сек. Если не хотите ждать, то <a    href='page.php?id=".$_SESSION['id']."'>нажмите    сюда.</a></body></html>";}//отправляем    пользователя назад
      } 
////////////////////////
            ////////ИЗМЕНЕНИЕ    ПАРОЛЯ
            ////////////////////////
else if    (isset($_POST['password'])) //Если существует    пароль
                  {
            $password = $_POST['password'];
            $password = stripslashes($password);$password    = htmlspecialchars($password);$password = trim($password);//удаляем все лишнее 
            if ($password == '') {    exit("Вы не ввели пароль");} //если    пароль не введен, то выдаем ошибку
if (strlen($password) < 3    or strlen($password) > 15) {//проверка на    количество символов
            exit ("Пароль должен    состоять не менее чем из 3 символов и не более чем из 15."); //останавливаем выполнение сценариев

            }
$password = md5($password);//шифруем пароль
            $password = strrev($password);// для надежности добавим реверс
            $password = $password."b3p6f";
            //можно    добавить несколько своих символов по вкусу, например, вписав    "b3p6f". Если этот пароль будут взламывать методом подбора у себя    на сервере этой же md5,то явно ничего хорошего не выйдет. Но советую ставить    другие символы, можно в начале строки или в середине.
            //При    этом необходимо увеличить длину поля password в базе. Зашифрованный пароль    может получится гораздо большего размера.
 
$result4 = mysql_query("UPDATE users SET    password='$password' WHERE login='$old_login'",$db);//обновляем пароль 

            if ($result4=='TRUE') {//если верно, то обновляем его в сессии
            $_SESSION['password'] = $password;
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$_SESSION['id']."'></head><body>Ваш пароль изменен! Вы    будете перемещены через 5 сек. Если не хотите ждать, то <a    href='page.php?id=".$_SESSION['id']."'>нажмите    сюда.</a></body></html>";}//отправляем    обратно на его страницу
                 } 
            ////////////////////////
            ////////ИЗМЕНЕНИЕ    АВАТАРЫ
            ////////////////////////
            else if    (isset($_FILES['fupload']['name'])) //отправлялась    ли переменная
                  {
if (empty($_FILES['fupload']['name']))
            {
            //если    переменная пустая (пользователь не отправил изображение),то присваиваем ему    заранее приготовленную картинку с надписью "нет аватара"
            $avatar =    "avatars/net-avatara.jpg"; //можете    нарисовать net-avatara.jpg или взять в исходниках
            $result7 = mysql_query("SELECT avatar    FROM users WHERE login='$old_login'",$db);//извлекаем текущий аватар 
            $myrow7 = mysql_fetch_array($result7);
            if ($myrow7['avatar'] == $ava)    {//если аватар был стандартный, то не удаляем    его, ведь у на одна картинка на всех.
            $ava = 1;
            }
            else {unlink    ($myrow7['avatar']);}//если аватар был свой, то    удаляем его, затем поставим стандарт
            }
else 
            {
            //иначе    - загружаем изображение пользователя для обновления
            $path_to_90_directory =    'avatars/';//папка, куда будет загружаться    начальная картинка и ее сжатая копия
                
            if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name']))//проверка формата исходного изображения

                             {             
                                                            
                                           $filename    = $_FILES['fupload']['name'];
                                           $source    = $_FILES['fupload']['tmp_name'];        
                                           $target    = $path_to_90_directory . $filename;
                                           move_uploaded_file($source, $target);//загрузка оригинала в папку $path_to_90_directory 
                if(preg_match('/[.](GIF)|(gif)$/',    $filename)) {
                            $im    = imagecreatefromgif($path_to_90_directory.$filename) ; //если оригинал был в формате gif, то создаем    изображение в этом же формате. Необходимо для последующего сжатия
                            }
                            if(preg_match('/[.](PNG)|(png)$/', $filename)) {

                            $im =    imagecreatefrompng($path_to_90_directory.$filename) ;//если    оригинал был в формате png, то создаем изображение в этом же формате.    Необходимо для последующего сжатия
                            }
                            
                            if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',    $filename)) {
                                           $im =    imagecreatefromjpeg($path_to_90_directory.$filename); //если оригинал был в формате jpg, то создаем изображение в этом же    формате. Необходимо для последующего сжатия
                            }
                            
            //СОЗДАНИЕ    КВАДРАТНОГО ИЗОБРАЖЕНИЯ И ЕГО ПОСЛЕДУЮЩЕЕ СЖАТИЕ ВЗЯТО С САЙТА www.codenet.ru
//    Создание квадрата 90x90
            //    dest - результирующее изображение 
            //    w - ширина изображения 
            //    ratio - коэффициент пропорциональности 
$w = 90;  // квадратная    90x90. Можно поставить и другой размер.
//    создаём исходное изображение на основе 
            //    исходного файла и определяем его размеры 
            $w_src = imagesx($im); //вычисляем ширину
            $h_src = imagesy($im); //вычисляем высоту изображения
         //    создаём пустую квадратную картинку 
                     // важно именно truecolor!, иначе    будем иметь 8-битный результат 
                     $dest = imagecreatetruecolor($w,$w); 
nbsp;        //    вырезаем квадратную серединку по x, если фото горизонтальное 
                     if ($w_src>$h_src) 
                        imagecopyresampled($dest, $im, 0, 0,
                                         round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                                     0, $w, $w,    min($w_src,$h_src), min($w_src,$h_src)); 
            // вырезаем квадратную верхушку по    y, 
                     // если фото вертикальное (хотя    можно тоже серединку) 
                     if ($w_src<$h_src) 
                        imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w,
                                      min($w_src,$h_src),    min($w_src,$h_src)); 
         //    квадратная картинка масштабируется без вырезок 
                     if ($w_src==$h_src) 
                     imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src); 
                                            
$date=time(); //вычисляем время в настоящий момент.
            imagejpeg($dest, $path_to_90_directory.$date.".jpg");//сохраняем изображение формата jpg в нужную папку,    именем будет текущее время. Сделано, чтобы у аватаров не было одинаковых    имен.
//почему    именно jpg? Он занимает очень мало места + уничтожается анимирование gif    изображения, которое отвлекает пользователя. Не очень приятно читать его    комментарий, когда краем глаза замечаешь какое-то движение.
$avatar =    $path_to_90_directory.$date.".jpg";//заносим в переменную путь до аватара.
$delfull = $path_to_90_directory.$filename; 
            unlink ($delfull);//удаляем оригинал загруженного изображения, он нам    больше не нужен. Задачей было - получить миниатюру.
$result7 =    mysql_query("SELECT avatar FROM users WHERE    login='$old_login'",$db);//извлекаем текущий аватар пользователя

            $myrow7 = mysql_fetch_array($result7);
if ($myrow7['avatar'] == $ava)    {//если он стандартный, то не удаляем его, ведь у    нас одна картинка на всех.
            $ava = 1;
            }
            else {unlink    ($myrow7['avatar']);}//если аватар был свой, то    удаляем его
 
}
            else 
                    {
                                          //в    случае несоответствия формата, выдаем соответствующее сообщение

                    exit ("Аватар должен быть в    формате <strong>JPG,GIF или PNG</strong>");

                                          }
}
$result4 = mysql_query("UPDATE users SET    avatar='$avatar' WHERE login='$old_login'",$db);//обновляем аватар в базе 

            if ($result4=='TRUE') {//если верно, то отправляем на личную страничку
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$_SESSION['id']."'></head><body>Ваша аватарка изменена! Вы    будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите    сюда.</a></body></html>";}
      } 
            ?>

Если пользователь не собирается менять свои данные, а зашел почитать сообщения, то, скорее всего, захочет удалить лишние. Для этого у нас есть файл drop_post.php, который удаляет из базы сообщение с нужным id.
<?php
          session_start();//запускаем сессии
          include ("bd.php");//подключаемся к базе
if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //если    существует логин и пароль в сессиях, то проверяем, действительны ли они
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))
               {
               //данные    пользователя неверны. 
                exit("Вход на эту страницу разрешен    только зарегистрированным пользователям!");
               }
            }
            else {
            //Проверяем,    зарегистрирован ли вошедший
            exit("Вход на эту    страницу разрешен только зарегистрированным пользователям!"); }
            $id2 = $_SESSION['id']; //получаем идентификатор своей страницы
 
if (isset($_GET['id'])) { $id    = $_GET['id'];}//получаем через GET запрос    идентификатор сообщения, которое нужно удалить
$result = mysql_query("SELECT poluchatel    FROM messages WHERE id='$id'",$db); 
            $myrow =    mysql_fetch_array($result); //нужно уточнить,    кому сообщение отправлено
            //ведь    через GET запрос пользователь может ввести любой идентификатор и как    следствие удалить сообщения, которые отправляли не ему.
if ($login ==    $myrow['poluchatel']) {//если сообщение    отправляли данному пользователю, то разрешаем его удалить
$result = mysql_query ("DELETE FROM    messages WHERE id = '$id' LIMIT 1");//удаляем сообщение 
            if ($result == 'true') {//если удалено - перенаправляем на страничку    пользователя
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$id2."'></head><body>Ваше сообщение удалено! Вы    будете перемещены через 5 сек. Если не хотите ждать, то <a    href='page.php?id=".$id2."'>нажмите    сюда.</a></body></html>";
            }
            else {//если    не удалено, то перенаправляем, но выдаем сообщение о неудаче
            echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$id2."'></head><body>Ошибка! Ваше сообщение не    удалено. Вы будете перемещены через 5 сек. Если не хотите ждать, то <a    href='page.php?id=".$id2."'>нажмите    сюда.</a></body></html>"; }
}
            else {exit("Вы пытаетесь    удалить сообщение, отправленное не вам!");} //если    сообщение отправлено не этому пользователю. Значит, он попытался удалить его,    введя в адресной строке какой-то другой идентификатор
            ?>

Если же пользователь вошел не на свою страничку, следовательно, изменять ничего не сможет, но у него есть возможность отправить сообщение. Файл post.php добавляет в базу сообщения:
<?php
          session_start(); //запускаем сессию. Обязательно в начале страницы
          include ("bd.php"); // соединяемся с базой, укажите свой путь, если у вас    уже есть соединение
if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //если    существует логин и пароль в сессиях, то проверяем, действительны ли они
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))

               {
               //если логин    или пароль не действителен
                exit("Вход на эту страницу разрешен    только зарегистрированным пользователям!");
               }
            }
            else {

            //Проверяем,    зарегистрирован ли вошедший
            exit("Вход на эту    страницу разрешен только зарегистрированным пользователям!"); }
if (isset($_POST['id'])) { $id    = $_POST['id'];}//получаем идентификатор страницы    получателя
            if (isset($_POST['text'])) { $text =    $_POST['text'];}//получаем текст сообщения 
            if (isset($_POST['poluchatel'])) {    $poluchatel = $_POST['poluchatel'];}//логин получателя 
            $author = $_SESSION['login'];//логин автора 
            $date = date("Y-m-d");//дата добавления 
if (empty($author) or empty($text) or    empty($poluchatel) or empty($date)) {//есть ли все необходимые    данные? Если нет, то останавливаем
            exit ("Вы ввели не всю    информацию, вернитесь назад и заполните все поля");}
$text = stripslashes($text);//удаляем обратные слеши
            $text =    htmlspecialchars($text);//преобразование    спецсимволов в их HTML эквиваленты

            $result2 = mysql_query("INSERT INTO    messages (author, poluchatel, date, text) VALUES    ('$author','$poluchatel','$date','$text')",$db);//заносим в базу сообщение 
echo "<html><head><meta    http-equiv='Refresh' content='5;    URL=page.php?id=".$id."'></head><body>Ваше сообщение передано! Вы    будете перемещены через 5 сек. Если не хотите ждать, то <a    href='page.php?id=".$id."'>нажмите    сюда.</a></body></html>";//перенаправляем    пользователя
            ?>

Страничка со списком всех пользователей сделает сайт намного удобней. all_users.php:
<?php
          //    вся процедура работает на сессиях. Именно в ней хранятся данные пользователя,    пока он находится на сайте. Очень важно запустить их в самом начале    странички!!!
          session_start();
 include ("bd.php");// файл bd.php должен быть в той же папке, что и все    остальные, если это не так, то просто измените путь 
 if (!empty($_SESSION['login']) and    !empty($_SESSION['password']))
            {
            //если    существует логин и пароль в сессиях, то проверяем, действительны ли они

            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            $result2 = mysql_query("SELECT id FROM    users WHERE login='$login' AND password='$password'",$db); 
            $myrow2 = mysql_fetch_array($result2); 
            if (empty($myrow2['id']))
               {
               //если данные    пользователя не верны
                exit("Вход на эту страницу разрешен    только зарегистрированным пользователям!");
               }
            }
            else {
            //Проверяем,    зарегистрирован ли вошедший
            exit("Вход на эту    страницу разрешен только зарегистрированным пользователям!"); }
            ?>
            <html>
            <head>
            <title>Список пользователей</title>
            </head>
            <body>
            <h2>Список    пользователей</h2>
  
 <?php
            //выводим    меню
            print <<<HERE
            |<a    href='page.php?id=$_SESSION[id]'>Моя страница</a>|<a    href='index.php'>Главная страница</a>|<a    href='all_users.php'>Список пользователей</a>|<a    href='exit.php'>Выход</a><br><br>
            HERE;
 $result = mysql_query("SELECT login,id    FROM users ORDER BY login",$db); //извлекаем логин и идентификатор пользователей 
            $myrow = mysql_fetch_array($result);
            do
            {
            //выводим их в цикле 
            printf("<a    href='page.php?id=%s'>%s</a><br>",$myrow['id'],$myrow['login']);
            }
            while($myrow = mysql_fetch_array($result));
 ?>
            </body>
            </html>

Добавить навигацию на страничке index.php было бы замечательно. Допишем ее после «print <<<HERE» между звездочками.
//при    удачном входе пользователю выдается все, что расположено ниже между    звездочками.
          //************************************************************************************
  
 print <<<HERE

            |<a    href='page.php?id=$_SESSION[id]'>Моя страница</a>|<a    href='index.php'>Главная страница</a>|<a    href='all_users.php'>Список пользователей</a>|<a    href='exit.php'>Выход</a><br><br>

А теперь давайте поговорим с вами о том, для чего вообще лучше использовать регистрацию. Ведь ее использование может привести к уменьшению вашей аудитории и новых пользователей. Мы все ценим свое время, как и посетители наших сайтов, которым не доставляет никакого удовольствия заполнять поля. Поэтому не стоит бессмысленно закрывать ссылки и материалы только для того, чтобы пользователь зарегистрировался. Если я встречаю сайт, где есть такая ссылка, то сразу покидаю его, так как знаю, что найду другой сайт с тем же материалом, где ссылка открыта, причем это займет меньше времени. А если и не меньше, то просто задумайтесь, зачем администрация сайта хочет, чтобы пользователи регистрировались? В голову приходит только две мысли: чтобы хвалиться количеством пользователей или, чтобы узнать ваши пароли. Тем более, многие всегда используют одинаковые пароли на всех сайтах.

Гораздо лучше использовать регистрацию, что бы сэкономить время пользователя. То есть при отправки комментария ему не придется каждый раз вводить свой ник, е-мейл, не говорю уже об аватаре. Зарегистрировавшись, он сможет общаться с другими пользователями сайта, это очень полезно.