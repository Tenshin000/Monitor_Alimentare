<?php
    require '../utility.php';
    
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();
    
    function aggiorna_account(){
        GLOBAL $db_connection;

        if($_SESSION["login"]){
            $email = $_SESSION["email"];
            $attributo = $_POST["attribute"];
            $valore = $_POST["value"];

            if($attributo == "Nome"){
                if(controllo_nome($valore)){
                    if(!controllo_nome($valore))
                        $risultato = false;
                    else{
                        $query = "UPDATE Utente SET Nome = ? WHERE Email = ?";
                        $statement = mysqli_prepare($db_connection, $query);
                        mysqli_stmt_bind_param($statement, "ss", $valore, $email);
                        $risultato = mysqli_stmt_execute($statement); 
                    }
                }
                else
                    $risultato = false;
            }

            if($attributo == "Cognome"){
                if(controllo_cognome($valore)){
                    if(!controllo_cognome($valore))
                        $risultato = false;
                    else{
                        $query = "UPDATE Utente SET Cognome = ? WHERE Email = ?";
                        $statement = mysqli_prepare($db_connection, $query);
                        mysqli_stmt_bind_param($statement, "ss", $valore, $email);
                        $risultato = mysqli_stmt_execute($statement); 
                    }
                }
                else
                    $risultato = false;
            }

            if($attributo == "Sesso")
                $risultato = aggiornamento_sesso($valore);

            if($attributo == "DataDiNascita"){
                $eta = calcolo_eta($valore);
                if($eta < 0 || $eta > 121)
                    $risultato = false;
                else
                    $risultato = aggiornamento_nascita($valore);
            }

            if($attributo == "Statura"){
                if(!controllo_statura($valore))
                    $risultato = false;
                else{
                    $query = "UPDATE Utente SET Statura = ? WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "is", $valore, $email);
                    $risultato = mysqli_stmt_execute($statement); 
                }
            }

            if($attributo == "Peso"){
                if(controllo_peso($valore))
                    $risultato = aggiornamento_peso($valore);
                else
                    $risultato = false;
            }
                
            if($attributo == "Attività"){
                if(!controllo_attivita($valore))
                    $risultato = false;
                else
                    $risultato = aggiornamento_attivita($valore); 
            }
                      

            if($attributo == "PreferenzaDietetica"){
                if(!controllo_dieta($valore))
                    $risultato = false;
                else
                    $query = "UPDATE Utente SET Dieta = ? WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "ss", $valore, $email);
                    $risultato = mysqli_stmt_execute($statement); 
            }

            if($attributo == "DimensioneDelBicchiere"){
                if($valore <= 0)
                    $risultato = false;
                else{
                    $valore = number_format($valore, 2);
                    $query = "UPDATE Utente SET DimensioneBicchiere = ? WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "ds", $valore, $email);
                    $risultato = mysqli_stmt_execute($statement);
                }
            }

            $response = [
                'result' => $risultato
            ];
        }
        else{
            $response = [
                'result' => false
            ];
        }

        echo json_encode($response);
    }

    aggiorna_account();
    mysqli_close($db_connection);
?>