<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();

    function recupera_fabbisogno(){
        GLOBAL $db_connection;

        if($_SESSION["login"] && !empty($_SESSION["orario"])){
            $email = $_SESSION["email"];

            $query = "SELECT FabbisognoCalorico FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_bind_result($statement, $fabbisogno);
                if(mysqli_stmt_fetch($statement)){
                    if($_SESSION["orario"] == "Colazione")
                        $fabbisogno = intval(30 * $fabbisogno / 100);
                    else{
                        if($_SESSION["orario"] == "Pranzo")
                            $fabbisogno = intval(40 * $fabbisogno / 100);
                        else{
                            if($_SESSION["orario"] == "Cena")
                                $fabbisogno = intval(25 * $fabbisogno / 100);
                            else if($_SESSION["orario"] == "Spuntini")
                                $fabbisogno = intval(5 * $fabbisogno / 100);
                        }
                    }

                    $response = [
                        'result' => true,
                        'fabbisogno' => $fabbisogno
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

    recupera_fabbisogno();
    mysqli_close($db_connection);
?>