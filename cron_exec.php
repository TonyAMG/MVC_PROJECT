<?php



// создание нового cURL ресурса
$ch = curl_init();

// установка URL и других необходимых параметров
curl_setopt($ch, CURLOPT_URL, 'localhost/'.basename(__DIR__).'/cron/');
curl_setopt($ch, CURLOPT_HEADER, 0);


curl_exec($ch);

// завершение сеанса и освобождение ресурсов
curl_close($ch);
