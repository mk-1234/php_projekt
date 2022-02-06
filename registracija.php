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

        $msg = '';
    
    if(isset($_POST['prijava'])) {
        
        $ime = $_POST['ime'];
        $prezime = $_POST['prezime'];
        $username = $_POST['username'];
        $lozinka = $_POST['pass'];
        $hashed_password = password_hash($lozinka, CRYPT_BLOWFISH);
        $razina = 0;
        $registriranKorisnik = '';
        
        //Provjera postoji li u bazi već korisnik s tim korisničkim imenom
        $sql = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }
        if(mysqli_stmt_num_rows($stmt) > 0){
            $msg='Korisničko ime već postoji!';
        } else {
            // Ako ne postoji korisnik s tim korisničkim imenom - Registracija korisnika 
            //u bazi pazeći na SQL injection
            $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka,
                    razina) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 'ssssd', $ime, $prezime, $username, $hashed_password, $razina);
                mysqli_stmt_execute($stmt);
                $registriranKorisnik = true;
            }
        }
        mysqli_close($dbc);
        if($registriranKorisnik == true) {
            echo '<p class="succ-login">Korisnik je uspješno registriran!</p>';
        } else {
            echo '
            <p class="bad-login">Dogodila se greška u procesu registracije, molim <a href="registracija.php">pokušajte ponovo!</a></p>
            <p class="bad-login">' . $msg . '<p>';
        }
    } else {

        echo '
        <section role="main">
            <form enctype="multipart/form-data" class="reg-form" action="" method="POST">
                <div class="form-item">
                    <label for="title">Ime: </label>
                    <div class="form-field">
                        <input type="text" name="ime" id="ime" class="form-field-textual">
                        <span id="porukaIme" class="bojaPoruke"></span>
                    </div>
                </div>
                <div class="form-item">
                    <label for="about">Prezime: </label>
                    <div class="form-field">
                        <input type="text" name="prezime" id="prezime" class="form-field-textual">
                        <span id="porukaPrezime" class="bojaPoruke"></span>
                    </div>
                </div>
                <div class="form-item">
                    <label for="content">Korisničko ime:</label>
                    <br><span class="bojaPoruke">' . $msg . '</span>
                    <div class="form-field">
                        <input type="text" name="username" id="username" class="form-field-textual">
                        <span id="porukaUsername" class="bojaPoruke"></span>
                    </div>
                </div>
                <div class="form-item">
                    <label for="pass">Lozinka: </label>
                    <div class="form-field">
                        <input type="password" name="pass" id="pass" class="form-field-textual">
                        <span id="porukaPass" class="bojaPoruke"></span>
                    </div>
                </div>
                <div class="form-item">
                    <label for="passRep">Ponovite lozinku: </label>
                    <div class="form-field">
                        <input type="password" name="passRep" id="passRep" class="form-field-textual">
                        <span id="porukaPassRep" class="bojaPoruke"></span>
                    </div>
                </div><br />
                <div class="form-item">
                    <button type="submit" name="prijava" value="Prijava" id="slanje">Registracija</button>
                </div>
            </form>
        </section>';
    }

    echo '
    
    <footer>
        <p>Miroslav Krznar, mkrznar@tvz.hr - 2021.</p>
    </footer>';
    ?>

    <script type="text/javascript">
        document.getElementById("slanje").onclick = function(event) {
            var slanjeForme = true;

            // Ime korisnika mora biti uneseno
            var poljeIme = document.getElementById("ime");
            var ime = document.getElementById("ime").value;
            if (ime.length == 0) {
                slanjeForme = false;
                poljeIme.style.border="1px dashed red";
                document.getElementById("porukaIme").innerHTML="<br>Unesite ime!<br>";
            } else {
                poljeIme.style.border="1px solid green";
                document.getElementById("porukaIme").innerHTML="";
            }

            // Prezime korisnika mora biti uneseno
            var poljePrezime = document.getElementById("prezime");
            var prezime = document.getElementById("prezime").value;
            if (prezime.length == 0) {
                slanjeForme = false;
                poljePrezime.style.border="1px dashed red";
                document.getElementById("porukaPrezime").innerHTML="<br>Unesite Prezime!<br>";
            } else {
                poljePrezime.style.border="1px solid green";
                document.getElementById("porukaPrezime").innerHTML="";
            }

            // Korisničko ime mora biti uneseno
            var poljeUsername = document.getElementById("username");
            var username = document.getElementById("username").value;
            if (username.length == 0) {
                slanjeForme = false;
                poljeUsername.style.border="1px dashed red";
                document.getElementById("porukaUsername").innerHTML="<br>Unesite korisničko ime!<br>";
            } else {
                poljeUsername.style.border="1px solid green";
                document.getElementById("porukaUsername").innerHTML="";
            }

            // Provjera podudaranja lozinki
            var poljePass = document.getElementById("pass");
            var pass = document.getElementById("pass").value;
            var poljePassRep = document.getElementById("passRep");
            var passRep = document.getElementById("passRep").value;
            if (pass.length == 0 || passRep.length == 0 || pass != passRep) {
                slanjeForme = false;
                poljePass.style.border="1px dashed red";
                poljePassRep.style.border="1px dashed red";
                document.getElementById("porukaPass").innerHTML="<br>Lozinke nisu iste!<br>";
                document.getElementById("porukaPassRep").innerHTML="<br>Lozinke nisu iste!<br>";
            } else {
                poljePass.style.border="1px solid green";
                poljePassRep.style.border="1px solid green";
                document.getElementById("porukaPass").innerHTML="";
                document.getElementById("porukaPassRep").innerHTML="";
            }
            if (slanjeForme != true) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>