<?

/**
 * Lomake lähettää kaikki vakio asetukset(vaikka tekstikenttiä ei ole täytetty):
 * - Oppilaitos
 */

set_include_path("/var/www/saku.vaolabs.fi/");

require("Kirjasto/Tietokanta.php");
require("Kirjasto/Laji.php");
require("Kirjasto/Oppilaitos.php");
require("Kirjasto/Tulos.php");

function required() {
    $required = array('sijoitus1_nimi', 'sija1jaettu_nimi', 'sijoitus2_nimi', 'sija2jaettu_nimi');
    
    foreach($required as $arvo) {
      var_dump($_POST[$arvo]);
      if(empty($_POST[$arvo])) {
        return false;
       }
    }
    return true;
}

$tuloshallinta = new TulosHallinta(false); 
$lajit = new LajiHallinta();
$oppilaitokset = new OppilaitosHallinta();

$l_id = $lajit->getIdByName($_GET["lajit_valinta"]);
$laji = $lajit->getById($l_id);
//var_dump($laji);
//$tuloshallinta->tallennaTulokset($laji);
$tuloshallinta->setOppilaitokset($oppilaitokset);
//$tuloshallinta->foo($laji);
$tuloshallinta->tallennaKysely($laji);
//var_dump($_GET);
?>


<!DOCTYPE html>
<html>
    <head><title>Tietojen tallennus...</title></head>
    <body>
        <a href="http://saku.vaolabs.fi/Tuloslomake/tuloslomake.php">Palaa tästä lomakkeelle</a><br />
        <a href="http://saku.vaolabs.fi/nakyma/tulosnakyma.php?laji=<?php echo $_GET["lajit_valinta"]; ?>">Näytä laji tulosnäkymässä.</a>
    </body>
</html>
