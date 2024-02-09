<?php

    // QUI SONO CONTENUTE TUTTE LE FUNZIONI DI UTILITY PER LA PARTE LATO SERVER

    function calcolo_eta($dataNascita){
        $dataNascita = new DateTime($dataNascita);
    
        // Ottieni la data corrente
        $dataCorrente = new DateTime();
        
        // Calcola la differenza tra le due date
        $differenza = $dataNascita->diff($dataCorrente);
        
        // Restituisci l'età
        return $differenza->y;
    }

    function controllo_nome($nome){
        if(strlen($nome) < 2 || strlen($nome) > 50) {
            $response = [
                'sign' => false,
                'msg' => 'Il nome non può essere considerato valido!'
            ];
            echo json_encode($response);
            return false;
        }
        return true;
    }

    function controllo_cognome($cognome){
        if(strlen($cognome) < 2 || strlen($cognome) > 50) {
            $response = [
                'sign' => false,
                'msg' => 'Il cognome non può essere considerato valido!'
            ];
            echo json_encode($response);
            return false;
        }
        return true;
    }

    function controllo_statura($statura){
        if($statura < 50 || $statura > 300){
            $response = [
                'sign' => false,
                'msg' => 'Statura sproporzionata!'
            ];
            echo json_encode($response);
            return false;
        }
        return true;
    }

    function controllo_peso($peso){
        if($peso < 10 || $peso> 650){
            $response = [
                'sign' => false,
                'msg' => 'Peso non fattibile!'
            ];
            echo json_encode($response);
            return false;
        }
        return true;
    }

    function controllo_attivita($attivita){
        if($attivita != "Basso" && $attivita != "Moderato" && $attivita != "Elevato"){
            $response = [
                'sign' => false,
                'msg' => 'Attività errata!'
            ];
            echo json_encode($response);
            return false;                
        }
        return true;
    }

    function controllo_dieta($dieta){
        if($dieta != "Classica" && $dieta != "Vegetariana" && $dieta != "Vegana"){
            $response = [
                'sign' => false,
                'msg' => 'Dieta Errata!'
            ];
            echo json_encode($response);
            return false;
        }
        return true;
    }

    function controllo_data_corrente($data){
        // Serve a vedere se la data inserita è quella corrente.

        $dataCorrente = date("Y-m-d");
        return ($data == $dataCorrente);
    }

    function calcolo_fabbisogno_calorico($sesso, $peso, $eta, $attivita_fisica){
        $fabbisogno = 0;

        if($sesso === "M"){
            if($eta < 30)
                $fabbisogno += 679 + 15.3 * $peso;
            else{
                if($eta >= 30 && $eta < 60)
                    $fabbisogno += 879 + 11.6 * $peso;
                else{
                    if($eta >= 60 && $eta < 74)
                        $fabbisogno += 700 + 11.9 * $peso;
                    else if($eta >= 74)
                        $fabbisogno += 819 + 8.4 * $peso;
                }
            }

            if($attivita_fisica === "Basso")
                $fabbisogno *= 1.55;
            else{
                if($attivita_fisica === "Moderato")
                    $fabbisogno *= 1.78;
                else if($attivita_fisica === "Elevato")
                    $fabbisogno *= 2;
            }
        }
        else if($sesso === "F"){
            if($eta < 30)
                $fabbisogno += 496 + 14.7 * $peso;
            else{
                if($eta >= 30 && $eta < 60)
                    $fabbisogno += 829 + 8.7 * $peso;
                else{
                    if($eta >= 60 && $eta < 74)
                        $fabbisogno += 688 + 9.2 * $peso;
                    else if($eta >= 74)
                        $fabbisogno += 624 + 9.8 * $peso;
                }
            }

            if($attivita_fisica === "Basso")
                $fabbisogno *= 1.56;
            else{
                if($attivita_fisica === "Moderato")
                    $fabbisogno *= 1.64;
                else if($attivita_fisica === "Elevato")
                    $fabbisogno *= 1.82;
            }
        }

        return $fabbisogno;
    }

    function verifica_eta(){
        $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
        if(mysqli_connect_errno()){
                exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
        }

        if($_SESSION["login"]){
            $query = "SELECT DataNascita FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $_SESSION["email"]);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $data);
            if(mysqli_stmt_fetch($statement)){
                if($data == $_SESSION["data"]){
                    mysqli_stmt_close($statement);
                    $query = "SELECT Sesso, Peso, LivelloAttivita FROM Utente WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "s", $_SESSION["email"]);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_bind_result($statement, $sesso, $peso, $attivita);
                    if(mysqli_stmt_fetch($statement)){
                        mysqli_stmt_close($statement);
                        $eta = calcolo_eta($data);
                        $fabbisogno_calorico = intval(calcolo_fabbisogno_calorico($sesso, $peso, $eta, $attivita));
                        $query = "UPDATE Utente SET FabbisognoCalorico = ? WHERE Email = ?";
                        $statement = mysqli_prepare($db_connection, $query);
                        mysqli_stmt_bind_param($statement, "is", $fabbisogno_calorico, $_SESSION["email"]);
                        mysqli_stmt_execute($statement);
                        return true;
                    }
                    else
                        return false;
                }
                else
                    return false;
            }
            else
                return false;
        }
        else{
            session_unset();
            session_destroy();
            return false;
        }        
    }

    function inserisci_in_bicchieri(){
        $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
        if(mysqli_connect_errno()){
                exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
        }

        if($_SESSION["login"]){
            $query = "INSERT INTO Bicchieri(Utente, Data, Numero) VALUES(?, ?, ?)";
            $statement = mysqli_prepare($db_connection, $query);
            $numero = 0;
            mysqli_stmt_bind_param($statement, "ssi", $_SESSION["email"], $_SESSION["data"], $numero);
            mysqli_stmt_execute($statement);
            mysqli_close($db_connection);
        }
        else{
            session_unset();
            session_destroy();
        }
    }

    function aggiornamento_peso($peso){
        $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
        if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
        }

        if($_SESSION["login"]){
            $email = $_SESSION["email"];

            if(!controllo_peso($peso))
                return false;

            $query = "SELECT Sesso, DataNascita, LivelloAttivita FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_bind_result($statement, $sesso, $data_nascita, $attivita);
                if(mysqli_stmt_fetch($statement)){
                    mysqli_stmt_close($statement);
                    $eta = calcolo_eta($data_nascita);
                    $fabbisogno_calorico = intval(calcolo_fabbisogno_calorico($sesso, $peso, $eta, $attivita));
                    $query = "UPDATE Utente SET Peso = ?, FabbisognoCalorico = ? WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "dis", $peso, $fabbisogno_calorico, $email);
                    $risultato = mysqli_stmt_execute($statement); 
                } 
                else
                    $risultato = false;     
            }
            else
                $risultato = false;
            
            return $risultato;
        }
        else{
            return false;
        }

        mysqli_close($db_connection);
    }

    function aggiornamento_sesso($sesso){
        $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
        if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
        }

        if($_SESSION["login"]){
            $email = $_SESSION["email"];

            $query = "SELECT Peso, DataNascita, LivelloAttivita FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_bind_result($statement, $peso, $data_nascita, $attivita);
                if(mysqli_stmt_fetch($statement)){
                    mysqli_stmt_close($statement);
                    $eta = calcolo_eta($data_nascita);
                    $fabbisogno_calorico = intval(calcolo_fabbisogno_calorico($sesso, $peso, $eta, $attivita));
                    $query = "UPDATE Utente SET Sesso = ?, FabbisognoCalorico = ? WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "sis", $sesso, $fabbisogno_calorico, $email);
                    $risultato = mysqli_stmt_execute($statement); 
                } 
                else
                    $risultato = false;     
            }
            else
                $risultato = false;
            
            return $risultato;
        }
        else{
            return false;
        }

        mysqli_close($db_connection);
    }

    function aggiornamento_nascita($nascita){
        $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
        if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
        }

        if($_SESSION["login"]){
            $email = $_SESSION["email"];

            $query = "SELECT Sesso, Peso, LivelloAttivita FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_bind_result($statement, $sesso, $peso, $attivita);
                if(mysqli_stmt_fetch($statement)){
                    mysqli_stmt_close($statement);
                    $eta = calcolo_eta($nascita);
                    $fabbisogno_calorico = intval(calcolo_fabbisogno_calorico($sesso, $peso, $eta, $attivita));
                    $query = "UPDATE Utente SET DataNascita = ?, FabbisognoCalorico = ? WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "sis", $nascita, $fabbisogno_calorico, $email);
                    $risultato = mysqli_stmt_execute($statement); 
                } 
                else
                    $risultato = false;     
            }
            else
                $risultato = false;
            
            return $risultato;
        }
        else{
            return false;
        }

        mysqli_close($db_connection);
    }

    function aggiornamento_attivita($attivita){
        $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
        if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
        }

        if($_SESSION["login"]){
            if(!controllo_attivita($attivita))
                return false;

            $email = $_SESSION["email"];

            $query = "SELECT Sesso, DataNascita, Peso FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_bind_result($statement, $sesso, $data_nascita, $peso);
                if(mysqli_stmt_fetch($statement)){
                    mysqli_stmt_close($statement);
                    $eta = calcolo_eta($data_nascita);
                    $fabbisogno_calorico = intval(calcolo_fabbisogno_calorico($sesso, $peso, $eta, $attivita));
                    $query = "UPDATE Utente SET LivelloAttivita = ?, FabbisognoCalorico = ? WHERE Email = ?";
                    $statement = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($statement, "sis", $attivita, $fabbisogno_calorico, $email);
                    $risultato = mysqli_stmt_execute($statement); 
                } 
                else
                    $risultato = false;     
            }
            else
                $risultato = false;
            
            return $risultato;
        }
        else{
            return false;
        }

        mysqli_close($db_connection);
    }
?>