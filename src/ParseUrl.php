<?php
namespace LoaderImage;
/**
 * Обработка web-страницы по ссылке
 * @author Ann Shpakova <anet@lectra.me>
 * @version 1.0
 */
class ParseUrl
{
    
    /**
     * @var string
     */
    protected $url;

    /**
     *
     * @var string
     */
    protected $domen;
    
    /**
     * constructor
     */
    public function __construct() {}

    /**
     * Возрващает массив с абсолютными ссылками на изображения
     * @param string $url
     * @return array
     */
    public function getImages($url)
    {
        $this->setUrl($url); //set the page
        
        $tags = $this->getContent()->getElementsByTagName('img'); //get all image tags

        $image = array();
        foreach ($tags as $tag) {
            $image_src = $tag->getAttribute('src'); //get image's link
            
            if (!empty($image_src)) {
                $image[] = $this->validSrc($image_src); // to absolute link
            }
        }
        return $image;
    }
    
    /**
     * Устанавливает ссылку для обработки
     * @param string $url
     * @throws CustomException Если ссылка указана не правильно или не существует такой страницы
     */
    public function setUrl($url)
    {
        try {
            if ($this->isValidUrl($url)) {
                $this->url = $url;
                $this->domen = $this->urlToHost($url);
            }
        }
        catch (CustomException $e) {
            echo '[w] ' . $e->getMessage() . '<br/>';
            exit();
        }
    }

    /**
     * Из ссылки формирует протокол + доменное имя 
     * @param string $url
     * @return string http://example.com.ua
     */
    public function urlToHost($url)
    {
        $path = parse_url($url);

        $link = (isset($path['scheme'])) ? $path['scheme'] . '://' : '';
        $link .= (isset($path['host'])) ? $path['host'] : '';
        return $link;
    }

    /**
     * Проверка ссылки 
     * @param string $url
     * @return boolean
     * @throws CustomException Если не указана ссылка
     * @throws CustomException Если такой страницы не существует
     */
    public function isValidUrl($url)
    {
        if (is_null($url) || empty($url)){
            throw new CustomException('Link is empty. Please provide to a remote page');
        } else {
            $headers = @get_headers($url);

            if (!$headers || !preg_match("/(200)/", $headers[0])) {
                throw new CustomException('Invalid link or a remote page does not exist');
            }
        }
        return true; 
    }
    
    /**
     * @return string
     */
    public function getUrl() 
    {
        return $this->url;
    }

    /**
     * Обработка удаленной страницы
     * @return \DOMDocument
     */
    public function getContent()
    {
        try {
            $content = @file_get_contents($this->url);
            $doc = new \DOMDocument();
            @$doc->loadHTML($content);
        } catch (Exception $e){
            echo 'Не удалось загрузить контент';
            exit();
        }
        return $doc;
    }

    /**
     * Формирование абсолютной ссылки на изображение
     * @param string $path
     * @return string
     */
    public function validSrc($path)
    {
        if (preg_match("/^http/", $path)) {
            return $path;
        } elseif (substr($path, 0, 1) == '/') {
            return $this->domen . $path;
        } else {
            return trim($this->url, '/') . '/' . $path;
        }
    }

}
