<?php
class Laji {
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
class LajiHallinta {
	private $mLajit = array ();
	private $kanta;
	
	/**
	 * Alustaa LajiHallinta palikan.
	 * mm. Suorittaa vaadittavat alustus kyselyt.
	 * @param Tietokanta $yhteys
	 */
	public function __construct() {
		$this->kanta = Tietokanta::getTietokanta();
		$this->taytalajit();
	}
	
	/**
	 * Suorittaa yksinkertaisen kyselyn lajit tauluun,
	 * jonka jälkeen lykkää tiedot mLajit taulukkoon.
	 */
	private function taytalajit() {
		$laitos = $this->kanta->Kysely ( "SELECT * FROM lajit;" );
		while ( $rivi = $laitos->fetch_assoc () ) {
			$laji = new Laji ( $rivi ['laji_ID'], $rivi ['lajin_nimi'] );
			array_push ( $this->mLajit, $laji );
		}
	}
	
	/**
	 * Ottaa parametrinä lajin nimen ja palauttaa
	 * nimeä vastaavan ID:n mLajit taulukosta.
	 */
	public function getIdByName($nimi) {
		for($i = 0; $i < count ( $this->mLajit ); $i ++) {
			if ($this->mLajit [$i]->getNimi () == $nimi)
				return $this->mLajit [$i]->getId();
		}
	}
    
    /**
     * Palauttaa Laji Idn mukaan.
     */
    public function getById($id) {
        return $this->mLajit[$id - 1];
    }
	
	/**
	 * Palauttaa taulukon Lajien nimistä.
	 */
	public function getNimet() {
		$nimet = array ();
		foreach ( $this->mLajit as $nimi )
			array_push ( $nimet, $nimi->getNimi () );
		return $nimet;
	}
}
?>
