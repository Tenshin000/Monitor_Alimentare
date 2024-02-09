<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
		    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
	}

    function login(){
        GLOBAL $db_connection;
        
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if(empty($_POST["Email"]) || empty($_POST["Password"]))
            return false;

        $email = mysqli_real_escape_string($db_connection, $_POST["Email"]);
        $password = mysqli_real_escape_string($db_connection, $_POST["Password"]);

        $query = "SELECT Email FROM Utente WHERE Email = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "s", $email);
        mysqli_stmt_execute($statement);
        mysqli_stmt_store_result($statement);

        if(mysqli_stmt_num_rows($statement) == 0){
            $response = [
                'login' => false,
                'msg' => 'Utente non registrato!'
            ];
            echo json_encode($response);
            return false;
        }

        mysqli_stmt_close($statement);

        $query = "SELECT Password, Dieta FROM Utente WHERE Email = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "s", $email);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $hash, $dieta);

        while(mysqli_stmt_fetch($statement)){
            if (password_verify($password, $hash)) {
                $_SESSION["login"] = true;
                $_SESSION["email"] = $email;
                $_SESSION["dieta"] = $dieta;

                $response = [
                    'login' => true,
                    'msg' => 'Login avvenuto con successo!'
                    ];
                echo json_encode($response);
                return true;
            } 
            else{
                $response = [
                    'login' => false,
                    'msg' => 'Password errata!'
                    ];
                echo json_encode($response);
                return false;
            }
        }

        $response = [
            'login' => false,
            'msg' => 'Login non avvenuto!'
            ];
        echo json_encode($response);
        return false;
    }

    login();
    mysqli_close($db_connection);  
?>