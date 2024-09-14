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



if (empty($_POST['fname']))
    $errors['fname'] = $lang['validate_field_ajax122'];

if (empty($_POST['lname']))
    $errors['lname'] = $lang['validate_field_ajax123'];

if (empty($_POST['email']))
    $errors['email'] = $lang['validate_field_ajax125'];

if ($user->cdp_emailExists($_POST['email']))
    $errors[] = $lang['validate_field_ajax126'];

if (!$user->cdp_isValidEmail($_POST['email']))
    $errors[] = $lang['validate_field_ajax127'];

if (empty($_POST['phone_custom']))
    $errors['phone_custom'] = $lang['validate_field_ajax128'];


if (empty($_POST['address']))
    $errors['address'] = $lang['validate_field_ajax88'];


if (empty($errors)) {

    $data = array(

        'lname' => cdp_sanitize($_POST['lname']),
        'fname' => cdp_sanitize($_POST['fname']),
        'phone' => cdp_sanitize($_POST['phone']),
        'email' => cdp_sanitize($_POST['email']),
        'sender_id' => $_SESSION['userid']
    );


    $recipient_id = cdp_insertRecipient($data);

    if ($recipient_id !== null && isset($_POST["total_address"])) {

        for ($count = 0; $count < $_POST["total_address"]; $count++) {

            $dataAddresses = array(
                'recipient_id' =>  $recipient_id,
                'address' =>  cdp_sanitize($_POST["address"][$count]),
                'country' =>  cdp_sanitize($_POST["country"][$count]),
                'city' =>  cdp_sanitize($_POST["city"][$count]),
                'state' =>  cdp_sanitize($_POST["state"][$count]),
                'postal' =>  cdp_sanitize($_POST["postal"][$count])
            );

            cdp_insertAddressRecipient($dataAddresses);
        }
    }

    if ($recipient_id) {
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
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <p><span class="icon-info-sign"></span>
            <?php
            foreach ($messages as $message) {
                echo $message;
            }
            ?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

<?php
}
?>