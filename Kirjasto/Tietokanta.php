<?php

/**
 * Hyvin yksinkertainen wrapperi mysqli objektin ympärille.
 * Laajenna tarvittaessa.
 */
class Tietokanta {
	private $mMysqli;
	public function __construct($osoite, $kt, $pwd, $kanta) {
		$this->mMysqli = new mysqli ( $osoite, $kt, $pwd, $kanta );
		$this->mMysqli->set_charset ( "utf8" ); // Merkistönä UTF-8
	}
	
	// Palauttaa kyselyn.
	public function Kysely($sql) {
		return $this->mMysqli->query ( $sql );
	}
}

//$kanta_yhteys = new Tietokanta("localhost", "vaolabsfi_saku", "S4ku2014Vaa5a!");

?>