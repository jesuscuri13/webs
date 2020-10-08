<?php

namespace App\Scrapp\Soat;

use App\Scrapper\ScAspScrappingWeb;

class SoatWeb extends ScAspScrappingWeb {

    /**
     * @var string $url
     */
    protected $url = 'http://www.soat.com.pe/php/placas.php';
    protected $captchaUrl = 'http://www.soat.com.pe/php/captcha.php';

    protected $session = '';

    /**
     * @var \App\Scrapper\Scrapper
     */
    protected $requester;

    protected $captcha;

    public function setRequester($requester) {
        $this->requester = $requester;
    }

    public function requestSession() {
        $basicUrl = $this->captchaUrl;
        $client = $this->requester->createClient();
        $response = $client->request('GET', $basicUrl, [ 'headers' => $this->basicHeaders() ]);

        $cookies = $this->cutCookie ($response->getHeader('Set-Cookie')[0]);
        $this->captureCaptchaFromBody($response->getBody());
        $this->session = $cookies['PHPSESSID'];
        return $cookies['PHPSESSID'];
    }

    public function captureCaptchaFromBody($body) {
        $this->captcha = $body;
    }

    public function requestCaptcha () {
        return $this->captcha;
    }

    public function requestData($placa, $scraptcha) {
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
        $response = $this->requester
            ->createClient()
            ->request(
                'POST',
                $this->url,
                [
                    'headers' => $headers,
                    'json' => [
                        'ose1' => $type,
                        'ose2' => $placa,
                        'ose3' => $scraptcha
                    ] 
                ]
            );

        $result = json_decode ($response->getBody());
        return $result->d;
    }
    

    public function testCaptchaValue (string $captchaValue) {
        $captchaValue = str_replace(' ', '', $captchaValue);
        $captchaValue = str_replace('-', '', $captchaValue);
        $captchaValue = str_replace('/[aZ]/', '', $captchaValue);
                
        preg_match('/^[0-9]{6}$/', $captchaValue, $matches);
        return json_encode ($matches);
    }
}