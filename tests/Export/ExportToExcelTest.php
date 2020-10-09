<?php

use App\Excel\ExportToExcel;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExportToExcelTest extends TestCase {
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        echo (new ExportToExcel())->run();
    }
}
