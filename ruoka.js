// Timo Mykkänen 2021
$(document).ready(function () {

    $("#haeNappi").click(function () {
      $("#mainDiv").text("Haetaan tietoja...");
      if ($("#tuotenimi").val() != "") {
        // Haetaan annetuilla hakuehdoilla serverilta dataa.
        $.get(
          "http://localhost/query.php?tuotenimi=" + $("#tuotenimi").val(),
          function (data, status, xhr) {
            var json_array = data;

            // Jos hakuehdoilla ei löytynyt yhtään tuotteita näytetään käyttäjälle teksti.
            if (json_array.length <= 0) {
              $("#mainDiv").text("Hakuehdoilla ei löytynyt yhtään tulosta.");
            }
            else {
              $("#mainDiv").text("");
              // JSON-arraysta saadaan jokaisen tuotteen tiedot loopissa ja luodaan jokaiselle tuotteelle oma html-elementti, jossa tuotteen tiedot.
              for (var i = 0; i < json_array.length; i++) {
                $("#mainDiv").append(
                  "<div class='myDiv'><font size='5vh' color='orange'>" + json_array[i].name
                  + "</font><b><font size='5vh'></br>" + json_array[i].price
                  + "€</b> </font><font size='2vh'>" + json_array[i].comp_price + "€ /kg </font>"
                  // Haetaan foodie.fi-sivuston käyttämästä pilvestä tuotteen oikea kuva tuotteen EAN-koodilla.
                  + "</br><img src=https://cdn.s-cloud.fi/v1/h80w80/product/ean/" + json_array[i].ean + "_kuva1.jpg><br></div>");
              };
            }
          }
        )
      }
      else {
        $("#mainDiv").text("Syötä ensin joku hakuehto.");
      }
    });
  });