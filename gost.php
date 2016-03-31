<?php
require_once("./database.php");
require_once("./models/gost.php");

$link = db_connect();

if(isset($_GET['action']))
    $action = stripslashes(htmlspecialchars(strip_tags(trim($_GET['action']))));
else
    $action = "";



if($action == "add") {
  if(!empty($_POST['content'])){//если в POST действительно передана переменная Контент
      //проверим данные
    $content = stripslashes(htmlspecialchars(strip_tags(trim($_POST['content']))));
    $user = stripslashes(htmlspecialchars(strip_tags(trim($_POST["username"]))));
    
    if (checkImage($_FILES['userfile'])) {
       $file= SaveImage($_FILES['userfile']);
    }
      else {$file=NULL;}
      
    $ufb =$_COOKIE["UFBdata"];
      comment_new($link, $user, $content, $ufb, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"],$file);
      header("Location: gost.php");
  }
}

/*В удалении пока еще есть баг - если страница с комментами не обновлялась, то
можно удалить коммент и чуть позже, чем через 5 минут. Решается перепроверкой даты
сообщения вот тут, перед удалением*/
if($action == "del") {
  if(!empty($_GET['id'])){//если в POST действительно передана переменная Контент
      //проверим данные
      $id = stripslashes(htmlspecialchars(strip_tags(trim($_GET['id']))));
      $id = (int)$id;
        if ($id == 0) header("Location: gost.php");//не только ноль, но и не интеджер
        else
            comment_delete($link, $id);
      header("Location: gost.php");
  }
}

else {
$comments = comment_all($link);
include("./views/gost.php");}


?>