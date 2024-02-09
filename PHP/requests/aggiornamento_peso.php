<?php
    require '../utility.php';

    session_start();
    $peso = $_POST["peso"];    

    $risultato = aggiornamento_peso($peso);
    $response = [
        'result' => $risultato
    ];
    echo json_encode($response);
?>