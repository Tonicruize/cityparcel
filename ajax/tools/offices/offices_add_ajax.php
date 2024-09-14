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



require_once("../../../loader.php");
require_once("../../../helpers/querys.php");

$errors = array();

if (empty($_POST['name_off']))
  $errors['name_off'] = $lang['validate_field_ajax84'];

if (cdp_officeExistsjmbj1($_POST['name_off']))
  $errors['name_off'] = $lang['validate_field_ajax85'];

if (empty($_POST['code_off']))
  $errors['code_off'] = $lang['validate_field_ajax86'];

if (cdp_codeofficeExists($_POST['code_off']))
  $errors['code_off'] = $lang['validate_field_ajax87'];

if (empty($_POST['address']))
  $errors['address'] = $lang['validate_field_ajax88'];

if (empty($_POST['city']))
  $errors['city'] = $lang['validate_field_ajax89'];

if (empty($_POST['phone_off']))
  $errors['phone_off'] = $lang['validate_field_ajax90'];


if (empty($errors)) {

  $data = array(
    'name_off' => cdp_sanitize($_POST['name_off']),
    'code_off' => cdp_sanitize($_POST['code_off']),
    'address' => cdp_sanitize($_POST['address']),
    'city' => cdp_sanitize($_POST['city']),
    'phone_off' => cdp_sanitize($_POST['phone_off'])
  );

  $insert = cdp_insertOffices($data);

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
  </div>

<?php
}
?>