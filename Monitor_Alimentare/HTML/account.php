<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Account</title>
    <link rel="stylesheet" href="../CSS/page_form.css">
    <style>
        nav{
            text-align: center; 
        }

        main{
            display: flex;
            justify-content: center;
        }
        
        main table{
            background-color:#1F2E2E;
        }

        main table td{
            padding: 0.5rem;
        }

        main button{
            background-color: #1F2E2E;
            border-color: #FFE066;
            color: white;
            padding: 0.3rem;
            margin-left: 1%;
            outline: none;
        } 

        main input{
            background-color: #1F2E2E;
            border: 2px solid #FFE066;
            color: white;
            padding: 0.3rem;
            width: 10rem;
        }

        main input[type="number"]{
            background-color: #1F2E2E;
            border: 2px solid #FFE066;
            color: white;
            padding: 0.3rem;
            width: 6rem;
        }

        main input[type="date"]{
            background-color: #1F2E2E;
            border: 2px solid #FFE066;
            color: white;
            padding: 0.3rem;
            width: 8rem;
            text-align: center;
        }

        main select{
            background-color: #1F2E2E;
            border: 2px solid #FFE066;
            color: white;
            padding: 0.3rem;
            width: 7rem;
        }

        footer{
            text-align: center;
        }

        a{
            color: white;
            font-size: 12pt;
        }

        a:hover {
            color: blue; 
        }
    </style>
    <script src="../JS/account.js"></script>
    <?php
        require '../PHP/utility.php';
        
        session_start();
        if(!$_SESSION["login"]){
            session_destroy();
            header("Location: login.php");
        }

        $_SESSION["data"] = date("Y-m-d");
    ?>
</head>
<body onload="inizializzazione()">
    <div class="margini"></div>
    <div id="centro">
       <nav><h1>Account</h1></nav> 
       <main>
            <table id="info">

            </table>
        </main>
        <br>
        <footer><a href='./mainpage.php'>Torna alla pagina principale</a></footer>
    </div>
    <div class="margini"></div>
</body>