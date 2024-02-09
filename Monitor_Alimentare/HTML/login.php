<?php
    session_start();
    session_destroy();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/page_form.css">
    <style>
        #centro{
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo{
            height: 4.4rem;
            width: 4.4rem;
            border-radius: 100%;
            margin: 0 auto;
            margin-top: -10%;
        }

        form{
            margin: 0 auto;
            height: 25rem;
            width: 25rem;
            background-color:#1F2E2E;
        }

        table{
            margin: 0 auto;
        }

        td{
            padding: 0.4rem;
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

        button{
            background-color: #1F2E2E;
            border-color: #FFE066;
            color: white;
            padding: 0.3rem;
            margin-left: 6%;
        }

        #msg{
            margin-top: 1%;
            margin-left: 3%;
            color: white;
            font-size: 14pt;
            padding: 0.1rem;
        }
    </style>
</head>
<body onload="inizializzazione()">
    <div class="margini"></div>
    <div id="centro">
        <img src="../img/logo.png" class = "logo" alt="Logo del Sito">
        <br>
        <form id = "formLogin">
            <br><br><br><br><br><br><br><br>
            <table>
                <tr>
                    <td><label for="Email">E-mail</label></td> <td><input type="text" id="Email" name="Email" required></td>
                </tr>
                <tr>
                    <td><label for="Password">Password</label></td> <td><input type="password" id="Password" name="Password" autocomplete="off" required></td>
                </tr>
                <tr>
                    <td></td> <td><input type="button" value="Mostra Password" id="mostraPassword"></td>
                </tr>
            </table>
            <br><br>
            <button type="submit" name="inserisci">Login</button>
            <div id = "msg"></div>
        </form>
    </div>
    <div class="margini"></div>

    <script>
        const form = document.getElementById("formLogin");  
        const password = document.getElementById("Password");
        const mostra = document.getElementById("mostraPassword");
        const msg = document.getElementById("msg");   

        function login(event){
            event.preventDefault();
            const _data = new FormData(form);

            fetch("../PHP/requests/login.php", { method: 'POST', body: _data })
            .then(response => response.json())
            .then(data => {
                            if(data["login"]){
                                msg.textContent = data["msg"];
                                setTimeout(function(){
                                                msg.textContent = "";   
                                                window.location.href = "mainpage.php";
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
        
        function mostra_password(){
                if(password.type === "password"){
                    password.type = "text";
                    mostra.value = "Nascondi Password";
                }
                else{
                    password.type = "password";
                    mostra.value = "Mostra Password";
                }
        }

        function inizializzazione(){
            form.addEventListener("submit", login);
            mostra.addEventListener("click", mostra_password);
        }
    </script>
</body>