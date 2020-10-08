<?php

namespace App\Scrapp\Mtc;

use App\Scrapper\ScScratcher;
use App\Scratcher\ImageScratched;
use App\Scratcher\Scratcher;

class MtcScratcher extends ScScratcher {


    public function __construct() {
        $this->scratcher = new Scratcher();
    }

    public function scratch ($file, $ext) {
        $this->modifyCaptcha ($file, $ext);
        return $this->readImage ("{$file}_copy.$ext");
    }
    
    public function modifyCaptcha($file, $ext) {
        
        file_put_contents (
            "{$file}_copy.$ext",
            (new ImageScratched($file, $ext))
                ->brightnessContrastRefact()
        );
        
    }

    public function readImage ($path) { return $this->scratcher->readImage($path); }

    public function saveImage ($filename, $content) { return $this->scratcher->saveImage($filename, $content); }

    public function path ($filename) { return $this->scratcher->path ($filename); }

}