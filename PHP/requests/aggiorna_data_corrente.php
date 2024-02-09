<?php
    session_start();

    if($_SESSION["login"]){
        $response = [
            'result' => true,
            'data'=> $_SESSION["data"]
        ];

        echo json_encode($response);
    }
    else{
        $response = [
            'result' => false
        ];

        echo json_encode($response);
    }
?>