<?php

namespace App\Scrapp\Soat;

use App\Scrapper\Scrapper;
use App\Scrapper\ScScrapper;
use App\Scrapper\ScScrappResult;
use Exception;

class SoatScrapper extends ScScrapper {
    
    /**
     * @var \App\Scrapp\Mtc\MtcWeb;
     */
    protected $web;

    /**
     * @var \App\Scrapp\Mtc\MtcScratcher;
     */
    protected $scratcher;

    /**
     * @var \App\Scrapper\Scrapper;
     */
    protected $requester;

    public function __construct() {
        $this->requester = new Scrapper(null);
        $this->web = new SoatWeb();
        $this->web->setRequester($this->requester);
        $this->scratcher = new SoatScratcher();
        $this->result = new ScScrappResult();
    }

    public $result;

    public function run($placa) {
        echo 'here';
        $this->log ('Captured session ' . $this->web->requestSession());
        $this->result->session();
        
        $captcha = $this->web->requestCaptcha();

        //$content = base64_decode(explode (',', $captcha)[1]);
        
        //$this->log ('Captured captcha' . $captcha . '<br />');
        $this->result->captcha();

        $this->scratcher->saveImage(($filename = uniqid()) . '.' . ($ext = 'png'), $captcha);
        
        $this->log ('Saved captcha in ' . $filename . '.' . $ext );
        
        $this->log ("Image: <img src=\"$filename.$ext\" />");
        
        
        try {
            $scraptcha = $this->scratcher->scratch( $this->scratcher->path($filename), $ext);
            $this->result->readed();
        } catch (Exception $ex) {
            $this->log ($ex->getMessage());
            $scraptcha = '';
        }

        $this->log ('Scratched captcha: ' . ($scraptcha));

        
        
        return $this;
    }
}