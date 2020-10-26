<?php


namespace lib;


use Controllers\ConfigController;

class Captcha
{

    private $config;

    public function __construct()
    {
        $this->config = new ConfigController();
    }

    public function generateCAPTCHA($secret_number)
    {

        //путь к шрифту
        $font = $this->config->font;

        //создаем фон рисунка
        $img = imagecreate(130, 60);

        //задаем цвет фона
        imagecolorallocate($img, 255, 255, 255);

        //это цвета будущих цифр на рисунке
        $imgcolor[0] = imagecolorallocate($img, 0, 0, 0);
        $imgcolor[1] = imagecolorallocate($img, 0, 100, 0);
        $imgcolor[2] = imagecolorallocate($img, 200, 0, 0);
        $imgcolor[3] = imagecolorallocate($img, 0, 0, 250);

        //Вывод цифр на наш рисунок со случайной генерацией их расположения и указанием шрифта
        imagettftext($img, mt_rand(30, 40), mt_rand(-10, 10), mt_rand(3, 8), mt_rand(40, 60), $imgcolor[mt_rand(0, 3)], $font, substr($secret_number, 0, 1));
        imagettftext($img, mt_rand(30, 40), mt_rand(-10, 10), mt_rand(40, 45), mt_rand(40, 60), $imgcolor[mt_rand(0, 3)], $font, substr($secret_number, 1, 1));
        imagettftext($img, mt_rand(30, 40), mt_rand(-10, 10), mt_rand(65, 75), mt_rand(40, 60), $imgcolor[mt_rand(0, 3)], $font, substr($secret_number, 2, 1));
        imagettftext($img, mt_rand(30, 40), mt_rand(-10, 10), mt_rand(100, 110), mt_rand(40, 60), $imgcolor[mt_rand(0, 3)], $font, substr($secret_number, 3, 1));

        //создаем помехи в виде точек на нашем рисунке
        $imgpx = mt_rand(444, 1000);
        while($imgpx > 0){
            imagesetpixel($img, mt_rand(0, 150), mt_rand(0,60), $imgcolor[mt_rand(0, 3)]);
            $imgpx--;
        }

        //сглаживающий фильтр
        imagefilter($img, IMG_FILTER_SMOOTH, mt_rand(10, 12));

        //Заголовок, который сообщает браузеру, что далее последует картинка
        header('Content-type: image/gif');

        //Собственно функция, формирующая картинку
        imagePNG($img);
    }
}