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


$db = new Conexion;
$db->cdp_query("SELECT * FROM cdb_settings");

$db->cdp_execute();
$settings = $db->cdp_registro();
$numrows = $db->cdp_rowCount();

if ($numrows > 0) {

	$config_lang = $settings->language;
	// if (empty($_SESSION["languages"])) {

	// 	$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	// 	$_SESSION["languages"] = $language;
	// 	if ($language != "en") {
	// 		$_SESSION["languages"] = "en";
	// 	}
	// }

	// if (isset($_SESSION["languages"])) {
	// 	$language = $_SESSION["languages"];
	// }

	// var_dump($config_lang);
	if ($config_lang == "ar") {
		$direction_layout = "rtl";
		// $direction_layout = "ltr";
	} else {
		$direction_layout = "ltr";
	}


	switch ($config_lang) {
		case "fr":
			//echo "PAGE FR";
			include("languages/$config_lang.php"); //include check session FR
			break;
		case "br":
			//echo "PAGE BRAZIL";
			include("languages/$config_lang.php");
			break;
		case "ar":

			include("languages/$config_lang.php");
			break;
		case "es":
			//echo "PAGE ES";
			include("languages/$config_lang.php");
			break;
		case "en":
			//echo "PAGE EN";
			include("languages/$config_lang.php");
			break;
		default:
			//echo "PAGE EN - Setting Default";
			include("languages/$config_lang.php"); //include EN in all other cases of different lang detection
			break;
	}
}
