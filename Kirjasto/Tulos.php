<?

/**
 * Joel Anttila 2014
 * 
 * TODO: Poista tarpeeton koodi(myös kommentoidut).
 * TODO: Lisää virhehallintaa. (funktio setOppilaitos()).
 *
 * TÄRKEIN:
 * TODO: Korjaa SQL lauseke.
 */

/**
 * Tulos objektia ei saa vastaamaan kannassa olevaa taulua. 
 * Taulu ei ole järkevästi suunniteltu vastaamaan oliomallia.
 * Vaikka mahdollisesti olisi jonkinmoinen voinut,
 * mutta aivan turhaan. => Yksi tieto vastaa yhtä lajia, jne.
 
class Tulos {
	const KULTA = 1;
	const HOPEA = 2;
	const PRONSSI = 3;
	const KUNNIA = 4;
	
	// private $mId;
	private $mSijoitus;
	private $mNimi;
	private $mPisteet;
    private $mLaji;
	private $mOppilaitos;
    private $mJaettu;
    
	public function __construct($sijoitus, $nimi) {
	  $this->mSijoitus = $sijoitus;
	  $this->mNimi = $nimi;
	  $this->mPisteet = 0;
      $this->mLaji = 0;
	  $this->mOppilaitos = 0;
      $this->mJaettu = false;
	}
    

	public function __construct($sijoitus, $nimi, $laji ,$pisteet, $oppilaitos, $jaettu) {
		$this->mSijoitus = $sijoitus;
		$this->mNimi = $nimi;
		$this->mPisteet = $pisteet;
        $this->mLaji = $laji;
		$this->mOppilaitos = $oppilaitos;
        $this->mJaettu = $jaettu;
	}
	
	public function getSijoitus() {
		return $this->mSijoitus;
	}
	public function getNimi() {
		return $this->mNimi;
	}
	public function getPisteet() {
		return $this->mPisteet;
	}
    public function getLaji() {
        return $this->mLaji;
    }
	public function getOppilaitos() {
		return $this->mOppilaitos;
	}
    public function isJaettu() {
        return $this->mJaettu;
    }
    
    public function setSijoitus($sija) {
        $this->mSijoitus = $sija;
    }
    public function setNimi($nimi) {
        $this->mNimi = $nimi;
    }
    public function setPisteet($pisteet) {
        $this->mPisteet = $pisteet;
    }
    public function setLaji($laji) {
        $this->mLaji = $laji;
    }
    public function setOppilaitos($op) {
        $this->mOppilaitos = $op;
    }
    public function setJaettu($jaettu) {
        $this->mJaettu = $jaettu;
    }
} */

/*
 * Tulos tiedot lähetetty tänne. ($POST)
 * 
 * sijoitus1_nimi=Kalle&sijoitus1_pisteet=10&sija1_laitos=Oulun+Seudun+ammattiopisto
 * &jaettu1_nimi=Hannes&jaettu1_pisteet=10&sija1jaettu_laitos=Omnia
 * &sijoitus2_nimi=Joel&sijoitus2_pisteet=8&sija2_laitos=Vaasan+ammattiopisto
 * &sijoitus3_nimi=&sijoitus3_pisteet=&sija3_laitos=Jyv%3Fskyl%3Fn+ammattiopisto
 * &kunnia_nimi%5B%5D=Tero&kunnia_pisteet%5B%5D=&sijak_laitos=Keuda
 *
 * Proto tavoitteet:
 * - Kunnia -> Testattu 
 * - Jaettu
 * - Sijoitukset
 * - TALLENNA
 *
 * $mTulos jäsen on Tulos taulukko, koska kannassa ei ole erikseen Tuloksia vaan yksi iso Tulos läjä johon kuuluu
 * kaikki 10 kunnia mainintaa, erikseen jaetut sijat sekä sijoitukset yleensä.
 *
 * Jonkinlainen varmistus siihen, ettei tule samasta lajista kahta merkintää. Ei mahdollista tietokannan suhteen, 
 * koska pääavaimena on lajiId. Lätkäse joku ilmotus siitä ruudulle.
 * 
 * (Parissa kohtaa indent 2 spaces kun taas joissakin kohtaa 4. Tasota!! Ja laita koulun koneelle 4 indent!)
 *
 * TODO: Poista turhat var_dumpit => Käytetty debuggauksessa.
 */
