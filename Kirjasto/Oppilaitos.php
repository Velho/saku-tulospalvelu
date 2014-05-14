<?php
class Oppilaitos {
	private $mId;
	private $mNimi;
	
	/**
	 * 
	 * @param unknown $id
	 * @param unknown $nimi
	 */
	public function __construct($id, $nimi) {
		$this->mId = $id;
		$this->mNimi = $nimi;
	}
	
	/**
	 * 
	 * @return unknown
	 */
	public function getId() {
		return $this->mId;
	}
	
	/**
	 * 
	 * @return unknown
	 */
	public function getNimi() {
		return $this->mNimi;
	}
}

class OppilaitosHallinta {
	private $mOppilaitokset = array ();
	private $kanta;
	
	/**
	 * Alustaa OppilaitosHallinta palikan.
	 * @param Tietokanta $yhteys
	 */
	public function __construct() {
		$this->kanta = Tietokanta::getTietokanta();
		$this->taytaOppilaitokset ();
	}
	
	/**
	 * Suorittaa yksinkertaisen kyselyn oppilaitokset tauluun ja
	 * täyttää oppilaitos taulukon tiedon hakua varten.
	 */
	private function taytaOppilaitokset() {
		$laitos = $this->kanta->Kysely ( "SELECT * FROM oppilaitokset;" );
		while ( $rivi = $laitos->fetch_assoc () ) {
			$opinahjo = new Oppilaitos ( $rivi ['oppilaitoksen_ID'], $rivi ['oppilaitoksen_nimi'] );
			array_push ( $this->mOppilaitokset, $opinahjo );
		}
	}
	
	/**
	 * Palautetaan ID nimen mukaan.
	 * @param unknown $nimi
	 */
	public function getIdByName($nimi) {
		for($i = 0; $i < count ( $this->mOppilaitokset ); $i ++) {
			if ($this->mOppilaitokset [$i]->getNimi () == $nimi)
				return $this->mOppilaitokset [$i]->getId();
		}
	}
    
    /**
     * Palauttaa Oppilaitoksen nimen Id:n mukaan.
     * $param $id oppilaitos_ID (taulun mukaan, ei taulukon)
     */
    public function getNameById($id) {
        return $this->mOppilaitokset[$id - 1];
    }
	
	/**
	 * Palauttaa taulukon oppilaitosten nimistä.
	 */
	public function getNimet() {
		$nimet = array ();
		for($i = 0; $i < count ( $this->mOppilaitokset ); $i ++)
			array_push ( $nimet, $this->mOppilaitokset [$i]->getNimi () );
		return $nimet;
	}
}

?>
