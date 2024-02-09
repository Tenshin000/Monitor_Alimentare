<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }


    function trova_dati_alimento(){
        GLOBAL $db_connection;

        if(!isset($_POST["ID"])){
            $errorResponse = array("error" => "Alimento non trovato");
            echo json_encode($errorResponse);
            return;
        }
        
        $id = $_POST["ID"];

        $query = "SELECT Nome, VitaminaA, VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, VitaminaB7, VitaminaB11, VitaminaB12, VitaminaC, VitaminaD, 
                         VitaminaE, VitaminaK, Calcio, Fosforo, Magnesio, Sodio, Potassio, Zolfo, Cromo, Ferro, Fluoro, Iodio, Manganese, Rame, Selenio, Zinco
                  FROM Cibo WHERE ID = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $nome, $vitaminaA, $vitaminaB1, $vitaminaB2, $vitaminaB3, $vitaminaB5, $vitaminaB6, $vitaminaB7, $vitaminaB11, $vitaminaB12, 
                                            $vitaminaC, $vitaminaD, $vitaminaE, $vitaminaK, $calcio, $fosforo, $magnesio, $sodio, $potassio, $zolfo, $cromo, $ferro, 
                                            $fluoro, $iodio, $manganese, $rame, $selenio, $zinco);
        
        $response = array();
        while(mysqli_stmt_fetch($statement)){
            $response["nome"] = $nome;
            $response["Vitamina A"] = $vitaminaA;
            $response["Vitamina B1"] = $vitaminaB1;
            $response["Vitamina B2"] = $vitaminaB2;
            $response["Vitamina B3"] = $vitaminaB3;
            $response["Vitamina B5"] = $vitaminaB5; 
            $response["Vitamina B6"] = $vitaminaB6;
            $response["Vitamina B7"] = $vitaminaB7;
            $response["Vitamina B11"] = $vitaminaB11;
            $response["Vitamina B12"] = $vitaminaB12;
            $response["Vitamina C"] = $vitaminaC;
            $response["Vitamina D"] = $vitaminaD;
            $response["Vitamina E"] = $vitaminaE;
            $response["Vitamina K"] = $vitaminaK;
            $response["Calcio"] = $calcio;
            $response["Fosforo"] = $fosforo; 
            $response["Magnesio"] = $magnesio; 
            $response["Sodio"] = $sodio; 
            $response["Potassio"] = $potassio;
            $response["Zolfo"] = $zolfo;
            $response["Cromo"] = $cromo;
            $response["Ferro"] = $ferro; 
            $response["Fluoro"] = $fluoro;
            $response["Iodio"] = $iodio;
            $response["Manganese"] = $manganese;
            $response["Rame"] = $rame; 
            $response["Selenio"] = $selenio;
            $response["Zinco"] = $zinco;
        }

        mysqli_stmt_close($statement);
        $query = "SELECT Catalogazione, Calorie, Carboidrati, Proteine, Grassi, Quantita, UnitaMisura FROM Porzione WHERE Cibo = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $catalogazione, $calorie, $carboidrati, $proteine, $grassi, $quantita, $misura);
        $contatore = 0;
        while(mysqli_stmt_fetch($statement)){
            $response[$contatore]["catalogazione"] = $catalogazione;
            $response[$contatore]["calorie"] = floatval($calorie);
            $response[$contatore]["carboidrati"] = floatval($carboidrati);
            $response[$contatore]["proteine"] = floatval($proteine);
            $response[$contatore]["grassi"] = floatval($grassi);
            $response[$contatore]["quantita"] = intval($quantita);
            $response[$contatore]["misura"] = $misura;
            $contatore++;
        }
        $response["length"] = $contatore;

        echo json_encode($response);
    }

    trova_dati_alimento();
    mysqli_close($db_connection);
?>