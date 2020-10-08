<?php

namespace App\Scrapper;

abstract class WebScrapper {

    protected $url;

    public abstract function initialData($txt, $catcha = '');

    public abstract function getUrl();

    public abstract function captureResponse($body);

    public abstract function captureSession(Scrapper $scapper);

    public abstract function method();

    public function basicHeaders() {
        return [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'es-ES,es;q=0.9',
            'Connection' => 'keep-alive',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'none',
            'User-Agent' => UserAgent::genMozilla()
        ];
    }

    public function jsonHeaders() {
        return $this->appendHeaders (
            $this->basicHeaders(),
            [
                'Accept' => 'application/json, text/javascript, */*; q=0.01',
                'Content-Type' => 'application/json; charset=utf-8',
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'same-origin',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        );
    }

    protected function appendHeaders(...$headers) {
        $arr = [];
        foreach ($headers as $headerList) {
            foreach ($headerList as $key => $value) {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    protected function captureStringFromTo(string $strFrom, string $strTo, string $data) {
        $pos = strpos($data, $strFrom, 0);
        if ($pos === false) {
            return null;
        }
        $offset = $pos + strlen($strFrom);
        $pos = strpos ($data, $strTo, $offset);
        if ($pos === false) {
            return null;
        }
        $top = $pos - $offset;
        return substr ($data, $offset, $top);
    }

    public abstract function getCaptcha();
}