<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
        exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();

    function inizializza_peso(){
        GLOBAL $db_connection;

        $query = "SELECT Peso FROM Utente WHERE Email = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "s", $_SESSION["email"]);
        if(mysqli_stmt_execute($statement)){
            mysqli_stmt_bind_result($statement, $peso);
            $response["result"] = true;
            while(mysqli_stmt_fetch($statement))
                $response["peso"] = floatval($peso); 
        }
        else
            $response["result"] = false;
        
        echo json_encode($response);
    }

    inizializza_peso();
    mysqli_close($db_connection);
?>