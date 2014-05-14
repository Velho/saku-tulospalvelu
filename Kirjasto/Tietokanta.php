<?php

/**
 * Hyvin yksinkertainen wrapperi mysqli objektin ympärille.
 * Laajenna tarvittaessa.
 */
class Tietokanta {
	private $mMysqli;
	private static $tietokanta;
	
	// Tarvittavat tiedot yhteyden luomiseen.
	private $osoite = "localhost";
	private $kayttaja = "username";
	private $salasana = "salasana";
	private $kanta = "tietokanta";
	
	/**
	 * Privaatti konstruktori.
	 * Alustaa yhteyden. Vain luokan omassa käytössä(SINGLETON).
	 */
	private function __construct() {
		// Luodaan uusi mysqli olio.
		$this->mMysqli = new mysqli(
			$this->osoite,
			$this->kayttaja,
			$this->salasana, 
			$this->kanta);
			
		$this->mMysqli->set_charset("utf8"); // Merkistönä UTF-8
	}
	
	// Palauttaa kyselyn.
	public function Kysely($sql) {
		return $this->mMysqli->query ( $sql );
	}
	
	/**
	 * Palauttaa Singleton objektin tietokannasta.
	 */
	public static function getTietokanta() {
		if(!isset(self::$tietokanta))
			Tietokanta::$tietokanta = new Tietokanta();

		return Tietokanta::$tietokanta;
	}
}
?>