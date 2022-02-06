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
        <div id="helpFixed"></div>
        ';

        //session_unset();
        $uspjesnaPrijava = false;

        if(isset($_POST['delete'])){
            $id = $_POST['id'];
            $query = "DELETE FROM vijesti WHERE id = $id";
            $result = mysqli_query($dbc, $query);
        }

        if(isset($_POST['update'])) {
            $picture = $_FILES['pphoto']['name'];
            $title = $_POST['title'];
            $about = $_POST['about'];
            $content = $_POST['content'];
            $category = $_POST['category'];
            if(isset($_POST['archive'])){
                $archive = 1;
            } else {
                $archive = 0;
            }

            $target_dir = 'img/' . $picture;
            move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_dir);
            $id = $_POST['id'];
            $query = "UPDATE vijesti SET naslov = '$title', sazetak = '$about', tekst = '$content',
                slika = '$picture', kategorija = '$category', arhiva = '$archive' WHERE id = $id";
            $result = mysqli_query($dbc, $query);
        }


        if(isset($_POST['prijava'])) {
            // Provjera da li korisnik postoji u bazi uz zaštitu od SQL injectiona
            $prijavaImeKorisnika = $_POST['username'];
            $prijavaLozinkaKorisnika = $_POST['lozinka'];

            $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
            }
            mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
            mysqli_stmt_fetch($stmt);
            
            //Provjera lozinke
            if (password_verify($_POST['lozinka'], $lozinkaKorisnika) && mysqli_stmt_num_rows($stmt) > 0) {
                $uspjesnaPrijava = true;
                // Provjera da li je admin
                if($levelKorisnika == 1) {
                    $admin = true;
                } else {
                    $admin = false;
                }
                //postavljanje session varijabli
                $_SESSION['$username'] = $imeKorisnika;
                $_SESSION['$level'] = $levelKorisnika;
            } else {
                $uspjesnaPrijava = false;
                session_unset();
            }
        }

        if (($uspjesnaPrijava == true && $admin == true) || 
            (isset($_SESSION['$username'])) && $_SESSION['$level'] == 1) {

        echo '
        <div class="logout-segm">
            <a href="logout.php" class="logout">Odjava</a>
            <p>Dobar dan, <span id="user">' . $_SESSION['$username'] . '</span>!</p>
        </div>
        <p class="odabir-unosa"><a href="unos.html">Unos nove vijesti</a></p>';

        $query = "SELECT * FROM vijesti ORDER BY id DESC";
        $result = mysqli_query($dbc, $query);
        while($row = mysqli_fetch_array($result)) {
            echo '
            <form enctype="multipart/form-data" class="mod-form" action="" method="POST">
                <div class="form-item">
                    <label for="title">Naslov vijesti:</label>
                    <div class="form-field">
                        <input type="text" name="title" class="form-field-textual" value="' . $row['naslov'] . '">
                    </div>
                </div>
                <div class="form-item">
                    <label for="about">Kratki sadržaj vijesti (do 50 znakova):</label>
                    <div class="form-field">
                        <textarea name="about" id="" cols="30" rows="10" class="form-field-textual">' . $row['sazetak'] . '</textarea>
                    </div>
                </div>
                <div class="form-item">
                    <label for="content">Sadržaj vijesti:</label>
                    <div class="form-field">
                        <textarea name="content" id="" cols="30" rows="10" class="form-field-textual">' . $row['tekst'] . '</textarea>
                    </div>
                </div>
                <div class="form-item">
                    <label for="pphoto">Slika:</label>
                    <div class="form-field">
                        <input type="file" accept="image/png" class="input-text" id="pphoto" 
                            value="' . $row['slika'] . '" name="pphoto"/> <br><img src="img/' .
                            $row['slika'] . '" width=100px>
                    </div>
                </div>
                <div class="form-item">
                    <label for="category">Kategorija vijesti:</label>
                    <div class="form-field">
                        <select name="category" id="" class="form-field-textual" value="' . $row['kategorija'] . '">
                            <option value="Politique"'; 
                                if($row['kategorija'] == 'Politique') {
                                    echo ' selected';
                                }
                                echo '>Politique</option>
                            <option value="Immobilier"'; 
                                if($row['kategorija'] == 'Immobilier') {
                                    echo ' selected';
                                }
                                echo '>Immobilier</option>
                        </select>
                    </div>
                </div>
                <div class="form-item">
                    <label>Spremiti u arhivu:
                    <div class="form-field">';
                        if($row['arhiva'] == 0) {
                        echo '<input type="checkbox" name="archive" id="archive"/>Arhiviraj?';
                        } else {
                        echo '<input type="checkbox" name="archive" id="archive" checked/>Arhiviraj?';
                        }
                    echo '
                    </div>
                    </label>
                </div>
            </div>
                <div class="form-item">
                    <input type="hidden" name="id" class="form-field-textual" value="' . $row['id'] . '">
                    <button type="reset" value="Poništi">Poništi</button>
                    <button type="submit" name="update" class="update" value="Prihvati">Izmjeni</button>
                    <button type="submit" name="delete" class="delete" value="Izbriši">Izbriši</button>
                </div>
                <br /><hr /><br />
            </form>';
        }
        } elseif ($uspjesnaPrijava == true && $admin == false) {
            echo '
            <div class="logout-segm">
                <a href="logout.php" class="logout">Odjava</a>
                <p>Dobar dan, <span id="user">' . $_SESSION['$username'] . '</span>!</p>
            </div>
            <p class="msg-not-admin">Bok ' . $imeKorisnika . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
        } elseif (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
            echo '
            <div class="logout-segm">
                <a href="logout.php" class="logout">Odjava</a>
                <p>Dobar dan, <span id="user">' . $_SESSION['$username'] . '</span>!</p>
            </div>
            <p class="msg-not-admin">Bok ' . $_SESSION['$username'] . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
        } elseif ($uspjesnaPrijava == false) {
            ?>
            <form action="" class="login-form" method="POST">
                <div>    
                    <label>Unesite korisničko ime:</label><br />
                    <input type="text" name="username" id="username" />
                    <span id="porukaUsername" class="bojaPoruke"></span>
                </div>
                <div>
                    <label>Unesite lozinku:</label><br />
                    <input type="password" name="lozinka" id="lozinka" />
                    <span id="porukaPass" class="bojaPoruke"></span>
                </div>
                <p><a href="registracija.php">Registracija</a></p>
                <button type="submit" name="prijava" id="slanje" value="Prijava">Prijava</button>
            </form>

            <script type="text/javascript">
                document.getElementById("slanje").onclick = function(event) {
                    var slanjeForme = true;
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
                    // Lozinka mora biti unesena
                    var poljeLozinka = document.getElementById("lozinka");
                    var lozinka = document.getElementById("lozinka").value;
                    if (lozinka.length == 0) {
                        slanjeForme = false;
                        poljeLozinka.style.border="1px dashed red";
                        document.getElementById("porukaPass").innerHTML="<br>Unesite lozinku!<br>";
                    } else {
                        poljeLozinka.style.border="1px solid green";
                        document.getElementById("porukaPass").innerHTML="";
                    }
                    if (slanjeForme != true) {
                        event.preventDefault();
                    }
                }
            </script>
        <?php
        }
        mysqli_close($dbc);
        
        echo '
        <footer>
            <p>Miroslav Krznar, mkrznar@tvz.hr - 2021.</p>
        </footer>
    </body>
</html>';
?>