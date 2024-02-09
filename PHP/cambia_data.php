<?php
    session_start();

    if($_SESSION["login"] && !empty($_POST["data"])){
        $_SESSION["data"] = $_POST["data"];
        
        $response = [
            'result' => true
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