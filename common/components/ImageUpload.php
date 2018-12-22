<?php
namespace common\components;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 05.10.2014
 * Time: 18:17
 */
use EasyImage;

class ImageUpload extends \CApplicationComponent
{
    /**
     * @var EasyImage
     */
    private $_image;
    private $_path;

    public function load($image)
    {
        $this->_path = $image;
        $this->_image = new EasyImage($image);

        return $this;
    }

    public function save($path, $deleteTmp = true)
    {
        if($this->_image === null)
            throw new \Exception(\Yii::t('errors', 'Некорректное изображение'));

        $r = $this->_image->save($path);
        if($r && $deleteTmp)
            @unlink($this->_path);

        return $r;
    }

    public function resize($width = null, $height = null, $master = null)
    {
        if($this->_image === null)
            throw new \Exception(\Yii::t('errors', 'Некорректное изображение'));

        $this->_image->resize($width, $height, $master);
        return $this;
    }
} 