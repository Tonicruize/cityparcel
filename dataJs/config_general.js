"use strict";

$("#save_config_general").on('submit', function (event) {

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "ajax/tools/config_general_ajax.php",
        data: parametros,
        beforeSend: function (objeto) {
            $("#resultados_ajax").html("Wait a moment...");
        },
        success: function (datos) {
            $("#resultados_ajax").html(datos);

            $("html, body").animate({
                scrollTop: 0
            }, 600);

        }
    });
    event.preventDefault();

});


document.getElementById('colaboradores').onchange = function () {
    /* Referencia al option seleccionado */
    var mOption = this.options[this.selectedIndex];
    /* Referencia a los atributos data de la opción seleccionada */
    var mData = mOption.dataset;

    /* Referencia a los input */
    var elId = document.getElementById('id');
    var elCodigo = document.getElementById('currency_symbol');
    var elCurren = document.getElementById('currency');


    /* Asignamos cada dato a su input*/
    elId.value = this.value;
    elCodigo.value = mData.currency_symbol;
    elCurren.value = mData.currency;


};