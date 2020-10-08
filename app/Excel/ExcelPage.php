<?php

namespace App\Excel;

class ExcelPage {

    public function __construct() {
        
    }

    public $data = [
        'title' => 'ORDEN DE SALIDA ',

    ];

    public $rows = [
        [
            [ 'action' => ''],
            [ 'action' => 'compose', 'lines' => 2 ]
        ]
    ];

}