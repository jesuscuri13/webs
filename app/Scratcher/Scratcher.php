<?php

namespace App\Scratcher;

use thiagoalessio\TesseractOCR\TesseractOCR;
use \Imagick;
use \ImagickPixel;

class Scratcher {

    private $public = __DIR__ . '/../../public/';

    public function scratchFile ($file, $ext) {
        $this->modify($file, $ext);
        return $this->readImage("{$file}_copy.$ext");
    }

    public function scratchSoat ($file, $ext) {
        $this->modifySoatCaptcha ($file, $ext);
        return $this->readImage ("{$file}_copy.$ext");
    }

    public function modify ($file, $ext) {
        header('Content-type: image/jpeg');
        $image = new Imagick("$file.$ext");/* Crear un borde para la imagen */
        
        $image->brightnessContrastImage(0, 45, Imagick::CHANNEL_DEFAULT);
        
        file_put_contents("{$file}_copy.$ext", $image->__toString());
    }
    

    public function modifySoatCaptcha ($file, $ext) {
        header('Content-type: image/jpeg');
        
        file_put_contents(
            "{$file}_copy.$ext",
            (new ImageScratched($file, $ext))
                ->strongBrightnessContrastRefact()
                ->resize (300, 68)
                ->negateAll()
                ->onlyColor('rgb(0, 255, 255)')
        );
    }

    public function createImage ($file, $ext) {
        return new ImageScratched ($file, $ext);
    }

    public function readImage ($path) {
        return (new TesseractOCR($path))
            ->run();
    }

    public function saveImage ($filename, $content) {
        file_put_contents ($this->path($filename), $content);
    }

    public function path ($filename) {
        return $this->public . $filename;
    }

}