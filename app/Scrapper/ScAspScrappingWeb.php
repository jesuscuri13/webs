<?php

namespace App\Scrapper;

abstract class ScAspScrappingWeb extends ScScrappingWeb {
    
    public function cutCookie ($cookie) {
        return array_reduce (explode(';', $cookie), function ($prev, $value) {
            $arr = explode('=', $value);
            
            $prev[$arr[0]] = isset ($arr[1]) ? $arr[1] : '';
            
            return $prev;
        }, []);
    }

}