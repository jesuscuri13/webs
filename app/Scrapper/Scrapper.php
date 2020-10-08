<?php

namespace App\Scrapper;
use GuzzleHttp\Client;


class Scrapper {

    /**
     * @var WebScrapper $scrapper 
     */
    protected $scrapper;

    public function __construct(?WebScrapper $scrapper) {
        $this->scrapper = $scrapper;
    }

    public function scrap ($txtNroPlaca, $captcha = '') {
        
        $client = $this->createClient();
        $values = $this->scrapper->initialData($txtNroPlaca, $captcha);
        $res = $client->request($this->scrapper->method(), $this->scrapper->getUrl(), $values);

        
        return $this->scrapper->captureResponse($res->getBody());
    }

    public function createClient() {
        return new Client([
            'timeout'  => 2.0,
        ]);
    }

}