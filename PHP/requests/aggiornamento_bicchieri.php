<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();

    function aggiornamento_bicchieri(){
        GLOBAL $db_connection;
        
        $numero = $_POST["numero"];
        $email = $_SESSION["email"];
        $data = $_SESSION["data"];
        
        $query = "UPDATE Bicchieri SET Numero = ? WHERE Utente = ? AND Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "iss", $numero, $email, $data);
        $risultato = mysqli_stmt_execute($statement); 
        
        $response = [
            'result' => $risultato
        ];
        echo json_encode($response);
    }

    aggiornamento_bicchieri();
    mysqli_close($db_connection);
?>