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



require_once("../../loader.php");
require_once("../../helpers/querys.php");
$user = new User;
$core = new Core;
$errors = array();

if (empty($_POST['fname_recipient']))

    $errors['fname'] = $lang['validate_field_ajax122'];
if (empty($_POST['lname_recipient']))

    $errors['fname'] = $lang['validate_field_ajax123'];

if (empty($_POST['email_recipient']))

    $errors['email'] = $lang['validate_field_ajax127'];



if (empty($_POST['phone_recipient']))

    $errors['phone_custom'] = $lang['validate_field_ajax128'];


if (empty($errors)) {

    $data = array(
        'lname' => cdp_sanitize($_POST['lname_recipient']),
        'fname' => cdp_sanitize($_POST['fname_recipient']),
        'phone' => cdp_sanitize($_POST['phone_recipient']),
        'email' => cdp_sanitize($_POST['email_recipient']),
        'sender_id' => cdp_sanitize($_GET['sender']),
    );

    $recipient_id = cdp_insertRecipient($data);

    if ($recipient_id !== null) {

        $db->cdp_query("SELECT * FROM cdb_recipients where id= '" . $recipient_id . "'");
        $customer_data = $db->cdp_registro();

        $db->cdp_query("
            INSERT INTO cdb_recipients_addresses 
            (
                country,
                state,
                city,
                zip_code,
                address,
                recipient_id                                
            )
            VALUES 
            (
                :country,
                :state,
                :city, 
                :zip_code,
                :address,
                :recipient_id                            
            )
        ");

        $db->bind(':recipient_id',  $recipient_id);
        $db->bind(':country',  cdp_sanitize($_POST["country_modal_recipient"]));
        $db->bind(':state',  cdp_sanitize($_POST["state_modal_recipient"]));
        $db->bind(':city',  cdp_sanitize($_POST["city_modal_recipient"]));
        $db->bind(':zip_code',  cdp_sanitize($_POST["postal_modal_recipient"]));
        $db->bind(':address',  cdp_sanitize($_POST["address_modal_recipient"]));

        $insert = $db->cdp_execute();

        $last_address_id = $db->dbh->lastInsertId();

        $db->cdp_query("SELECT * FROM cdb_recipients_addresses where id_addresses= '" . $last_address_id . "'");
        $customer_address = $db->cdp_registro();
    }

    if ($insert) {
        $messages[] = $lang['message_ajax_success_add'];
    } else {
        $errors['critical_error'] = $lang['message_ajax_error1'];
    }
}

if (!empty($errors)) {
?>
    <div class="alert alert-danger" id="success-alert">
        <p><span class="icon-minus-sign"></span><i class="close icon-remove-circle"></i>
            <?php echo $lang['message_ajax_error2']; ?>
        <ul class="error">
            <?php
            foreach ($errors as $error) { ?>
                <li>
                    <i class="icon-double-angle-right"></i>
                    <?php
                    echo $error;
                    ?>
                </li>
            <?php

            }
            ?>
        </ul>
        </p>
    </div>
<?php
}

if (isset($messages)) {

?>
    <div class="alert alert-info" id="success-alert">
        <p><span class="icon-info-sign"></span><i class="close icon-remove-circle"></i>
            <?php
            foreach ($messages as $message) {
                echo $message;
            }

            ?>
        </p>

        <script>
            $("#add_recipient_from_modal_shipments")[0].reset();
        </script>
    </div>

    <script>
        var data = {
            id: <?php echo $customer_data->id; ?>,
            text: "<?php echo $customer_data->fname . ' ' . $customer_data->lname; ?>"
        };

        var newOption = new Option(data.text, data.id, false, false);

        $('#recipient_id').append(newOption).trigger('change');
        $('#recipient_id').val(data.id).trigger('change');

        var data_address = {
            id: <?php echo $customer_address->id_addresses; ?>,
            text: "<?php echo $customer_address->address; ?>"
        };

        var newOption = new Option(data_address.text, data_address.id, false, false);

        $('#recipient_address_id').append(newOption).trigger('change');
        $('#recipient_address_id').val(data_address.id).trigger('change');
    </script>

<?php
}
?>