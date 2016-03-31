<!DOCTYPE html>
<html>
    <head>
	<!--[if lt IE 9]> 
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> 
	<![endif]--> <!-- В эксплорерах до 9-й версии этот скрипт помогает отображать новые элементы html5 -->
    <meta charset="utf-8">
    <title>Гостевая книга | Тестовое задание</title>
	<meta name="keywords" content="гостевая, вход, авторизация" />
    <meta name="description" content="Гостевая книга ООО Вектор - отличное место для обсуждения Вашх вопросов." />
    
	<link rel="icon" type="image/png" href="./img/favicon.ico">
    
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,900&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/css.css"> <!-- В данном задании дизайн - не главная часть, к тому же сложных элементов не будет. Потому обойдусь без css-фреймворков. Используем только normalize.css от necolas и простенький шрифт с Google Fonts -->
    </head>
    
<body>
<!-- СКРИПТ ФЕЙСБУКА --> 
    <script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    if (response.status === 'connected') {
      // если человек вошел, то testAPI здоровается с ним
      testAPI();
    } else if (response.status === 'not_authorized') {
      // Если человек не залогинен на сайте, кидаем его на главную.
        document.location.href = 'http://test.format-center.com/index.php';
    } else {
      // Если человек не залогинен в ФБ в принципе, так же - на главную.
      document.location.href = 'http://test.format-center.com/index.php';
    }
  }

        //функция на случай, если пользователь хочет выйти из фб
function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
        
  window.fbAsyncInit = function() { //стандартная часть АПИ фейсбука с данными приложения (веб-сайта)
  FB.init({
    appId      : '681787198629120', //ид моего приложения
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously - стандартная часть АПИ фб. Без нее не заработает. 
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // та самая функция, которая выведет "привет, юзернейм!"
  // еще она ставит куки, откуда данные юзера попадут в БД
  function testAPI() {
    FB.api('/me', function(response) {
    console.log('Успешный вход для: ' + response.name);
        
      document.getElementById('status').innerHTML =
        'Добро пожаловать, ' + response.name + '!';
        //функция для установки куки
        function setCookie(name,value){
            document.cookie = name + "=" + value;
        }
        
        queryJSon = JSON.stringify(response);//из JSon в строку
        setCookie("Uname",response.name); //и в куки
        setCookie("UFBdata",queryJSon);
    });
  }
</script>
<!-- КОНЕЦ скрипта фейсбука -->    
    
    <header>
        <h1>Гостевая книга</h1>
        <div id="status">
        </div>
        <fb:login-button class="fb-login-button" data-max-rows="1" data-size="small" data-show-faces="false" data-auto-logout-link="true" scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>
    </header>
    <div class="content">
        
        <form enctype="multipart/form-data" method="post" action="./gost.php?action=add">
        <div class="wrapper"><label>Ваш комментарий:</label></div>
        <textarea class="form-item" name="content" required></textarea>
        <input type="hidden" name="username" id="unmid" value="<?=$_COOKIE["Uname"]?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
            <!-- Здесь максимальный размер файла задан ТОЛЬКО для удобства пользователя. На стороне сервера стоит прописать нужные значения в php.ini (или в админке, где как) параметрами  upload_max_filesize = 1M post_max_size = 1M и в .htacess-->
        <input type="file" name="userfile" accept="image/jpeg,image/png,image/gif,text/plain">
        <input type="submit" value="Отправить" class="btn">
        </form>
        
        <?php foreach($comments as $comment): ?>
        <div class="comment">
            <p class="comment_head"><span class="comment_number"><?=$comment['comm_id']?></span> <?=$comment['user_name']?> <span>Опубликовано: <?=substr($comment['date'],0,-3)?></span></p>
            <!-- nl2br нужен, чтобы отображался перенос строки в комментарии. Можно было бы заменять /n на <br> еще при записи в БД, но я не большой любитель порить данные в базе. Лучше форматировать вывод. -->
            <div class="comment_content"><?=nl2br($comment['content'])?></div>
            <? if (!is_null($comment['user_file'])) 
                echo '<div class="ufile">Прикрепленный файл: <a target="_blank" href="'.$comment['user_file'].'">Файл</a></div>';
            ?>
            <?  $interval=(time('Y-m-d G:i:s') - strtotime($comment['date']));
                /*Если прошло менее 5 минут с момента отправки сообщения*/
                if ($interval<=300){ 
                    /*И если при этом совпадают данные пользователя от фб в куки и в базе данных
                    (сравнение именно по данным от фб я выбрала, т.к. в куки легко подделать имя пользователя, которое на виду, а вот данных от Фб не подделать так просто - там, как минимум, еще и id*/
                    if ($comment['user_fb_data'] == $_COOKIE["UFBdata"])
                        echo '<p class="tools"><a href="./gost.php?action=del&id='.$comment['comm_id'].'">Удалить комментарий</a></p>';}
            ?>
        </div>
        <?php endforeach ?>
        
    </div>
    <footer>
    <p>Created by: 2late2heal &copy; 2016</p>
    </footer>

</body>    
</html>