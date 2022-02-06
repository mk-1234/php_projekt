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

        $query = "SELECT * FROM vijesti WHERE id = " . $_GET['id'];
        $result = mysqli_query($dbc, $query);
        while($row = mysqli_fetch_array($result)) {
            $nazivMjeseca = dohvatiNaziv(substr($row['datum'], 3, 2));
            echo '
            <section class="clanak-sec">
                <p><a href="index.php">L\'Obs</a> > 
                <a href="kategorija.php?id=' . $row['kategorija'] . '">' . $row['kategorija'] . '</a></p>
                <h1>' . $row['naslov'] . '</h1>
                <img src=\'img/' . $row['slika'] . '\'" />
                <p>' . $row['sazetak'] . '</p>
                <p>Published on ' . substr($row['datum'], 0, 2) . ' ' . $nazivMjeseca . ' '
                     . substr($row['datum'], 6, 4) . ' at '
                     . substr($row['datum'], 11, 2) . 'h' . substr($row['datum'], 14, 2) . '</p>
                <p>' . $row['tekst'] . '</p>
            </section>';
        }

        mysqli_close($dbc);
        
        echo '
        <footer>
            <p>Miroslav Krznar, mkrznar@tvz.hr - 2021.</p>
        </footer>
    </body>
</html>';

function dohvatiNaziv($mjesec) {
    switch ($mjesec) {
        case 1:
            return 'jan';
            break;
        case 2:
            return 'feb';
            break;
        case 3:
            return 'mar';
            break;
        case 4:
            return 'apr';
            break;
        case 5:
            return 'may';
            break;
        case 6:
            return 'jun';
            break;
        case 7:
            return 'jul';
            break;
        case 8:
            return 'aug';
            break;
        case 9:
            return 'sep';
            break;
        case 10:
            return 'oct';
            break;
        case 11:
            return 'nov';
            break;
        case 12:
            return 'dec';
            break;
        
        default:
            return 'greška!';
            break;
    }
}
?>