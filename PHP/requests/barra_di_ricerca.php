<?php
    $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
    if(mysqli_connect_errno()){
            exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
    }
    session_start();

    if(empty($_SESSION["email"]) && !$_SESSION["login"]){
        session_destroy();
        header("Location: login.html");
    }
    
    function ricerca_cibo(){
        GLOBAL $db_connection;

        $stringa = mysqli_real_escape_string($db_connection, $_POST["ricerca"]);
        
        if(empty($stringa)) {
            $response = array();
            $response["length"] = 0;
            echo json_encode($response);
            return;
        }

        $query = "SELECT DISTINCT C.ID, C.Nome FROM Cibo C INNER JOIN Porzione P ON C.ID = P.Cibo WHERE C.Nome LIKE ? ORDER BY C.Nome LIMIT 10";
       
        if($_SESSION["dieta"] == "Vegetariana"){
            $query = "SELECT DISTINCT C.ID, C.Nome FROM Cibo C INNER JOIN Porzione P ON C.ID = P.Cibo WHERE C.Nome LIKE ? AND C.Vegetariano IS TRUE ORDER BY C.Nome LIMIT 10";
        }
        else if($_SESSION["dieta"] == "Vegana"){
                $query = "SELECT DISTINCT C.ID, C.Nome FROM Cibo C INNER JOIN Porzione P ON C.ID = P.Cibo WHERE C.Nome LIKE ? AND C.Vegano IS TRUE ORDER BY C.Nome LIMIT 10";
        }
        
        $statement = mysqli_prepare($db_connection, $query);
        $stringa = "%" . $stringa . "%";
        mysqli_stmt_bind_param($statement, "s", $stringa);
        if(mysqli_stmt_execute($statement)){
            mysqli_stmt_bind_result($statement, $id, $nome);

            $contatore = 0;
            $response = array();
            while(mysqli_stmt_fetch($statement)){
                $response[$contatore]["ID"] = $id;
                $response[$contatore]["nome"] = $nome;
                $contatore++;
            }
            $response["length"] = $contatore;
        }
        else
            $response["length"] = 0;
        
        echo json_encode($response);
    }

    function ricerca_attivita(){
        GLOBAL $db_connection;

        $stringa = mysqli_real_escape_string($db_connection, $_POST["ricerca"]);

        $query = "SELECT DISTINCT Nome FROM Esercizi WHERE Nome LIKE ? ORDER BY Nome";        
        $statement = mysqli_prepare($db_connection, $query);
        $stringa = "%" . $stringa . "%";
        mysqli_stmt_bind_param($statement, "s", $stringa);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $nome);

        $contatore = 0;
        $response = array();
        while(mysqli_stmt_fetch($statement)){
            $response[$contatore]["nome"] = $nome;
            $contatore++;
        }
        $response["length"] = $contatore;
        echo json_encode($response);
    }

    if($_POST["tipo"] == "cibo")
        ricerca_cibo();
    else if($_POST["tipo"] == 'attivita')
        ricerca_attivita();
    mysqli_close($db_connection);
?>