<?php

namespace App\Excel;

class PrintPage extends ExcelPage {

    public function run ($data) {
        return [
            [],
            [],
            [
                [ 'action' => 'print', 'text' => 'TOUR:' ],
                [ 'action' => '' ],
                [ 'action' => 'print', 'text' => $data['Tour_Name'] ],
                [ 'action' => 'advance', 'lines' => 4],
                [ 'action' => 'print', 'text' => 'NRO. PAX:' ],
                [ 'action' => 'print', 'text' => $data['Tour_NoPassenger'] ],
            ],
            [
                [ 'action' => 'print', 'text' => 'GUIA:' ],
                [ 'action' => '' ],
                [ 'action' => 'print', 'text' => ''], //$data['Group_MainGuide'] . ' - ' . $data['Group_MainGuide_Phone'] ],
                [ 'action' => 'advance', 'lines' => 4],
                [ 'action' => 'print', 'text' => 'SEGUNDO GUIA:' ],
                [ 'action' => 'print', 'text' => ''] //$data['Group_SecondGuide'] . ' - ' . $data['Group_SecondGuidePhone'] ],
            ],
        ];
    }

}