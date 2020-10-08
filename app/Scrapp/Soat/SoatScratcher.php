<?php

namespace App\Scrapp\Soat;

use App\Scrapper\ScScratcher;
use App\Scratcher\ImageScratched;
use App\Scratcher\Scratcher;

class SoatScratcher extends ScScratcher {

    public function __construct() {
        $this->scratcher = new Scratcher();
    }

    public function scratch ($file, $ext) {
        $this->modifyCaptcha ($file, $ext);
        return $this->readImage ("{$file}_copy.$ext");
    }
    
    public function modifyCaptcha($file, $ext) {
        (new ImageScratched($file, $ext))
            ->strongBrightnessContrastRefact()
            ->resize (300, 68)
            ->image
            ->WriteImage("{$file}_copy.$ext");
        (new ImageScratched ("{$file}_copy", $ext))
            ->onlyColor('rgb(250, 0, 0)')
            ->image
            ->WriteImage("{$file}_copy.$ext");
            
        
    }

    public function readImage ($path) { return $this->scratcher->readImage($path); }

    public function saveImage ($filename, $content) { return $this->scratcher->saveImage($filename, $content); }

    public function path ($filename) { return $this->scratcher->path ($filename); }

}