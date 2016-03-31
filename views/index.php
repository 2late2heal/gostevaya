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
        <div class="login">
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Пожалуйста, войдите ' +
        'для доступа к гостевой.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Пожалуйста, войдите ' +
        'для доступа к гостевой.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '681787198629120',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Успешный вход для: ' + response.name);
        console.log(JSON.stringify(response));
        
      document.getElementById('status').innerHTML =
        'Спасибо за вход, ' + response.name + '! Теперь Вы можете <a href="./gost.php">перейти к комментариям</a>.';
        
        function setCookie(name,value){
            document.cookie = name + "=" + value;
        }
        
        console.log(JSON.stringify(response));
        queryJSon = JSON.stringify(response);
        setCookie("Uname",response.name);
        setCookie("UFBdata",queryJSon);
  });

  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<h1>Добро пожаловать на тестовый сайт!</h1> 
<p>Для доступа к функционалу гостевой книги Вам необходимо войти в Facebook.</p>
            
<fb:login-button class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="true" scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>

<div id="status">
</div>
            
        </div>
    </body>
    
</html>