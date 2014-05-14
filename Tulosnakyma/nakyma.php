<?php

set_include_path("/var/www/saku.vaolabs.fi/");

// Sisällytetään kirjasto.
require 'Kirjasto/Tietokanta.php';
require 'Kirjasto/Oppilaitos.php';
require 'Kirjasto/Laji.php';
require 'Kirjasto/Tulos.php';

/**
 * 
 *
 * Simple usage:
 * 
 * $haku = new TulosHaku();
 * $haku->getTulos("Taidetta kahdessa tunnissa");
 *
 * var_dump($haku->getSija1());
 * var_dump($haku->getSija2());
 * var_dump($haku->getSija3());
 * var_dump($haku->getKunniat());
 * 
 * $muuttuja = haku->getSija1()['j']; // Sisältää Sija 1 jaetut tulokset.
 * 
 */
class TulosHaku {
  private $laji;
  private $tulokset;
  private $oppilaitokset;
  private $sarakkeet;
  
  private $tulos;
  
  /**
   * Ottaa lajin nimen parametrinä.
   */
  public function __construct($laji_nimi) {
    $this->alusta();
    $this->tulos = $this->palautaTulos($laji_nimi);
   // var_dump($this->oppilaitokset);
  }
  
  private function alusta() {
    $this->laji = new LajiHallinta();
    $this->oppilaitokset = new OppilaitosHallinta();
    $this->tulokset = new TulosHallinta(true);
    $this->sarakkeet = $this->tulokset->getSarakkeet();
  }
  
  private function palautaTulos($lajin_nimi) {
    $laji_id = $this->laji->getIdByName($lajin_nimi);
    $laji = $this->laji->getById($laji_id);
   
    return $this->tulokset->getTulokset($laji);
  }
  
  /**
   * Jos on kyseessä oppilaitoksen id, muutetaan se oppilaitoksen nimeksi.
   */
  private function lisaaTulos($sarake) {
    if(!(is_null($this->tulos[$sarake]))) {
      if(strpos($sarake, 'oppilaitoksen_ID') !== false) {
        $laitos = $this->oppilaitokset->getNameById(intval($this->tulos[$sarake]));
        return $laitos->getNimi();
      }
    }
    return $this->tulos[$sarake];
  }
  
  public function getSija1() {
    $sija1 = array();
    $sija1_srk = $this->sarakkeet[0];
    
    foreach($sija1_srk as $val)
      $sija1[$val] = $this->lisaaTulos($val);
    
    return $sija1;
  }
  
  public function getSija2() {
    $sija2 = array();
    $sija2_srk = $this->sarakkeet[1];
    
    foreach($sija2_srk as $val)
      $sija2[$val] = $this->lisaaTulos($val);
    
    return $sija2;
  }
  
  public function getSija3() {
    $sija3 = array();
    $sija3_srk = $this->sarakkeet[2];
    
    foreach($sija3_srk as $val) 
      $sija3[$val] = $this->lisaaTulos($val);
    
    return $sija3;
  }
  
  public function getKunniat() {
    $kunniat = array();
    $kunnia_srk = $this->sarakkeet[3];
    
    foreach($kunnia_srk as $val)
      $kunniat[$val] = $this->lisaaTulos($val);
      
    return $kunniat;
  } 
}


//$haku = new TulosHaku("Taidetta kahdessa tunnissa");

/*
$j_s1 = $haku->getSija1()['s1_j'];
var_dump($j_s1); 
*/

/*
var_dump($haku->getSija2());
var_dump($haku->getSija3());
var_dump($haku->getKunniat());
*/

/**
 * Luo moniulotteisia JS taulukoita. Taulukot on eritelty hallitakseen taulukoiden kokoja.
 * Pääosin 3 taulukkoa; tulokset, kunniamaininnat ja jaetut sijat.
 * Jokaisen taulukon alkioon viitataan avaimella ja avaimena on laji merkkijonona
 * Kun laji on valittuna, kyseinen taulukko sisältää taulukoita riippuen sen pituudesta.
 * Mutta pääsääntönä on, että taulukon taulukossa on 2 arvoa jotka ovat nimi ja oppilaitos.
 * 
 * Virheidenhallintaa ei tarvita, koska undefined tyyppistä kaatumista ei tapahdu. Ainut undefined 
 * tapahtuu jaetuissa sijoissa, jos jaettua sijaa ei ole. 
 * 
 * Tämä tarkoittaa sitä, että jokainen laji on otettava huomioon vaikka ei välttämättä sisältäis tietoa.
 * 
 * Esimerkiksi
 * var tulokset = { "LAJI": [ [NIMI, OPPILAITOS], [NIMI, OPPILAITOS], [NIMI, OPPILAITOS] ] }; length = 3
 * 
 * Tämä rakenne on helppo muuttaa ns esitysmalliseksi. Käytää hyödyksi jo valmiita muuttujia vain hakea monta kertaa
 * tulokset eri lajeille TulosHaku luokall, jonka jälkeen muodostaa niistä javascript taulukot 
 *  => Javascript/JQuery hoitaa loput. (Rakennettu esitysmalli mielessä, mutta aika vaatimuksen takia jätetty pois).
 * 
 */
