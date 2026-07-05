  
const provinceDropdown = document.getElementById('province');
const municipalityDropdown = document.getElementById('municipality');
const barangayDropdown = document.getElementById('barangay');

// Define the municipalities and barangays for each province
const municipalitiesByProvince = {
  Albay:['Camalig','Daraga','Guinobatan','Jovellar','Libon','Ligao','Malinao','Manito','Oas','Pio Duran','Polangui','Rapu-Rapu','Santo Domingo','Tabaco','Tiwi'],
  CamarinesNorte:['Basud','Capalonga','Daet','Jose Panganiban'],
  CamarinesSur:['Baao','Balatan','Cabusao','Calabanga','Canaman'],
Catanduanes:['Bagamanoc','Bato','Baras','Caramoran','Gigmoto'],
  Masbate: ['Aroroy', 'Baleno', 'Balud', 'Cataingan', 'Dimasalang'],
  Sorsogon: ['Bulan','Bulusan','Casiguran','Castilla', 'Donsol', 'Gubat','Irosin', 'Juban','Magallanes', 'Matnog', 'Pilar',   'Prieto Diaz',  'Sta Magdalena']



  // Add more provinces, municipalities, and barangays as needed
};

const barangaysByMunicipality = {
  //albay
  'Camalig': ['Anoling', 'Baligang', 'Bantonan','Bariw','Binanderahan','Binitayan','Cabagñan','Cabraran Pequeño','Calabidongan','Comun','Cotmon','Del Rosario','Gapo','Gotob',
  'Ilawod Moroña Compound','Iluluan','Libod','Ligban','Mabunga'],
  'Daraga': ['Alcala', 'Alobo', 'Anislag','Bagumbayan','Balinad','Bañadero','Bañag','Bascaran'],
  'Guinobatan': ['Agpay', 'Balite', 'Banao','Batbat','Binogsacan Lower','Bololo','Bubulusan','Ilawod'],
  'Jovellar':['Aurora Poblacion','Bagacay','Bautista','Cabraran','Calzada Poblacion','Del Rosario','Estrella'],
   'Libon':['Alongong','Apud','Bacolod','Zone I (Pob.)','Zone II (Pob.)','Zone III (Pob.)','Zone IV (Pob.)','Zone V (Pob.)','Zone VI (Pob.)','Zone VII (Pob.)'],
   'Ligao':['Abella','Allang','Amtic','Bacong','Bagumbayan','Balanac','Baligang','Barayong','Basag','Batang','Bay'],
   'Malinao':['Awang','Bagatangk','Balading','Balza','Bariw','Baybay','Bulang','Burabod'],
   'Manito':['Balabagon','Balasbas','Bamban','Buyo','Cabacongan','Cabit','Cawayan','Cawit','Holugan'],
   'Oas':['Badbad','Badian','Bagsa','Bagumbayan','Balogo','Banao','Bangiawon','Bongoran','Bogtong','Busac','Cadawag'],
   'Pio Duran':['Agol','Alabangpuro','Basicao Coastal','Basicao Interior','Banawan (Binawan)','Binodegahan','Buenavista','Buyo'],
   'Polangui':['Matacon','Maynaga','Maysua','Mendez','Napo','Pinagdapugan','Ponso','Salvacion','San Roque','Santicon'],
   'Rapu-Rapu':['Bagaobawan','Batan','Bilbao','Binosawan','Bogtong','Buenavista','Buhatan','Calanaga'],
   'Santo Domingo':['Alimsog','Buhatan','Calayucay','Lidong','Salvacion','Bagong San Roque'],
   'Tabaco':['Bacolod','Bangkilingan','Bantayan','Baranghawon','Basagan'],
   'Tiwi':['Bagumbayan','Bariis','Baybay','Biyong','Bolo','Cale','Cararayan'],
   //camarinesnorte
 'Basud':['Angas','Bactas','Binatagan','Caayunan','Guinatungan','hinampacan','langa','laniton','mampili','mandazo','makamagong','manmuntay','mantugawe','matnog','mocon','oliva','pagsangahan','pinagrawassan','plaridel','pob1','pob2','san filipe','sanjose','san paskwal','tabataba','takad','taisan','tuaca'],
 'Capalonga':['Alayao','Binawangan','Calabaca','Camagsaan','Catabaguangan','catiuan','del pilar','itok','lokbangan','mabini','mactan','magsaysay','mataque','oldcamp','pob','san antonio','san isidro','san roque','tanawan','jubang','villa','villa belen'],
 'Daet':['Alawihao','Awitan','Bagasbas','brgy 1','brgy 2','brgy 3','brgy 4','brgy 5','brgy 6','brgy 7','brgy 8','bibirao','burabod','calasgasan','camabungan','gumambangbang','dogongan','gahonon','gubat','lagong','magang','mambalite','man cruz','pamurangon','san isidro'], 
 'Jose Panganiban':['Calero','Dahican','Dayhagan','Larap','Nakalaya','north pob','osmenia','pagasa','parang','plaridel','salvacion','san isidro','san jose','san martin','san rafael','san pedro','sata cruz','santa elena','santa milagrosa','santa rosa norte','santa rosa sur','south pob','tamisan'],
 //camarines sur
 'Baao':['Antipolo','Bagumbayan','Iyagan','Lourdes','Nababarera','agdangan','del rosario','iyagan','lamidalia','tarnday','lourdes','nababarera','sagrada','salvacion','san antonio','san francisco','san isidro','san jose','san juan','san nicolas','san rafel','pugay','san ramon','san roque','san vicente','santacruz','santa isabel','satan terisita','tapol'],
  'Balatan':['Cabanbanan','Cabungan','Camangahan','Cayogcog','Coguit','duran','laganac','diluasan','montenegro','pararao','siramag','pulang daga','sagrada nacacale','san francisco','santiago nacacale','tapayas','tumatarayo'],
  'Cabusao':['Agnohoyos','Banca','Bansalan','Bontoc','barseluneta','biong','kamagong','castillo','new pob','pandan san pedro','san cruz','lut garda'],
  'Calabanga':['Balatasan','Balombon','Balongay','Belen','Bigaas','binaliw','burabod','cabanbanan','cagsao','del carmen','dominorog','fabrica','harubay','lapurisima','lugsag','pagtpat','paolbo','pinada','punta taraway','sabang','salvacion','san benardino','san francisco','san isidro','san lucas','san miguel','san pablo','san roque'],
  'Canaman':['Baras','Fundado','Haring','Iquin','Linaga','mangyawan','palo','pangpang','san agustin','san jose east','san jose west'],
  //catanduanes
  'Bagamanoc':['Abunao','Adrian','Akbar','Asingan','Atipac-an','antipolo','bacac','bagatabaon','bugao','kahan','bugao','hinipaan','magsaysay','pob'],
  'Bato':['Acaba','Aguinaldo','Ambucay','Arteche','Banaue'],
  'Caramoran':['Bocon','Buenavista','Bulalacao','Camburo','Dariao'],
  'Gigmoto':['Biong','Dororian','Sicmil','Poblacion District I','Poblacion District II'],
  'Baras':['Abihao','Agban','Benticayan','Buenavista','Caragumihan'],
  //masbate
  'Aroroy':['Ambolong','Amoroy','Amotag','Balawing','Balete'],
  'Baleno':['Lipata','Madancalan','Magdalena','Manoboc','Polot'],
  'Balud':['Jintotolo','Mapili','Mapitogo','Pajo','Palani'],
  'Cataingan':['Abaca','Aguada','Badiang','Bagumbayan','Cadulawan'],
  'Dimasalang':['Balantay','Balocawe','Banahao','Buenaflor','Buracan'],
  //sorsogon
  'Bulan':['Zone 1','Zone 2','Zone 3','Zone 4','Zone 5','zone6','zone7','zone8','a.Bonifacio','Abad Santos','Antipolo','Aquino','Bical','Beguin','Bonga','Butag','Cadandanan','calomagon','calpi','cocok-cabitan','daganas','danao','bulos','fabrica','Gate','g.delpilar','inararan','j.gerona','jp.laurer','jamorawon','lajong','magsaysay','managanaga','marinab','nroque','obrero','otavi','sagrada','san isidro','san juan bag-o','san juan daan','san rafael','san ramon','san vicente','santa remedios','santa teresita','sumagongsong','taromata'],
  'Bulusan':['Bagacay','Cogon','Dancalan','Dapdap','Lalud','central','looban','mabuhay','madlawon','puktol','purog','sabang','salvacion','san antonio','san bernardo','san francisco','san isidro','san jose','san rafael','san roque','san vicente','santa barbara','sapngan','tinampo'],
  'Casiguran':['Adovis','Boton','Burgos','Casay','Cawit','barangay1','barangay2','barangay3','barangay4','barangay5','barangay6','barangay7','barangay8','bianuan','calabgan','calangcuasan','calantas','cozo','colat','dibakong','dibek','ditinagyan','esperanza','esteves','lual','marikit','tabas','tinib'],
  'Castilla':['Amomonting','Bagalayag','Bonga','Buenavista','Burabod','caburacan','canjela','cogon','cumagcad','dancalan','dinapa','la union','libtong','loreto','makalaya','mayon','pange','milagrosa','miluya','monte carmelo','pandan','poblacion','quirapi','saklayan','salvacion','san isidro','san rafael','san roque','san vicente','sogoy','tumalaytay'],
  'Donsol':['Alin','Banban','Bandi','Baras','Bayawas','bororan brgy1','cabugaw','central brgy2','cristo','dancalan','divera','gimagaan','girawan','gogon','gura','juan andre','lordes','mabini','malapok','malinao','market site brgy3','new maguisa','ogod','old maguisa','orange','pangpang','parina','pawala','pinamanaan','poso poblacion','rawis','san antonio','san isidro','san jose','san rafael','san ramon','san vicente','santa cruz','sibago','dongdol','tres maria','tupas'],
    'Gubat':['Ariman','Bagacay','Dita','Jupi','Casili','cogon','cota na daco','lapinig','manapao','manuok','naagtan','nato','nazareno','ogao','paco','panganiban','patag','payawin','rizal','sangat santa ana','tabi','tagaytay','tigkiw','tiris','towgawe','union'],
    'Irosin':['Bacolod','Bagsangan','Batang','Bolos','Cogon','buenavista','bulawan','carriedo','casini','cawayan','gabao','gulang gulang','gumapia','liang','macawayan','mapaso','monbon','patag','salvacion','san agustin','san isidro','san juan','san julian','san pedro','sto domingo','tabon tabon','tinampo','tongdol'],
    'Juban':['Aroroy','Bacolod','Binanuahan','Biriran','Buraburan','calatio','calmayon','caruhayon','catanagan','catanusan','cogon','embarcadero','guruyan','lajong','maalo','north pob','south pob','puting sapa','rangas','sablayan','sipaya','taboc','tinago','tughan'],
    'Magallanes':['Aguada Norte','Aguada Sur','Anibong','Bacalon','Bacolod','Banacud','Behia','Biga','Binisitahan Norte','Binisitahan Del Sur','Biton','Bulala','Busay','Caditaan','Cagbolo','Cagtalaba','Cawit Extension','Cawit Proper ','Ginangra','Hubo','Incarizan','Lapinig','Magsaysay','Malbog','Pantalan','Pawik','Pili','Poblacion','Salvacion','Santa Elena','Siuton','Tagas','Tulatula Norte','Tulatula Sur'],
    'Matnog':['Balocawe','Banogao','Bariis','Calayuan','Laboy','balucawe','banugao','banuang daan','bariis','bolo','bun-ot dako','bun-ot saday','cabagahan','calayuan','calintaan','caloocan','calpi','camachili','camcaman','coron coron','culasi','gadgaron','hidhid','lahong','manjudlad','manuragi','pange','pawa','sisigon','tabunan','tugas','suwa','tablac','sulangan'],
    'Pilar':['Abas','Abucay','Bantayan','Cabiguan','Bayawas','binanuhang','cadongon','calungay','calpi','catamlangan','cumapo capo','damlog','dao','dapdap','del rosario','esmerada','ezperanza','guirong','ginablan','inang','inapugan','liona','lipason','lordes','lungib','lumbang','malbog','mercedez','megabod','naspi','palanas','pangpang','pinagsalog','pineda','oktol','pudo','putiaw','saknangan','salvacion','san antonio milabas','san antonio sapa','san jose','san rafael','santa fe'],
    'Prieto Diaz':['Brillante ','Bulawan','Calao','Carayat','Gogon','lupi','manlabong','maningcay de oro','perlas','quidolog','rizal','san antonio','san fernando','san isidro','san juan','san rafael','san ramon','san lordes','sto domingo','talisayan','tupaz','ulag'],
    'Sta Magdalena':['Peñafrancia','Salvacion','San Antonio','San Rafae','San Isidro','brgy pob1','brgy pob2','brgy pob3','brgy pob4','la ezperansa','pena prancia','san antonio','san bartolome','san eugenio','san isidro','san rafel','san roque','san sebastian']


};

