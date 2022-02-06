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

        $title = $_POST['title'];
        $about = $_POST['about'];
        $content = $_POST['content'];
        $picture = $_FILES['pphoto']['name'];
        $category = $_POST['category'];
        $date = date('d.m.Y H:i');
        if(isset($_POST['archive'])){
            $archive = 1;
        }else{
            $archive = 0;
        }
        $nazivMjeseca = dohvatiNaziv(substr($date, 3, 2));

        $target_dir = 'img/'. $picture;
        move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_dir);
        $query = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija,
                    arhiva) VALUES ('$date', '$title', '$about', '$content', '$picture',
                    '$category', '$archive')";
        $result = mysqli_query($dbc, $query) or die('Error querying databese.');
        mysqli_close($dbc);

        echo '
        <section class="clanak-sec">
            <p><a href="index.php">L\'Obs</a> > 
            <a href="kategorija.php?id=' . $category . '">' . $category . '</a></p>
            <h1>' . $title . '</h1>
            <img src=\'img/' . $picture . '\'" />
            <p>' . $about . '</p>
            <p>Published on ' . substr($date, 0, 2) . ' ' . $nazivMjeseca . ' ' . substr($date, 6, 4) . ' at ' .
                    substr($date, 11, 2) . 'h' . substr($date, 14, 2) . '</p>
            <p>' . $content . '</p>
        </section>
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