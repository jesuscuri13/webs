<?php

namespace App\Scrapping\MtcRecord;

use App\Scrapper\Scrapper;
use App\Scrapper\UserAgent;
use App\Scrapper\WebScrapper;

class MtcRecordScrapper extends WebScrapper {

    /**
     * @var string $url
     */
    protected $url = 'https://recordconductor.mtc.gob.pe/RecCon/ObtenerDatosAdministrado';
    protected $captchaUrl = 'https://recordconductor.mtc.gob.pe/Captcha/CaptchaImage';

    protected $session = '';

    public function getUrl() {
        return $this->url;
    }

    public function captureResponse($body) {
        return (object)[
            'success' => true,
            "type" => "json",
            "data" => $body
        ];
    }

    public function captureSession (Scrapper $scrapper) {
        $basicUrl = 'https://recordconductor.mtc.gob.pe/Captcha/CaptchaImage';
        $client = $scrapper->createClient();
        $response = $client->request('GET', $basicUrl, [ 'headers' => $this->basicHeaders() ]);

        $cookies = $this->cutCookie ($response->getHeader('Set-Cookie')[0]);
        return $cookies['ASP.NET_SessionId'];
    }

    public function cutCookie ($cookie) {
        return array_reduce (explode(';', $cookie), function ($prev, $value) {
            $arr = explode('=', $value);
            
            $prev[$arr[0]] = isset ($arr[1]) ? $arr[1] : '';
            
            return $prev;
        }, []);
    }

    public function getCaptcha() {
        return [
            'required' => true,
            'api' => $this->captchaUrl,
        ];
    }

    public function method() { return 'GET'; }

    public function captchaHeaders() {
        return [
            'Accept' => 'image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'es-ES,es;q=0.9',
            'Connection' => 'keep-alive',
            'Host' => 'recordconductor.mtc.gob.pe',
            'Referer' => 'https://recordconductor.mtc.gob.pe/',
            'Sec-Fetch-Dest' => 'image',
            'Sec-Fetch-Mode' => 'no-cors',
            'Sec-Fetch-Site' => 'same-origin',
            'User-Agent' => UserAgent::genMozilla()
        ];
    }

    /**
     * @param string $txtNroPlaca
     */
    public function initialData($txtNroPlaca, $captcha = '') {
        return [
            'headers' => [
                'Accept' => 'application/json, text/javascript, */*; q=0.01',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'es-ES,es;q=0.9',
                'Connection' => 'keep-alive',
                'Cookie' => 'ASP.NET_SessionId=sq2q3vgo0fdws5cqjs52lwoc',
                'Host' => 'recordconductor.mtc.gob.pe',
                'Referer' => 'https://recordconductor.mtc.gob.pe/',
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'same-origin',
                'User-Agent' => UserAgent::genMozilla(),
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'query' => [
                'str_tpbusqueda' => 1,
                'str_tipo_documento' => 2,
                'str_num_documento' => $txtNroPlaca,
                'str_captcha' => $captcha
            ]
        ];
    }

    /**
     * @param string $txtNroPlaca
     * 
     */
    protected function requireData ($txtNroPlaca) {
        return [
            'headers' => [],
            'data' => [
                'ctl00$MainContent$txtNoPlaca' => $txtNroPlaca
            ]
        ];
    }
}