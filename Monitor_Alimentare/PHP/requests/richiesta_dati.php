<?php
    require '../utility.php';

    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();

    function trasferimento_dati_barra(){
        GLOBAL $db_connection;

        if($_SESSION["login"]){
            $email = $_SESSION["email"];
            $data = $_SESSION["data"];

            // Parte Relativa alle Calorie prese dal cibo
            $query = "SELECT IFNULL((SUM(Pa.Quantita * Po.Calorie)), 0) AS Calorie FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione) WHERE Pa.Utente = ? AND Pa.Data = ? ";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $email, $data);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $result);
            $calorie_prese = 0;
            while(mysqli_stmt_fetch($statement)){
               $calorie_prese = $result;
            } 

            mysqli_stmt_close($statement);
            // Parte Relativa alle Calorie perse per le attività
            $query = "SELECT IFNULL((SUM(A.Quantita * (E.Calorie * U.Peso / 100))), 0) AS Calorie FROM Attivita A INNER JOIN Esercizi E ON (A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura) INNER JOIN Utente U ON A.Utente = U.Email WHERE A.Utente = ? AND A.Data = ? ";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $email, $data);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $result);
            $calorie_perse = 0;
            while(mysqli_stmt_fetch($statement)){
               $calorie_perse = $result;
            } 

            mysqli_stmt_close($statement);
            // Recupero il fabbisogno calorico
            $query = "SELECT FabbisognoCalorico FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $result);
            while(mysqli_stmt_fetch($statement)){
                $fabbisogno_calorico = $result;
            } 

            mysqli_stmt_close($statement);
            $query = "SELECT IFNULL((SUM(Pa.Quantita * Po.Calorie)), 0) AS Calorie FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione) WHERE Pa.Utente = ? AND Pa.Data = ? AND Pa.Orario = 'Colazione'";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $email, $data);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $result);
            $calorie_colazione = 0;
            while(mysqli_stmt_fetch($statement)){
               $calorie_colazione = $result;
            } 

            mysqli_stmt_close($statement);
            $query = "SELECT IFNULL((SUM(Pa.Quantita * Po.Calorie)), 0) AS Calorie FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione) WHERE Pa.Utente = ? AND Pa.Data = ? AND Pa.Orario = 'Pranzo'";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $email, $data);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $result);
            $calorie_pranzo = 0;
            while(mysqli_stmt_fetch($statement)){
               $calorie_pranzo = $result;
            } 

            mysqli_stmt_close($statement);
            $query = "SELECT IFNULL((SUM(Pa.Quantita * Po.Calorie)), 0) AS Calorie FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione) WHERE Pa.Utente = ? AND Pa.Data = ? AND Pa.Orario = 'Cena'";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $email, $data);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $result);
            $calorie_cena = 0;
            while(mysqli_stmt_fetch($statement)){
               $calorie_cena = $result;
            } 

            mysqli_stmt_close($statement);
            $query = "SELECT IFNULL((SUM(Pa.Quantita * Po.Calorie)), 0) AS Calorie FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione) WHERE Pa.Utente = ? AND Pa.Data = ? AND Pa.Orario = 'Spuntini'";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $email, $data);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $result);
            $calorie_spuntini = 0;
            while(mysqli_stmt_fetch($statement)){
               $calorie_spuntini = $result;
            } 

            mysqli_stmt_close($statement);
            $query = "SELECT IFNULL((SUM(Pa.Quantita * Po.Carboidrati)), 0) AS Carboidrati, IFNULL((SUM(Pa.Quantita * Po.Grassi)), 0) AS Grassi, IFNULL((SUM(Pa.Quantita * Po.Proteine)), 0) AS Proteine FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione) WHERE Pa.Utente = ? AND Pa.Data = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $email, $data);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $carboidrati, $grassi, $proteine);
            $c = 0;
            $g = 0;
            $p = 0;
            while(mysqli_stmt_fetch($statement)){
               $c = $carboidrati;
               $g = $grassi;
               $p = $proteine; 
            } 

            if(empty($calorie_prese))
                $calorie_prese = 0;
            
            if(empty($calorie_perse))
                $calorie_perse = 0;

            if(!empty($fabbisogno_calorico)){
                $response = [
                        'result' => true,
                        'fabbisogno' => $fabbisogno_calorico,
                        'guadagnate' => $calorie_prese,
                        'perse' => $calorie_perse,
                        'colazione' => $calorie_colazione,
                        'pranzo' => $calorie_pranzo,
                        'cena' => $calorie_cena,
                        'spuntini' => $calorie_spuntini,
                        'carboidrati' => $c,
                        'grassi' => $g,
                        'proteine' => $p
                    ];
            }   
            else{
                $response = [
                    'result' => false
                ];
            }
            echo json_encode($response);
            return $response["result"];
        }
        else{
            $response = [
                'result' => false
            ];
            echo json_encode($response);
            return false;
        }
    }

    trasferimento_dati_barra();
    mysqli_close($db_connection);
?>