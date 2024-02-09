<?php

    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();
    
    function informazioni_account(){
        GLOBAL $db_connection;

        if($_SESSION["login"]){
            $email = $_SESSION["email"];

            $query = "SELECT Nome, Cognome, Sesso, DataNascita, Statura, Peso, LivelloAttivita, Dieta, DimensioneBicchiere FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_bind_result($statement, $nome, $cognome, $sesso, $nascita, $statura, $peso, $attivita, $dieta, $bicchiere);
                if(mysqli_stmt_fetch($statement)){
                    $response = [
                        'result' => true,
                        'Nome' => $nome,
                        'Cognome' => $cognome,
                        'Sesso' => $sesso,
                        'Data di Nascita' => $nascita,
                        'Statura' => $statura,
                        'Peso' => $peso,
                        'Attività' => $attivita,
                        'Preferenza Dietetica' => $dieta,
                        'Dimensione del Bicchiere' => $bicchiere
                    ];
                }
                else{
                    $response = [
                        'result' => false
                    ];
                }
            }
            else{
                $response = [
                    'result' => false
                ];
            }
        }
        else{
            $response = [
                'result' => false
            ];
        }

        echo json_encode($response);
    }

    informazioni_account();
    mysqli_close($db_connection);
?>