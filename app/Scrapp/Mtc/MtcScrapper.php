<?php

namespace App\Scrapp\Mtc;

use App\Scrapper\Scrapper;
use App\Scrapper\ScScrapper;
use App\Scrapper\ScScrappResult;
use Exception;

class MtcScrapper extends ScScrapper {
    
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

    public function __construct(Scrapper $requester) {
        $this->web = new MtcWeb();
        $this->scratcher = new MtcScratcher();
        $this->requester = $requester;
    }

    public $result;

    public function run($placa) {
        
        $this->result = new ScScrappResult();
        
        $this->web->setRequester ($this->requester);
        
        $this->log ('Captured session ' . $this->web->requestSession());
        $this->result->session();
        
        $captcha = $this->web->requestCaptcha();

        $content = base64_decode(explode (',', $captcha)[1]);
        
        //$this->log ('Captured captcha' . $captcha . '<br />');
        $this->result->captcha();

        $this->scratcher->saveImage(($filename = uniqid()) . '.' . ($ext = 'jpg'), $content);
        
        $this->log ('Saved captcha in ' . $filename . '.' . $ext );
        
        //$this->log ('Image: <img src="' . $captcha . '" />');
        
        
        try {
            $scraptcha = $this->scratcher->scratch( $this->scratcher->path($filename), $ext);
            $this->result->readed();
        } catch (Exception $ex) {
            $this->log ($ex->getMessage());
            $scraptcha = '';
        }

        $this->log ('Scratched captcha: ' . ($scraptcha));

        $this->log ('Captcha testing: ' . ($match = $this->web->testCaptchaValue ($scraptcha)));
        $match = json_decode ($match);

        if (!count($match))  return; 

        $this->result->tested();
        //$this->log ('Captured data: ' . ($response = $this->web->requestData ($placa, $match[0])));
        $response = $this->web->requestData ($placa, $match[0]);
        $this->result->response();
        $responseData = json_decode($response);

        if ($responseData && $responseData->DATA) {
            $this->log ('Captured data: ' . $responseData->DATA[0]->certificado);
            $this->result->data();
        }
        
    }
}