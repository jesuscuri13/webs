<?php

namespace App\Http\Controllers;

use App\Excel\ExportToExcel;
use App\Scrapp\Mtc\MtcScrapper;
use App\Scrapp\Soat\SoatScrapper;
use App\Scrapper\Scrapper;
use App\Scrapping\MtcRecord\MtcRecordScrapper;
use App\Scrapping\MtcRecord\MtcRecordScrapping;
use App\Scratcher\Scratcher;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ExampleController extends Controller {

    public $public = __DIR__ . '/../../../public';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function show() {
        //return $this->read('./captcha.jpg');
        //return '';
        //return $this->scrapp();
        return (new ExportToExcel())->run();
        //return $this->scrappSoat();
    }

    public function read() {
        $scratcher = new Scratcher();
        return $scratcher->scratchSoat($this->public . '/captcha', 'jpg');
    }

    public function scrapp () {
        //$scrap = new Scrapper(new SunarpScrapper());
        $web = new MtcRecordScrapper();
        $scrapper = new Scrapper($web);
        $mtc = new MtcRecordScrapping();
        return $mtc->run ($scrapper, $web, '71749321');
        /*
        if ($response->success) {
            if ($response->type == 'image64') {
                $this->saveImage64($response->data, './' . uniqid() . '.png');
                return '<img src="' . $response->data . '" />';
            } else {
                return $response->data;
            }
            
        }*/
    }

    public function scrappSoat() {
        return (new SoatScrapper())
            ->run ('X3Q220')
            ->printLog();
    }

    public function scrappMtc() {
        $scrapper = new Scrapper (null);
        $mtc = new MtcScrapper($scrapper);

        return $mtc->run('X3Q220');
    }

    // public function scrappMtc () {
    //     $web = new MtcScrapper();
    //     $scrapper = new Scrapper($web);
    //     $mtc = new MtcScrapping();
    //     return $mtc->run ($scrapper, $web, 'X3Q220');
    // }
    
    public function saveImage64($base64, $path) {
        $base64ToPhp = explode(',', $base64);
        $data = base64_decode($base64ToPhp[1]);
        file_put_contents($path, $data);
    }

    public function readImage ($path) {
        return (new TesseractOCR($path))
            ->run();
    }
}
