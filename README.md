НЕОБХОДИМО СДЕЛАТЬ

Спроектировать архитектуру вашего будущего сайта
  Все довольно просто. Две страницы. В папке views два варианта представления - для главной и для гостевой.
  В папке models одна модель - для гостевой. Для главной пока нет ничего - на ней только вход в гостевую, логин в фейсбук
  Контроллеры у нас - это точки входа (в корневом каталоге). Контроллер для главной совсем простой. Он ничего особо не контролирует. Контроллер гостевой
  обрабатывает запросы на удаление и добавление записи и выводит контент на экран.
  В остальном все просто - папка uploads, папка css...
  
Создание несложного дизайна
  У меня просто нет фантазии, как оформить гостевую книгу, не зная ничего об остальном контенте - шапке, меню, клонках или их отсутствии.
  В общем, продемонстрировать, что я умею верстать, я предпочту на других макетах - есть у меня несколько интересных тренировочных psd, 
  которые потребуют более интересных или оригинальных решений
  
Предложить и обосновать варианты среды выполнения (серв, БД, ОС)
  Мне следовало бы использовать MongoDB или что-то подобное - именно в ней было бы удобно хранить массивы с данными
  о пользователе (получаемые из fb), а заодно (в случае расширения функционала) выделять пользователю его записи, 
  возможно, удобнее хранить ветки записей, прикрутить систему оценки (рейтинга) записей... В любом случае, я предпочла MySQL
  - с ней я знакома глубже (а с NoSQL я знакома лишь теоретически); хоть и не так удобно, но реализовать все вышеописанное можно;
  и главное - доступность хостинга с MySQL. По условиям задачи мне не известны масштабы проекта, возможность аренды vds/vps 
  серверов или свои собственные в наличии, так что MySQL определенно наиболее доступный вариант для компании, которой 
  нужна Гостевая Книга.

  В целом, по тем же причинам стандартный LAMP (Линукс, Апач, MySQL и PHP) для проекта вполне подойдет - найти хостинг 
  с данной связкой легче легкого, его стоимость приемлема для такого простого сайта, способна удовлетворить все потребности 
  данного проекта.
  
Написать небольшой прототип, демонстрирующий, как будет работать сайт
  Да, пожалуй, этот итог я назвала бы прототипом - на этом этапе я обычно согласовываю свои действия с заказчиком и 
  полирую неосновной функционал и фичи.
  
РЕЗУЛЬТАТОМ ОЖИДАЛОСЬ

Описание приняых проектных решений
  Таблица в БД - простая табличка с колонками 
    `comm_id` - уникальный id записи, заполняется автоматически, юзер его не трогает,
    `user_name` - имя пользователя, оставившего запись, просто чтобы не выаскивать его из массива данных от ФБ
    `date` - дата создания записи, которая атоматически ставится функцией-обработчиком добавления
    `content` - содержимое записи, пока чот очищенное от всех тегов,
    `user_fb_data` - массив данных о пользователе от фейсбука,
    `user_ip` - ip пользователя,
    `user_browser` - информация о браузере,
    `user_file` - данные о месте хранения и имени пользовательского файла (мультизагрузку пока не делала)
    
  Вот так вот все простенько с БД, сложных выборок на данном этапе не происходит, обычные SELECT, INSERT и DELETE. Если буду делать
  ветки комментариев, будут SELECT-ы совсем капельку интереснее.
  
Описание выбранного ПО
  Собственно, выше все обозначено вместе с причинами, почему я для данного проекта предпочитаю каноничный LAMP
  
Код прототипа
  Доступен в репозитории. Могу выслать на почту. Могу дать доступ к Ftp.
  
ОБЯЗАТЕЛЬНЫЕ ТРЕБОВАНИЯ

Возможность отправки сообщений
  Есть, сэр
  
Авторизация с помощью Fb
  Есть, сэр. Т.к. в задании авторизация почему-то на сайте, сделала страницу гостевой условно недоступной для неавторизованных
  
Все данные сохраняются в БД, включая данные из Fb, IP и сводка о браузере
  Фейсбук сейчас предпочитает, чтобы его звали из js, потому пришлось сохранять данные из js в куки
  и оттуда в бд. Мне не нравится это решение. Но это обмен данными между js и php, который мне удалось 
  навскидку изобразить, не углубляясь в проработку этого момента
  
БУДЕТ ПЛЮСОМ

Выполнение задания, используя архитектуру MVC
  Я бы не назвала это идеальным применением принципов MVC. т.к. есть еще острые углы, но в целом - да,
  примерно так я его понимаю.
  
Возможность добавления картинки или текста к сообщению
  Готово. Без мультизагрузки.
  
Изображение должно быть не более 500*500 или пропорционально уменьшить
  Готово.
  
Допустимые форматы - jpg, gif, png, txt
  Готово. На сколько могла, на стороне настроек сервера и обработчиков данных это реализовала
  
Текстовый файл не более 1Мб
  Реализовано для всех файлов, в т.ч. картинок
  
Возможность удалять свои сообщения в теч. 5 минут
  Готово. Но есть баг - если страница с комментами не обновлялась, то можно удалить коммент и чуть позже, 
  чем через 5 минут. Решается перепроверкой даты в обработчике. Пока не сделала. Допиливается быстро.
  
Валидация вводимых данных на стороне сервера и клиента.
  Нууу... На стороне клиента у меня есть обязательное для заполнения поле (js-ом не проверяется), а сервер
  тщательно проверяет присланные данные.
  
Добавление, удаление, предпросмотр - без перезагрузки.
  Сделать это не так уж сложно, но перезагрузка именно в гостевой кажется мне логичной и естественной,
  хотя с расширением функционала это сделать не так уж сложно
  
Добавление визуальных эффектов.
  Хотела добавить всплывающие изображения при клике на прикрепленный файл. Пока поленилась. Могу сделать,
  это быстро.
  
  
  
  Благодарю за внимание, подробнее о самом коде - в комментариях к этому самому коду. =)
