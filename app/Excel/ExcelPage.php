<?php

namespace App\Excel;

class ExcelPage {

    public function run($data) {
        return [
            [],
            [],
            [
                [ 'action' => 'merge', 'lines' => 1 ],
                [ 'action' => 'merge', 'lines' => 5 ],
            ],
            [
                [ 'action' => 'merge', 'lines' => 1 ],
                [ 'action' => 'merge', 'lines' => 5 ],
            ],
            [
                [ 'action' => 'merge', 'lines' => 1 ],
                [ 'action' => 'merge', 'lines' => 5 ],
            ],
            [
                [ 'action' => 'merge', 'lines' => 1 ],
                [ 'action' => 'merge', 'lines' => 5 ],
            ],
            [],
            [
                [ 'action' => '' ],
                [ 'action' => 'merge', 'lines' => 1 ],
            ]
        ];
    }

}