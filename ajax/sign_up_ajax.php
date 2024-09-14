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

require_once("../loader.php");
require_once("../helpers/querys.php");
require_once("../helpers/phpmailer/class.phpmailer.php");
require_once("../helpers/phpmailer/class.smtp.php");

$user = new User;
$core = new Core;
$db = new Conexion;

$error = "";

if (empty($_POST['terms']))

    $error = 'Please accept the terms and conditions';

if (empty($_POST['country']))

    $error = 'Please enter the country';

if (empty($_POST['state']))

    $error = 'Please enter the state';

if (empty($_POST['city']))

    $error = 'Please enter the city';

if (empty($_POST['address']))

    $error = 'Please enter the address';

if (empty($_POST['postal']))

    $error = 'Please enter the postal';

if (empty($_POST['username']))

    $error = 'Enter a valid username';

if ($value = $user->cdp_usernameExists($_POST['username']))

    if ($value == 1)

        $error = 'Username is too short (less than 4 characters long).';

if ($value == 2)

    $error = 'Invalid characters found in the username.';

if ($value == 3)
    $error = 'Sorry, this username is already taken';

if (empty($_POST['email']))

    $error = 'Enter a valid email address';

if ($user->cdp_emailExists($_POST['email']))

    $error = 'The email address you entered is already in use.';

if (!$user->cdp_isValidEmail($_POST['email']))

    $error = 'The email address you entered is invalid.';

if (empty($_POST['phone']))

    $error = 'Please enter the phone';

if (empty($_POST['fname']))

    $error = 'Please enter the name';
if (empty($_POST['lname']))

    $error = 'Please enter the last name';

if (empty($_POST['pass']))

    $error = 'Enter a valid password.';

if (strlen($_POST['pass']) < 6)

    $error = 'Password is too short (less than 6 characters)';

if ($_POST['pass'] != $_POST['pass2'])

    $error = 'Your password does not match the confirmed password!.';


