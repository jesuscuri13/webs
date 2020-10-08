<?php

namespace App\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExportToExcel {

    public function run() {

        $page = new ExcelPage();
        $usuario = 'auasd';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->renderPage ($sheet, $page);
        $sheet->setCellValue('A1', 'El ID del USUARIO ES:'.$usuario);

        

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="file.xlsx"');
        $writer->save("php://output");
    }

    public function renderPage (Spreadsheet $sheet, ExcelPage $page) {
        $
        $curr = 0;
        foreach ($page->rows as $ind => $row) {
            
        }
    }

}