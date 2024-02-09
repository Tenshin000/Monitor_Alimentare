<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Attivit&agrave;</title>
    <link rel="stylesheet" href="../CSS/attivita.css">
    <script src="../JS/attivita.js"></script>
    <?php
        require '../PHP/utility.php';
        
        session_start();
        if(!$_SESSION["login"]){
            session_destroy();
            header("Location: login.php");
        }

    ?>
</head>
<body onload="inizializzazione()">
    <div class="margini"></div>
    <div id="centro">
        <div id="ricerca">
            <form id="formRicercaA">
                <table>
                    <tr>
                        <td><input type="text" placeholder="Cosa stai cercando?" id="barra" name="ricerca"><button type="submit"><img src="../img/search.png" alt=""></button></td>
                    </tr>
                </table>
            </form>
            <div id="risultati">
                
            </div>
        </div>
        <div id="attivita">
            <p id="msg"></p>
        </div>
        <div id="lista">
            <h2>Attivit&agrave; Praticate</h2>
            <a href='./mainpage.php'>Torna alla pagina principale</a>
            <br><br>
            <div id="inseriti">

            </div>  
            <br>
            <p id="conto_calorie"></p> 
        </div>
    </div>
    <div class="margini"></div>
</body>