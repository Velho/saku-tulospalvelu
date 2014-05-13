<?

/**
 * Nopeasti kyhätty surkein olemassaoleva autentikointi tapa.
 * Pää toimisesti estää väärinkäyttöä.
 * 
 * Joel Anttila 22.01.14
 */

class Tunnus {
  private $nimi;
  private $salasana;

  public function __construct($nimi, $salasana) {
    $this->nimi = $nimi;
    $this->salasana = $salasana;
  }

  public function autentikoi($nimi, $salasana) {
    if($this->nimi == $nimi)
      if($this->salasana == $salasana)
        return true;
    return false;
  }
}

class AutentikointiForm {
  private $kayttajat = array();
  const salis = "KtK011r";
  
  public function __construct() {
    array_push($this->kayttajat, new Tunnus("Julius", self::salis));
    array_push($this->kayttajat, new Tunnus("Aleksi", self::salis));
    array_push($this->kayttajat, new Tunnus("Eetu", self::salis));
    array_push($this->kayttajat, new Tunnus("Kristian", self::salis));
    array_push($this->kayttajat, new Tunnus("Suvi", self::salis));
    array_push($this->kayttajat, new Tunnus("Mohamed", self::salis));
    array_push($this->kayttajat, new Tunnus("Arttu", self::salis));
    array_push($this->kayttajat, new Tunnus("Janne", self::salis));
    array_push($this->kayttajat, new Tunnus("Jussi-Pekka", self::salis));
    array_push($this->kayttajat, new Tunnus("Ville T.", self::salis));
    array_push($this->kayttajat, new Tunnus("Ville K.", self::salis));
    array_push($this->kayttajat, new Tunnus("Benjamin", self::salis));
    array_push($this->kayttajat, new Tunnus("Joel", self::salis));
    array_push($this->kayttajat, new Tunnus("Tino", self::salis));
    array_push($this->kayttajat, new Tunnus("Matti", self::salis));
    array_push($this->kayttajat, new Tunnus("Joona", self::salis));
    array_push($this->kayttajat, new Tunnus("Henri", self::salis));
    array_push($this->kayttajat, new Tunnus("Kalle", self::salis));
    array_push($this->kayttajat, new Tunnus("Teemu", self::salis));
    array_push($this->kayttajat, new Tunnus("Hannes", self::salis));
    array_push($this->kayttajat, new Tunnus("Rami", self::salis));
    array_push($this->kayttajat, new Tunnus("Hesmatullah", self::salis));
    array_push($this->kayttajat, new Tunnus("Anne", self::salis));
    array_push($this->kayttajat, new Tunnus("Olli", self::salis));
    array_push($this->kayttajat, new Tunnus("Sebastian", self::salis));
  }

  public function form() {
      echo "<form action=\"tuloslomake.php\" method=\"post\">\n";
      echo "<input type=\"text\" name=\"nimi\" value=\"Nimi\" />\n";
      echo "<input type=\"password\" name=\"passu\" value=\"Salasana\"/>\n";
      echo "<input type=\"submit\" value=\"Autentikoi\" />\n";
      echo "</form>\n";
  }
  
  public function validoi($nimi, $salis) {
    for($i = 0; $i < count($this->kayttajat); $i++)
        if($this->kayttajat[$i]->autentikoi($nimi, $salis) == true)      
          return true;
    return false;
  }
}

?>
