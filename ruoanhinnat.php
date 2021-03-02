<!-- Ruoan hintojen etsiminen foodie.fi-sivustoa parsettamalla-->
<!-- Timo Mykkänen 2021-->

<style>
.myDiv {
  border: solid 5px green;
  text-align: center;
  margin-bottom: 1%;
}
</style>
<form method="post">
<input type="text" name="tuotenimi" />
<input type="submit" name="hae" value="Hae hinnat" />
</form>

<?php
$itemid = 0;
$tuoteid = null;
$tuotehinta = null;
$ostoskori = [];

if (!empty($_POST['tuotenimi']))
 {
   // Korvataan välilyönnit %20, jotta haku toimii oikein.
    $noSpacestuotenimi = str_replace(' ', '%20', $_POST['tuotenimi']);
    $data = file_get_contents("https://www.foodie.fi/products/search2?term=".$noSpacestuotenimi);

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
// Jos hakuehdoilla ei löytynyt yhtään tuotteita.
else if(count($json["entries"]) <= 0)
{
  echo "Hakuehdoilla ei löytynyt tuotteita.";
}


// JSON-arraysta saadaan jokaisen tuotteen tiedot loopissa ja luodaan jokaiselle tuotteelle oma html-elementti, jossa tuotteen tiedot.
foreach($json["entries"] as $data) {

   $itemid = $itemid + 1;
   echo "<div class='myDiv'>";
   echo "<font size='5vh' color='orange'>". $data["name"] ."</font>" . "<b><font size='5vh'></br>" . $data["price"] . "€</b> </font><font size='2vh'>" . $data["comp_price"] . "€ /kg </font>";
   
   // Käytetään tuotteen EAN-koodia myöhemmin, jotta saadaan tuotteelle oikea kuva dynaamisesti.
   $kuva = $data["ean"];
   echo "<br>";
   // Haetaan foodie.fi-sivuston käyttämästä pilvestä tuotteen oikea kuva tuotteen EAN-koodilla.
   echo "</br><img src=https://cdn.s-cloud.fi/v1/h80w80/product/ean/$kuva". "_kuva1.jpg>";
   echo "<br>";
  //echo"<form method='post'>
    //<input id=$itemid type='submit' onclick='getItems()' name='someAction' value='Lisää ostoskoriin' /></form>";
   echo "</div>";
}
}
}
else {
  echo "Syötä ensin joku hakuehto.";
}
?>
