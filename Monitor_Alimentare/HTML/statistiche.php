<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Statistiche</title>
    <link rel="stylesheet" href="../CSS/statistiche.css">
    <script src="../JS/statistiche.js"></script>
    <?php
        require '../PHP/utility.php';
        
        session_start();
        if(!$_SESSION["login"]){
            session_destroy();
            header("Location: login.php");
        }

        // $_SESSION["data"] = date("Y-m-d");
    ?>
</head>
<body onload="inizializzazione()">
    <div class="margini"></div>
    <div id="centro">
        <br>
        <a href='./mainpage.php'>Torna alla pagina principale</a>
        <br>
        <div id = "scelta">
            <select id = "selezione">
                <option value="1" selected>Giornaliero</option>
                <option value="2">Settimanale</option>
                <option value="3">Mensile</option>
                <option value="4">Annuale</option>
            </select>
        </div> 
        <div id = "grafici">

        </div>
    </div>
    <div class="margini"></div>
</body>