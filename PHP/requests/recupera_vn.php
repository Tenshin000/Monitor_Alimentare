<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }

    session_start();

    function recupera_vitamine_minerali(){
        GLOBAL $db_connection;

        if(!$_SESSION["login"])
            exit();
        
        $email = $_SESSION["email"];
        $data = $_SESSION["data"];  
        if($_POST["valore"] == 1){
            // Caso Giornaliero
            $query = "SELECT C.VitaminaA, C.VitaminaB1, C.VitaminaB2, C.VitaminaB3, C.VitaminaB5, C.VitaminaB6, C.VitaminaB7, 
                             C.VitaminaB11, C.VitaminaB12, C.VitaminaC, C.VitaminaD, C.VitaminaE, C.VitaminaK, C.Calcio, C.Fosforo, 
                             C.Magnesio, C.Sodio, C.Potassio, C.Zolfo, C.Cromo, C.Ferro, C.Fluoro, C.Iodio, C.Manganese, C.Rame, 
                             C.Selenio, C.Zinco
                      FROM Cibo C INNER JOIN Pasto P ON C.ID = P.Cibo WHERE P.Utente = ? AND P.Data = ?";
        }
        else{
            if($_POST["valore"] == 2){
                // Caso Settimanale
                $query = "SELECT C.VitaminaA, C.VitaminaB1, C.VitaminaB2, C.VitaminaB3, C.VitaminaB5, C.VitaminaB6, C.VitaminaB7, 
                                 C.VitaminaB11, C.VitaminaB12, C.VitaminaC, C.VitaminaD, C.VitaminaE, C.VitaminaK, C.Calcio, C.Fosforo, 
                                 C.Magnesio, C.Sodio, C.Potassio, C.Zolfo, C.Cromo, C.Ferro, C.Fluoro, C.Iodio, C.Manganese, C.Rame, 
                                 C.Selenio, C.Zinco
                          FROM Cibo C INNER JOIN Pasto P ON C.ID = P.Cibo WHERE P.Utente = ? AND WEEK(P.Data) = WEEK(?)";
            }
            else{
                if($_POST["valore"] == 3){
                    // Caso Mensile
                    $query = "SELECT C.VitaminaA, C.VitaminaB1, C.VitaminaB2, C.VitaminaB3, C.VitaminaB5, C.VitaminaB6, C.VitaminaB7, 
                                 C.VitaminaB11, C.VitaminaB12, C.VitaminaC, C.VitaminaD, C.VitaminaE, C.VitaminaK, C.Calcio, C.Fosforo, 
                                 C.Magnesio, C.Sodio, C.Potassio, C.Zolfo, C.Cromo, C.Ferro, C.Fluoro, C.Iodio, C.Manganese, C.Rame, 
                                 C.Selenio, C.Zinco
                              FROM Cibo C INNER JOIN Pasto P ON C.ID = P.Cibo WHERE P.Utente = ? AND MONTH(P.Data) = MONTH(?)";
                }
                else{
                    if($_POST["valore"] == 4){
                        // Caso Annuale
                        $query = "SELECT C.VitaminaA, C.VitaminaB1, C.VitaminaB2, C.VitaminaB3, C.VitaminaB5, C.VitaminaB6, C.VitaminaB7, 
                                 C.VitaminaB11, C.VitaminaB12, C.VitaminaC, C.VitaminaD, C.VitaminaE, C.VitaminaK, C.Calcio, C.Fosforo, 
                                 C.Magnesio, C.Sodio, C.Potassio, C.Zolfo, C.Cromo, C.Ferro, C.Fluoro, C.Iodio, C.Manganese, C.Rame, 
                                 C.Selenio, C.Zinco
                              FROM Cibo C INNER JOIN Pasto P ON C.ID = P.Cibo WHERE P.Utente = ? AND YEAR(P.Data) = YEAR(?)";
                    }
                    else
                        exit();
                }
            }
        }
        
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $vitaminaA, $vitaminaB1, $vitaminaB2, $vitaminaB3, $vitaminaB5, $vitaminaB6, $vitaminaB7, $vitaminaB11, $vitaminaB12, 
                                            $vitaminaC, $vitaminaD, $vitaminaE, $vitaminaK, $calcio, $fosforo, $magnesio, $sodio, $potassio, $zolfo, $cromo, $ferro, 
                                            $fluoro, $iodio, $manganese, $rame, $selenio, $zinco);
        
        $response = array();

        $response["Vitamina A"] = FALSE;
        $response["Vitamina B1"] = FALSE;
        $response["Vitamina B2"] = FALSE;
        $response["Vitamina B3"] = FALSE;
        $response["Vitamina B5"] = FALSE; 
        $response["Vitamina B6"] = FALSE;
        $response["Vitamina B7"] = FALSE;
        $response["Vitamina B11"] = FALSE;
        $response["Vitamina B12"] = FALSE;
        $response["Vitamina C"] = FALSE;
        $response["Vitamina D"] = FALSE;
        $response["Vitamina E"] = FALSE;
        $response["Vitamina K"] = FALSE;
        $response["Calcio"] = FALSE;
        $response["Fosforo"] = FALSE; 
        $response["Magnesio"] = FALSE; 
        $response["Sodio"] = FALSE; 
        $response["Potassio"] = FALSE;
        $response["Zolfo"] = FALSE;
        $response["Cromo"] = FALSE;
        $response["Ferro"] = FALSE; 
        $response["Fluoro"] = FALSE;
        $response["Iodio"] = FALSE;
        $response["Manganese"] = FALSE;
        $response["Rame"] = FALSE; 
        $response["Selenio"] = FALSE;
        $response["Zinco"] = FALSE;

        while(mysqli_stmt_fetch($statement)){
            $response["Vitamina A"] = ($vitaminaA) ? TRUE : $response["Vitamina A"];
            $response["Vitamina B1"] = ($vitaminaB1) ? TRUE : $response["Vitamina B1"];
            $response["Vitamina B2"] = ($vitaminaB2) ? TRUE : $response["Vitamina B2"];
            $response["Vitamina B3"] = ($vitaminaB3) ? TRUE : $response["Vitamina B3"];
            $response["Vitamina B5"] = ($vitaminaB5) ? TRUE : $response["Vitamina B5"]; 
            $response["Vitamina B6"] = ($vitaminaB6) ? TRUE : $response["Vitamina B6"];
            $response["Vitamina B7"] = ($vitaminaB7) ? TRUE : $response["Vitamina B7"];
            $response["Vitamina B11"] = ($vitaminaB11) ? TRUE : $response["Vitamina B11"];
            $response["Vitamina B12"] = ($vitaminaB12) ? TRUE : $response["Vitamina B12"];
            $response["Vitamina C"] = ($vitaminaC) ? TRUE : $response["Vitamina C"];
            $response["Vitamina D"] = ($vitaminaD) ? TRUE : $response["Vitamina D"];
            $response["Vitamina E"] = ($vitaminaE) ? TRUE : $response["Vitamina E"];
            $response["Vitamina K"] = ($vitaminaK) ? TRUE : $response["Vitamina K"];
            $response["Calcio"] = ($calcio) ? TRUE : $response["Calcio"];
            $response["Fosforo"] = ($fosforo) ? TRUE : $response["Fosforo"]; 
            $response["Magnesio"] = ($magnesio) ? TRUE : $response["Magnesio"]; 
            $response["Sodio"] = ($sodio) ? TRUE : $response["Sodio"]; 
            $response["Potassio"] = ($potassio) ? TRUE : $response["Potassio"];
            $response["Zolfo"] = ($zolfo) ? TRUE : $response["Zolfo"];
            $response["Cromo"] = ($cromo) ? TRUE : $response["Cromo"];
            $response["Ferro"] = ($ferro) ? TRUE : $response["Ferro"]; 
            $response["Fluoro"] = ($fluoro) ? TRUE : $response["Fluoro"];
            $response["Iodio"] = ($iodio) ? TRUE : $response["Iodio"];
            $response["Manganese"] = ($manganese) ? TRUE : $response["Manganese"];
            $response["Rame"] = ($rame) ? TRUE : $response["Rame"]; 
            $response["Selenio"] = ($selenio) ? TRUE : $response["Selenio"];
            $response["Zinco"] = ($zinco) ? TRUE : $response["Zinco"];
        }

        echo json_encode($response);
    }

    recupera_vitamine_minerali();
    mysqli_close($db_connection);
?>