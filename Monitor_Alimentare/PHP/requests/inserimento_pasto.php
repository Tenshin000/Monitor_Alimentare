<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();
    
    function inserimento_pasto(){
        GLOBAL $db_connection;
        
        if(empty($_SESSION["email"]) && empty($_SESSION["orario"])){
           exit(); 
        }
        
        $email = $_SESSION["email"];
        $cibo = $_POST["cibo"];
        $data = $_SESSION["data"];
        $catalogazione = $_POST["catalogazione"];
        $orario = $_SESSION["orario"];
        $quantita = $_POST["quantita"];

        $query = "INSERT INTO Pasto(Utente, Cibo, Data, Catalogazione, Orario, Quantita) VALUES (? ,? ,? ,? ,? ,?)";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "sisssi", $email, $cibo, $data, $catalogazione, $orario, $quantita);
        
        if(mysqli_stmt_execute($statement)){
            $response = [
                'result' => true,
                'msg' => 'Inserimento avvenuto con successo!'
            ];
            echo json_encode($response);
            return true;           
        }
        else{
            mysqli_stmt_close($statement);
            $query = "UPDATE Pasto SET Quantita = Quantita + ? WHERE Utente = ? AND Cibo = ? AND Data = ? AND Catalogazione = ? AND Orario = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "isisss", $quantita, $email, $cibo, $data, $catalogazione, $orario);

            if(mysqli_stmt_execute($statement)){
                $response = [
                    'result' => true,
                    'msg' => 'Inserimento avvenuto con successo!'
                ];
                echo json_encode($response);
                return true;           
            }
            else{
                $response = [
                    'result' => false,
                    'msg' => 'Inserimento fallito!'
                ];
                echo json_encode($response);
                return false;
            }
        }
    }

    inserimento_pasto();
    mysqli_close($db_connection);
?>