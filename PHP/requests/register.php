<?php
    require '../utility.php';

    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()) {
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    function registrazione(){
        GLOBAL $db_connection;
        
        if(empty($_POST["Email"]) || empty($_POST["Nome"]) || empty($_POST["Cognome"]) || empty($_POST["Password"]) || empty($_POST["ConfirmPassword"]) 
           || empty($_POST["Sesso"]) || $_POST["Sesso"] == "" || empty($_POST["Nascita"]) || empty($_POST["Statura"]) || empty($_POST["Peso"]) 
           || empty($_POST["Statura"] || empty($_POST["Attivita"]) || $_POST["Attivita"] == "" || empty($_POST["Dieta"]) || $_POST["Dieta"] == ""))
                return false;


        $email = mysqli_real_escape_string($db_connection, $_POST["Email"]);
        $nome = mysqli_real_escape_string($db_connection, $_POST["Nome"]);
        $cognome = mysqli_real_escape_string($db_connection, $_POST["Cognome"]);
        $password = mysqli_real_escape_string($db_connection, $_POST["Password"]);
        $confirm_password = mysqli_real_escape_string($db_connection, $_POST["ConfirmPassword"]);
        $sesso = mysqli_real_escape_string($db_connection, $_POST["Sesso"]);
        $nascita = mysqli_real_escape_string($db_connection, $_POST["Nascita"]);
        $statura = intval(mysqli_real_escape_string($db_connection, $_POST["Statura"]));
        $peso = floatval(mysqli_real_escape_string($db_connection, $_POST["Peso"]));
        $attivita = mysqli_real_escape_string($db_connection, $_POST["Attivita"]);
        $dieta = mysqli_real_escape_string($db_connection, $_POST["Dieta"]);

        if($password != $confirm_password){
            $response = [
                'sign' => false,
                'msg' => 'Password discordanti!'
            ];
            echo json_encode($response);
            return false;
        }          

        if(strlen($password) < 6){
            $response = [
                'sign' => false,
                'msg' => 'La password deve essere lunga almeno 6 caratteri!'
            ];
            echo json_encode($response);
            return false;
        }
            
        if(!controllo_nome($nome))
            return false;

        if(!controllo_cognome($cognome))
            return false;

        if(!controllo_statura($statura))
            return false;

        if(!controllo_peso($peso))
            return false;
            
        if(!controllo_attivita($attivita))
            return false;

        if(!controllo_dieta($dieta))
            return false;
            
        $eta = calcolo_eta($nascita);
        if($eta < 0 || $eta > 121){
            $response = [
                'sign' => false,
                'msg' => 'Età assurda!'
            ];
            echo json_encode($response);
            return false;
        }
        
        $fabbisogno = intval(calcolo_fabbisogno_calorico($sesso, $peso, $eta, $attivita));

        if($fabbisogno <= 0){
            $response = [
                'sign' => false,
                'msg' => 'Errore nel calcolo del fabbisogno calorico!'
            ];
            echo json_encode($response);
            return false;
        }
                
        $password = password_hash($password, PASSWORD_BCRYPT);      
        $bicchiere = 0.25;             
            
        $query = "INSERT INTO Utente(Email, Password, Nome, Cognome, Sesso, DataNascita, Statura, Peso, LivelloAttivita, Dieta, DimensioneBicchiere, FabbisognoCalorico) VALUES (? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)";
           
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ssssssidssdi", $email, $password, $nome, $cognome, $sesso, $nascita, $statura, $peso, $attivita, $dieta, $bicchiere, $fabbisogno);
            
        if(!mysqli_stmt_execute($statement)){
            //La query è fallita poiché l'utente esiste già
            $response = [
                'sign' => false,
                'msg' => 'Utente già registrato!'
            ];
            echo json_encode($response);
            return false;
        }
        else{
            $response = [
                'sign' => true,
                'msg' => 'Registrazione avvenuta con successo!'
            ];
            echo json_encode($response);
            return true;
        }
    }

    registrazione();
    mysqli_close($db_connection);
?>