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

        $kategorija = $_GET['id'];
        $query = "SELECT * FROM vijesti WHERE kategorija = '$kategorija' ORDER BY id DESC";
        $result = mysqli_query($dbc, $query);
        $rowNmb = mysqli_num_rows($result);
        $i = 0;
            
        while($row = mysqli_fetch_array($result)) {
            if($i % 3 == 0) {
                echo '<section class="home-sec">';
                if($i == 0) {
                    echo '<h2>' . $kategorija . '</h2>';
                }
                echo '<div>';
            }
            echo '
            <article>
                <a href="clanak.php?id=' . $row['id'] . '">
                <img src="img/' . $row['slika'] . '" alt="slika" /></a>
                <div>
                    <h3><a href="clanak.php?id=' . $row['id'] . '">' . $row['naslov'] . '</a></h3>
                    <p>' . $row['kategorija'] . ' - Publie il y a ' . $row['datum'] . '</p>
                </div>
            </article>';
            if($i % 3 == 2) {
                echo '</section>';
            }
            $i += 1;
            if($i == $rowNmb && $i % 3 != 0) {
                echo '</section>';
            }
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