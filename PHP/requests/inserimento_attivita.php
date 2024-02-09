<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();
    
    function inserimento_attivita(){
        GLOBAL $db_connection;
        
        if(!$_SESSION["login"]){
           exit(); 
        }
        
        $email = $_SESSION["email"];
        $esercizio = $_POST["esercizio"];
        $unita = $_POST["unita"];
        $data = $_SESSION["data"];
        $quantita = $_POST["quantita"];

        $query = "INSERT INTO Attivita(Utente, Esercizio, UnitaMisura, Data, Quantita) VALUES (?, ?, ?, ?, ?)";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ssssd", $email, $esercizio, $unita, $data, $quantita);
        
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
            $query = "UPDATE Attivita SET Quantita = Quantita + ? WHERE Utente = ? AND Esercizio = ? AND UnitaMisura = ? AND Data = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "dssss", $quantita, $email, $esercizio, $unita, $data);

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

    inserimento_attivita();
    mysqli_close($db_connection);
?>