class TulosHallinta {
  private $mKanta;
  private $mOppilaitokset;
  
  public function __construct(Tietokanta $kanta, $nakyma) {
    $this->mKanta = $kanta;
    
    // Jätetään alustamatta jos ei ole kyseessä Näkymä.
    if($nakyma)
      $this->mOppilaitokset = new OppilaitosHallinta($kanta);
  }
  
  public function setOppilaitokset($op) { $this->mOppilaitokset = $op; }
  
  /**
   * [0] => Sija 1
   * [1] => Sija 2
   * [2] => Sija 3
   * [3] => Kunnia
   */
    public function getTulokset(Laji $laji) {
        $rslt = array();
        $tulokset = $this->mKanta->Kysely("SELECT * FROM tulokset WHERE laji_ID=" . $laji->getId() . ";");
        $sarakkeet = $this->getSarakkeet();
    
        while($rivi = $tulokset->fetch_assoc()) {
            // Loopataan sarakkeet ja tallennetaan tiedot moniulotteiseen taulukkoon.
            // Tiedon saa haettua antamalla sarakkeen avaimena taulukolle.
            for($i = 0; $i < count($sarakkeet); $i++) {
                foreach($sarakkeet[$i] as $r)
                    $rslt[$r] = $rivi[$r];
            }
        }
        return $rslt;
    }
    
    /**
     * Palauttaa taulukon lajeista, joiden tulokset ovat syötetty.
     */
    public function availableTulokset() {
      $kysely = $this->mKanta->Kysely("SELECT laji_ID FROM tulokset;");
      $tulokset = array();
      
      while($rivi = $kysely->fetch_assoc()) {
        array_push($tulokset, $rivi);
      }
      
      return $tulokset;
    }
    
    /**
     * Tarkistaa onko Tulokset olemassa Laji kohtaisesti.
     * Palauttaa tosi, jos laji on olemassa.
     */
    public function checkTulosByLaji(Laji $laji) {
        $kysely = $this->mKanta->Kysely("SELECT * FROM tulokset WHERE laji_ID=" . $laji->getId() . ";");
        return $kysely->num_rows ? true : false;
    }

  /**
   * Palauttaa moniulotteisen taulukon tulokset taulun sarakkeista.
   */
    public function getSarakkeet() {
        $sarake_kulta = "kultaa_";
        $sarake_hopea = "hopeaa_";
        $sarake_pronssi = "pronssia_";
        $ominaisuudet = array("nimi", "pisteet", "oppilaitoksen_ID");
        $result = array();
    
        $kultaa = array();
        $hopeaa = array();
        $pronssia = array();
        $kunnia = array();
    
        // Määritetään kullan sarakkeet.
        for($i = 0; $i < 3; $i++)
          array_push($kultaa, $sarake_kulta . $ominaisuudet[$i]);
        // Kulta jaettu
        for($i = 0; $i < 3; $i++)
          array_push($kultaa, $sarake_kulta . "jaettu_" . $ominaisuudet[$i]);
    
        // Määritetään hopean sarakkeet.
        for($i = 0; $i < 3; $i++)
            array_push($hopeaa, $sarake_hopea . $ominaisuudet[$i]);
        // Hopea jaettu
        for($i = 0; $i < 3; $i++)
          array_push($hopeaa, $sarake_hopea . "jaettu_" . $ominaisuudet[$i]);
        // Määritetään pronssin sarakkeet.
        for($i = 0; $i < 3; $i++)
            array_push($pronssia, $sarake_pronssi . $ominaisuudet[$i]);
        // pronssi jaetut
        for($i = 0; $i < 3; $i++)
          array_push($pronssia, $sarake_pronssi . "jaettu_" . $ominaisuudet[$i]);

        // Määritetään kunniamaininnan sarakkeet.
        for($i = 1; $i <= 10; $i++)
            for($j = 0; $j < 3; $j++)
                array_push($kunnia, "kunniamaininta" . $i . "_" . $ominaisuudet[$j]);
        /*        
        asort($kultaa);
        asort($hopeaa);
        asort($pronssia); */
        
        array_push($result, $kultaa); // Pusketaan kullat + jaetut.
        array_push($result, $hopeaa); // Pusketaan hopeat + jaetut.
        array_push($result, $pronssia); // Pusketaan pronssit + jaetut.
        array_push($result, $kunnia);
        
        return $result;
    }
  
