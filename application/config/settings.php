<?php
$available_tags=array (
	'TAG_PUBLIC',
	'TAG_PRIVATE',
	'TAG_MAINTENANCE',
  'TAG_REPAIR',
	'TAG_SPARES',
	'TAG_WHEELS',
  'TAG_FUEL',
	'TAG_RESTORATION',
	'default' => 'TAG_OTHER',
);

$no_sho_tags=array('TAG_PUBLIC',	'TAG_PRIVATE');  //tags with no enable/disable checkboxes

/*
$user_units = array(
  'distance' => 'km',     // km / mi
  'currency' => '&euro;',           //EUR, USD, GBP etc
  'consumption' => 'l/100km',     // eu / us / uk => l/100, mpg us, mpg uk
);  */

$car_data_bits=array(
  'BIT_PROD_DATE',
  'BIT_DISPLACEMENT',
  'BIT_POWER_HP',
  'BIT_POWER KW',
  'BIT_FUEL_TYPE',
  'BIT_COLOR_CODE',
  'BIT_TRIM_COLOR_CODE',
  'BIT_NO_SEATS',
  'BIT_NO_DOORS',
  'BIT_TRANS_TYPE',
  'BIT_GVWR',

  'BIT_ODO',
  'BIT_WHEEL_SIZE',
  'BIT_TYRE_SIZE',
  'BIT_TYRE_TYPE',
  'BIT_WINDSHIELD_WIPER',
  'default' => 'BIT_OTHER'
);

$available_languages = array(
        'en'=>'en_US.UTF-8',
        'lt'=>'lt_LT.UTF-8'
    );

$language_names = array(
        'en'=>'English',
        'lt'=>'Lietuviškai'
    );

$available_currencies = array('EUR', 'GBP', 'USD');
$consumption_units  = array('eu', 'uk', 'us');
$distance_units  = array('km', 'mi');
    
    
    


/*
access levels
99 owner (everything)
70 service provider (add event, add car data, no delete, no event edit except self-added)






Tr. priemonÄ—s tipas iš sÄ…raÅ¡o pavadinimas ir dar langelis- kodas tipo M1

MarkÄ— - pasirinkimas iš sÄ…rašo

Modelis - pasirinkimas iš s sÄ…rašo

Modifikacija - paÄiam Ä¯rašyti tipo Aero, arba expression, vector, Alpine ir t.t

Valstybinis numeris

VIN numeris (nurodytiems variantams atsidarytÅ³ 11 langeliÅ³ t.y pagrindinei masei)

Gamybos metai pilna versija metai mÄ—nuo diena (gerai atrodo toks skaitliukas kur sukioji skaiÄius kaip kodinÄ—j spynoj)

Darbinis tÅ«ris cm kubiniais

Galia kW arba AG - manau galimybÄ— pasirinkti, o užkulisiuose skaiÄiuoklÄ— paverstÅ³ visaip kaip reikia

Kuro tipas (reiktÅ³ pažiÅ«rÄ—ti kas po ko eiliš¡kume kuras po ko)

Spalva iš sÄ…rašo plius spalvos koodas ir Trim kodas keli langeliai arba pritaikyti kai ne vienos spalvos.

SÄ—dimÅ³ vietÅ³ skaiÄius- iš sÄ…rašo
DurÅ³ skaiÄius iš sÄ…rašo

>PavarÅ³ dÄ—žÄ—s tipas

Mases irgi reiktÅ³ surašyti , bet dar išsiaiškinsiu kokias 





*/
 ?>