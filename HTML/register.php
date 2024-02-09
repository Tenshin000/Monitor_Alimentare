<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Registrazione</title>
    <link rel="stylesheet" href="../CSS/page_form.css">
    <style>
        #centro{
            display: flex;
            align-items:center;
        }

        h2{
            margin: 0 auto;
        }

        #registrazione{
            width: 50%;
        }

        #informazioni{
            width: 50%;
            display: flex;
            flex-direction: column;
            color: white;
        }

        form{
            margin-left: 10%;
        }

        label{
            color: white;
            font-family: sans-serif;       
            font-size: 14pt;  
        }  

        input{
            background-color: #1F2E2E;
            border-color: #FFE066;
            color: white;
            height: 1.5rem;
            width: 15rem;
            padding: 0.1rem;
        }

        select{
            background-color: #1F2E2E;
            border-color: #FFE066;
            color: white;
            height: 1.5rem;
        }

        button{
            background-color: #1F2E2E;
            border-color: #FFE066;
            color: white;
            padding: 0.3rem;
        }

        td{
            padding: 0.2rem;
            width: 12rem;
        }

        .logo{
            height: 4rem;
            width: 4rem;
            border-radius: 100%;
            margin: 0 auto;
        }

        .tabella{
            margin: 0 auto;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tabella td{
            vertical-align: top;
            border: 1px solid black;
        }

        #msg{
            margin-top: 1%;
            margin-left: 1%;
            color: white;
            font-size: 14pt;
            padding: 0.1rem;
        }
    </style>
</head>
<body onload="inizializzazione()">
    <div class="margini"></div>
    <div id="centro">
        <div id="registrazione">
            <form id="formRegister" autocomplete="off">
                <table>
                    <tr>
                        <td><label for="Email">E-mail</label></td> <td><input type="text" id="Email" name="Email" pattern="^(.+)@([^\.].*)\.([a-z]{2,})$" required></td>
                    </tr>
                    <tr>
                        <td><label for="Nome">Nome</label></td> <td><input type="text" id="Nome" name="Nome" pattern="[\p{L}'\s]+" required></td>
                    </tr>
                    <tr>
                        <td><label for="Cognome">Cognome</label></td> <td><input type="text" id="Cognome" name="Cognome" pattern="[\p{L}'\s]+" required></td>
                    </tr>
                    <tr>
                        <td><label for="Password">Password</label></td> <td><input type="password" id="Password" name="Password" required></td>
                    </tr>
                    <tr>
                        <td><label for="ConfirmPassword">Conferma Password</label></td> <td><input type="password" id="ConfirmPassword" name="ConfirmPassword" required></td>
                    </tr>
                    <tr>
                        <td><label for="Sesso">Sesso</label></td> 
                        <td>
                            <select id="Sesso" name="Sesso" required>
                                <option value="" selected disabled>-</option>
                                <option value="M">M</option>
                                <option value="F">F</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="Nascita">Data di Nascita</label></td> <td><input type="date" id="Nascita" name="Nascita" required></td>
                    </tr>
                    <tr>
                        <td><label for="Statura">Statura (in cm)</label></td> <td><input type="text" id="Statura" name="Statura" pattern="\d{2,3}\" required></td>
                    </tr>
                    <tr>
                        <td><label for="Peso">Peso (in Kg)</label></td> <td><input type="text" id="Peso" name="Peso" pattern="^([1-9]\d*)(.\d+)?$" required></td>
                    </tr>
                    <tr>
                        <td><label for="Attivita">Livello di Attivit&agrave;</label></td>
                        <td>
                            <select id="Attivita" name="Attivita" required>
                                <option value="" selected>-</option>
                                <option value="Basso">Basso</option>
                                <option value="Moderato">Moderato</option>
                                <option value="Elevato">Elevato</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="Dieta">Dieta</label></td> 
                        <td>
                            <select id="Dieta" name="Dieta" required>
                                <option value="" selected>-</option>
                                <option value="Classica">Classica</option>
                                <option value="Vegetariana">Vegetariana</option>
                                <option value="Vegana">Vegana</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
                <button type="submit" name="inserisci">Registrati</button>
                <div id = "msg"></div>
            </form>
        </div>
        <article id="informazioni">
            <img src="../img/logo.png" alt="Logo del Sito" class="logo">
            <br>
            <h2>Informazioni</h2>
            <table class="tabella">
                <tr>
                    <th colspan="3">Livello di Attivit&agrave;</th>
                </tr>
                <tr>
                    <td>Basso</td> <td>Moderato</td> <td>Elevato</td>
                </tr>
                <tr>
                    <td>Un livello di attivit&agrave; basso &egrave; adatto a persone che non fanno lavori pesanti e che nel tempo libero non svolgono una particolare attivit&agrave; fisica.</td>
                    <td>Un livello di attivit&agrave; moderato &egrave; adatto a persone che non fanno lavori pesanti, ma che nel tempo libero svolgono una particolare attivit&agrave; fisica, come per esempio camminare a passo sostenuto, andare in bicicletta, fare ginnastica dolce, ballare, fare giardinaggio o svolgere lavori in casa come lavare finestre o pavimenti.</td>
                    <td>Un livello di attivit&agrave; elevato &egrave; adatto a persone fanno lavori pesanti e/o che nel tempo libero svolgono attivit&agrave; fisica, come per esempio correre (regolarmente), pedalare velocemente, fare ginnastica aerobica o sport agonistici.</td>
                </tr>
                <tr>
                    <td>I lavori tipici di un'attivit&agrave; bassa sono: impiegati, personale amministrativo e dirigenziale, liberi professionisti, tecnici o simili.</td>
                    <td>I lavori tipici di un'attivit&agrave; moderata sono: casalinghe/i, collaboratori domestici, personale di vendita e lavoratori del terziario.</td>
                    <td>I lavori tipici di un'attivit&agrave; elevata sono: atleti, lavoratori in agricoltura, allevamento, silvicoltura e pesca, manovali, operatori di produzione e di attrezzature di trasporto.</td>
                </tr>
            </table>
            <br>
            <table class="tabella">
                <tr>
                    <th colspan="3">Dieta</th>
                </tr>
                <tr>
                    <td>Classica</td> <td>Vegetariana</td> <td>Vegana</td>
                </tr>
                <tr>
                    <td>La dieta classica permette di mangiare qualsiasi tipo di cibo (senza contare intolleranze e allergeni).</td>
                    <td>La dieta vegetariana esclude di mangiare carne e pesce.</td>
                    <td>La dieta vegana esclude di mangiare carne, pesce e tutti i derivati di origine animale (come latte, formaggi, uova e miele) ammettendo solo alimenti di origine vegetale.</td>
                </tr>
            </table>
            <br>
        </article>
    </div>
    <div class="margini"></div>
    
    <script>
        const form = document.getElementById("formRegister");  
        const msg = document.getElementById("msg");   

        function registrazione(event){
            event.preventDefault();
            const data = new FormData(form);

            fetch("../PHP/requests/register.php", { method: 'POST', body: data })
            .then(response => response.json())
            .then(data => {
                            if(data["sign"]){
                                msg.textContent = data["msg"];
                                setTimeout(function(){
                                                msg.textContent = "";   
                                                window.location.href = "login.php";
                                            }, 1000);
                            }
                            else{
                                msg.textContent = data["msg"];
                                setTimeout(function(){
                                                msg.textContent = "";   
                                            }, 3000);
                            }
                          })
            .catch(error => window.alert(error));

        }
        
        function inizializzazione(){
            form.addEventListener("submit", registrazione);
        }
    </script>
</body>