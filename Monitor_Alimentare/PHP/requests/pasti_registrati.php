<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    session_start();

    function pasti_registrati(){
        GLOBAL $db_connection;

        $email = $_SESSION["email"];
        $data = $_SESSION["data"];
        $orario = $_SESSION["orario"];

        $query = "SELECT DISTINCT C.ID, C.Nome, Pa.Quantita, Pa.Catalogazione, (Pa.Quantita * Po.Calorie) AS Calorie FROM Pasto Pa INNER JOIN Cibo C ON Pa.Cibo = C.ID INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione) WHERE Pa.Utente = ? AND Pa.Data = ? AND Pa.Orario = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "sss", $email, $data, $orario);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $id, $nome, $quantita, $catalogazione, $calorie);

        $response = array();
        $contatore = 0;
        while(mysqli_stmt_fetch($statement)){
            $response[$contatore]["ID"] = $id;
            $response[$contatore]["nome"] = $nome;
            $response[$contatore]["catalogazione"] = $catalogazione;
            $response[$contatore]["quantita"] = $quantita;
            $response[$contatore]["calorie"] = $calorie;
            $contatore++;
        }
        $response["length"] = $contatore;

        mysqli_stmt_close($statement);
        $query = "SELECT IFNULL(SUM(A.Quantita * (E.Calorie * U.Peso / 100)), 0) AS Calorie
                  FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                                  INNER JOIN Utente U ON A.Utente = U.Email
                  WHERE A.Utente = ? AND A.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $result);
        mysqli_stmt_fetch($statement);

        $calorie_perse = 0;
        if($orario == "Colazione")
            $calorie_perse = $result * 30 / 100; 
        else{
            if($orario == "Pranzo")
                $calorie_perse = $result * 40 / 100; 
            else{
                if($orario == "Cena")
                    $calorie_perse = $result * 25 / 100; 
                else if($orario == "Spuntini")
                    $calorie_perse = $result * 5 / 100; 
            }
        }
        $response["calorie perse"] = $calorie_perse; 

        echo json_encode($response);
    }
    
    pasti_registrati();
    mysqli_close($db_connection);
?>