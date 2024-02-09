<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
        exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();

    function inizializza_bicchieri(){
        GLOBAL $db_connection;

        $query = "SELECT B.Numero, U.DimensioneBicchiere FROM Bicchieri B INNER JOIN Utente U ON B.Utente = U.Email WHERE B.Utente = ? AND B.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $_SESSION["email"], $_SESSION["data"]);
        if(mysqli_stmt_execute($statement)){
            mysqli_stmt_store_result($statement);
            if (mysqli_stmt_num_rows($statement) > 0) {
                mysqli_stmt_bind_result($statement, $numero, $dimensione);
                mysqli_stmt_fetch($statement);
                $bevuti = $numero;
                if($numero < 8)
                    $numero = 8;
            } else {
                $bevuti = 0;
                $numero = 8;
                $dimensione = 0.25;
            }

            $response = [
                'totali' => $numero,
                'bevuti' => $bevuti,
                'dimensione' => $dimensione
            ];
        } else {
            $response = [
                'totali' => 8,
                'bevuti' => 0,
                'dimensione' => 0.25
            ];
        }

        echo json_encode($response);
    }

    inizializza_bicchieri();
    mysqli_close($db_connection);
?>