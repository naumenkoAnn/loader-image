<?php
namespace LoaderImage;
/**
 * Загрузка изображений из удаленной страницы
 * @author Ann Shpakova <anet@lectra.me>
 * @version 1.0
 */
class Image
{
    
    /**
     * Директория для загрузки изображений
     * @var string
     */
    protected $uploads_dir;

    /**
     * Перезапись изображений
     * @var boolean
     */
    protected $replace = false;
    
    /**
     * К-ство загруженных изображений
     * @var int
     */
    protected $count = 0;
    
    /**
     * Массив допустимых расширений
     * @var array
     */
    protected $extension = array('jpg', 'png', 'gif');

    /**
     * constructor
     */
    public function __construct()
    {
        $this->setUploadsDir();
    }

    /**
     * Загрузка всех изображений с удаленной страницы
     * @param string $url
     * @return int К-ство загруженых изображений
     */
    public function loadByUrl($url)
    {   
        $Parser = new ParseUrl();
        $images = $Parser->getImages($url);
        return $this->loadByArray($images);
    }

    /**
     * Загрузка изображений из массива
     * @param array $images Абсолютные ссылки на изображения
     * @return int К-ство загруженых изображений
     */
    public function loadByArray($images)
    {
        foreach($images as $image) {
            $path_from = trim($image);

            try {
                $this->isAllowedExtension($path_from);
            } catch (CustomException $e){
                echo '[i] ' . $e->getMessage() . '<br/>'; 
                continue;
            }

            $path_to = $this->uploads_dir . basename($path_from);
            $path_to = strtolower($path_to);
            
            if (!file_exists($path_to) || $this->replace) {
               
                try {
                    $this->saveFromContent($path_from, $path_to);
                    $this->count++;
                } catch (Exception $ex) {
                    echo '[w] ' . $e->getMessage() . '\n';
                }
            }
        }
        return $this->count; 
    }

    /**
     * Сохранение изображения
     * @param string $path_from
     * @param string $path_to
     * @throws CustomException Если не удалось загрузить файл
     * @throws CustomException Если не удалось сохранить файл
     */
    public function saveFromContent($path_from, $path_to)
    {
        $image = @file_get_contents($path_from);
        if (!$image){
            throw new CustomException('Could not load file'); 
        }
        
        $fp = fopen($path_to, "w+");
        if(!$fp){
            throw new CustomException('Unable to save file'); 
        }
        
        fwrite($fp, $image);
        fclose($fp); 
    }
    
    /**
     * Установка директории для загрузки изображений
     * @param string $path
     * @throws CustomException Если нет прав для создания директории
     * @throws CustomException Если нет прав для загрузки изображений в директорию
     */
    public function setUploadsDir($path = 'uploads')
    {
        $vendor_path = substr(__FILE__, 0, strpos(__FILE__, 'vendor/'));
        $this->uploads_dir = $vendor_path . trim($path, '/') . '/';

        if (!is_dir($this->uploads_dir)) {
            if (!@mkdir($this->uploads_dir, 0777)){
                throw new CustomException('You are not allowed to create directories');
            }
        } elseif (!is_writable($this->uploads_dir)) {
            throw new CustomException('Insufficient permissions to upload images');
        }
    }

    /**
     * Проверка формата файла
     * @param string $file
     * @return boolean
     * @throws CustomException Если недопустимый формат изображения
     */
    public function isAllowedExtension($file)
    {
        $file = strtolower($file);
        
        $ext = $this->getFileExtension($file);
        if ( !$ext || !in_array($ext, $this->extension) ) {
            throw new CustomException('Invalid image format');
        }
        return true;
    }

    /**
     * Расширение файла
     * @param string $file
     * @return boolean or string
     */
    public function getFileExtension($file)
    {
        if (false === ($start = strrpos($file, '.'))) {
            return false;
        } else {
            return substr($file, $start + 1);
        }
    }

    /**
     * 
     * @param string $extension
     */
    public function setAllowedExtension($extension)
    {
        $this->extension = $extension;
    }

}
