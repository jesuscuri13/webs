<?php
namespace App\Scrapping\MtcRecord;

use App\Scrapper\Scrapper;

class MtcRecordScrapping {

    public function run(
        Scrapper $scrapper,
        MtcRecordScrapper $web,
        $dni
    ) {
        return $web->captureSession($scrapper);
    }
}