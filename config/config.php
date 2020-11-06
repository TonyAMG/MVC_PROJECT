<?php

//require dirname(__FILE__) . '/../config/error_msg.php';



//название основной директроии приложения
$host = 'MVC_PROJECT';
//директория с HTML-шаблонами
$templates_dir =  dirname(__FILE__).'/../templates/';
//директория с файлами локализации
$localisation_dir = dirname(__FILE__).'/../localisation/';
//файл текущей локализации
$localisation_file = 'ru.php';
//путь к шрифту для капчи
$font = dirname(__FILE__) . '/../font/gost.ttf';
//директория загрузки файлов
$upload_dir = dirname(__FILE__) . '/../upload/';
//конфиг именования загруженых фото
$upload_photo_path = $upload_dir . session_id() . '_photo.jpg';
//максимальный размер загружаемых фото (в байтах)
$photo_max_size = 2097152;  //по умолчанию - 2 Мб

//список маршрутизации
$routes_list = [
    '~session_reset/$~' => [Controllers\RegController::class, 'sessionUnsetAction'],
    '~main_paige$~' => [Controllers\MainController::class, 'mainPage'],
    '~(^$)|(main/(.*))~' => [Controllers\MainController::class, 'main'],
    '~register_user_post$~' => [Controllers\RegController::class, 'register'],
    '~reg/$~' => [Controllers\RegController::class, 'mainAction'],
    '~reg/successful$~' => [Controllers\RegController::class, 'successfulRegAction'],
    '~^auth/$~' => [Controllers\AuthController::class, 'main'],
    '~^captcha/(.*)$~' => [Controllers\AuthController::class, 'captcha'],
    '~server_error/$~' => [Controllers\MainController::class, 'errorServerAction']
];

//настройки для PHPMailer
$mail_config = [
    'Host'          =>  'smtp.gmail.com',
    'Username'      =>  '2005test2005test',
    'Password'      =>  'password',

];

//настройки базы данных
$db_config = [
    'dsn'       =>   'mysql:host=localhost;dbname=mvc_project',
    'user'      =>   'mvc_project',
    'password'  =>   'password'
];

//список доступных input с формы
//"название_параметра"      =>  ["его свойства"]
$inputs_properties = [
           "name"           =>  ["min_length" => 5, "max_length" => 20, "filter" => FILTER_VALIDATE_REGEXP, "filter_var_options" => ["options" => ["regexp" => "/^\w{5,20}$/"] ], "db_check_reverse" => true, "validation_type" => "db_filter"],
           "password"       =>  ["min_length" => 10, "max_length" => 30, "filter" => FILTER_VALIDATE_REGEXP, "filter_var_options" => ["options" => ["regexp" => "/^\w{10,30}$/"] ], "validation_type" => "filter" ],
           "sex"            =>  ["html_hook" => "sex_input", "db_check" => true, "validation_type" => "db"],
           "birth_year"     =>  ["max_length" => 4, "filter" => FILTER_VALIDATE_INT, "filter_var_options" => ["options" => ["min_range" => 1920, "max_range" => 2020]], "validation_type" => "filter"],
           "email"          =>  ["max_length" => 50, "filter" => FILTER_VALIDATE_EMAIL, "db_check_reverse" => true, "validation_type" => "db_filter"],
           "about_yourself" =>  ["min_length" => 10, "max_length" => 200, "filter" => FILTER_VALIDATE_REGEXP, "filter_var_options" => ["options" => ["regexp" => "/^(\s|\w){10,200}$/"]], "validation_type" => "filter"],
           "upload_photo"   =>  ["validation_type" => "file"],
           "send_email"     =>  ["db_check" => true, "validation_type" => "db"],
           "card_type"      =>  ["db_check" => true, "validation_type" => "db"],
           "card_number"    =>  ["max_length" => 16, "filter_credit_card" => FILTER_VALIDATE_REGEXP, "validation_type" => "filter", "filter_var_options" => [
                                                                                                                                                            "mastercard" => ["options" => ["regexp" => "/5[1-5][0-9]{14}/"]],
                                                                                                                                                            "visa" => ["options" => ["regexp" => "/^4[0-9]\d+$/"]]]]
];


//сверка с базой данных
$db_check = [
           "name"           => ["admin", "superuser", "guest", "animal"],
           "sex"            => ["male", "female"],
           "email"          => ["admin@mail.com", "superuser@mail.com", "guest@mail.com", "animal@mail.com"],
           "send_email"     => ["yes"],
           "card_type"      => ["visa", "mastercard"]
];


