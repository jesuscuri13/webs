<?php

namespace App\Scratcher;
use \Imagick;
use \ImagickPixel;

class ImageScratched {

    public $image;

    /**
     * Crear la imagen imagick
     * @param string $filename
     * @param string $extension
     */
    public function __construct($filename, $extension) {
        $this->image = new Imagick("$filename.$extension");
    }

    /**
     * Establece el brillo en 0 y el contraste en 45
     */
    public function brightnessContrastRefact() {
        $this->image->brightnessContrastImage(5, 45, Imagick::CHANNEL_DEFAULT);
        return $this;
    }

    /**
     * Establece el brillo en 0 y el contraste en 45
     */
    public function strongBrightnessContrastRefact() {
        $this->image->brightnessContrastImage(0, 75, Imagick::CHANNEL_DEFAULT);
        return $this;
    }

    public function onlyColor ($color) {
        $fuzz = Imagick::getQuantum() * 0.1; // 10%
        
        $this->image->setImageFormat('png'); 
        $this->image->transparentPaintImage ( $color , 0 , $fuzz, true);
        
        return $this;
    }
    
    public function negateAll () {
        $this->image->negateImage ( false );
        return $this;
    }

    public function resize ($width, $height) {
        $this->image->resizeImage($width, $height, Imagick::FILTER_SINC , 1);
        return $this;
    }

    /**
     * Imagen
     */
    public function __toString() { return $this->image->__toString(); }
    
}