"use strict";

var deleted_file_ids = [];

var packagesItems = [
    {
        qty: 1,
        description: '',
        length: 0,
        width: 0,
        height: 0,
        weight: 0,
        declared_value: 0,
        fixed_value: 0
    }
]

$(function () {
    loadPackages()


    $('#order_date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });

    $('#register_customer_to_user').click(function () {
        if ($(this).is(':checked')) {
            $('#show_hide_user_inputs').removeClass('d-none');
        } else {
            $('#show_hide_user_inputs').addClass('d-none');
        }
    });

    cdp_load_countries("_modal_user");
    cdp_load_states("_modal_user");
    cdp_load_cities("_modal_user");

    cdp_load_countries("_modal_user_address");
    cdp_load_states("_modal_user_address");
    cdp_load_cities("_modal_user_address");


    cdp_select2_init_sender();
    cdp_select2_init_sender_address();
});


function cdp_load_countries(modal) {

    $("#country" + modal).select2({
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
        placeholder: translate_search_country,
        allowClear: true
    }).on('change', function (e) {

        var country = $("#country" + modal).val();

        $("#state" + modal).attr("disabled", true);
        $("#state" + modal).val(null);

        if (country != null) {
            $("#state" + modal).attr("disabled", false);
        }

        cdp_load_states(modal);
    });
}

function cdp_load_states(modal) {
    var country = $("#country" + modal).val();

    $("#state" + modal).select2({
        ajax: {
            url: "ajax/select2_states.php?id=" + country,
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
        placeholder: translate_search_state,
        allowClear: true
    }).on('change', function (e) {

        var state = $("#state" + modal).val();

        $("#city" + modal).attr("disabled", true);
        $("#city" + modal).val(null);

        if (state != null) {
            $("#city" + modal).attr("disabled", false);
        }

        cdp_load_cities(modal);
    });
}

function cdp_load_cities(modal) {
    var state = $("#state" + modal).val();

    $("#city" + modal).select2({
        ajax: {
            url: "ajax/select2_cities.php?id=" + state,
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
        placeholder: translate_search_city,
        allowClear: true
    });
}


function loadPackages() {

    $('#data_items').html('');
    packagesItems.forEach(function (item, index) {
        var html_code = '';
        html_code += '<div  class= "card-hover" id="row_id_' + index + '">';
        html_code += '<hr>';
        html_code += '<div class="row"> ';
        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +
            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_quantity + '</label>' +
            '<div class="input-group">' +

            '<input type="text" onchange="changePackage(this)" value="' + item.qty + '" onkeypress="return isNumberKey(event, this)"  name="qty" id="qty_' + index + '" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="' + translate_quantity + '"  value="1"  />' +
            '</div>' +
            '</div>' +
            '</div>';

        html_code += '<div class="col-sm-12 col-md-6 col-lg-3">' +
            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_description + '</label>' +
            '<div class="input-group">' +
            '<input type="text" onchange="changePackage(this)" value="' + item.description + '" name="description" id="description_' + index + '" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" placeholder=" ' + translate_description + '" >' +
            '</div>' +
            '</div>' +
            '</div>';

        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +
            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_weight + '</label>' +
            '<div class="input-group">' +
            '<input type="text" onchange="changePackage(this)" value="' + item.weight + '" onkeypress="return isNumberKey(event, this)"  name="weight" id="weight_' + index + '" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="' + translate_weight + '"/>' +

            '</div>' +
            '</div>' +
            '</div>';

        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +
            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_length + '</label>' +
            '<div class="input-group">' +
            '<input type="text" onchange="changePackage(this)" value="' + item.length + '" onkeypress="return isNumberKey(event, this)" name="length" id="length_' + index + '" class="form-control input-sm text_only" data-toggle="tooltip" data-placement="bottom" title="' + translate_length + '"/>' +
            '</div>' +
            '</div>' +
            '</div>';
        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +

            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_width + '</label>' +
            '<div class="input-group">' +
            '<input type="text" onchange="changePackage(this)" value="' + item.width + '" onkeypress="return isNumberKey(event, this)" name="width" id="width_' + index + '" class="form-control input-sm text_only" data-toggle="tooltip" data-placement="bottom" title="' + translate_width + '"/>' +

            '</div>' +
            '</div>' +
            '</div>';

        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +

            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_height + '</label>' +
            '<div class="input-group">' +
            '<input type="text" onchange="changePackage(this)" value="' + item.height + '" onkeypress="return isNumberKey(event, this)"  name="height" id="height_' + index + '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' + translate_height + '" />' +
            '</div>' +
            '</div>' +
            '</div>';

        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +

            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_volweight + '</label>' +
            '<div class="input-group">' +
            '<input type="text" readonly value="0" onkeypress="return isNumberKey(event, this)"  name="weightVol" id="weightVol_' + index + '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' + translate_volweight + '" />' +
            '</div>' +
            '</div>' +
            '</div>';

        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +
            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_charge + '</label>' +
            '<div class="input-group">' +
            '<input type="text" onchange="changePackage(this)" value="' + item.fixed_value + '" onkeypress="return isNumberKey(event, this)"  name="fixed_value" id="fixedValue_' + index + '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' + translate_charge + '"/>' +
            '</div>' +
            '</div>' +
            '</div>';

        html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +
            '<div class="form-group">' +
            '<label for="emailAddress1"> ' + translate_declared + '</label>' +
            '<div class="input-group">' +
            '<input type="text" onchange="changePackage(this)" value="' + item.declared_value + '" onkeypress="return isNumberKey(event, this)"  name="declared_value" id="declaredValue_' + index + '" class="form-control input-sm number_only" data-toggle="tooltip" data-placement="bottom" title="' + translate_declared + '"/>' +
            '</div>' +
            '</div>' +
            '</div>';

        if (index > 0) {
            html_code += '<div class="col-sm-12 col-md-6 col-lg-1">' +
                '<div class="form-group  mt-4">' +
                '<button type="button"  onclick="deletePackage(' + index + ')"  name="remove_rows"  class="btn btn-danger mt-2 "><i class="fa fa-trash"></i>   </button>' +
                '</div>' +
                '</div>';
        }
        html_code += '</div>';

        html_code += '<hr>';

        html_code += '</div>';

        $('#data_items').append(html_code);
    })
}

function addPackage() {

    packagesItems.push({
        qty: 1,
        description: '',
        length: 0,
        width: 0,
        height: 0,
        weight: 0,
        declared_value: 0,
        fixed_value: 0
    })

    var index = packagesItems.length - 1

    loadPackages();
    calculateFinalTotal();

    $('#row_id_' + index).animate({
        'backgroundColor': '#18BC9C'
    }, 400);

    $('#add_row').attr("disabled", true);

    setTimeout(function () {
        $('#row_id_' + index).css({ 'background-color': '' });
        $('#add_row').attr("disabled", false);
    }, 900);
}

function deletePackage(index) {

    packagesItems = packagesItems.filter((item, i) => index !== i)
    $('#row_id_' + index).animate({
        'backgroundColor': '#FFBFBF'
    }, 400);

    $('#row_id_' + index).fadeOut(400, function () {
        $('#row_id_' + index).remove();
        loadPackages();
        calculateFinalTotal();
    });
}

function changePackage(e) {

    var field = e.id.split('_')
    packagesItems = packagesItems.map(function (item, index) {

        if (index === parseInt(field[1])) {
            item[e.name] = e.value;
        }

        if (field[0] !== "description") {
            if (!e.value) {
                $('#' + e.id).val(0);
                item[e.name] = 0;
            }
        }
        return item
    })
    calculateFinalTotal();
}
function calculateFinalTotal(element = null) {

    if (element) {
        if (!element.value) {
            $(element).val(0);
        }
    }

    var sumador_total = 0;
    var sumador_valor_declarado = 0;
    var max_fixed_charge = 0;
    var sumador_libras = 0;
    var sumador_volumetric = 0;

    var precio_total = 0;
    var total_impuesto = 0;
    var total_descuento = 0;
    var total_seguro = 0;
    var total_peso = 0;
    var total_impuesto_aduanero = 0;
    var total_valor_declarado = 0;

    var tariffs_value = $('#tariffs_value').val();
    var declared_value_tax = $('#declared_value_tax').val();
    var insurance_value = $('#insurance_value').val();
    var tax_value = $('#tax_value').val();
    var discount_value = $('#discount_value').val();
    var reexpedicion_value = $('#reexpedicion_value').val();
    var price_lb = $('#price_lb').val();
    var insured_value = $('#insured_value').val();

    reexpedicion_value = parseFloat(reexpedicion_value);
    insured_value = parseFloat(insured_value);
    price_lb = parseFloat(price_lb);

    packagesItems.forEach(function (item, i) {
        var quantity = parseFloat(item.qty);
        var description = parseFloat(item.description);
        var weight = parseFloat(item.weight);
        var length = parseFloat(item.length);
        var width = parseFloat(item.width);
        var height = parseFloat(item.height);
        var fixed_value = parseFloat(item.fixed_value);
        var declared_value = parseFloat(item.declared_value);

        var core_meter = $('#core_meter').val();
        var core_min_cost_tax = $('#core_min_cost_tax').val();
        var core_min_cost_declared_tax = $('#core_min_cost_declared_tax').val();

        var total_metric = (length * width * height) / core_meter;
        total_metric = parseFloat(total_metric);

        $('#weightVol_' + i).val(total_metric.toFixed(2));

        if (weight > total_metric) {
            var calculate_weight = weight;
        } else {
            var calculate_weight = total_metric;
        }

        sumador_libras += weight;
        sumador_volumetric += total_metric;

        precio_total = calculate_weight * price_lb;
        sumador_total += precio_total;
        sumador_valor_declarado += declared_value;
        max_fixed_charge += fixed_value;

        if (sumador_total > core_min_cost_tax) {
            total_impuesto = sumador_total * tax_value / 100;
        }

        if (sumador_valor_declarado > core_min_cost_declared_tax) {
            total_valor_declarado = sumador_valor_declarado * declared_value_tax / 100;
        }

    })

    total_descuento = sumador_total * discount_value / 100;
    total_peso = sumador_libras + sumador_volumetric;
    total_seguro = insured_value * insurance_value / 100;
    total_impuesto_aduanero = total_peso * tariffs_value;
    var total_envio = (sumador_total - total_descuento) + total_seguro + total_impuesto + total_impuesto_aduanero + total_valor_declarado + max_fixed_charge + reexpedicion_value;

    if (total_descuento > sumador_total) {
        alert(validation_discount_1);
        $('#discount_value').val(0);
        return false;
    } else if (discount_value < 0) {
        alert(validation_discount_2);
        $('#discount_value').val(0);
        return false;
    }

    $('#subtotal').html(sumador_total.toFixed(2));
    // $('#total_declared').html(sumador_valor_declarado);
    $('#discount').html(total_descuento.toFixed(2));
    $('#impuesto').html(total_impuesto.toFixed(2));
    $('#declared_value_label').html(total_valor_declarado.toFixed(2));
    $('#fixed_value_label').html(max_fixed_charge.toFixed(2));
    $('#insurance').html(total_seguro.toFixed(2));
    // $('#total_libras').html(sumador_libras);
    // $('#total_volumetrico').html(sumador_volumetric);
    // $('#total_peso').html(total_peso);
    $('#total_impuesto_aduanero').html(total_impuesto_aduanero.toFixed(2));
    $('#total_envio').html(total_envio.toFixed(2));
    $('#total_weight').html(sumador_libras.toFixed(2));
    $('#total_vol_weight').html(sumador_volumetric.toFixed(2));
    $('#total_fixed').html(max_fixed_charge.toFixed(2));
    $('#total_declared').html(sumador_valor_declarado.toFixed(2));

}

$("#invoice_form").on('submit', function (event) {
    for (let [i, val] of packagesItems.entries()) {

        if ($.trim($('#description_' + i).val()).length == 0) {
            alert(validation_description);
            $('#description_' + i).focus();
            return false;
        }
        if ($.trim($('#qty_' + i).val()).length == 0) {
            alert(validation_quantity);
            $('#qty_' + i).focus();
            return false;
        }
        if ($.trim($('#weight_' + i).val()).length == 0) {
            alert(validation_weight);
            $('#weight_' + i).focus();
            return false;
        }
        if ($.trim($('#length_' + i).val()).length == 0) {
            alert(validation_length);
            $('#length_' + i).focus();
            return false;
        }
        if ($.trim($('#width_' + i).val()).length == 0) {
            alert(validation_width);
            $('#width_' + i).focus();
            return false;
        }
        if ($.trim($('#height_' + i).val()).length == 0) {
            alert(validation_height);
            $('#height_' + i).focus();
            return false;
        }
        if ($.trim($('#fixedValue_' + i).val()).length == 0) {
            alert(validation_charge);
            $('#fixedValue_' + i).focus();
            return false;
        }
        if ($.trim($('#declaredValue_' + i).val()).length == 0) {
            alert(validation_declared);
            $('#declaredValue_' + i).focus();
            return false;
        }
    }

    var prefix_check = $('#prefix_check').val();
    var code_prefix = $('#code_prefix').val();
    var code_prefix2 = $('#code_prefix2').val();
    var notify_sms_sender = $('#notify_sms_sender').val();
    var agency = $('#agency').val();
    var origin_off = $('#origin_off').val();
    var sender_id = $('#sender_id').val();
    var sender_address_id = $('#sender_address_id').val();
    var order_item_category = $('#order_item_category').val();
    var order_courier = $('#order_courier').val();
    var order_service_options = $('#order_service_options').val();
    var order_package = $('#order_package').val();
    var order_date = $('#order_date').val();
    var order_deli_time = $('#order_deli_time').val();
    var provider_purchase = $('#provider_purchase').val();
    var price_purchase = $('#price_purchase').val();
    var tracking_purchase = $('#tracking_purchase').val();

    var driver_id = $('#driver_id').val();
    var price_lb = $('#price_lb').val();
    var insured_value = $('#insured_value').val();
    var insurance_value = $('#insurance_value').val();
    var reexpedicion_value = $('#reexpedicion_value ').val();
    var discount_value = $('#discount_value').val();
    var tax_value = $('#tax_value').val();
    var declared_value_tax = $('#declared_value_tax').val();
    var tariffs_value = $('#tariffs_value').val();

    var data = new FormData();

    data.append('packages', JSON.stringify(packagesItems));

    if (tracking_purchase) { data.append('tracking_purchase', tracking_purchase) }
    if (provider_purchase) { data.append('provider_purchase', provider_purchase) }
    if (price_purchase) { data.append('price_purchase', price_purchase) }
    if (prefix_check) { data.append('prefix_check', prefix_check) }
    if (code_prefix) { data.append('code_prefix', code_prefix) }
    if (code_prefix2) { data.append('code_prefix2', code_prefix2) }
    if (agency) { data.append('agency', agency) }
    if (origin_off) { data.append('origin_off', origin_off) }
    if (sender_id) { data.append('sender_id', sender_id) }
    if (sender_address_id) { data.append('sender_address_id', sender_address_id) }
    if (order_item_category) { data.append('order_item_category', order_item_category) }
    if (order_courier) { data.append('order_courier', order_courier) }
    if (order_service_options) { data.append('order_service_options', order_service_options) }
    if (order_package) { data.append('order_package', order_package) }
    if (order_date) { data.append('order_date', order_date) }
    if (order_deli_time) { data.append('order_deli_time', order_deli_time) }
    if (driver_id) { data.append('driver_id', driver_id) }
    if (price_lb) { data.append('price_lb', price_lb) }
    if (insured_value) { data.append('insured_value', insured_value) }
    if (reexpedicion_value) { data.append('reexpedicion_value', reexpedicion_value) }
    if (discount_value) { data.append('discount_value', discount_value) }
    if (tax_value) { data.append('tax_value', tax_value) }
    if (declared_value_tax) { data.append('declared_value_tax', declared_value_tax) }
    if (tariffs_value) { data.append('tariffs_value', tariffs_value) }
    if (insurance_value) { data.append('insurance_value', insurance_value) }

    if (notify_sms_sender) { data.append('notify_sms_sender', notify_sms_sender) }

    $.ajax({
        type: "POST",
        url: "ajax/customers_packages/add_customers_packages_multiple_ajax.php",
        data: data,
        contentType: false,
        dataType: 'json',
        cache: false,             // To unable request pages to be cached
        processData: false,
        beforeSend: function (objeto) {
            $('#create_invoice').attr("disabled", true);
            Swal.fire({
                title: message_loading,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            })
        },
        success: function (data) {
            $('#create_invoice').attr("disabled", false);
            if (data.success) {
                cdp_showSuccess(data.messages)
            } else {
                cdp_showError(data.errors)
            }
        }
    });

    event.preventDefault();

})


$(function () {
    $('#code_prefix2').hide();
    $('#prefix_check').on('change', function (event) {
        if ($('#prefix_check').is(":checked")) {
            $('#code_prefix').hide();
            $('#code_prefix').attr("disabled", true);
            $('#prefix_check').val(1);
            $('#code_prefix2').show();
            $('#code_prefix2').attr("disabled", false);
            $("#code_prefix2").attr("required", "true");
        }
        else {
            $('#prefix_check').val(0);
            $('#code_prefix2').hide();
            $('#code_prefix2').attr("disabled", true);
            $('#code_prefix').show();
            $('#code_prefix').attr("disabled", false);
            $("#code_prefix2").attr("required", "false");
        }
    });
});

function isNumberKey(evt, element) {


    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57) && !(charCode == 46 || charCode == 8))
        return false;
    else {
        var len = $(element).val().length;
        var index = $(element).val().indexOf('.');
        if (index > 0 && charCode == 46) {
            return false;
        }
        if (index > 0) {
            var CharAfterdot = (len + 1) - index;
            if (CharAfterdot > 4) {
                return false;
            }
        }
    }
    return true;
}


function cdp_select2_init_sender() {

    $("#sender_id").select2({
        ajax: {
            url: "ajax/select2_sender.php",
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
        placeholder: search_sender,
        allowClear: true
    }).on('change', function (e) {

        var sender_id = $("#sender_id").val();
        $("#sender_address_id").attr("disabled", true);
        $("#add_address_sender").attr("disabled", true);
        $("#sender_address_id").val(null);
        if (sender_id != null) {
            $("#add_address_sender").attr("disabled", false);
            $("#sender_address_id").attr("disabled", false);
        }
        cdp_select2_init_sender_address();
    });
}

function cdp_select2_init_sender_address() {

    var sender_id = $("#sender_id").val();
    $("#sender_address_id").select2({
        ajax: {
            url: "ajax/select2_sender_addresses.php?id=" + sender_id,
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

        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        // minimumInputLength: 1,
        templateResult: cdp_formatAdress, // omitted for brevity, see the source of this page
        templateSelection: cdp_formatAdressSelection, // omitted for brevity, see the source of this page
        // minimumInputLength: 2,
        placeholder: search_sender_address,
        allowClear: true

    });
}

function cdp_formatAdress(item) {

    if (item.loading) return item.text;
    var markup = "<div class='select2-result-repository clearfix'>";

    markup += "<div class='select2-result-repository__statistics'>" +
        "<div class='select2-result-repository__forks'><i class='la la-code-fork mr-0'></i> <b> " + translate_search_address_address + ": </b> " + item.text + " | <b>" + translate_search_address_country + ": </b>" + item.country + " | <b>" + translate_search_address_state + ": </b>" + item.state + " | <b>" + translate_search_address_city + ": </b>" + item.city + " | <b>" + translate_search_address_zip + ": </b>" + item.zip_code + " </div>" +

        "</div>" +
        "</div></div>";

    return markup;
}


function cdp_formatAdressSelection(repo) {
    return repo.text;
}

$("#add_user_from_modal_shipments").on('submit', function (event) {

    if ($.trim($('#country_modal_user').val()).length == 0) {
        alert(validation_city);
        $('#country_modal_user').focus();
        return false;
    }

    if ($.trim($('#state_modal_user').val()).length == 0) {
        alert(validation_state);
        $('#state_modal_user').focus();
        return false;
    }

    if ($.trim($('#city_modal_user').val()).length == 0) {
        alert(validation_city);
        $('#city_modal_user').focus();
        return false;
    }

    if ($.trim($('#postal_modal_user').val()).length == 0) {
        alert(validation_zip);
        $('#postal_modal_user').focus();
        return false;
    }

    if ($.trim($('#address_modal_user').val()).length == 0) {
        alert(validation_address);
        $('#address_modal_user').focus();
        return false;
    }

    if (iti.isValidNumber()) {

        var sender_id = $('#sender_id').val();
        $('#save_data_user').attr("disabled", true);
        var parametros = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "ajax/courier/add_users_ajax.php?sender=" + sender_id,
            data: parametros,

            success: function (datos) {
                cdp_select2_init_sender();
                $(".resultados_ajax_add_user_modal_sender").html(datos);
                $('#save_data_user').attr("disabled", false);
                $("#myModalAddUser").modal('hide');

                window.setTimeout(function () {
                    $(".alert").fadeTo(500, 0).slideUp(500, function () {
                        $(this).remove();
                    });
                }, 5000);
            }
        });

    } else {

        input.classList.add("error");
        var errorCode = iti.getValidationError();
        errorMsg.innerHTML = errorMap[errorCode];
        errorMsg.classList.remove("hide");

    }
    event.preventDefault();

})


$("#add_address_users_from_modal_shipments").on('submit', function (event) {

    if ($.trim($('#address_modal_user_address').val()).length == 0) {
        alert(validation_address);
        $('#address_modal_user_address').focus();
        return false;
    }

    if ($.trim($('#country_modal_user_address').val()).length == 0) {
        alert(validation_city);
        $('#country_modal_user_address').focus();
        return false;
    }
    if ($.trim($('#state_modal_user_address').val()).length == 0) {
        alert(validation_state);
        $('#state_modal_user_address').focus();
        return false;
    }

    if ($.trim($('#city_modal_user_address').val()).length == 0) {
        alert(validation_city);
        $('#city_modal_user_address').focus();
        return false;
    }

    if ($.trim($('#postal_modal_user_address').val()).length == 0) {
        alert(validation_zip);
        $('#postal_modal_user_address').focus();
        return false;
    }

    var sender_id = $('#sender_id').val();
    $('#save_data_address_users').attr("disabled", true);
    var parametros = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "ajax/courier/add_address_users_ajax.php?sender=" + sender_id,
        data: parametros,
        success: function (datos) {
            $('#save_data_address_users').attr("disabled", false);
            $(".resultados_ajax_add_user_modal_sender").html(datos);

            $("#myModalAddUserAddresses").modal('hide');
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 5000);
        }
    });
    event.preventDefault();
})


