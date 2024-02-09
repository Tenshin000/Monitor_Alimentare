<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();

    function numero_giorni_mese($mese, $anno) {
        // Mi assicuro che il mese sia un numero compreso tra 1 (Gennaio) e 12 (Dicembre)
        $mese = max(1, min(12, $mese));
    
        $giorni = cal_days_in_month(CAL_GREGORIAN, $mese, $anno);
    
        return $giorni;
    }

    function recupera_dati_utente(){
        GLOBAL $db_connection;

        if(!$_SESSION["login"])
            exit();

        $email = $_SESSION["email"];
        $data = $_SESSION["data"];  

        $istanze = 0;

        if($_POST["valore"] == 2){
            // Caso Settimanale
            $query = "SELECT DAYOFWEEK(Pa.Data) AS Giorno, ROUND(IFNULL(SUM(Po.Calorie * Pa.Quantita), 0), 2) AS Calorie, ROUND(IFNULL(SUM(Po.Carboidrati * Pa.Quantita), 0), 2) AS Carboidrati,
                             ROUND(IFNULL(SUM(Po.Grassi * Pa.Quantita), 0), 2) AS Grassi, ROUND(IFNULL(SUM(Po.Proteine * Pa.Quantita), 0), 2) AS Proteine,
                             IFNULL(B.Numero * U.DimensioneBicchiere, 0) AS Acqua
                      FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione)
                                    INNER JOIN Bicchieri B ON (Pa.Utente = B.Utente AND Pa.Data = B.Data)
                                    INNER JOIN Utente U ON Pa.Utente = U.Email
                      WHERE Pa.Utente = ? AND WEEK(Pa.Data) = WEEK(?)
                      GROUP BY DAYOFWEEK(Pa.Data)
                      ORDER BY DAYOFWEEK(Pa.Data)";
            $istanze = 7;
        }
        else{
            if($_POST["valore"] == 3){
                // Caso Mensile

                $query = "SELECT DAY(Pa.Data) AS Giorno, ROUND(IFNULL(SUM(Po.Calorie * Pa.Quantita), 0), 2) AS Calorie, ROUND(IFNULL(SUM(Po.Carboidrati * Pa.Quantita), 0), 2) AS Carboidrati,
                                 ROUND(IFNULL(SUM(Po.Grassi * Pa.Quantita), 0), 2) AS Grassi, ROUND(IFNULL(SUM(Po.Proteine * Pa.Quantita), 0), 2) AS Proteine,
                                 IFNULL(B.Numero * U.DimensioneBicchiere, 0) AS Acqua
                          FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione)
                                        INNER JOIN Bicchieri B ON (Pa.Utente = B.Utente AND Pa.Data = B.Data)
                                        INNER JOIN Utente U ON Pa.Utente = U.Email
                          WHERE Pa.Utente = ? AND MONTH(Pa.Data) = MONTH(?)
                          GROUP BY DAY(Pa.Data)
                          ORDER BY DAY(Pa.Data)";

                $copia_data = new DateTime($data);
                $mese = $copia_data->format('m'); // Prende il mese
                $anno = $copia_data->format('Y'); // Prende l'anno
                $istanze = numero_giorni_mese($mese, $anno);
            }
            else{
                if($_POST["valore"] == 4){
                    // Caso Annuale
                    $query = "SELECT MONTH(Pa.Data) AS Mese, ROUND(IFNULL(SUM(Po.Calorie * Pa.Quantita), 0), 2) AS Calorie, ROUND(IFNULL(SUM(Po.Carboidrati * Pa.Quantita), 0), 2) AS Carboidrati,
                                     ROUND(IFNULL(SUM(Po.Grassi * Pa.Quantita), 0), 2) AS Grassi, ROUND(IFNULL(SUM(Po.Proteine * Pa.Quantita), 0), 2) AS Proteine,
                                     IFNULL(SUM(B.Numero * U.DimensioneBicchiere), 0) AS Acqua
                              FROM Pasto Pa INNER JOIN Porzione Po ON (Pa.Cibo = Po.Cibo AND Pa.Catalogazione = Po.Catalogazione)
                                            INNER JOIN Bicchieri B ON (Pa.Utente = B.Utente AND Pa.Data = B.Data)
                                            INNER JOIN Utente U ON Pa.Utente = U.Email
                              WHERE Pa.Utente = ? AND YEAR(Pa.Data) = YEAR(?)
                              GROUP BY MONTH(Pa.Data)
                              ORDER BY MONTH(Pa.Data)";
                    $istanze = 12;
                }
                else
                    exit();
            }        
        }

        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $giorno_mese, $calorie, $carboidrati, $grassi, $proteine, $acqua);

        $response = array();

        while(mysqli_stmt_fetch($statement)){
            $response[$giorno_mese]["calorie"] = $calorie;
            $response[$giorno_mese]["carboidrati"] = $carboidrati;
            $response[$giorno_mese]["grassi"] = $grassi;
            $response[$giorno_mese]["proteine"] = $proteine;
            $response[$giorno_mese]["acqua"] = $acqua;
        }

        for($i=1; $i <= $istanze; $i++){
            if(empty($response[$i])){
                $response[$i]["calorie"] = 0;
                $response[$i]["carboidrati"] = 0;
                $response[$i]["grassi"] = 0;
                $response[$i]["proteine"] = 0;
                $response[$i]["acqua"] = 0;
            }
        }

        $response["length"] = $istanze;

        echo json_encode($response);
    }

    recupera_dati_utente();
    mysqli_close($db_connection);
?>