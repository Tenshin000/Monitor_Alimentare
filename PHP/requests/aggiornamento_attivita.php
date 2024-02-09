<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();
    
    function aggiornamento_attivita(){
        GLOBAL $db_connection;
        
        if(!$_SESSION["login"]){
           exit(); 
        }
        
        $esercizio = $_POST["nome"];
        $unita = $_POST["unita"];
        $quantita = $_POST["quantita"];
        $email = $_SESSION["email"];
        $data = $_SESSION["data"];

        $query = "SELECT * FROM Attivita WHERE Utente = ? AND Esercizio = ? AND UnitaMisura = ? AND Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ssss", $email, $esercizio, $unita, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_store_result($statement);

        if(mysqli_stmt_num_rows($statement) == 0){
            mysqli_stmt_close($statement);
            $query = "INSERT INTO Attivita(Utente, Esercizio, UnitaMisura, Data, Quantita) VALUES(?, ?, ?, ?, ?)";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ssssd", $email, $esercizio, $unita, $data, $quantita);
            
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_close($statement);

                $query = "SELECT (A.Quantita * (E.Calorie * U.Peso / 100)) AS Calorie
                          FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                                          INNER JOIN Utente U ON A.Utente = U.Email 
                          WHERE A.Utente = ? AND A.Esercizio = ? AND A.UnitaMisura = ? AND A.Data = ?";
                $statement = mysqli_prepare($db_connection, $query);
                mysqli_stmt_bind_param($statement, "ssss", $email, $esercizio, $unita, $data);
                mysqli_stmt_execute($statement);    
                mysqli_stmt_bind_result($statement, $calorie);
                mysqli_stmt_fetch($statement);

                $response = [
                    'result' => true,
                    'calorie' => $calorie
                ];
            }
            else{
                $response = [
                    'result' => false
                ];
            }
        }
        else{
            mysqli_stmt_close($statement);

            if($quantita > 0){
                $query = "UPDATE Attivita SET Quantita = ? WHERE Utente = ? AND Esercizio = ? AND UnitaMisura = ? AND Data = ?";
                $statement = mysqli_prepare($db_connection, $query);
                mysqli_stmt_bind_param($statement, "dssss", $quantita, $email, $esercizio, $unita, $data);
                if(mysqli_stmt_execute($statement)){
                    mysqli_stmt_close($statement);

                    $query = "SELECT (A.Quantita * (E.Calorie * U.Peso / 100)) AS Calorie
                              FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                                              INNER JOIN Utente U ON A.Utente = U.Email 
                              WHERE A.Utente = ? AND A.Esercizio = ? AND A.UnitaMisura = ? AND A.Data = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "ssss", $email, $esercizio, $unita, $data);
                    mysqli_stmt_execute($statement);    
                    mysqli_stmt_bind_result($statement, $calorie);
                    mysqli_stmt_fetch($statement);

                    $response = [
                        'result' => true,
                        'calorie' => $calorie
                    ];
                }
                else{
                    $response = [
                        'result' => false
                    ];
                }
            }
            else{
                $query = "DELETE FROM Attivita WHERE Utente = ? AND Esercizio = ? AND UnitaMisura = ? AND Data = ?";
                $statement = mysqli_prepare($db_connection, $query);
                mysqli_stmt_bind_param($statement, "ssss", $email, $esercizio, $unita, $data);
                if(mysqli_stmt_execute($statement)){
                    $response = [
                        'result' => true,
                        'calorie' => 0
                    ];
                }
                else{
                    $response = [
                        'result' => false
                    ];
                }
            }
        }

        echo json_encode($response);
    }

    aggiornamento_attivita();
    mysqli_close($db_connection);
?>