<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if (mysqli_connect_errno()) {
        exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();

    function trova_dati_attivita() {
        GLOBAL $db_connection;
        if($_SESSION["login"] && !empty($_POST["nome"])){
            $nome = $_POST["nome"];
            $email = $_SESSION["email"];

            $query = "SELECT UnitaMisura, Calorie FROM Esercizi WHERE Nome = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $nome);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $unita, $calorie);

            $contatore = 0;
            $response = array();
            while(mysqli_stmt_fetch($statement)){
                $response[$contatore]["unita"] = $unita;
                $response[$contatore]["calorie"] = $calorie;
                $contatore++;
            }
            $response["length"] = $contatore;

            mysqli_stmt_close($statement);
            $query = "SELECT Peso FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $peso);
            
            if(mysqli_stmt_fetch($statement)){
                for($i = 0; $i < $contatore; $i++) 
                    $response[$i]["calorie"] = floatval($response[$i]["calorie"] * $peso / 100);
            }
            else
                $response["length"] = -1;            
        }
        else
            $response["length"] = -2;
        
        echo json_encode($response);
    }

    trova_dati_attivita();
    mysqli_close($db_connection);
?>