const zipcodeByMunicipality = {
  // Albay
  'Camalig': '4502',
  'Daraga': '4501',
  'Guinobatan': '4503',
  'Jovellar': '4515',
  'Libon': '4514',
  'Ligao': '4504',
  'Malinao': '4512',
  'Manito': '4510',
  'Oas': '4505',
  'Pio Duran': '4516',
  'Polangui': '4506',
  'Rapu-Rapu': '4517',
  'Santo Domingo': '4508',
  'Tabaco': '4511',
  'Tiwi': '4513',

  // Camarines Norte
  'Basud': '4608',
  'Capalonga': '4607',
  'Daet': '4600',
  'Jose Panganiban': '4606',

  // Camarines Sur
  'Baao': '4432',
  'Balatan': '4436',
  'Cabusao': '4407',
  'Calabanga': '4405',
  'Canaman': '4402',

  // Catanduanes
  'Bagamanoc': '4807',
  'Baras': '4803',
  'Bato': '4801',
  'Caramoran': '4808',
  'Gigmoto': '4804',

  // Masbate
  'Aroroy': '5414',
  'Baleno': '5413',
  'Balud': '5412',
  'Cataingan': '5405',
  'Dimasalang': '5403',

  // Sorsogon
  'Bulan': '4706',
  'Bulusan': '4704',
  'Casiguran': '4712',
  'Castilla': '4703',
  'Donsol': '4715',
  'Gubat': '4710',
  'Irosin': '4707',
  'Juban': '4709',
  'Magallanes': '4705',
  'Matnog': '4708',
  'Pilar': '4714',
  'Prieto Diaz': '4711',
  'Sta Magdalena': '4713',
};

