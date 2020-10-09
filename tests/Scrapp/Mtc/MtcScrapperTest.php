<?php

use App\Scrapp\Mtc\MtcScrapper;
use App\Scrapper\Scrapper;

class MtcScrapperTesto extends TestCase {

    /**
     * 
     */
    public $testData = [
        'X3Q220',
        'V1K838'
    ];
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample() {
        
        $this->runMtc (3);
    }

    public function runMtc ($tests) {
        
        $scrapper = new Scrapper (null);
        $mtc = new MtcScrapper($scrapper);
        $length = count ($this->testData);
        for ($i = 0; $i < $tests; $i++) {
            $mtc->run ($this->testData[$i % $length]);
            echo $mtc->printLog();
            var_dump ($mtc->result);
        }
    }
}
