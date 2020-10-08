<?php

namespace App\Scrapper;

class SunarpScrapper extends WebScrapper {

    /**
     * @var string $url
     */
    protected $url = 'https://www.sunarp.gob.pe/ConsultaVehicular/ConsultaPlaca';

    public function getUrl() {
        return $this->url;
    }

    public function captureResponse($body) {
        $plateCarImageData = $this->captureStringFromTo ('<img id="MainContent_imgPlateCar" src="', '"', $body);

        if (!$plateCarImageData) {
            return (object)[
                'success' => false,
                'data' => $this->captureError ($body)
            ];
        }
        return (object)[
            'success' => true,
            "type" => "image64",
            "data" => $plateCarImageData
        ];
    }

    public function method () { return 'POST'; }

    public function captureError ($body) {
        $warning = $this->captureStringFromTo ('<span id="MainContent_lblWarning">', '</span', $body);
        if (!$warning) {
            return $body;
        }
        return $warning;
    }

    public function getCaptcha () {
        return [
            'required' => false
        ];
    }

    /**
     * @param string $txtNroPlaca
     */
    public function initialData($txtNroPlaca) {
        return [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Referer' => 'https://www.sunarp.gob.pe/ConsultaVehicular/ConsultaPlaca',
                'Origin' => 'https://www.sunarp.gob.pe',
                'User-Agent' => UserAgent::genMozilla(),
                'Host' => 'www.sunarp.gob.pe',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Cookie' => '_ga=GA1.3.1453400721.1600466098; _gid=GA1.3.300058781.1601396762; ASPSESSIONIDSUARSQBC=EILPDDCBLJLABAEDPGDOKBBG; ASP.NET_SessionId=d0dz3qj141qkpu320mozmdzn; _gat=1',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'es-ES,es;q=0.9',
                'Cache-Control' => 'max-age=0',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'same-origin',
                'Sec-Fetch-User' => '?1',
                'Upgrade-Insecure-Requests' => '1'
            ],
            'form_params' => [
                'ctl00$MainContent$txtNoPlaca' => $txtNroPlaca,
                'ctl00$MainContent$txtCaptcha' => '',
                'ctl00$MainContent$btnSend' => 'Buscar',
                'ctl00_MainContent_captch_cv_ClientState' => '{"TextLength":8,"Width":240,"Height":40,"FontFamily":"Verdana","ForeColor":-8355712,"BackColor":-1,"BrushFillerColor":-1015680,"TextBrush":1,"BackBrush":1,"LineNoise":3,"BackgroundNoise":1,"FontWarpLevel":2,"CharSet":"1;2;3;4;5;6;7;8;9;0;a;b;c;d;e;f;g;h;i;j;k;l;m;n;o;p;q;r;s;t;u;v;w;x;y;z"}',
                '_CurrentGuid_ctl00_MainContent_captch_cv' => '1535860476',
                '__EVENTTARGET' => '',
                '__EVENTARGUMENT' => '',
                '__VIEWSTATE' => 'UjlT81VMpPumNyNFGxusiOG76t3vMlY7WsHcJH8o828ISZCfqWVf9v5u0gUBXBxM/404wAC0Edb9j6KGXI4k4v2tzJ6pl0ebHQyrsJmUqGz8zh/bRNMsF5vpZ13tHufNOP5nwnJ8fUgCQcADctmAUC2OznrnsUm/KpdbOgFjBIHQLAV3SmAYsgRb7kSmGdfvi7SjxbWhPb9aRKvz9ygdJyNeAI50eI70bRaQQ0OziGwefq6a+yvscVJoO93d8hfEpDo8eSdQThZJ/04/alcGOuUyXLh3N5Qj/Qn7/tn60tg=',
                '__VIEWSTATEGENERATOR' => '73FA0208',
                '__EVENTVALIDATION' => 'DOGa5k0CzEoNalbbDF7T12aCQBoKKYBZ58nXJ4hvimlB/VZzwtlCxTn8yp3dOxsncX6rYGN4EKWwt5y7+MJGTQuBRXu75PmG+vlZvPdvfI/cvfP+yGG6OjZomqiIsvH0HyCfRx+sPqAKspoAQ3Jng6ND1hxYLR1OGkIcBgGoj8w='
            ]
        ];
    }

    public function captureSession (Scrapper $scrapper) { }

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