//в данной таблице хранятся данные для автозамены в HTML-шаблонах
//сама таблица являет собой ассоциативный массив, за исключением
//ключа "regex", который сам является ассоциативным массивом
//вида ключ = [пара] для автозамены по регулярным выражениям
$parse_table = [
           '<!--RESUME_REGISTRATION_BEGIN-->'   =>           '<?php if (count(array_filter($_SESSION["input"])) 
                                                              && @!($_SERVER["HTTP_REFERER"] === "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")): ?>',
           '<!--RESUME_REGISTRATION_END-->'     =>            '<?php endif; ?>',
           '<!--INPUT_NAME-->'                  =>           '<?=$_SESSION["input"]["name"] ?? @$sanitized_post["name"] ?>',
           '<!--INPUT_PASSWORD-->'              =>           '<?=$_SESSION["input"]["password"] ?? @$sanitized_post["password"] ?>',
           '<!--INPUT_SEX_MALE-->'              =>           '<?=(@$sanitized_post["sex"]==="male" || @$_SESSION["input"]["sex"] === "male") ? "checked" : "" ?>',
           '<!--INPUT_SEX_FEMALE-->'            =>           '<?=(@$sanitized_post["sex"]==="female" || @$_SESSION["input"]["sex"] === "female") ? "checked" : "" ?>',
           '<!--INPUT_EMAIL-->'                 =>           '<?=$_SESSION["input"]["email"] ?? @$sanitized_post["email"] ?>',
           '<!--INPUT_BIRTH_YEAR-->'            =>           '<?=$_SESSION["input"]["birth_year"] ?? @$sanitized_post["birth_year"] ?>',
           '<!--INPUT_ABOUT_YOURSELF-->'        =>           '<?=$_SESSION["input"]["about_yourself"] ?? @$sanitized_post["about_yourself"] ?>',
           '<!--INPUT_SEND_EMAIL-->'            =>           '<?=(@$sanitized_post["send_email"]==="yes" || @$_SESSION["input"]["send_email"] === "yes") ? "checked" : "" ?>',
           '<!--INPUT_CARD_TYPE_MASTERCARD-->'  =>           '<?=(@$sanitized_post["card_type"]==="mastercard" || @$_SESSION["input"]["card_type"] === "mastercard") ? "selected" : "" ?>',
           '<!--INPUT_CARD_TYPE_VISA-->'        =>           '<?=(@$sanitized_post["card_type"]==="visa" || @$_SESSION["input"]["card_type"] === "visa") ? "selected" : "" ?>',
           '<!--INPUT_CARD_NUMBER-->'           =>           '<?=$_SESSION["input"]["card_number"] ?? @$sanitized_post["card_number"] ?>',

           '<!--VALIDATION_ERROR-->'            =>           '<?=$vars??""?>',

            '<!--PREVIEW_KEY-->'                =>           '<?=(!$this->input_correct[$key])?:$this->preview_loc[$key]?>',
            '<!--PREVIEW_VALUE-->'              =>           '<?=$value?>',

            "regex"                             =>           [0 => ['/(<!--SPACES\[)(\d{1,3})(\]-->)/', '<?=$this->spaceGen($2)?>'] ]





];


$error_msg = ["name"             =>   ["db_check"       => "* <b>Имя</b> уже занято другим пользователем. <br>",
                                       "validator"      => "* <b>Имя</b> должно состоять из _, латиницы или цифр (от 5 до 20 символов). <br>"],
              "password"         =>   ["validator"      => "* <b>Пароль</b> должен состоять из латинских букв, цифр (от 10 до 30 символов). <br>"],
              "sex"              =>   ["db_check"       => "* <b>Пол</b> не выбран. <br>"],
              "birth_year"       =>   ["validator"      => "* <b>Год рождения</b> указан неверно. <br>"],
              "email"            =>   ["db_check"       => "* <b>Email</b> уже занят другим пользователем. <br>",
                                       "validator"      => "* <b>Email</b> указан неверно. <br>"],
              "about_yourself"   =>   ["validator"      => "* <b>Кратко о себе</b> должно состоять из латинских букв, цифр, допускаются пробелы (от 10 до 200 символов). <br>"],
              "file"             =>   ["file_handler"   => "* <b>Фото</b> не загружено. <br>",
                                       "big_file"       => "* <b>Фото</b> слишком большое. Максимальный размер 2 Мб.<br>"],
              "send_email"       =>   ["db_check"       => "* <b>Флажок</b> отправки формы регистрации и фото на email не отмечен. <br>"],
              "card_type"        =>   ["db_check"       => "* <b>Тип</b> кредитной карты не выбран. <br>"],
              "card_number"      =>   ["validator"      => "* <b>Номер</b> кредитной карты указан неверно. <br>",
                                       "validator_2"    => "* <b>Номер</b> и <b>тип</b> платежной системы кредитной карты должны <b>обязательно</b> быть указаны, чтобы привязать её к аккаунту. <br>"]
];