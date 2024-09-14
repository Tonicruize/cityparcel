"use strict";

$(function () {
	cdp_load(1);
	cdp_load_countries_origin();
	cdp_load_countries_destiny();
});


//Cargar datos AJAX
function cdp_load(page) {
	var origin = $("#country_origin").val();
	var destiny = $("#country_destiny").val();
	var search = $("#search").val();
	var parametros = { "page": page, 'search': search, 'origin': origin, 'destiny': destiny };
	$("#loader").fadeIn('slow');
	$.ajax({
		url: './ajax/tools/ship_tariffs/ship_tariffs_list_ajax.php',
		data: parametros,
		beforeSend: function (objeto) {
		},
		success: function (data) {
			$(".outer_div").html(data).fadeIn('slow');
		}
	})
}

function cdp_load_countries_destiny() {
	$("#country_destiny").select2({
		ajax: {
			url: "ajax/select2_countries.php",
			dataType: 'json',

			delay: 250,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 2,
		placeholder: translate_search_destiny,
		allowClear: true
	});
}

function cdp_load_countries_origin() {
	$("#country_origin").select2({
		ajax: {
			url: "ajax/select2_countries.php",
			dataType: 'json',

			delay: 250,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 2,
		placeholder: translate_search_origin,
		allowClear: true
	});
}


function cdp_eliminar(id) {

	var parent = $('#item_' + id).parent().parent();
	var name = $(this).attr('data-rel');
	new Messi('<p class="messi-warning"><i class="icon-warning-sign icon-3x pull-left"></i>' + message_delete_confirm + '<br /><strong>' + message_delete_confirm2 + '</strong></p>', {
		title: message_delete_confirm1,
		titleClass: '',
		modal: true,
		closeButton: true,
		buttons: [{
			id: 0,
			label: message_delete_confirm1,
			class: '',
			val: 'Y'
		}],
		callback: function (val) {
			if (val === 'Y') {
				$.ajax({
					type: 'post',
					url: './ajax/tools/ship_tariffs/ship_tariffs_delete_ajax.php',
					data: {
						'id': id,
					},
					beforeSend: function () {
						parent.animate({
							'backgroundColor': '#FFBFBF'
						}, 400);
					},
					success: function (data) {
						$('html, body').animate({
							scrollTop: 0
						}, 600);
						$('#resultados_ajax').html(data);
						cdp_load(1);
					}
				});
			}
		}
	});
}