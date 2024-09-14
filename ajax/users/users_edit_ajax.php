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

$errors = array();

if (empty($_POST['branch_office']))

    $errors['branch_office'] = $lang['validate_field_ajax121'];

if (empty($_POST['fname']))

    $errors['fname'] = $lang['validate_field_ajax122'];
if (empty($_POST['lname']))

    $errors['lname'] = $lang['validate_field_ajax123'];

if (empty($_POST['email']))

    $errors['email'] = $lang['validate_field_ajax125'];

if ($user->cdp_emailExists($_POST['email'], $_POST['id']))

    $errors[] = $lang['validate_field_ajax126'];

if (!$user->cdp_isValidEmail($_POST['email']))

    $errors[] = $lang['validate_field_ajax127'];

if (empty($_POST['phone']))

    $errors['phone'] = $lang['validate_field_ajax128'];


if (!empty($_FILES['avatar']['name'])) {

    $target_dir = "../../assets/uploads/";
    $image_name = time() . "_" . basename($_FILES["avatar"]["name"]);
    $target_file = $target_dir . $image_name;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $imageFileZise = $_FILES["avatar"]["size"];

    if (($imageFileType != "jpg" && $imageFileType != "png")) {

        $errors['avatar'] = $lang['validate_field_ajax62'];
    } else if (empty(getimagesize($_FILES['avatar']['tmp_name']))) { //1048576 byte=1MB

        $errors['avatar'] = $lang['validate_field_ajax62'];
    }
}

if (CDP_APP_MODE_DEMO === true) {
?>

    <div class="alert alert-warning" id="success-alert">
        <p><span class="icon-minus-sign"></span><i class="close icon-remove-circle"></i>
            <span>Error! </span> There was an error processing the request
        <ul class="error">

            <li>
                <i class="icon-double-angle-right"></i>
                This is a demo version, this action is not allowed, <a class="btn waves-effect waves-light btn-xs btn-success" href="https://codecanyon.net/item/courier-deprixa-pro-integrated-web-system-v32/15216982" target="_blank">Buy DEPRIXA PRO</a> the full version and enjoy all the functions...

            </li>


        </ul>
        </p>
    </div>
    <?php
} else {

    if (empty($errors)) {


        if (isset($_POST['document_type'])) {

            $document_type = $_POST['document_type'];
        } else {

            $document_type = '';
        }

        if (isset($_POST['document_number'])) {

            $document_number = $_POST['document_number'];
        } else {

            $document_number = '';
        }

        $data = array(

            'email' => cdp_sanitize($_POST['email']),
            'lname' => cdp_sanitize($_POST['lname']),
            'fname' => cdp_sanitize($_POST['fname']),
            'newsletter' => intval($_POST['newsletter']),
            'notes' => cdp_sanitize($_POST['notes']),
            'phone' => cdp_sanitize($_POST['phone']),
            'gender' => cdp_sanitize($_POST['gender']),
            'document_number' => cdp_sanitize($document_number),
            'document_type' => cdp_sanitize($document_type),
            'active' => cdp_sanitize($_POST['active']),
            'id' => cdp_sanitize($_POST['id'])
        );

        $userDataEdit = cdp_getUserEdit4bozo($_POST['id']);

        if ($_POST['password'] != "") {

            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {

            $data['password'] = $userDataEdit['data']->password;
        }


        if (isset($_POST['userlevel'])) {

            $data['userlevel'] = $_POST['userlevel'];
        } else {

            $data['userlevel'] = $userDataEdit['data']->userlevel;
        }




        if (!empty($_FILES['avatar']['name'])) {

            move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
            $imagen = basename($_FILES["avatar"]["name"]);
            $data['avatar'] = 'uploads/' . $image_name;
        } else {

            $data['avatar'] = $userDataEdit['data']->avatar;
        }


        if (!empty($_POST['branch_office'])) {

            $data['branch_office'] = $_POST['branch_office'];
        } else {

            $data['branch_office'] = $userDataEdit['data']->name_off;
        }


        $insert = cdp_updateUserrx0xr($data);

        if (isset($_FILES['filesMultiple'])) {

            if (count($_FILES['filesMultiple']['name']) > 0 && $_FILES['filesMultiple']['tmp_name'][0] != '') {

                $target_dir = "../../driver_files/";

                $deleted_file_ids = array();

                if (isset($_POST['deleted_file_ids']) && !empty($_POST['deleted_file_ids'])) {

                    $deleted_file_ids = explode(",", $_POST['deleted_file_ids']);
                }


                foreach ($_FILES["filesMultiple"]['tmp_name'] as $key => $tmp_name) {


                    if (!in_array($key, $deleted_file_ids)) {

                        $image_name = time() . "_" . basename($_FILES["filesMultiple"]["name"][$key]);
                        $target_file = $target_dir . $image_name;
                        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                        $imageFileZise = $_FILES["filesMultiple"]["size"][$key];

                        $target_file_url = 'driver_files/' . $image_name;


                        if ($imageFileZise > 0) {

                            move_uploaded_file($_FILES["filesMultiple"]["tmp_name"][$key], $target_file);
                            $imagen = basename($_FILES["filesMultiple"]["name"][$key]);
                            $file = "image_path='img/usuarios/$image_name' ";
                        }

                        cdp_insertDriverFiles($_POST['id'], $target_file_url, $image_name, date("Y-m-d H:i:s"), $imageFileType);
                    }
                }
            }
        }




        if ($insert) {
            $messages[] = $lang['message_ajax_success_updated'];
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
}
?>