var errorMsg = document.querySelector("#error-msg");
var validMsg = document.querySelector("#valid-msg");

// here, the index maps to the error code returned from getValidationError - see readme
var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];


var input = document.querySelector("#phone_custom");
var iti = window.intlTelInput(input, {

    geoIpLookup: function (callback) {
        $.get("http://ipinfo.io", function () { }, "jsonp").always(function (resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
        });
    },
    initialCountry: "auto",
    nationalMode: true,

    separateDialCode: true,
    utilsScript: "assets/template/assets/libs/intlTelInput/utils.js",
});


var reset = function () {
    input.classList.remove("error");
    errorMsg.innerHTML = "";
    errorMsg.classList.add("hide");
    validMsg.classList.add("hide");
};

// on blur: validate
input.addEventListener('blur', function () {
    reset();
    if (input.value.trim()) {

        if (iti.isValidNumber()) {

            $('#phone').val(iti.getNumber());

            validMsg.classList.remove("hide");

        } else {

            input.classList.add("error");
            var errorCode = iti.getValidationError();
            errorMsg.innerHTML = errorMap[errorCode];
            errorMsg.classList.remove("hide");

        }
    }
});

// on keyup / change flag: reset
input.addEventListener('change', reset);
input.addEventListener('keyup', reset);



function cdp_showError(errors) {

    var html_code = '';
    html_code += '<ul class="error" > ';

    for (var error in errors) {
        html_code += '<li class="text-left">';
        html_code += '<i class="icon-double-angle-right"></i>';
        html_code += errors[error];
        html_code += '</li>';
    }
    '</ul >';

    Swal.fire({
        title: message_error,
        html: html_code,
        icon: 'error',
        allowOutsideClick: false,
        confirmButtonText: 'Ok'
    })

}

function cdp_showSuccess(messages) {
    Swal.fire({
        title: messages,
        icon: 'success',
        allowOutsideClick: false,
        confirmButtonText: 'Ok'
    })
}
