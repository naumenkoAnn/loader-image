<?php

require_once 'src/Loader/Image/CustomException.php';
require_once 'src/Loader/Image/ParseUrl.php';
require_once 'src/Loader/Image/LoaderImage.php';

//require __DIR__.'/../vendor/autoload.php';

define('PROJECT_ROOT', __DIR__.'/');

$url = 'http://www.1plus1.ua/novyny/do-merezhi-potrapili-znimki-z-repeticiy-vechirnogo-kvartalu-foto-610549.html';

$Loaders = new LoaderImage();

$Loaders->setUploadsDir('uploads'); //Загрузка в корневую директорию uploads

$total = $Loaders->loadByUrl($url);

echo '[i] Загружено ' . $total . ' изображений';