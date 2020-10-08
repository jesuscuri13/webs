<?php

namespace App\Scrapping\Soat;

use App\Scrapper\AspScrapper;
use App\Scrapper\Scrapper;
use App\Scrapper\UserAgent;

class SoatScrapper extends AspScrapper {

    /**
     * @var string $url
     */
    protected $url = 'http://www.soat.com.pe/php/placas.php';
    protected $captchaUrl = 'http://www.soat.com.pe/php/captcha.php';

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
        $basicUrl = $this->captchaUrl;
        $client = $scrapper->createClient();
        $response = $client->request('GET', $basicUrl, [ 'headers' => $this->basicHeaders() ]);

        $cookies = $this->cutCookie ($response->getHeader('Set-Cookie')[0]);
        $this->session = $cookies['ASP.NET_SessionId'];
        return $cookies['ASP.NET_SessionId'];
    }

    public function testCaptchaValue (string $captchaValue) {
        $captchaValue = str_replace(' ', '', $captchaValue);
        $captchaValue = str_replace('-', '', $captchaValue);
        $captchaValue = str_replace('/[aZ]/', '', $captchaValue);
                
        preg_match('/^[0-9]{6}$/', $captchaValue, $matches);
        return json_encode ($matches);
    }

    public function captureCaptcha(Scrapper $scrapper) {
        $headers = $this->appendHeaders(
            $this->jsonHeaders(),
            [
                'Cookie' => "ASP.NET_SessionId={$this->session}",
                'Host' => 'portal.mtc.gob.pe',
                'Origin' => 'https://portal.mtc.gob.pe',
                'Referer' => 'https://portal.mtc.gob.pe/reportedgtt/form/frmconsultaplacaitv.aspx'
            ]
        );

        $response = $scrapper
            ->createClient()
            ->request(
                'POST',
                $this->captchaUrl,
                [ 'headers' => $headers ]
            );

        $result = json_decode ($response->getBody());
        return $result->d;
    }

    
    /**
     * @param string $txtNroPlaca
     * 
     */
    public function captureData ($txtNroPlaca, $scraptcha, Scrapper $scrapper) {

        $type = '1';
        $headers = $this->appendHeaders (
            $this->jsonHeaders(),
            [
                'Cookie' => "ASP.NET_SessionId={$this->session}",
                'Host' => 'portal.mtc.gob.pe',
                'Origin' => 'https://portal.mtc.gob.pe',
                'Referer' => 'https://portal.mtc.gob.pe/reportedgtt/form/frmconsultaplacaitv.aspx'
            ]
        );
        var_dump ([
            'headers' => $headers,
            'data' => [
                'ose1' => $type,
                'ose2' => $txtNroPlaca,
                'ose3' => $scraptcha
            ] 
        ]);
        $response = $scrapper
            ->createClient()
            ->request(
                'POST',
                $this->url,
                [
                    'headers' => $headers,
                    'json' => [
                        'ose1' => $type,
                        'ose2' => $txtNroPlaca,
                        'ose3' => $scraptcha
                    ] 
                ]
            );

        $result = json_decode ($response->getBody());
        return $result->d;
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