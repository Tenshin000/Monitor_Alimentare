<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();
    
    function eliminazione_pasto(){
        GLOBAL $db_connection;

        $cibo = $_POST["ID"];
        $quantita =  $_POST["quantita"];
        $catalogazione = $_POST["catalogazione"];
        $email = $_SESSION["email"];
        $data = $_SESSION["data"];
        $orario = $_SESSION["orario"];

        
        $query = "DELETE FROM Pasto WHERE Utente = ? AND Cibo = ? AND Data = ? AND Catalogazione = ? AND Orario = ? AND Quantita = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "sisssi", $email, $cibo, $data, $catalogazione, $orario, $quantita);

        if(mysqli_stmt_execute($statement)){
            $response = [
                'result' => true,
                'msg' => 'Cancellazione avvenuta con successo!'
            ];
            echo json_encode($response);
            return true;           
        }
        else{
            $response = [
                'result' => false,
                'msg' => 'Cancellazione fallita!'
            ];
            echo json_encode($response);
            return false;
        }
    }
    
    eliminazione_pasto();
    mysqli_close($db_connection);
?>