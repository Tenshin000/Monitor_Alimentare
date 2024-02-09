<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Monitor Alimentare</title>
    <link rel="stylesheet" href="../CSS/mainpage.css">
    <script src="../JS/mainpage.js"></script>
    <?php
        require '../PHP/utility.php';

        $db_connection = mysqli_connect("localhost", "root", "", "DB_Monitor_Alimentare");
        if(mysqli_connect_errno()){
                exit('Connessione a database non riuscita. (' . mysqli_connect_error() . ')');
        }

        session_start();

        if(!empty($_SESSION["email"]) && $_SESSION["login"]){
            if(empty($_SESSION["data"]))
                $_SESSION["data"] = date("Y-m-d");
            $email = $_SESSION["email"];
            $query = "SELECT Nome, Dieta FROM Utente WHERE Email = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $email);
            mysqli_stmt_execute($statement);
            mysqli_stmt_bind_result($statement, $nome, $dieta);
            while(mysqli_stmt_fetch($statement)){
                $_SESSION["nome"] = $nome;
                $_SESSION["dieta"] = $dieta;
            } 

            $query = "SELECT Numero FROM Bicchieri WHERE Utente = ? AND Data = ?";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ss", $_SESSION["email"], $_SESSION["data"]);
            mysqli_stmt_execute($statement);
            mysqli_stmt_store_result($statement);

            if(mysqli_stmt_num_rows($statement) == 0){
                inserisci_in_bicchieri();                    
            }
        }
        else{
            session_destroy();
            header("Location: login.php");
        }

        verifica_eta();

        function alimentazione(){
            if(isset($_GET["colazione"])){
                $_SESSION["orario"] = "Colazione";
                header("Location: alimentazione.php");
            }

            if(isset($_GET["pranzo"])){
                $_SESSION["orario"] = "Pranzo";
                header("Location: alimentazione.php");
            }

            if(isset($_GET["cena"])){
                $_SESSION["orario"] = "Cena";
                header("Location: alimentazione.php");
            }

            if(isset($_GET["spuntini"])){
                $_SESSION["orario"] = "Spuntini";
                header("Location: alimentazione.php");
            }
        }

        alimentazione();
        mysqli_close($db_connection);
    ?>
</head>
<body onload="inizializzazione()">
    <div class="margini"></div>
    <div id="centro">
        <h1 class="welcome">Benvenuto <?php echo $_SESSION["nome"]; ?>! <?php if(controllo_data_corrente($_SESSION["data"])){ echo "Oggi è il " . $_SESSION["data"] . "."; } else { echo "La schermata riguarda il " . $_SESSION["data"] . "."; } ?></h1>
        <div id="cambia_data">
            <label for="data">Cambia Data</label>
            <input type="date" id="data" onkeydown="return false">
        </div>
        <img src="../img/menu.png" id="menu" alt="Menù a tendina">
        <div id="menu-dropdown">
            <br><br>
            <ul>
                <li><a href="./account.php">Account</a></li>
                <li><a href="./statistiche.php">Statistiche</a></li>
                <li><a href="./manuale2.html">Manuale</a></li>
                <li><a href="../PHP/requests/logout.php">Logout</a></li>
            </ul>
        </div>
        <div id = "container_barra">
            <div id="barra">
                <div id="progresso">
                </div>
            </div> 
        </div>
        <br>
        <main>
            <section id="alimentazione">
                <h2>Alimentazione</h2>
                <form method="GET">
                    <table>
                        <tr>
                            <td><button type="submit" name="colazione">Colazione</button></td> 
                            <td> 
                                <svg width="100" height="100">
                                    <circle class="circonferenza_bg" cx="50" cy="50" r="40"/>
                                    <circle id="colazione" class="circonferenza" cx="50" cy="50" r="40"/>
                                    <text id="colazione_text" x="50" y="50"></text>
                                </svg>
                            </td>
                        </tr>
                        <tr>
                            <td><button type="submit" name="pranzo">Pranzo</button></td>
                            <td> 
                                <svg width="100" height="100">
                                    <circle class="circonferenza_bg" cx="50" cy="50" r="40"/>
                                    <circle id="pranzo" class="circonferenza" cx="50" cy="50" r="40"/>
                                    <text id="pranzo_text" x="50" y="50"></text>
                                </svg>
                            </td>
                        </tr>
                        <tr>
                            <td><button type="submit" name="cena">Cena</button></td>
                            <td> 
                                <svg width="100" height="100">
                                    <circle class="circonferenza_bg" cx="50" cy="50" r="40"/>
                                    <circle id="cena" class="circonferenza" cx="50" cy="50" r="40" />
                                    <text id="cena_text" x="50" y="50"></text>
                                </svg>
                            </td>
                        </tr>
                        <tr>
                            <td><button type="submit" name="spuntini">Spuntini</button></td>
                            <td> 
                                <svg width="100" height="100">
                                    <circle class="circonferenza_bg" cx="50" cy="50" r="40"/>
                                    <circle id="spuntini" class="circonferenza" cx="50" cy="50" r="40"/>
                                    <text id="spuntini_text" x="50" y="50"></text>
                                </svg>
                            </td>
                        </tr>
                    </table>
                </form> 
            </section>
            <section id="altro">
                <h2>Contatore d'Acqua</h2>
                <div id="bicchieri">
                    <div id = "aggiungi_acqua" onclick="aggiungi_bicchiere(this)">+</div>
                    <br>
                    <div id = "msg_acqua"> </div>
                </div>
                <br>
                <h2>Peso</h2>
                <div id="peso"></div> 
            </section>
            <section id="attivita">
                <h2>Attivit&agrave;</h2>
                <button type="submit" name="attivita" onclick="click_attivita()">Attivit&agrave;</button>
                <table id="info_attivita">
                    
                </table>    
            </section>
        </main>
    </div>
    <div class="margini"></div>
</body>