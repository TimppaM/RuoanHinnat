
<?php
// Ruoan hintojen tarkistaminen foodie.fi-sivustoa parsettamalla
// Timo Mykkänen 2021
// PHP Backend
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=UTF-8');


if (!empty($_GET['tuotenimi']))
 {
    // Poistetaan välilyönnit ja encodataan ääkköset, jotta haku toimii oikein.
    $tuotenimi = urlencode($_GET['tuotenimi']);
    $data = file_get_contents("https://www.foodie.fi/products/search2?term=".$tuotenimi);

    // Foodie.fi-sivustolta parsetetaan json-dataa, jossa on ylempää hakuehtoa vastaavat tulokset.
    // Pitää hiukan manuaalisesti muokkailla ja trimmata palautuvaa objectia, jotta saadaan data oikeanlaiseen JSON-muotoon.
	if (preg_match('/(page.init)(.*?)("pagination)/', $data, $matches)) {

	$str_new = ltrim($matches[2], '(');
	$str_new = rtrim($str_new, ', ');
	$str_new = $str_new . "}";
	
	$json = json_decode($str_new, true);

// Haetaan json-arraysta rivi "hinta", jotta voidaan myöhemmin lajitella tuotteet hinnan mukaan.                                                                                                                                                           
foreach ($json["entries"] as $key => $row)
{
    $tuotteenhinta[$key]  = $row['price'];
}    

// Palautetaan hakua vastaavat tulokset, jos niitä löytyi enemmän kuin yksi ja lajitellaan hinnan mukaan pienimmästä suurimpaan.
if(count($json["entries"]) > 1)
{
array_multisort($tuotteenhinta, SORT_ASC, $json["entries"]);
}

}
}

// Palautetaan fronttipuolelle json-array.
echo json_encode($json["entries"]); 

?>