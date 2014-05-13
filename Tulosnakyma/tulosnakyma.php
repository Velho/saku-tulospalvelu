<!doctype html>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>SakuStars Tulokset</title>
  <link href='http://fonts.googleapis.com/css?family=Denk+One' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="nakyma.css">
  
  <script src="js/jquery-1.11.0.js"></script>
  
  <script>
	/**
   * Lopputyö
   * 
	 * SAKUSTARS Tulospalvelu
   * Projekti Tulosnäkymä
   * 
   * Ohjelmoija: Joel Anttila 2014
   * index.html
	 */
  
  /*
	var tulokset = {
    "Runo Tunnissa":[["Joel Anttila", "VAO"], ["Kalle Nissinen", "Tampereen Valio-opisto"], []],
    "Yksin laulua":[["Hannes Salo", "VAO"], ["Tero Ulvinen", "Hervannan Tuliopisto"], ["Kalle Nissinen", "Kristillinen Penaalikoulu"]],
    "Akustiset soittimet":[["Joel Anttila", "VAO"], ["Kalle Nissinen", "Tampereen Valio-opisto"], []]
  };
  
  var kunniat = {
    "Runo Tunnissa":[["ANTEEEKSI", "VAO"], ["Pulla Bolger", "TAO"], ["Hirven Vasa", "Ullakko"], ["Kahvi Kupillinen", "Pannullisen Ammattikoulu"], 
                    ["Rami Leuka", "Näppäimistön ammattikoulu"], ["Teemu Näyttö", "Näytön Ammattikoulu"], ["Hannes Puhelin", "Puhelimen Ammattikoulu"], ["Kai Hanzein", "School of R0ck"], ["Marko Hietala", "Hietalan Ammattikoulu"], ["Tuomas Holopoinen", "HAO"]],
    "Yksin laulua":[["Hermanni Voikukka", "Tuubin Opisto"], ["LP Miljönääri", "RAO"], ["ANTEEEKSI", "VAO"], ["Pulla Bolger", "TAO"], ["Hirven Vasa", "Ullakko"], ["Kai Hanzein", "School of R0ck"]],
    "Akustiset soittimet":[]
    };
  
  // Jaetut tulokset. 
  var j_tulokset = {
    "Runo Tunnissa":[[], ["Tero Ulvinen", "VAO"], []],
    "Yksin laulua":[],
    "Akustiset soittimet":[["Tero Ulvinen", "VAO"], [], []]
  };
  */
  
  var tulokset;
  var kunniat;
  var j_tulokset;
  
  
  
  var lajit = new Array();
  
  var laji;

  var tulos; 
  var jaettu;

  var index = 0; // Pitää kirjaa missä lajissa mennään.
  
  <?php
    require 'nakyma.php';
    
    if(!empty($_GET['laji'])) {
      $js = new JSTulos($_GET['laji']);
    
      echo $js->getTulokset() . "\n";
      echo $js->getJaetutTulokset() . "\n";
      echo $js->getKunniat() . "\n";
    } 
  ?>
  
	$(document).ready(function() {
    juokse();
    /*
    var tulosesitys = setInterval(function() {
      juokse();
    }, 20000); // 15000ms = 15sec */
  });
	
  function juokse() {
    alusta();
    animoiSijat();

    paivitaLajiIdx();
  }
  
	/**
	 * Alustaa koko verkkosovelman elementit.
	 */
  function alusta() {
	  $(".sijat").hide(); 
    $('.oppilaitos').hide();
    $('.kunnia').hide();
    $("h3").hide();
    
    // Viittaa lajiin => tulokset[lajit[index]][0][0]
    for(key in tulokset) {
      lajit.push(key);
    }
    
    //alustaLajiLista();
    
    laji = lajit[index];
    tulos = tulokset[laji];
    jaettu = j_tulokset[laji];
      
    $("#laji").text(laji);
	}
  
	var counter = 0;
  function animoiSijat() {
    counter = 0;
    var ajastin = setInterval(function() {
      //console.log("Counter: " + counter);
      if(counter < 3) {
        switch(counter) {
          case 0:
            naytaAnimaatio("#sija1", "1");
            break;
          case 1:
            naytaAnimaatio("#sija2", "2");
          break;
          case 2:
            naytaAnimaatio("#sija3", "3");
          break;
          }
        } else if(counter == 3)
            animoiKunniat();
          else
            clearInterval(ajastin);
        counter++;
		}, 2500);
  }
  
  function animoiKunniat() {
    var k_p = $(".kunnia");
    var knn = kunniat[laji];
   
    if(knn.length > 0) {
      $("h3").text("Kunniamaininnat");
      $("h3").show(); 
    }
    
    for(var i = 0; i < knn.length; i++) {
      k_p.eq(i).html(knn[i][0] + "<br/>" + knn[i][1]);
      k_p.eq(i).fadeIn(2000);
    }
  }
  
	function naytaAnimaatio(sija, arvo) {
	  //console.log(sija[sija.length - 1]);
	  //console.log(tulos[counter].length > 0);
	  if(tulos[counter].length > 0) {
		  $(sija).text(arvo + ". " + tulos[counter][0]);// [0] => Viittaa Nimeen.
		  $(sija + "_oppi").text(tulos[counter][1]); // [1] => Viittaa oppilaitokseen.
		  $(sija).slideToggle(1000);
		  $(sija + "_oppi").slideToggle('slow');
    }
    
    if(jaettu.length != 0 && typeof jaettu[counter] !== 'undefined' && jaettu[counter].length != 0) {
      // Show jaetut.
      $(sija + "_jaettu").text(arvo + ". " + jaettu[counter][0]);
      $(sija + "_jaettu").slideToggle(1000);
      
      $(sija + "_oppi_jaettu").text(jaettu[counter][1]);
      $(sija + "_oppi_jaettu").slideToggle('slow');
    }
    
	}
  
  function paivitaLajiIdx() {
    if(index < lajit.length)
        index++;
      else 
        index = 0;
  }
	
  </script>
  
  </head>
  
  <body>
    <div id="sisalto">
      
      <div id="kunniat">
      <h3></h3>
      <div id="kunnia_srk1">
        <p class="kunnia"></p> <!-- 1 -->
        <p class="kunnia"></p> <!-- 2 -->
        <p class="kunnia"></p> <!-- 3 -->
        <p class="kunnia"></p> <!-- 4 -->
        <p class="kunnia"></p> <!-- 5 -->
      </div>
      <div id="kunnia_srk2">
        <p class="kunnia"></p> <!-- 6 -->
        <p class="kunnia"></p> <!-- 7 -->
        <p class="kunnia"></p> <!-- 8 -->
        <p class="kunnia"></p> <!-- 9 -->
        <p class="kunnia"></p> <!-- 10 -->
      </div>
    </div>
      
      <div id="tulos">
    
        <h1 id="laji"></h1>
        <p id="sija1" class="sijat"></p>
        <p id="sija1_oppi" class="oppilaitos"></p>
        <p id="sija1_jaettu" class="sijat jaettu"></p>
        <p id="sija1_oppi_jaettu" class="oppilaitos jaettu"></p>
       
        
        <p id="sija2" class="sijat"></p>
        <p id="sija2_oppi" class="oppilaitos"></p>
        <p id="sija2_jaettu" class="sijat jaettu"></p>
        <p id="sija2_oppi_jaettu" class="oppilaitos jaettu"></p>
        
        <p id="sija3" class="sijat"></p>
        <p id="sija3_oppi" class="oppilaitos"></p>
        <p id="sija3_jaettu" class="sijat jaettu"></p>
        <p id="sija3_oppi_jaettu" class="oppilaitos jaettu"></p>
      </div>
   
      <div id="logo">
        <img src="saku.png" />
      </div>
    </div>
  </body>
  
</html>
