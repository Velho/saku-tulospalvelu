<html>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <head><title>Valitse laji</title></head>
  <body>
    <form action="tulosnakyma.php" method="get">
      <select name="laji">
      <?php
        set_include_path("/var/www/saku.vaolabs.fi/");

        // Sisällytetään kirjasto.
        require 'Kirjasto/Tietokanta.php';
        require 'Kirjasto/Oppilaitos.php';
        require 'Kirjasto/Laji.php';
        require 'Kirjasto/Tulos.php';
        
        $kanta = new Tietokanta(/** DATABASE INFORMATION **/);
        $tulos = new TulosHallinta($kanta, false);
        $lajit = new LajiHallinta($kanta);
        
        $laji_id = $tulos->availableTulokset();
        
        for($i = 0; $i < count($laji_id); $i++) {
          $laji = $lajit->getById(intval($laji_id[$i]["laji_ID"]));
          echo "<option>" . $laji->getNimi() . "</option>\n";
        }
      ?>
      </select>
      <input type="submit" value="Näytä tulokset"></input>
    </form>
  </body>
</html>

