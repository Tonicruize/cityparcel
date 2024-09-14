"use strict";

$(function () {

  $.ajax({
    url: './ajax/dashboard/packages_registered/load_graphics_packages_registered_ajax.php',
    type: "POST",
    dataType: "json",
    success: function (data) {

      new Chart(document.getElementById("myChart"), {
        type: 'bar',
        data: {
          labels: [
            translate_graphic_0,
            translate_graphic_1,
            translate_graphic_2,
            translate_graphic_3,
            translate_graphic_4,
            translate_graphic_5,
            translate_graphic_6,
            translate_graphic_7,
            translate_graphic_8,
            translate_graphic_9,
            translate_graphic_10,
            translate_graphic_11,
          ], datasets: [
            {
              // label: translate_graphic_12,
              backgroundColor: ["#03a9f4", "#e861ff", "#08ccce", "#e2b35b", "#e40503", "#004146", "#e7843a", "#a3eb9f", "#77767c", "#ffe761", "#1f1307", "#8e1198"],
              data: data
            }
          ]
        },
        options: {
          legend: { display: false },
          title: {
            display: true,
            text: translate_graphic_13
          },
          tooltips: {
            mode: 'index',
            intersect: false
          },
          responsive: true,
        },

      });



    },
    error: function (data) {

    },
  });

  cdp_load(1);

});


//Cargar datos AJAX
function cdp_load(page) {

  var parametros = { "page": page };
  $("#loader").fadeIn('slow');
  $.ajax({
    url: './ajax/dashboard/packages_registered/load_packages_registered_ajax.php',
    data: parametros,
    beforeSend: function (objeto) {
    },
    success: function (data) {
      $(".outer_divz").html(data).fadeIn('slow');
    }
  })
}


