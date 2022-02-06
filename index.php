<?php
session_start();
include 'connect.php';
echo '
<!DOCTYPE html>
<html lang="hr-HR">
    <head>
        <meta charset="UTF-8" />
        <title>Projekt</title>
        <meta name="author" content="Miroslav Krznar"/>
        <meta name="description" content="Projekt" />
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="apple-touch-icon" sizes="180x180" href="img/favicon_io/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon_io/favicon-16x16.png">
        <link rel="manifest" href="img/favicon_io/site.webmanifest">
    </head>
    <body>
        <header>
            <a href="index.php"/><img src="img/logo.png" alt="logo" /></a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="kategorija.php?id=politique">Politique</a></li>
                    <li><a href="kategorija.php?id=immobilier">Immobilier</a></li>
                    <li><a href="administracija.php">Administracija</a></li>
                </ul>
            </nav>
            <hr />
        </header>
        <div id="helpFixed"></div>';

        if(isset($_SESSION['$username'])) {
            echo '
            <div class="logout-segm">
                <a href="logout.php" class="logout">Odjava</a>
                <p>Dobar dan, <span id="user">' . $_SESSION['$username'] . '</span>!</p>
            </div>';
        } else {
            echo '
            <div class="login-segm">
                <a href="administracija.php" class="login">Prijava</a>
                <p>Niste prijavljeni</p>
            </div>';
        }
        
        echo '
        <section class="home-sec">
            <h2>politique</h2>
            <div>';
            $query = "SELECT * FROM vijesti WHERE kategorija = 'politique' AND arhiva = 0 ORDER BY id DESC LIMIT 3";
            $result = mysqli_query($dbc, $query);
            while($row = mysqli_fetch_array($result)) {
                echo '
                <article>
                    <a href="clanak.php?id=' . $row['id'] . '">
                    <img src="img/' . $row['slika'] . '" alt="slika" /></a>
                    <div>
                        <h3><a href="clanak.php?id=' . $row['id'] . '">' . $row['naslov'] . '</a></h3>
                        <p>' . $row['kategorija'] . ' - Published on ' . $row['datum'] . '</p>
                    </div>
                </article>';
            }
        echo '
        </section>
        <section class="home-sec">
            <h2>immobilier</h2>
            <div>';
            $query = "SELECT * FROM vijesti WHERE kategorija = 'immobilier' AND arhiva = 0 ORDER BY id DESC LIMIT 3";
            $result = mysqli_query($dbc, $query);
            while($row = mysqli_fetch_array($result)) {
                echo '
                <article>
                    <a href="clanak.php?id=' . $row['id'] . '">
                    <img src="img/' . $row['slika'] . '" alt="slika" /></a>
                    <div>
                        <h3><a href="clanak.php?id=' . $row['id'] . '">' . $row['naslov'] . '</a></h3>
                        <p>' . $row['kategorija'] . ' - Published on ' . $row['datum'] . '</p>
                    </div>
                </article>';
            }

        mysqli_close($dbc);

        echo '
        </section>
        <footer>
            <p>Miroslav Krznar, mkrznar@tvz.hr - 2021.</p>
        </footer>
    </body>
</html>';
?>