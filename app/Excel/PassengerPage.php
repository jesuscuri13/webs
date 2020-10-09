<?php

namespace App\Excel;

class PassengerPage extends ExcelPage {

    public function run ($data) {
        $passengers = $data['Passenger'];
        $turn = [[], [], [], [], [], [], [], [
            [ 'action' => 'print', 'text' => 'NO' ],
            [ 'action' => ''],
            [ 'action' => 'print', 'text' => 'NOMBRES' ],
            [ 'action' => 'print', 'text' => 'APELLIDOS' ],
            [ 'action' => 'print', 'text' => 'SEXO' ],
            [ 'action' => 'print', 'text' => 'TELEFONO' ],
            [ 'action' => 'print', 'text' => 'TIPO DOC.' ],
            [ 'action' => 'print', 'text' => 'NUMERO DOC.' ],
            [ 'action' => 'print', 'text' => 'PAIS' ],
        ]];
        foreach ($passengers as $key => $pax) {
            $turn[] = [
                [ 'action' => 'print', 'text' => ($key + 1) . '' ],
                [ 'action' => ''],
                [ 'action' => 'print', 'text' => $pax['Passenger_Name'] ],
                [ 'action' => 'print', 'text' => $pax['Passenger_LastName'] ],
                [ 'action' => 'print', 'text' => $pax['Passenger_Gender'] ],
                [ 'action' => 'print', 'text' => 'phone' ],
                [ 'action' => 'print', 'text' => 'doctype' ],
                [ 'action' => 'print', 'text' => 'docnumber' ],
                [ 'action' => 'print', 'text' => 'country' ],
            ];
        }
        
        return $turn;
    }

}