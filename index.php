<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="./CSS/page_form.css">
    <style>
        .logo{
            height: 15em;
            width: 15em;
            border-radius: 100%;
        }

        section{
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        header{
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        nav{
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        footer{
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        header img{
            margin: 1% 5%;
            height: 8em;
            width: 8em;
        }

        a{
            color: white;
            font-size: 24pt;
            margin-left: 4%;
            margin-right: 4%;
        }

        a:hover{
            color: blue;
        }
    </style>
</head>
<body>
    <div class="margini"></div>
    <div id="centro">
        <header>
            <img src="./img/hamburger.png" alt="">
            <img src="./img/polpette_vegetariane.png" alt="">
            <img src="./img/amici_ristorante.png" alt="">
            <img src="./img/pizza.png" alt="">
        </header>
        <br>
        <section>
            <h1>MONITOR ALIMENTARE</h1>
        </section>
        <br><br>
        <nav>
            <a href='./HTML/login.php'><u>Login</u></a> <a href='./HTML/register.php'><u>Registrazione</u></a> <a href='./HTML/manuale1.html'><u>Manuale</u></a>
        </nav>
        <br><br><br>
        <footer>
            <img src="./img/logo.png" class = "logo" alt="Logo del Sito">
        </footer>
    </div>
    <div class="margini"></div>
</body>