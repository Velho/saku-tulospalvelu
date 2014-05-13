<?php 
/**
 * Joel Anttila 2014
 */


set_include_path("/var/www/saku.vaolabs.fi/");

require("Kirjasto/Tietokanta.php");
require("Kirjasto/Oppilaitos.php");
require("Kirjasto/Laji.php");
require("Kirjasto/Tulos.php");

require("Tuloslomake/autentikointi.php");

function foo() {
    $yhteys = new Tietokanta (/** DATABASE INFORMATION **/);
    $lajit = new LajiHallinta($yhteys);
    $tulokset = new TulosHallinta($yhteys, true);
    $laji = $lajit->getById(2);
    //var_dump($tulokset->palautaSarakkeet());
    var_dump($tulokset->checkTulosByLaji($laji));
}


/**
 * Tulostetaan <option> elementtiin laji valinnat.
 */
function LajiValinnat() {
    $yhteys = new Tietokanta(/** DATABASE INFORMATION **/);
    $lajit = new LajiHallinta ( $yhteys );
    
    foreach ( $lajit->getNimet () as $nimi ) {
        echo '<option>';
        echo $nimi;
        echo '</option>';
    }
}
/**
 * Luo yhteyden tietokantaan ja hakee OppilaitosHallinta luokan avulla oppilaitoksien nimet.
 * @return Palauttaa taulukon oppilaitosten nimist�.
 */
function getOppilaitosNimet() {
    $yhteys = new Tietokanta(/** DATABASE INFORMATION **/);
    $laitokset = new OppilaitosHallinta ( $yhteys );
    
    return $laitokset->getNimet ();
}
/**
 * Luo <option> elementit oppilaitosten nimien perusteella.
 */
function OppilaitosValinnat() {
    $laitokset = getOppilaitosNimet ();
    foreach ( $laitokset as $nimi ) {
        echo '<option value="';
        echo $nimi;
        echo '">';
        echo $nimi; 
        echo '</option>';
    }
}
/**
 * Luo listan Oppilaitoksien nimist� pilkulla(,) eroteltuna.
 * JS k�ytt��n.
 */
function OppilaitosNimet() {
    $laitokset = getOppilaitosNimet ();
    
    if(count($laitokset) > 1) {
        foreach ( $laitokset as $nimi ) {
            if($nimi === $laitokset[0]) // Ensinm�isen nimen kohdalla ei lis�t� pilkkua.
              echo $nimi;
            else {
              echo ',';
              echo $nimi;
            }
        }
    } else {
        echo $laitokset[0]; // ns. mahdoton tilanne, koska ei ole vain yht� oppilaitosta. Jos on niin ei sis�llytet� pilkkua merkkijonoon.
    }
}
?>



<!DOCTYPE html>
<html>
  <head>
    <title>Tuloslomake</title>
  </head>
  <body>
  	<script src="js/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="tuloslomake.js"></script>
    <p><img src="saku.png" alt="Sakustars" width="255" height="230" /></p>
	<center>
<? 
$autti = new AutentikointiForm(); 

$nimi = empty($_POST['nimi']) ? '' : $_POST['nimi'];
$passu = empty($_POST['passu']) ? '' : $_POST['passu'];


if($autti->validoi($nimi, $passu)) {
   
      echo "<form action=\"tallennalomake.php\" method=\"get\">\n";
      echo '<select name="lajit_valinta">';
      echo LajiValinnat();
      echo '</select></br>';
      echo "<table id=\"sijoitus_taulu\">\n";
      echo "<tr>\n<td></td>\n<td>Sijoitus</td>\n<td>Nimi</td>\n<td>Pisteet</td>\n<td>Oppilaitos</td>\n</tr>\n";
      
      echo "<tr>\n";
      echo '<td><input type="button" value="Jaettu" onclick="lisaaJaettu(1, \'';
      echo OppilaitosNimet();
      echo '\')" /></td>';
      echo "<td>1</td>";
      echo "<td><input type=\"text\" name=\"kultaa_nimi\" /></td>\n";
      echo "<td><input type=\"text\" name=\"kultaa_pisteet\" /></td>\n";
      echo '<td><select name="kultaa_oppilaitoksen_ID">';
      echo OppilaitosValinnat();
      echo '</select></td>';
      echo "</tr>\n";

      echo "<tr>\n";
      echo '<td><input type="button" value="Jaettu" onclick="lisaaJaettu(2, \'';
      echo OppilaitosNimet();
      echo '\')" /></td>';
      echo "<td>2</td>\n";
      echo "<td><input type=\"text\" name=\"hopeaa_nimi\" /></td>\n";
      echo "<td><input type=\"text\" name=\"hopeaa_pisteet\" /></td>\n";
      echo '<td><select name="hopeaa_oppilaitoksen_ID">';
      echo OppilaitosValinnat();
      echo '</select></td>';
      echo "</tr>\n";

      echo "<tr>\n";
      echo '<td><input type="button" value="Jaettu" onclick="lisaaJaettu(3, \'';
      echo OppilaitosNimet();
      echo '\')" /></td>';
      echo "<td>3</td>\n";
      echo "<td><input type=\"text\" name=\"pronssia_nimi\" /></td>\n";
      echo "<td><input type=\"text\" name=\"pronssia_pisteet\" /></td>\n";
      echo '<td><select name="pronssia_oppilaitoksen_ID">';
      echo OppilaitosValinnat();
      echo '</select></td>';
      echo "</tr>\n";
      
      echo "<tr>\n";
      echo '<td><input type="button" value="Lisää" onclick="lisaaKunnia(\'';
      echo OppilaitosNimet();
      echo '\')" /></td>';
      echo "<td>Kunnia</td>\n";
      echo "<td><input type=\"text\" name=\"kunniamaininta1_nimi\" /></td>\n";
      echo "<td><input type=\"text\" name=\"kunniamaininta1_pisteet\" /></td>\n";
      echo '<td><select name="kunniamaininta1_oppilaitoksen_ID">';
      echo OppilaitosValinnat();
      echo '</select></td>';
      echo "</tr>\n";
      echo "</table>\n<input type=\"hidden\" name=\"luoja\" value=\"" . $nimi . "\" />\n<input type=\"submit\" class=\"laheta\" value=\"Tallenna\" />\n</form>\n";
} else {
  $autti->form();
}
?>
	</center>
	
	    <script>
			$("input.laheta").bind('click', function() {
				if(!confirm('Haluatko varmasti tallentaa seuraavat tiedot tietokantaan?'))
					return false;
			});
    </script>
	
  </body>
</html>
