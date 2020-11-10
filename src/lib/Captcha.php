<?php


namespace lib;


use Services\ConfigService;

class Captcha
{

    private $config;

    public function __construct()
    {
        $this->config = ConfigService::getInstance();
    }

    public function generateCAPTCHA(bool $create_noise = false) : void
    {
        //извлекаем секретный номер из сесси
        $secret_number = $_SESSION['secret_number'];
        //путь к шрифту
        $font = $this->config->font;
        //создаем фон рисунка
        $image = imagecreatetruecolor(130, 60);
        //задаем цвет фона (прозрачный)
        $background = imagecolorallocatealpha($image, 0, 0, 0, 127);
        //заполняем фон
        imagefill($image, 0, 0, $background);

        //генерируем цифры, их количество зависит от числа, переданного в $secret_number
        for ($i = 0, $n = 0, $x = 10; $i < strlen($secret_number); $i++, $n++, $x+=25) {
            $img_color[$i] = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            //вывод цифр на наш рисунок со случайной генерацией их расположения и указанием шрифта
            imagettftext($image,
                mt_rand(20, 50),
                mt_rand(-20, 20),
                $x,
                50,
                $img_color[$n],
                $font,
                substr($secret_number, $n, 1)
            );
        }

        //создаем помехи в виде точек на нашем рисунке,
        //если передано значение true в $create_noise
        if ($create_noise === true) {
            $imgpx = mt_rand(444, 1000);
            while($imgpx > 0){
                imagesetpixel($image, mt_rand(0, 130), mt_rand(0, 60), $img_color[mt_rand(0, 3)]);
                $imgpx--;
            }
        }

        //сглаживающий фильтр
        imagefilter($image, IMG_FILTER_SMOOTH, mt_rand(10, 12));
        //сохраняем прозрачный фон
        imagesavealpha($image, true);
        //заголовок, который сообщает браузеру, что далее последует картинка
        header('Content-type: image/png');
        //функция, формирующая картинку
        imagePNG($image);
    }
}