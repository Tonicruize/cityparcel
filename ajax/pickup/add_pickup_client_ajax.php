<?php
// *************************************************************************
// *                                                                       *
// * DEPRIXA PRO -  Integrated Web Shipping System                         *
// * Copyright (c) JAOMWEB. All Rights Reserved                            *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: support@jaom.info                                              *
// * Website: http://www.jaom.info                                         *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************

ini_set('display_errors', 0);


require_once("../../loader.php");
require_once("../../helpers/querys.php");
require_once("../../helpers/phpmailer/class.phpmailer.php");
require_once("../../helpers/phpmailer/class.smtp.php");
require_once '../../helpers/vendor/autoload.php';
require_once '../../helpers/src/src/Twilio/autoload.php';

use Twilio\Rest\Client;

$user = new User;
$core = new Core;
$errors = array();


if (empty($_POST['sender_id']))
    $errors['sender_id'] = $lang['validate_field_ajax150'];

if (empty($_POST['sender_address_id']))
    $errors['sender_address_id'] = $lang['validate_field_ajax145'];

if (empty($_POST['recipient_id']))
    $errors['recipient_id'] = $lang['validate_field_ajax146'];

if (empty($_POST['recipient_address_id']))
    $errors['recipient_address_id'] = $lang['validate_field_ajax147'];

if (empty($_POST['order_item_category']))
    $errors['order_item_category'] = $lang['validate_field_ajax151'];

if (empty($_POST['order_package']))
    $errors['order_package'] = $lang['validate_field_ajax152'];

if (empty($_POST['order_pay_mode']))
    $errors['order_pay_mode'] = $lang['validate_field_ajax156'];


