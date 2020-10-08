<?php

namespace App\Scrapper;

abstract class ScScrapper {


    public function __construct () {
    }
    
    public abstract function run($placa);

    protected $logInfo = '';

    protected function log (string $message) {
        $this->logInfo .= "$message<br />\n";
    }

    public function printLog () {
        return $this->logInfo;
    }

}