class JSTulos {
  private $tuloshaku;
  
  private $tulokset;
  private $kunniat;
  private $j_tulokset;
  
  // Dia esitys rakennetaan näistä palikoista.
  private $lajit;
  private $lajit_lista;
  
  private $laji_nimi;
  private $oppilaitos;
   
  /**
   * Alustaa TulosHaun. 
   */
  public function __construct($laji_nimi) {
    $this->laji_nimi = $laji_nimi;
    $this->tuloshaku = new TulosHaku($laji_nimi);
    
    $this->muodostaSijaTaulukko();
    $this->muodostaKunniaTaulukko();
  }
  
  /**
   * Muodostetaan javascript taulukko sijoituksille.
   * 
   * TODO Mahdollinen tuki monelle tulokselle, mutta tällä hetkellä tarvitaan tuki vain yhdelle lajille.
   * Muutos saadaan looppaamalla lajit ja muodostamalla taulukot sisältäen vaadittavat lajit järjestyksessä.
   */ 
  private function muodostaSijat() {
    $sija1 = $this->tuloshaku->getSija1();
    $sija2 = $this->tuloshaku->getSija2();
    $sija3 = $this->tuloshaku->getSija3();
    
    $tulos = "";

    $tulos .= "\"" . $this->laji_nimi . "\":[" . 
      $this->muodostaPari($sija1['kultaa_nimi'], $sija1['kultaa_oppilaitoksen_ID']) . ", ";
    
    $tulos .= "" . $this->muodostaPari($sija2['hopeaa_nimi'], $sija2['hopeaa_oppilaitoksen_ID']) . ",";
    $tulos .= "" . $this->muodostaPari($sija3['pronssia_nimi'], $sija3['pronssia_oppilaitoksen_ID']) . "]";

    
    $this->tulokset .= $tulos;
  }

  private function muodostaJaetutTulokset() {
    $sija1 = $this->tuloshaku->getSija1();
    $sija2 = $this->tuloshaku->getSija2();
    $sija3 = $this->tuloshaku->getSija3();  
      
    $j_tulos = "";
    
    $j_tulos .= "\"" . $this->laji_nimi . "\":[" . 
      $this->muodostaPari($sija1['kultaa_jaettu_nimi'], $sija1['kultaa_jaettu_oppilaitoksen_ID']) . ", ";
    $j_tulos .= "" . $this->muodostaPari($sija2['hopeaa_jaettu_nimi'], $sija2['hopeaa_jaettu_oppilaitoksen_ID']) . ", ";
    $j_tulos .= "" . $this->muodostaPari($sija3['pronssia_jaettu_nimi'], $sija3['pronssia_jaettu_oppilaitoksen_ID']) . "]";    
    
    $this->j_tulokset .= $j_tulos;
  }
  
  public function muodostaKunniat() {
    $knn_srk = $this->tuloshaku->getKunniat();
    $parit = array();
    
    $knn = "";
    
    $knn .= "\"" . $this->laji_nimi . "\":[";
    
    // Muodostetaan parit kunniamaininnoista.
    for($i = 1; $i <= 10; $i++) 
      if(!(empty($knn_srk["kunniamaininta" . $i . "_nimi"])))
        array_push($parit, $this->muodostaPari($knn_srk["kunniamaininta" . $i . "_nimi"], $knn_srk["kunniamaininta" . $i . "_oppilaitoksen_ID"]));
      
    for($i = 0; $i < count($parit); $i++)
      if($i !== (count($parit) - 1))
        $knn .= $parit[$i] . ", ";
      else
        $knn .= $parit[$i] . "]";
    
    if(count($parit) === 0)
      $knn .= "]";
    
    $this->kunniat .= $knn; 
  }
  
  private function muodostaPari($nimi, $oppilaitos) {
    if(empty($nimi))
      return "[]";  
    return "[\"" . $nimi . "\", \"" . $oppilaitos . "\"]";
  }
  
  private function muodostaSijaTaulukko() {
    $this->tulokset = "";
    $this->tulokset .= "tulokset = { ";
    $this->muodostaSijat();
    $this->tulokset .= " };";
    
    $this->j_tulokset = "";
    $this->j_tulokset .= "j_tulokset = { ";
    $this->muodostaJaetutTulokset();
    $this->j_tulokset .= " };";
  }
  
  private function muodostaKunniaTaulukko() {
    $this->kunniat = "";
    $this->kunniat .= "kunniat = { ";
    $this->muodostaKunniat();
    $this->kunniat .= " };";
  }
  
  public function getTulokset() {
    return $this->tulokset;
  }
  
  public function getJaetutTulokset() {
    return $this->j_tulokset;
  }
  
  public function getKunniat() {
    return $this->kunniat;
  }
} 
?>