if (empty($errors)) {

    $settings = cdp_getSettingsCourier();

    $order_prefix = $settings->prefix;
    $site_email = $settings->email_address;
    $check_mail = $settings->mailer;
    $names_info = $settings->smtp_names;
    $mlogo = $settings->logo;
    $msite_url = $settings->site_url;
    $msnames = $settings->site_name;
    //SMTP
    $smtphoste = $settings->smtp_host;
    $smtpuser = $settings->smtp_user;
    $smtppass = $settings->smtp_password;
    $smtpport = $settings->smtp_port;
    $smtpsecure = $settings->smtp_secure;
    $value_weight = $settings->value_weight;
    $meter = $settings->meter;


    $next_order = $core->cdp_order_track();
    $min_cost_tax = $core->min_cost_tax;
    $min_cost_declared_tax = $core->min_cost_declared_tax;

    $date = date('Y-m-d', strtotime(cdp_sanitize($_POST["order_date"])));
    $time = date("H:i:s");
    $date = $date . ' ' . $time;

    $status = 14;
    $is_pickup = true;
    $order_incomplete = 0;
    $days = 0;

    $days = intval($days);

    $sale_date   = date("Y-m-d H:i:s");
    $due_date = cdp_sumardias($sale_date, $days);
    $status_invoice = 2;

    $dataShipment = array(
        'user_id' =>  $_SESSION['userid'],
        'order_prefix' =>  $order_prefix,
        'order_incomplete' =>  $order_incomplete,
        'is_pickup' =>  $is_pickup,
        'order_no' => cdp_sanitize($next_order),
        'order_datetime' =>  cdp_sanitize($date),
        'sender_id' =>  cdp_sanitize(intval($_POST["sender_id"])),
        'recipient_id' =>  cdp_sanitize(intval($_POST["recipient_id"])),
        'sender_address_id' =>  cdp_sanitize(intval($_POST["sender_address_id"])),
        'recipient_address_id' =>  cdp_sanitize(intval($_POST["recipient_address_id"])),
        'order_date' =>  date("Y-m-d H:i:s"),
        'order_package' =>  cdp_sanitize(intval($_POST["order_package"])),
        'order_item_category' =>  cdp_sanitize(intval($_POST["order_item_category"])),
        'order_service_options' =>  cdp_sanitize(intval($_POST["order_service_options"])),
        'order_pay_mode' =>  cdp_sanitize(intval($_POST["order_pay_mode"])),
        'status_courier' =>  cdp_sanitize(intval($status)),
        'due_date' =>  $due_date,
        'status_invoice' =>  $status_invoice,
        'volumetric_percentage' =>  $meter
    );

    $shipment_id = cdp_insertCourierPickupFromCustomer($dataShipment);

    if ($shipment_id !== null) {

        if (isset($_POST["packages"])) {

            $packages = json_decode($_POST['packages']);

            $sumador_total = 0;
            $sumador_valor_declarado = 0;
            $max_fixed_charge = 0;
            $sumador_libras = 0;
            $sumador_volumetric = 0;

            $precio_total = 0;
            $total_impuesto = 0;
            $total_descuento = 0;
            $total_seguro = 0;
            $total_peso = 0;
            $total_impuesto_aduanero = 0;
            $total_valor_declarado = 0;

            $tariffs_value = $_POST["tariffs_value"];
            $declared_value_tax = $_POST["declared_value_tax"];
            $insurance_value = $_POST["insurance_value"];
            $tax_value = $_POST["tax_value"];
            $discount_value = $_POST["discount_value"];
            $reexpedicion_value = $_POST["reexpedicion_value"];
            $price_lb = $_POST["price_lb"];
            $insured_value = $_POST["insured_value"];

            foreach ($packages as $package) {

                $dataAddresses = array(
                    'order_id' =>  $shipment_id,
                    'qty' =>  $package->qty,
                    'description' =>  $package->description,
                    'length' =>  $package->length,
                    'width' =>  $package->width,
                    'height' =>  $package->height,
                    'weight' =>  $package->weight,
                    'declared_value' =>  $package->declared_value,
                    'fixed_value' =>  $package->fixed_value,
                );

                cdp_insertCourierShipmentPackages($dataAddresses);

                // calculate weight columetric box size
                $total_metric = $package->length * $package->width * $package->height / $meter;
                $weight = $package->weight;

                // calculate weight x price
                if ($weight > $total_metric) {
                    $calculate_weight = $weight;
                    $sumador_libras += $weight;
                } else {
                    $calculate_weight = $total_metric;
                    $sumador_volumetric += $total_metric;
                }

                $sumador_valor_declarado += $package->declared_value;
                $max_fixed_charge += $package->fixed_value;
            }
            // $precio_total = $calculate_weight * $price_lb;
            $sumador_total += $price_lb;

            if ($sumador_total > $min_cost_tax) {
                $total_impuesto = $sumador_total * $tax_value / 100;
            }

            if ($sumador_valor_declarado > $min_cost_declared_tax) {
                $total_valor_declarado = $sumador_valor_declarado * $declared_value_tax / 100;
            }

            $total_descuento = $sumador_total * $discount_value / 100;
            $total_peso = $sumador_libras + $sumador_volumetric;
            $total_seguro = $insured_value * $insurance_value / 100;
            $total_impuesto_aduanero = $total_peso * $tariffs_value;
            $total_envio = ($sumador_total - $total_descuento) + $total_seguro + $total_impuesto + $total_impuesto_aduanero + $total_valor_declarado + $max_fixed_charge + $reexpedicion_value;
        }

        $dataShipmentUpdateTotals = array(
            'order_id' =>  $shipment_id,
            'value_weight' =>  floatval($price_lb),
            'sub_total' =>  floatval($sumador_total),
            'tax_discount' =>  floatval($discount_value),
            'total_insured_value' => floatval($insured_value),
            'tax_insurance_value' => floatval($insurance_value),
            'tax_custom_tariffis_value' => floatval($tariffs_value),
            'tax_value' => floatval($tax_value),
            'declared_value' =>  floatval($declared_value_tax),
            'total_reexp' =>  floatval($reexpedicion_value),
            'total_declared_value' =>  floatval($total_valor_declarado),
            'total_fixed_value' =>  floatval($max_fixed_charge),
            'total_tax_discount' =>  floatval($total_descuento),
            'total_tax_insurance' =>  floatval($total_seguro),
            'total_tax_custom_tariffis' =>  floatval($total_impuesto_aduanero),
            'total_tax' =>  floatval($total_impuesto),
            'total_weight' =>  floatval($total_peso),
            'total_order' =>  floatval($total_envio),
        );

        $update = cdp_updateCourierShipmentTotals($dataShipmentUpdateTotals);
        $order_track = $order_prefix . $next_order;

        if (isset($_FILES['filesMultiple']) && count($_FILES['filesMultiple']['name']) > 0 && $_FILES['filesMultiple']['tmp_name'][0] != '') {

            $target_dir = "../../order_files/";
            $deleted_file_ids = array();

            if (isset($_POST['deleted_file_ids']) && !empty($_POST['deleted_file_ids'])) {
                $deleted_file_ids = explode(",", $_POST['deleted_file_ids']);
            }

            foreach ($_FILES["filesMultiple"]['tmp_name'] as $key => $tmp_name) {

                if (!in_array($key, $deleted_file_ids)) {
                    $image_name = $order_track .  date("Y-m-d") . "_" . basename($_FILES["filesMultiple"]["name"][$key]);
                    $target_file = $target_dir . $image_name;
                    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                    $imageFileZise = $_FILES["filesMultiple"]["size"][$key];

                    if ($imageFileZise > 0) {
                        move_uploaded_file($_FILES["filesMultiple"]["tmp_name"][$key], $target_file);
                        $imagen = basename($_FILES["filesMultiple"]["name"][$key]);
                    }

                    $target_file_db = "order_files/" . $image_name;
                    cdp_insertOrdersFiles($shipment_id, $target_file_db, $image_name, date("Y-m-d H:i:s"), '0', $imageFileType);
                }
            }
        }
        $dataTrack = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'order_track' =>  $order_track,
            't_date' =>  date("Y-m-d H:i:s"),
            'status_courier' =>  cdp_sanitize(intval($status)),
            'comments' =>  'Shipping created',
            'office' => cdp_sanitize(intval($_POST["origin_off"]))
        );

        cdp_insertCourierShipmentTrack($dataTrack);

        $sender_data = cdp_getSenderCourier(intval($_POST["sender_id"]));
        $receiver_data = cdp_getRecipientCourier(intval($_POST["recipient_id"]));

        $fullshipment = $order_prefix . $next_order;
        $add_status =   intval($status);
        $date_ship   = date("Y-m-d H:i:s a");

        $app_url = $settings->site_url . 'track.php?order_track=' . $fullshipment;
        $subject = $lang['notification_shipment2'] . $lang['notification_shipment6'] .  $fullshipment;

        $email_template = cdp_getEmailTemplatesdg1i4(16);

        $body = str_replace(
            array(
                '[NAME]',
                '[TRACKING]',
                '[DELIVERY_TIME]',
                '[URL]',
                '[URL_LINK]',
                '[SITE_NAME]',
                '[URL_SHIP]'
            ),
            array(
                $sender_data->fname . ' ' . $sender_data->lname,
                $fullshipment,
                $date_ship,
                $msite_url,
                $mlogo,
                $msnames,
                $app_url
            ),
            $email_template->body
        );

        $newbody = cdp_cleanOut($body);

        //SENDMAIL PHP

        if ($check_mail == 'PHP') {

            $message = $newbody;
            $to = $sender_data->email;
            $from = $site_email;

            $header = "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html; charset=UTF-8 \r\n";
            $header .= "From: " . $from . " \r\n";
            try {
                mail($to, $subject, $message, $header);
            } catch (Exception $e) {
            }
        } elseif ($check_mail == 'SMTP') {

            //PHPMAILER PHP
            $destinatario = $sender_data->email;

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Port = $smtpport;
            $mail->IsHTML(true);
            $mail->CharSet = "utf-8";

            // Datos de la cuenta de correo utilizada para enviar vía SMTP
            $mail->Host = $smtphoste;       // Dominio alternativo brindado en el email de alta
            $mail->Username = $smtpuser;    // Mi cuenta de correo
            $mail->Password = $smtppass;    //Mi contraseña


            $mail->From = $site_email; // Email desde donde envío el correo.
            $mail->FromName = $names_info;
            $mail->AddAddress($destinatario); // Esta es la dirección a donde enviamos los datos del formulario

            $mail->Subject = $subject; // Este es el titulo del email.
            $mail->Body = "
                    <html> 
                    <body> 
                    <p>{$newbody}</p>
                    </body> 
                    </html>
                    <br />"; // Texto del email en formato HTML

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            try {
                $estadoEnvio = $mail->Send();
                // echo "El correo fue enviado correctamente.";
            } catch (Exception $e) {
                // echo "Ocurrió un error inesperado.";
            }
        }

        //NOTIFY WHATSAPP API

        if (isset($_POST['notify_whatsapp_sender']) && intval($_POST['notify_whatsapp_sender']) == 1) {

            if ($core->twilio_sid != null && $core->twilio_token != null && $core->twilio_number != null) {
                $phone_sender = $sender_data->phone;
                $sid    = $core->twilio_sid;
                $token  = $core->twilio_token;
                try {
                    $twilio = new Client($sid, $token);
                    $message = $twilio->messages
                        ->create(
                            "whatsapp:" . $phone_sender, // to 
                            array(
                                "from" => "whatsapp:" . $core->twilio_number,
                                "body" => $lang['notification_shipment2'] . $lang['notification_shipment3'] . $fullshipment . $lang['notification_shipment4'] . $app_url
                            )
                        );
                } catch (Exception $e) {
                }
            }
        }


        if (isset($_POST['notify_whatsapp_receiver']) && intval($_POST['notify_whatsapp_receiver']) == 1) {

            if ($core->twilio_sid != null && $core->twilio_token != null && $core->twilio_number != null) {

                $phone_receiver = $receiver_data->phone;
                $sid    = $core->twilio_sid;
                $token  = $core->twilio_token;
                try {
                    $twilio = new Client($sid, $token);
                    $message = $twilio->messages
                        ->create(
                            "whatsapp:" . $phone_receiver, // to 
                            array(
                                "from" => "whatsapp:" . $core->twilio_number,
                                "body" => $lang['notification_shipment2'] . $lang['notification_shipment3'] . $fullshipment . $lang['notification_shipment4'] . $app_url
                            )
                        );
                } catch (Exception $e) {
                }
            }
        }

        //TEMPLATE NOTIFY SMS SENDER

        $subjectVal_sender = cdp_getsmsTemplates(2);
        $searchVal_sender = array("[NAME]", "[TRACK]", "[STATUS]", "[LINK]");
        $replaceVal_sender = array("" . $sender_data->fname . ' ' . $sender_data->lname . "", "" . $fullshipment . "", "" . $add_status . "", "" . $app_url . "");

        $sender_body = $subjectVal_sender->body;
        $resStr_sender = str_replace($searchVal_sender, $replaceVal_sender, $sender_body);
        $newbodyS_sender = $resStr_sender;

        //TEMPLATE NOTIFY SMS RECEIVER
        $subjectVal_receiver = cdp_getsmsTemplates(1);
        $searchVal_receiver = array("[NAME]", "[TRACK]", "[STATUS]", "[LINK]");
        $replaceVal_receiver = array("" . $sender_data->fname . ' ' . $sender_data->lname . "", "" . $fullshipment . "", "" . $add_status . "", "" . $app_url . "");

        $receiver_body = $subjectVal_receiver->body;
        $resStr_receiver = str_replace($searchVal_receiver, $replaceVal_receiver, $receiver_body);
        $newbodyS_receiver = $resStr_receiver;

        //NOTIFY SMS API
        if (isset($_POST['notify_sms_sender']) && intval($_POST['notify_sms_sender']) == 1) {

            if ($core->twilio_sms_sid != null && $core->twilio_sms_token != null && $core->twilio_sms_number != null) {
                try {

                    $phone_sender = $sender_data->phone;
                    // Your Account SID and Auth Token from twilio.com/console
                    $account_sid = $core->twilio_sms_sid;
                    $auth_token = $core->twilio_sms_token;
                    $twilio_numbers = $core->twilio_sms_number;

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create(
                        // Where to send a text message (your cell phone?)
                        '' . $phone_sender . '',
                        array(
                            'from' => $twilio_numbers,
                            'body' => htmlentities('' . $newbodyS_sender . '')
                        )
                    );
                } catch (Exception $e) {
                }
            }
        }


        if (isset($_POST['notify_sms_receiver']) && intval($_POST['notify_sms_receiver'] == 1)) {

            if ($core->twilio_sms_sid != null && $core->twilio_sms_token != null && $core->twilio_sms_number != null) {
                try {
                    $phone_receiver = $receiver_data->phone;
                    // Your Account SID and Auth Token from twilio.com/console
                    $account_sid = $core->twilio_sms_sid;
                    $auth_token = $core->twilio_sms_token;

                    $twilio_numbers = $core->twilio_sms_number;

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create(
                        // Where to send a text message (your cell phone?)
                        '' . $phone_receiver . '',
                        array(
                            'from' => $twilio_numbers,
                            'body' => htmlentities('' . $newbodyS_receiver . '')
                        )
                    );
                } catch (Exception $e) {
                }
            }
        }

        $dataHistory = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'action' =>  $lang['notification_shipment8'],
            'date_history' =>  cdp_sanitize(date("Y-m-d H:i:s")),
        );

        //INSERT HISTORY USER
        cdp_insertCourierShipmentUserHistory(
            $dataHistory
        );

        $dataNotification = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'notification_description' => $lang['notification_shipment'],
            'shipping_type' => '1',
            'notification_date' =>  cdp_sanitize(date("Y-m-d H:i:s")),
        );
        // SAVE NOTIFICATION
        cdp_insertNotification(
            $dataNotification
        );

        $notification_id = $db->dbh->lastInsertId();

        //NOTIFICATION TO DRIVER
        cdp_insertNotificationsUsers($notification_id, intval($_POST["driver_id"]));
        //NOTIFICATION TO ADMIN AND EMPLOYEES
        $users_employees = cdp_getUsersAdminEmployees();

        foreach ($users_employees as $key) {
            cdp_insertNotificationsUsers($notification_id, $key->id);
        }
        //NOTIFICATION TO CUSTOMER
        cdp_insertNotificationsUsers($notification_id, intval($_POST['sender_id']));

        $sender_address_data = cdp_getSenderAddress(intval($_POST["sender_address_id"]));
        $sender_country = $sender_address_data->country;
        $sender_state = $sender_address_data->state;
        $sender_city = $sender_address_data->city;
        $sender_zip_code = $sender_address_data->zip_code;
        $sender_address = $sender_address_data->address;

        $_sender_country = cdp_getCountry($sender_country);
        $final_sender_country = $_sender_country['data'];

        $_sender_state = cdp_getState($sender_state);
        $final_sender_state = $_sender_state['data'];

        $sender_city = cdp_getCity($sender_city);
        $final_sender_city = $sender_city['data'];


        $recipient_address_data = cdp_getRecipientAddress(intval($_POST["recipient_address_id"]));

        $recipient_address = $recipient_address_data->address;
        $recipient_country = $recipient_address_data->country;
        $recipient_city = $recipient_address_data->city;
        $recipient_state = $recipient_address_data->state;
        $recipient_zip_code = $recipient_address_data->zip_code;

        $_recipient_country = cdp_getCountry($recipient_country);
        $final_recipient_country = $_recipient_country['data'];

        $_recipient_state = cdp_getState($recipient_state);
        $final_recipient_state = $_recipient_state['data'];

        $recipient_city = cdp_getCity($recipient_city);
        $final_recipient_city = $recipient_city['data'];

        // SAVE ADDRESS FOR Shipments
        $dataAddresses = array(
            'order_id' =>   $shipment_id,
            'order_track' =>   $order_track,
            'sender_country' =>   $final_sender_country->name,
            'sender_state' =>   $final_sender_state->name,
            'sender_city' =>   $final_sender_city->name,
            'sender_zip_code' =>   $sender_zip_code,
            'sender_address' =>   $sender_address,
            'recipient_country' =>   $final_recipient_country->name,
            'recipient_state' =>   $final_recipient_state->name,
            'recipient_city' =>   $final_recipient_city->name,
            'recipient_zip_code' =>   $recipient_zip_code,
            'recipient_address' =>   $recipient_address,
        );

        cdp_insertCourierShipmentAddresses($dataAddresses);

        $messages[] = $lang['message_ajax_success_add_pickup'];
    } else {
        $errors['critical_error'] = $lang['message_ajax_error2'];
    }
}

if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
} else {
    echo json_encode([
        'success' => true,
        'messages' => $messages,
        'shipment_id' => $shipment_id,
    ]);
}
