<?php
$db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
if(mysqli_connect_errno()) {
    exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
}

session_start();

function recupera_info() {
    GLOBAL $db_connection;

    if ($_SESSION["login"]) {
        $email = $_SESSION["email"];
        $data = $_SESSION["data"];
        $passi = 0;
        $passi_approssimati = 0;
        $sonno = 0;
        $calorie_perse = 0;
        $attivita = 0;

        $query = "SELECT IFNULL(SUM(A.Quantita), 0) AS Passi
                  FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura
                  WHERE E.Nome = 'Camminare' AND E.UnitaMisura = 'passi' AND A.Utente = ? AND A.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $result);
        if (mysqli_stmt_fetch($statement))
            $passi += $result;
        else {
            $response = [
                'result' => false
            ];
            echo json_encode($response);
            return;
        }

        mysqli_stmt_close($statement);
        $query = "SELECT IFNULL(SUM(A.Quantita * 100), 0) AS Passi
                  FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                  WHERE E.Nome = 'Camminare' AND E.UnitaMisura = 'min' AND A.Utente = ? AND A.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $result);
        if (mysqli_stmt_fetch($statement))
            $passi_approssimati += $result;
        else {
            $response = [
                'result' => false
            ];
            echo json_encode($response);
            return;
        }

        mysqli_stmt_close($statement);
        $query = "SELECT IFNULL(SUM(A.Quantita), 0) AS Sonno
                  FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                  WHERE E.Nome = 'Dormire' AND E.UnitaMisura = 'h' AND A.Utente = ? AND A.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $result);
        if (mysqli_stmt_fetch($statement))
            $sonno += $result;
        else {
            $response = [
                'result' => false
            ];
            echo json_encode($response);
            return;
        }

        mysqli_stmt_close($statement);
        $query = "SELECT IFNULL(SUM(A.Quantita * (E.Calorie * U.Peso / 100)), 0) AS Calorie
                  FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                                  INNER JOIN Utente U ON A.Utente = U.Email
                  WHERE A.Utente = ? AND A.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $result);
        if (mysqli_stmt_fetch($statement))
            $calorie_perse += $result;
        else {
            $response = [
                'result' => false
            ];
            echo json_encode($response);
            return;
        }

        mysqli_stmt_close($statement);
        $query = "SELECT IFNULL(COUNT(*), 0) AS Attivita
                  FROM Attivita A INNER JOIN Esercizi E ON A.Esercizio = E.Nome AND A.UnitaMisura = E.UnitaMisura 
                  WHERE A.Utente = ? AND A.Data = ?";
        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $email, $data);
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $result);
        if (mysqli_stmt_fetch($statement))
            $attivita += $result;
        else {
            $response = [
                'result' => false
            ];
            echo json_encode($response);
            return;
        }

        $response = [
            'result' => true,
            'passi' => $passi,
            'passi approssimati' => $passi_approssimati,
            'sonno' => $sonno,
            'bruciate' => $calorie_perse,
            'attivita' => $attivita
        ];
    } else {
        $response = [
            'result' => false
        ];
    }

    echo json_encode($response);
}

recupera_info();
mysqli_close($db_connection);
?>