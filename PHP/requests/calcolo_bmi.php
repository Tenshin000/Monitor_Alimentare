<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();

    function calcolo_bmi(){
        GLOBAL $db_connection;

        if(!$_SESSION["login"])
            exit();

        $email = $_SESSION["email"];

        $query = "SELECT Statura, Peso FROM Utente WHERE Email = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "s", $email);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $altezza, $massa);

        if(mysqli_stmt_fetch($statement)){
            $bmi = $massa / (($altezza / 100) * ($altezza / 100));
            $response = [
                'result' => true,
                'bmi' => $bmi
            ];
        }
        else{
            $response = [
                'result' => false
            ];
        }
        echo json_encode($response);
    }


    calcolo_bmi();
    mysqli_close($db_connection);
?>