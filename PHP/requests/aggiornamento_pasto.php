<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();
    
    function aggiornamento_pasto(){
        GLOBAL $db_connection;

        $cibo = $_POST["ID"];
        $quantita =  $_POST["quantita"];
        $catalogazione = $_POST["catalogazione"];
        $email = $_SESSION["email"];
        $data = $_SESSION["data"];
        $orario = $_SESSION["orario"];

        if($quantita <= 0){
            $query = "DELETE FROM Pasto WHERE Utente = ? AND Cibo = ? AND Data = ? AND Catalogazione = ? AND Orario = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "sisss", $email, $cibo, $data, $catalogazione, $orario);
        }
        else{
            $query = "UPDATE Pasto SET Quantita = ? WHERE Utente = ? AND Cibo = ? AND Data = ? AND Catalogazione = ? AND Orario = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "isisss", $quantita, $email, $cibo, $data, $catalogazione, $orario);
        }
        
        if(mysqli_stmt_execute($statement)){
            mysqli_stmt_close($statement);
            $query = "SELECT Calorie FROM Porzione WHERE Cibo = ? AND Catalogazione = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "is", $cibo, $catalogazione);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $calorie);

            if(mysqli_stmt_fetch($statement)){
                $response = [
                    'result' => true,
                    'msg' => 'Aggiornamento avvenuto con successo!', 
                    'calorie' => $calorie
                ];
            }
            else{
                $response = [
                    'result' => true,
                    'msg' => 'Aggiornamento avvenuto con successo!', 
                    'calorie' => 0
                ];
            }
            echo json_encode($response);
            return true;           
        }
        else{
            $response = [
                'result' => false,
                'msg' => 'Aggiornamento fallito!'
            ];
            echo json_encode($response);
            return false;
        }
    }
    
    aggiornamento_pasto();
    mysqli_close($db_connection);
?>