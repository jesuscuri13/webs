<?php

namespace App\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportToExcel {

    public function run() {

        $page = new ExcelPage();
        $usuario = 'auasd';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->render (
            $sheet,
            [
                [
                    'Tour_Name' => 'asdasldk',
                    'Tour_NoPassenger' => '',
                    'Passenger' => [
                        [
                            'Passenger_Name' => 'asdsa',
                            'Passenger_LastName' => 'asdasd',
                            'Passenger_Gender' => 1,
                            'Passenger_DOB' => '6/10/2020',
                        ]
                    ]
                ],
                [
                    'Tour_Name' => 'asdasldk',
                    'Tour_NoPassenger' => '',
                    'Passenger' => [
                        [
                            'Passenger_Name' => 'asdsa',
                            'Passenger_LastName' => 'asdasd',
                            'Passenger_Gender' => 1,
                            'Passenger_DOB' => '6/10/2020',
                        ]
                    ]
                ]
            ]
        );
        /*$sheet->setCellValue('A1', 'El ID del USUARIO ES:'.$usuario);*/

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="file.xlsx"');
        $writer->save("php://output");
    }

    public function render (Worksheet $sheet, $tours) {
        
        $currentRow = 0;
        foreach ($tours as $tour) {
            
            $this->renderPage ($sheet, new ExcelPage(), $tour, $currentRow);
            $this->renderPage ($sheet, new PrintPage(), $tour, $currentRow);
            $this->renderPage ($sheet, new PassengerPage(), $tour, $currentRow);
            $currentRow += $this->evaluateRowLength($tour);
        }
        
    }

    public function evaluateRowLength ($tour) {
        $paddingTop = 8;
        $marginBottom = 3;
        return $paddingTop + count($tour['Passenger']) + $marginBottom;
    }

    public function renderPage (Worksheet $sheet, ExcelPage $page, $tour, $initialRow) {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        foreach ($page->run($tour) as $ind => $row) {
            $column = 0;
            foreach ($row as $order) {
                switch ($order['action']) {
                    case 'print':
                        $range1 = $letters[$column] . ($initialRow + $ind + 1);
                        $sheet->setCellValue("$range1", $order['text']);
                        break;
                    
                    case '': break;
                    case 'merge':
                        $range1 = $letters[$column] . ($initialRow + $ind + 1);
                        $range2 = $letters[$column + $order['lines']] . ($initialRow + $ind + 1);
                        $sheet->mergeCells("$range1:$range2");
                        $column += $order['lines'];
                        break;
                    case 'advance':
                        $column += $order['lines'];
                        break;
                }
                $column++;
            }
            
        }
    }

}