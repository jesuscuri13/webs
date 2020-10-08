<?php
namespace App\Scrapping\Mtc;

use App\Scrapper\Scrapper;
use App\Scratcher\Scratcher;

class MtcScrapping {

    private $logInfo = '';

    private function log (string $message) {
        $this->logInfo .= "$message<br />";
    }

    public function run( Scrapper $scrapper, MtcScrapper $web, string $placa ) {
        
        $this->log ('Captured session ' . $web->captureSession($scrapper));
        $captcha = $web->captureCaptcha($scrapper);

        $content = base64_decode(explode (',', $captcha)[1]);
        
        $this->log ('Captured captcha' . $captcha);

        $scratcher = new Scratcher();
        $scratcher->saveImage(($filename = uniqid()) . '.' . ($ext = 'jpg'), $content);
        
        $this->log ('Saved captcha in ' . $filename . '' . $ext);

        $this->log ('Image: <img src="' . $captcha . '" />');

        $this->log ('Scratched captcha: ' . ($scraptcha = $scratcher->scratchFile( $scratcher->path($filename), $ext)));

        $this->log ('Captcha testing: ' . ($match = $web->testCaptchaValue ($scraptcha)));
        $match = json_decode ($match);

        if (count($match)) {
            $this->log ('Captured data: ' . ($match = $web->captureData ($placa, $match[0], $scrapper)));
        }
        

        return $this->logInfo;
    }
}