// Function to populate the Municipality dropdown based on the selected province
function updateMunicipalities() {
  const selectedProvince = provinceDropdown.value;
  const municipalities = municipalitiesByProvince[selectedProvince];
  municipalityDropdown.innerHTML = '';



  for (const municipality of municipalities) {
      const option = document.createElement('option');
      option.text = municipality;
      municipalityDropdown.add(option);
      
  }

  // Update the Barangay dropdown as well
  updateBarangays();
}

// Function to populate the Barangay dropdown based on the selected municipality
function updateBarangays() {
  const selectedMunicipality = municipalityDropdown.value;
  const barangays = barangaysByMunicipality[selectedMunicipality];
  barangayDropdown.innerHTML = '';


  for (const barangay of barangays) {
      const option = document.createElement('option');
      option.text = barangay;
      barangayDropdown.add(option);
  }
}


function updateBarangays() {
  const selectedMunicipality = municipalityDropdown.value;

  // Set Zip Code
  const zipcodeInput = document.querySelector('input[name="zipcode"]');
  zipcodeInput.value = zipcodeByMunicipality[selectedMunicipality] || '';

  const barangays = barangaysByMunicipality[selectedMunicipality];
  barangayDropdown.innerHTML = '';

  for (const barangay of barangays) {
      const option = document.createElement('option');
      option.text = barangay;
      barangayDropdown.add(option);
  }
}

// Initialize the dropdowns
updateMunicipalities();


