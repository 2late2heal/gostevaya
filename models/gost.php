<?php

/* Вывод записей на страницу*/
function comment_all($link){
    
    $query = "SELECT * FROM comments ORDER BY comm_id DESC";
    $result = mysqli_query($link, $query);
    
    if (!result) die(mysqli_error($link));
    
    $n = mysqli_num_rows($result);
    $arr = array();
    
    for ($i=0; $i<$n; $i++)
    {
        $row = mysqli_fetch_assoc($result);
        $arr[$i] = $row;
    }
    return $arr;  
}

/* Запись новой записи в БД */
function comment_new($link,$user,$content,$fb,$ip,$browser,$file){
    
    $date = date('Y-m-d G:i:s');//ставим текущую дату
    
    /*Костыль с if-ами был поставлен, потому что перепробовала 4 варианта
    вставить корректный NULL в MySQL и пока за неимением лучшего запилила костыль*/
    if ($file == NULL)
        $query = "INSERT INTO comments (comm_id, user_name, date, content, user_fb_data, user_ip, user_browser, user_file) VALUES (NULL,'{$user}','{$date}','{$content}','{$fb}','{$ip}','{$browser}',NULL)";
    else
        $query = "INSERT INTO comments (comm_id, user_name, date, content, user_fb_data, user_ip, user_browser, user_file) VALUES (NULL,'{$user}','{$date}','{$content}','{$fb}','{$ip}','{$browser}','{$file}')";
    
    $result = mysqli_query($link, $query);
    if (!$result) die(mysqli_error($link));

    return true;
}

/* Редактирование записи, обновление информации в БД */
function comment_edit($id,$date,$content){} //еще не запилено, н нужно ж было показать, что я знаю, что есть CRUD

/* Удаление записи из БД */
function comment_delete($link,$id){
    
    $query = "DELETE FROM comments WHERE comm_id=".$id;
    $result = mysqli_query($link, $query);
    
    if (!result) die(mysqli_error($link));
    
    return mysqli_affected_rows($link); 
}


/*честно спертая функция для ресайза изображений, потому что я умею не только
изобретать велосипеды, но и использовать чужие*/
function resize($image, $w_o = false, $h_o = false) {
    if (($w_o < 0) || ($h_o < 0)) {
      return false;
    }
    list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
    $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
    $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
    if ($ext) {
      $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
      $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
    } else { //если формат изображения недопустимый
      return false;
    }
    /* Если указать только 1 параметр, то второй подстроится пропорционально */
    if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
    if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
    $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
    imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
    $func = 'image'.$ext; // Получаем функция для сохранения результата
    return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
  }

/*Функция для параноидальной проверки самого страшного для сайта -
ЗАГРУЖАЕМЫХ ФАЙЛОВ! Полна огораживания. Отчасти украдена с stackoverflow.com
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
Хорошо бы потом вместо return false прописать сообщения об ошибках для юзера*/
function checkImage ($file){
    $filename=$file['name'];
    $filetype=$file['type'];
    $filename = strtolower($filename);
    $filetype = strtolower($filetype);
    
    /*для начала нужно проверить, есть ли вообще файл, а то чего гонять то, чего нет*/
    if(!is_uploaded_file($file['tmp_name'])) return false;
    
    //а еще мы не принимаем файлы больше 1мб
    if ($file['size']>1000000)  return false;
    
    /*кстати, пустые файлы, или файлы, содержащие только
    <?php мне тоже очень не нравятся. Над минимальным размером стоит
    еще подумать, пока пусть будет 80*/
    if ($file['size']<80)  return false;
    
    /*Если в имени файла содержится "php" - он потенциально вредоносен.
    Может и не стоит так уж параноить, но всякие 1.php.jpg - гадость.*/
    $pos = strpos($filename,'php');
    if(!($pos === false)) return false;
    
    $file_ext = strrchr($filename, '.'); //получаем расширение файла
    $whitelist = array(".jpg",".jpeg",".gif",".png",".txt"); //допустимые расширения
    if (!(in_array($file_ext, $whitelist))) return false;
    
    //проверим на двойной тип файла
    if(substr_count($filetype, '/')>1) return false;
    
    /*Тепеь поработаем только с картинками*/
    $whitelist_img = array(".jpg",".jpeg",".gif",".png");
    if (in_array($file_ext, $whitelist_img)) {
        $size=getimagesize($file['tmp_name']);
        if (($size[0]>500) || ($size[1]>500)) { //если один из параметров больше 500
            if ($size[0]>$size[1]) //если ширина больше высоты
                resize($file['tmp_name'],500,false); //обрезаем ширину до 500
            else //если высота больше ширины
                resize($file['tmp_name'],false,500); //обрезаем высоту до 500
        }
    }

    return true;
}



/*ПОСЛЕ ПРОВЕРКИ перемещаем временный файл в "загрузки"*/
function SaveImage ($file){
    $filename=$file['name'];
    $file_ext = strrchr($filename, '.');
    
    /*В папке аплоадс лежит .htacess, который нельзя менять злоумышленникам.
    Он, конечно, будет с правами только на чтение, но исхитрившись, его можно переименовать и залить свой, например. Потому безопасней хранить загружаемые
    файлы не в самой аплоадс, а в подпапках, которые будут названы
    по дате, почему бы нет?*/
    $uploaddir = './uploads/'.date("Y-m-d").'/' ;
    //создаем папку, если она не существует
    if (file_exists($uploaddir)) {}
        else mkdir( $uploaddir, 0777);
    //сменим имя файла на рандомногенерируемое, во избежание
    $uploadfile = $uploaddir . uniqid() .$file_ext;
    //если файл загружен в постоянную папку успешно, вернем его адрес
    if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
        return $uploadfile;
    } else { //а иначе NULL
        return NULL;
    }
}


?>