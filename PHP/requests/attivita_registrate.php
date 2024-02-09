<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();

    function attivita_registrate(){
        GLOBAL $db_connection;

        $email = $_SESSION["email"];
        $data = $_SESSION["data"];

        $query = "SELECT A.Esercizio, A.UnitaMisura, A.Quantita, (A.Quantita * (E.Calorie * U.Peso / 100)) AS Calorie
                  FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                                  INNER JOIN Utente U ON A.Utente = U.Email 
                  WHERE A.Utente = ? AND A.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $esercizio, $unita, $quantita, $calorie);

        $response = array();
        $contatore = 0;
        while(mysqli_stmt_fetch($statement)){
            $response[$contatore]["esercizio"] = $esercizio;
            $response[$contatore]["unita"] = $unita;
            $response[$contatore]["quantita"] = $quantita;
            $response[$contatore]["calorie"] = $calorie;
            $contatore++;
        }
        $response["length"] = $contatore;
        echo json_encode($response);
    }

    attivita_registrate();
    mysqli_close($db_connection);
?>