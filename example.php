<?php

require 'vendor/autoload.php';


$url = 'http://www.1plus1.ua/novyny/do-merezhi-potrapili-znimki-z-repeticiy-vechirnogo-kvartalu-foto-610549.html';

$loaders = new LoaderImage\Image();

#$loaders->setUploadsDir('uploads'); //Загрузка в корневую директорию uploads

$total = $loaders->loadByUrl($url);

echo '[i] Загружено ' . $total . ' изображений';
