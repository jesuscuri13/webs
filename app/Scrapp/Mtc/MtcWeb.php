<?php

namespace App\Scrapp\Mtc;

use App\Scrapper\ScAspScrappingWeb;

class MtcWeb extends ScAspScrappingWeb {

    protected $url = 'https://portal.mtc.gob.pe/reportedgtt/form/frmConsultaPlacaITV.aspx/getPlaca';
    protected $captchaUrl = 'https://portal.mtc.gob.pe/reportedgtt/form/frmConsultaPlacaITV.aspx/refrescarCaptcha';
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
        $response = $this->requester
            ->createClient()
            ->request(
                'POST',
                $basicUrl,
                [ 'headers' => $this->jsonHeaders() ]
            );
        
        $this->captureCaptchaFromBody ($response->getBody());
        
        $cookies = $this->cutCookie ($response->getHeader('Set-Cookie')[0]);
        $this->session = $cookies['ASP.NET_SessionId'];
        return $cookies['ASP.NET_SessionId'];
    }

    public function captureCaptchaFromBody (string $body) {
        $data = json_decode ($body);
        $this->captcha = $data->d;
        return $this;
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