  /**
   * Revi lomakkeelta kunniamaininnat.
   * Funktio olettaa, että Oppilaitokset muuttuja on asetettu(!!!!!!!!!).
   * 
   * TODO Varmista ettei $this->getSarakkeet()[3]; ole syntaksi virhe.
   */
  private function kunniaTulos() {
    $sarakkeet = $this->getSarakkeet()[3];
    $rslt = array();

    for($i = 0; $i < count($sarakkeet); $i++) {
      $srk = $sarakkeet[$i];
      
      // Korjaa bugin missä pisteet jäi huomioimatta, koska arvo 0 on empty. Vaikutus: SQL kyselyn luomiseen.
      if(strpos($srk, 'pisteet') !== false)
        $rslt[$srk] = 0; 

      //echo (empty($_GET[$srk]) ? "" : $_GET[$srk]) . "\n";
      if(!empty($_GET[$srk])) {
        if(strpos($srk, 'oppilaitoksen_ID') !== false) { // Tässä kohtaa ID tarkoittaa nimeä.
          $oppilaitos_id = $this->mOppilaitokset->getIdByName($_GET[$srk]);
          $rslt[$srk] = $oppilaitos_id;
        } else 
          $rslt[$srk] = $_GET[$srk];
      }
    }
    return $rslt;
  }
  
  /**
   * Yritin mahdollisimman yksinkertaisesti tuoda esille tuloksien hakemista.
   * Dumppaa! 
   *
   * Lomake muutettu vastaamaan taulua. 
   * Oppilaitos saadaan lomakkeelta nimellä => joudutaan muuttamaan nimestä id.
   * Palauttaa oikein!
   */
  private function sijoitusTulos() {
    $sarakkeet = $this->getSarakkeet(); // 0; kultaa, 1; hopeaa, 2; pronssia
    $temp = array(); // TESTAUS

    for($i = 1; $i <= 3; $i++) {
      $srk = $sarakkeet[$i - 1];

      for($j = 1; $j <= 6; $j++) { // 0-6 ominaisuutta huomioiden jaetut.
        $nimi = $srk[$j - 1];
        if(!empty($_GET[$nimi]))
          if(strpos($nimi, 'oppilaitoksen_ID') !== false) {
            $oppilaitos_id = $this->mOppilaitokset->getIdByName($_GET[$nimi]);
            $temp[$nimi] = $oppilaitos_id;
          } else
            $temp[$nimi] = $_GET[$nimi];
      }
    }
    return $temp;
  }
  
