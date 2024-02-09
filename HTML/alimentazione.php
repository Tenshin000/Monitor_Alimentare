<?php
    require '../PHP/utility.php';
    
    session_start();
    if(!$_SESSION["login"]){
        session_destroy();
        header("Location: login.php");
    }
    
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Alimentazione</title>
    <link rel="stylesheet" href="../CSS/alimentazione.css">
</head>
<body onload = "inizializzazione()">
    <div class="margini"></div>
    <div id="centro">
        <div id="ricerca">
            <form id="formRicercaC">
                <table>
                    <tr>
                        <td><input type="text" placeholder="Cosa stai cercando?" id="barra" name="ricerca"><button type="submit"><img src="../img/search.png" alt=""></button></td>
                    </tr>
                </table>
            </form>
            <br>
            <div id = "risultati">

            </div>
        </div>
        <div id="alimento">
            <p id="msg"></p>
        </div>
        <div id="lista">
            <h2><?php echo $_SESSION["orario"]; ?></h2>
            <a href='./mainpage.php'>Torna alla pagina principale</a>
            <br><br>
            <button id="cancella">Cancella</button><button id="aggiorna">Aggiorna</button>
            <div id="inseriti"></div>  
            <br>
            <p id="conto_calorie"></p> 
        </div>
    </div>
    <div class="margini"></div>
    <script src="../JS/alimentazione.js"></script>
</body>