if (empty($error)) {

    $settings = cdp_getSettingsCourier();

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

    $datos = array(
        'username' => cdp_sanitize($_POST['username']),
        'email' => cdp_sanitize($_POST['email']),
        'lname' => cdp_sanitize($_POST['lname']),
        'fname' => cdp_sanitize($_POST['fname']),
        'locker' => cdp_sanitize($_POST['locker']),
        'phone' => cdp_sanitize($_POST['phone']),
        'userlevel' => 1,
        'active' => 1,

    );

    if ($_POST['pass'] != "") {

        $datos['password'] = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    }

    if (isset($_POST['terms'])) {
        $datos['terms'] = $_POST['terms'];
    } else {
        $datos['terms'] = "";
    }


    $datos['created'] = date("Y-m-d H:i:s");
    $db->cdp_query('INSERT INTO cdb_users
        (
            username,
            password,
            locker,
            userlevel,
            email,
            fname,
            lname,
            created,
            phone,
            active,
            terms
            
        )

        VALUES (
            :username,
            :password,
            :locker,
            :userlevel,
            :email,
            :fname,
            :lname,
            :created,
            :phone,
            :active,
            :terms
        )');


    $db->bind(':username', $datos['username']);
    $db->bind(':password', $datos['password']);
    $db->bind(':userlevel', $datos['userlevel']);
    $db->bind(':email', $datos['email']);
    $db->bind(':fname', $datos['fname']);
    $db->bind(':lname', $datos['lname']);
    $db->bind(':created', $datos['created']);
    $db->bind(':locker', $datos['locker']);
    $db->bind(':terms', $datos['terms']);
    $db->bind(':active', $datos['active']);
    $db->bind(':phone', $datos['phone']);

    $insert = $db->cdp_execute();

    $user_created_id = $db->dbh->lastInsertId();

    if ($user_created_id !== null) {
        $dataAddresses = array(
            'user_id' =>  $user_created_id,
            'address' =>  cdp_sanitize($_POST["address"]),
            'country' =>  cdp_sanitize($_POST["country"]),
            'city' =>  cdp_sanitize($_POST["city"]),
            'state' =>  cdp_sanitize($_POST["state"]),
            'postal' =>  cdp_sanitize($_POST["postal"])
        );

        cdp_insertAddressCustomer($dataAddresses);
    }

    if ($insert) {


        $_SESSION['userid'] = $user_created_id;
        $_SESSION['username'] =  $datos['username'];
        $_SESSION['email'] = $datos['email'];
        $_SESSION['name_off'] = $datos['name_off'];
        $_SESSION['name'] = $datos['fname'] . ' ' . $datos['lname'];
        $_SESSION['userlevel'] = $datos['userlevel'];
        $_SESSION['last'] = '';

        $db->cdp_query('UPDATE cdb_users SET  lastlogin=:lastlogin, lastip=:lastip where username=:user');

        $db->bind(':lastlogin', date("Y-m-d H:i:s"));
        $db->bind(':lastip', trim($_SERVER['REMOTE_ADDR']));
        $db->bind(':user', $datos['username']);

        $db->cdp_execute();

        $db->cdp_query("
                INSERT INTO cdb_notifications 
                (
                    user_id,
                    order_id,
                    notification_description,
                    shipping_type,
                    notification_date

                )
                VALUES
                    (
                    :user_id,                    
                    :order_id,                    
                    :notification_description,
                    :shipping_type,
                    :notification_date                    
                    )
              ");

        $db->bind(':order_id',  $user_created_id);
        $db->bind(':user_id',  $user_created_id);
        $db->bind(':notification_description', 'a new user has been registered');
        $db->bind(':shipping_type', '0');
        $db->bind(':notification_date',  date("Y-m-d H:i:s"));

        $db->cdp_execute();

        $notification_id = $db->dbh->lastInsertId();

        //NOTIFICATION TO ADMIN AND EMPLOYEES

        $users_employees = cdp_getUsersAdminEmployees();

        foreach ($users_employees as $key) {
            cdp_insertNotificationsUsers($notification_id, $key->id);
        }



        $email_template = cdp_getEmailTemplatesdg1i4(7);

        $body = str_replace(
            array(
                '[NAME]',
                '[USERNAME]',
                '[PASSWORD]',
                '[LOCKER]',
                '[VIRTUAL_LOCKER]',
                '[CCOUNTRY]',
                '[CCITY]',
                '[CPOSTAL]',
                '[CPHONE]',
                '[EMAIL]',
                '[URL]',
                '[URL_LINK]',
                '[SITE_NAME]'
            ),
            array(
                $_POST['fname'] . ' ' . $_POST['lname'],
                $_POST['username'],
                $_POST['pass'],
                $_POST['locker'],
                $core->locker_address,
                $core->c_country,
                $core->c_city,
                $core->c_postal,
                $core->c_phone,
                $_POST['email'],
                $core->site_url,
                $core->logo,
                $core->site_name
            ),
            $email_template->body
        );

        $newbody = cdp_cleanOut($body);


        //SENDMAIL PHP

        if ($core->mailer == 'PHP') {

            /*SIGUE RECOLECTANDO DATOS PARA FUNCION MAIL*/
            $message = $newbody;
            $websiteName = $core->site_name;
            $emailAddress = $core->site_email;
            $header = "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $header .= "From: " . $websiteName . " <" . $emailAddress . ">\r\n";
            $header .= "Bcc: " . $core->email_address . "\r\n";
            $subject = $email_template->subject;
            mail($_POST['email'], $subject, $message, $header);


            /*FINALIZA RECOLECTANDO DATOS PARA FUNCION MAIL*/
        } elseif ($core->mailer == 'SMTP') {

            //PHPMAILER PHP
            $destinatario = "" . $_POST['email'] . "";

            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

            //Server settings

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $smtphoste;                       // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $smtpuser;                   // SMTP username
            $mail->Password = $smtppass;               // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($site_email, $names_info);
            $mail->addAddress($destinatario);     // Add a recipient
            $mail->addCC($site_email, 'New customer registration');

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $email_template->subject;
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
                //echo "El correo fue enviado correctamente.";
            } catch (Exception $e) {
                //echo "Ocurrió un error inesperado.";
            }
        }


        $messages[] = "You have successfully registered. Please check your email for further information";
    } else {
        $error['critical_error'] = "An error occurred during the registration process. Contact the administrator ...";
    }
}





if (!empty($error)) {
    echo json_encode([
        'success' => false,
        'errors' => $error
    ]);
} else {
    echo json_encode([
        'success' => true,
        'messages' => $messages,
    ]);
}