  /**
   * Luodaan arvot valmiiksi syötettäviksi kantaan.
   * Ehkä hiukan turhan pitkä funktio, mutta ottaa huomioon sijoitukset ja kunniamaininnat.
   */
  private function luoArvot(Laji $laji) {
    // Tallenna tulokset, koska laji ei ole olemassa.
    // Alustetaan tarvittavat muuttujat.
    $taulu = array();
    $sarakkeet = $this->getSarakkeet();
    $sijoitukset = $this->sijoitusTulos();
    $kunniat = $this->kunniaTulos();
    
    //var_dump($sijoitukset);
    //var_dump($kunniat);
    // Palkintopallit.
    for($i = 0; $i < 3; $i++) { // <=  voisi olla foreach.
      $srk = $sarakkeet[$i];
      for($j = 0; $j < count($srk); $j++) {
        $omi = $srk[$j];
        if(!(empty($sijoitukset[$omi]))) {
          array_push($taulu, $omi);
        }
      }
    }
    // Kynsien syöjät.
    $ksrk = $sarakkeet[3];
    for($i = 0; $i < count($ksrk); $i++) {
      $omi = $ksrk[$i];
      if(!(empty($kunniat[$omi])))
        array_push($taulu, $omi);
    }

    return $taulu;
  }
  /* FINGELSKA; Ei löytyny hyvää suomenkielistä sanaa joka olisi sopinut.
  */
  private function availableSarakkeet(Laji $laji) {
    $sarakkeet = $this->getSarakkeet();
    //$arvot = $this->luoArvot($laji); // Arvot järjestyksessä.
    $kunniat = $this->kunniaTulos(); // Kunniat taulukko.
    $sijat = $this->sijoitusTulos();

    $saatavilla = 'laji_ID';

    // Palkintopallit.
    for($i = 0; $i < 3; $i++) { // <=  voisi olla foreach.
      $srk = $sarakkeet[$i];
      for($j = 0; $j < count($srk); $j++) {
        $omi = $srk[$j];
        if(!(empty($sijat[$omi])))
          $saatavilla .= ", " . $srk[$j];
      }
    }
    // Kynsien syöjät.
    $ksrk = $sarakkeet[3];
    for($i = 0; $i < count($ksrk); $i++) {
      $omi = $ksrk[$i];
      if(!(empty($kunniat[$omi])))
        $saatavilla .= ", " . $omi;
    }
    return $saatavilla; // Palauttaa saatavilla olevat sarakkeet.
  }

  /**
   * Kyselystä puuttuu luoja eli ketä tallentaa kyselyn kantaan.
   * Tämä periaatteessa pitäisi tapahtua sillain,
   * että tuloslomake.php kysyy ennen lomakkeen näyttämistä nimimerkkiä.
   * Nimimerkki tarkistetaan kannasta(tai määritellystä taulukosta) vastaamaan
   * KTKO11R opiskelijan nimeä. (Totaalisen surkea autentikointi).
   * Nimen valitsemisen jälkeen prelomake kysyy salasanaa, joka on valmiiksi määritelty
   * koko luokan yhteisessä käytössä.
   *
   * TODO: KYSELYN GENEROIMINEN EI ONNISTU. MUODOSTA KYSELY NIISTÄ TIEDOISTA MITKÄ OVAT SAATAVILLA.
   * Esim. INSERT INTO tulokset(laji_id, kultaa_nimi) VALUES(3, "HERMANNI"); ,jne
   */
  public function tallennaKysely(Laji $laji) {
    if(!($this->checkTulosByLaji($laji))) {    
      $today = date("Y-m-d H:i:s"); 

      $sijat = $this->sijoitusTulos();
      $kunniat = $this->kunniaTulos();
      $sarakkeet_sql = $this->availableSarakkeet($laji);
      $sarakkeet = $this->luoArvot($laji);

      // Alustetaan kysely tarvittavilla tiedoilla.
      $kysely = "INSERT INTO tulokset";
      $kysely .= "(" . $sarakkeet_sql . ", luoja)";
      $kysely .= "VALUES (" . $laji->getId() . "";

      
      for($i = 0; $i < count($sarakkeet); $i++) {
        $srk = $sarakkeet[$i];
        // Kunnia kyseessä.
        if(strpos($srk, 'kunnia') !== false) 
          is_numeric($kunniat[$srk]) ? ($kysely .= ", " . $kunniat[$srk] . "") : ($kysely .= ", \"" . $kunniat[$srk] . "\"");
        else
          is_numeric($sijat[$srk]) ? ($kysely .= ", " . $sijat[$srk] . "") : ($kysely .= ", \"" . $sijat[$srk] . "\"");
      } 

      $kysely .= ", \"" . $_GET['luoja'] . "\");";
      //var_dump($kysely);

      $this->mKanta->kysely($kysely); // Ajetaan kysely kantaan. 
      echo "<h3>Tiedot tallennettu kantaan. Kiitos yheistyöstäsi, " . $_GET['luoja'] . "! Tiimi kiittää ja kumartaa.</h3>";
    }
    else
      echo "<h3>Tulokset " . $laji->getNimi() . " löytyvät jo kannasta. Tarkista lomake ja yritä uudelleen. Tattista!</h3>\n";
  }
}

?>
