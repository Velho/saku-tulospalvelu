/**
 * JS toimintoa toteuttamaan "hienouksia".
 */

var kunniaCount = 2;
var kunniaMax = 11;

/**
 * Taulussa on 5 solua, joista ensinm‰inen on aina tyhj‰. (0 - 4) 
 * 1. Solu sis‰lt‰‰ Nimen, joka t‰ss‰ kohtaa on aina 'Kunnia'.
 * 2. Solu sis‰lt‰‰ tekstilaatikon kilpailijan nimelle.
 * 3. Solu sis‰lt‰‰ tekstilaatikon kilpailijan pisteille.
 * 4. Solu sis‰lt‰‰ Oppilaitoksen valintalaatikon.
 */
function lisaaKunnia(oppilaitokset) {
  var taulu = document.getElementById("sijoitus_taulu");
  var riviCount = taulu.rows.length;

  if(kunniaCount < kunniaMax) { 
    var rivi = taulu.insertRow(riviCount - 1); // Lis‰t‰‰n uusi rivi.
    rivi.insertCell(0);
           
    var solu1 = rivi.insertCell(1);
    solu1.innerHTML = "Kunnia";

    var solu2 = rivi.insertCell(2);
    var elementTeksti2 = document.createElement("input");
    elementTeksti2.type = "text";
    elementTeksti2.name = "kunniamaininta" + kunniaCount + "_nimi";
    solu2.appendChild(elementTeksti2);

    var solu3 = rivi.insertCell(3);
    var elementTeksti3 = document.createElement("input");
    elementTeksti3.type = "text";
    elementTeksti3.name = "kunniamaininta" + kunniaCount + "_pisteet";
    solu3.appendChild(elementTeksti3);

    var solu4 = rivi.insertCell(4);
    var valinta = document.createElement("select");
    valinta.name = "kunniamaininta" + kunniaCount + "_oppilaitoksen_ID";
        
    var oppilaitos_taulukko = oppilaitokset.split(",");
      
    for (i = 0; i < oppilaitos_taulukko.length; i++) {
      var pituus = oppilaitos_taulukko[i].length;
      var opt = document.createElement("option");

      opt.text = oppilaitos_taulukko[i];
      if(opt.text) // T‰ytet‰‰n vain jos on t‰ytett‰v‰‰.
        valinta.add(opt);
    }
    solu4.appendChild(valinta);
    kunniaCount++;
  }
} 


// Jaettu luku rajoittamaan jaettu sijojen m‰‰r‰‰.
var jaettuCount_1=0;
var jaettuCount_2=0;
var jaettuCount_3=0;

/**
 * Funktio lis‰‰m‰‰n jaettusija.
 */
function lisaaJaettu(sija, oppilaitokset) {
  var taulu = document.getElementById("sijoitus_taulu");

  switch(sija) {
    case 1:
      if(jaettuCount_1 < 1) {
        var rivi = taulu.insertRow(2);
        rivi.insertCell(0);
	
        var solu1 = rivi.insertCell(1);
        solu1.innerHTML = "Jaettu 1";
        var solu2 = rivi.insertCell(2);
        var elementtiTeksti1 = document.createElement("input");
        elementtiTeksti1.type = "text";
        elementtiTeksti1.name = "kultaa_jaettu_nimi";
        solu2.appendChild(elementtiTeksti1);
    
        var solu3 = rivi.insertCell(3);
        var elementtiTeksti2 = document.createElement("input");
        elementtiTeksti2.type = "text";
        elementtiTeksti2.name = "kultaa_jaettu_pisteet";
        solu3.appendChild(elementtiTeksti2);
	
        var solu4 = rivi.insertCell(4);

        var valinta = document.createElement("select");
        valinta.name = "kultaa_jaettu_oppilaitoksen_ID";
        var valinnat = oppilaitokset.split(",");
        
        for(var i = 0; i < valinnat.length; i++) {
          var pituus = valinnat[i].length;
          var opt = document.createElement("option");
            
          opt.text = valinnat[i];
          if(opt.text) // Ei lis‰t‰ listaan jos nimi on tyhj‰.
            valinta.add(opt);
        }
        solu4.appendChild(valinta);
        jaettuCount_1++;
      }
    break;
    case 2:
      if(jaettuCount_2 < 1) {
        var rivi = taulu.insertRow(3 + jaettuCount_1);
        rivi.insertCell(0);

	      var solu1 = rivi.insertCell(1);
	      solu1.innerHTML = "Jaettu 2";

	      var solu2 = rivi.insertCell(2);
	      var elementtiTeksti1 = document.createElement("input");
	      elementtiTeksti1.type = "text";
	      elementtiTeksti1.name = "hopeaa_jaettu_nimi";
	      solu2.appendChild(elementtiTeksti1);

	      var solu3 = rivi.insertCell(3);
	      var elementtiTeksti2 = document.createElement("input");
	      elementtiTeksti2.type = "text";
	      elementtiTeksti2.name = "hopeaa_jaettu_pisteet";
	      solu3.appendChild(elementtiTeksti2);

	      var solu4 = rivi.insertCell(4);
	      var valinta = document.createElement("select");
        valinta.name = "hopeaa_jaettu_oppilaitoksen_ID";
	      var valinnat = oppilaitokset.split(",");
        
        for(var i = 0; i < valinnat.length; i++) {
          var pituus = valinnat[i].length;
          var opt = document.createElement("option");
            
          opt.text = valinnat[i];

          if(opt.text)
            valinta.add(opt);
        }
    
	      solu4.appendChild(valinta);
	      jaettuCount_2++;
      }
    break;
    case 3:
      if(jaettuCount_3 < 1) {
        var rivi = taulu.insertRow(4 + jaettuCount_1 + jaettuCount_2);
        rivi.insertCell(0);
	
	      var solu1 = rivi.insertCell(1);
	      solu1.innerHTML = "Jaettu 3";
	
	      var solu2 = rivi.insertCell(2);
	      var elementtiTeksti1 = document.createElement("input");
	      elementtiTeksti1.type = "text";
	      elementtiTeksti1.name = "pronssia_jaettu_nimi";
	      solu2.appendChild(elementtiTeksti1);
	
	      var solu3 = rivi.insertCell(3);
	      var elementtiTeksti2 = document.createElement("input");
	      elementtiTeksti2.type = "text";
	      elementtiTeksti2.name = "pronssia_jaettu_pisteet";
	      solu3.appendChild(elementtiTeksti2);
	
	      var solu4 = rivi.insertCell(4);
	      var valinta = document.createElement("select");
        valinta.name = "pronssia_jaettu_oppilaitoksen_ID";
  
	      var valinnat = oppilaitokset.split(",");
        
        for(var i = 0; i < valinnat.length; i++) {
          var pituus = valinnat[i].length;
          var opt = document.createElement("option");
            
          opt.text = valinnat[i];
		
          if(opt.text)
			      valinta.add(opt);
        }
	      solu4.appendChild(valinta);	
	      jaettuCount_3++;
      }
    break;
  }
}
