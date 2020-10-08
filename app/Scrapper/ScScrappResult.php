<?php

namespace App\Scrapper;

class ScScrappResult {
    
    public $capturedSession = false;
    public $capturedCaptcha = false;
    public $readedCaptcha = false;
    public $testedCaptcha = false;
    public $capturedResponse = false;
    public $capturedData = false;

    public function session() {
        $this->capturedSession = true;
        $this->checkSuccess();
    }

    public function captcha() {
        $this->capturedCaptcha = true;
        $this->checkSuccess();
    }

    public function readed() {
        $this->readedCaptcha = true;
        $this->checkSuccess();
    }

    public function tested() {
        $this->testedCaptcha = true;
        $this->checkSuccess();
    }

    public function response() {
        $this->capturedResponse = true;
        $this->checkSuccess();
    }

    public function data() {
        $this->capturedData = true;
        $this->checkSuccess();
    }

    public function checkSuccess() {
        $this->success = $this->capturedSession
            && $this->capturedCaptcha
            && $this->readedCaptcha
            && $this->testedCaptcha
            && $this->capturedResponse
            && $this->capturedData;
    }
    
    public $success = false;
}