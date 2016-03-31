<?php
define('MYSQL_SERVER', 'localhost'); //u93033.mysql.masterhost.ru
define('MYSQL_USER', 'u93033');
define('MYSQL_PASSWORD', 'su2auelite');
define('MYSQL_DB', 'u93033');

function db_connect(){
    $link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB)
        or die("Error: ".mysqli_error($link));
    if(!mysqli_set_charset($link, "utf8")){
        printf("Error: ".mysqli_error($link));
    }
    
    return $link;
    
}
?>