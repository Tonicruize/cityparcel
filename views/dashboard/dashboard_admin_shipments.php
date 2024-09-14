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


$userData = $user->cdp_getUserData();
$statusrow = $core->cdp_getStatus();

$db = new Conexion;

?>
<!DOCTYPE html>
<!-- <html dir="rtl" lang="en"> -->
<html dir="<?php echo $direction_layout; ?>" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Courier DEPRIXA-Integral Web System" />
    <meta name="author" content="Jaomweb">
    <title> <?php echo $lang['left-menu-sidebar-14'] ?> | <?php echo $core->site_name ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/<?php echo $core->favicon ?>">

    <?php include 'views/inc/head_scripts.php'; ?>
</head>

<body>

    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->

        <?php include 'views/inc/preloader.php'; ?>

        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->

        <?php include 'views/inc/topbar.php'; ?>

        <!-- End Topbar header -->


        <!-- Left Sidebar - style you can find in sidebar.scss  -->

        <?php include 'views/inc/left_sidebar.php'; ?>


        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->

        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class=" align-self-center">
                        <h4 class="page-title"> <?php echo $lang['left-menu-sidebar-14'] ?></h4>
                    </div>
                </div>
            </div>

            <!-- Action part -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Sales chart -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-sm-12 col-md-12  col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">
                                            <?php echo $lang['dash-general-9'] ?>
                                        </h4>
                                        <h5 class="card-subtitle">
                                            <?php echo $lang['dash-general-24'] ?>
                                        </h5>

                                    </div>
                                </div>

                                <!-- column -->
                                <div class="col-lg-12 col-md-12">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><a href="courier_list.php">
                                                    <span class="text-orange display-5">
                                                        <i class="mdi mdi-package-variant-closed"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <span>
                                                    <?php echo $lang['dash-general-1'] ?>
                                                </span>
                                                <h3 class="font-medium m-b-0">
                                                    <?php
                                                    $month = date('m');
                                                    $year = date('Y');

                                                    $db->cdp_query("SELECT COUNT(*) as total FROM cdb_add_order WHERE month(order_date)='$month' AND year(order_date)='$year'and  is_pickup=0");

                                                    $db->cdp_execute();

                                                    $count = $db->cdp_registro();

                                                    echo $count->total;
                                                    ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10">
                                                <a href="courier_list.php">
                                                    <span class="text-cyan display-5">
                                                        <i class="mdi mdi-star-circlemdi mdi-clock-fast"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div>
                                                <span> <?php echo $lang['dash-general-2'] ?></span>
                                                <h3 class="font-medium m-b-0">
                                                    <?php
                                                    $month = date('m');
                                                    $year = date('Y');

                                                    $db->cdp_query("SELECT COUNT(*) as total FROM cdb_add_order WHERE month(order_date)='$month' AND year(order_date)='$year' and  is_pickup=1 and status_courier=14");

                                                    $db->cdp_execute();

                                                    $count = $db->cdp_registro();

                                                    echo $count->total;
                                                    ?>

                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><a href="courier_list.php"><span class="text-danger display-5"><i class="mdi mdi-gift"></i></span></a></div>
                                            <div><span><?php echo $lang['dash-general-3'] ?></span>
                                                <h3 class="font-medium m-b-0">
                                                    <?php

                                                    $month = date('m');
                                                    $year = date('Y');

                                                    $db->cdp_query("SELECT COUNT(*) as total FROM cdb_add_order WHERE month(order_date)='$month' AND year(order_date)='$year'and  is_consolidate=1");

                                                    $db->cdp_execute();

                                                    $count = $db->cdp_registro();

                                                    echo $count->total;
                                                    ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><a href="courier_list.php">
                                                    <span class="text-success display-5">
                                                        <i class="mdi mdi-package-down"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div><span><?php echo $lang['dash-general-25'] ?></span>
                                                <h3 class="font-medium m-b-0">
                                                    <?php
                                                    $month = date('m');
                                                    $year = date('Y');

                                                    $db->cdp_query("SELECT COUNT(*) as total FROM cdb_add_order WHERE month(order_date)='$month' AND year(order_date)='$year'and is_pickup=0  and  status_courier=8");

                                                    $db->cdp_execute();

                                                    $count = $db->cdp_registro();

                                                    echo $count->total;
                                                    ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="text-info display-5"><i class="mdi mdi-currency-usd"></i></span></div>
                                            <div><span><?php echo $lang['dash-general-27'] ?></span>
                                                <h4 class="font-medium m-b-0">
                                                    <?php echo $core->currency; ?>
                                                    <?php
                                                    $month = date('m');
                                                    $year = date('Y');

                                                    $db->cdp_query("SELECT IFNULL(SUM(total_order), 0) as total FROM cdb_add_order WHERE status_courier!=21 and is_pickup=0 and month(order_date)='$month' AND year(order_date)='$year'");

                                                    $db->cdp_execute();

                                                    $count = $db->cdp_registro();

                                                    echo cdb_money_format($count->total);
                                                    ?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title"><?php echo $lang['dash-general-26'] ?></h4>

                                    </div>
                                </div>
                                <div>
                                    <canvas id="bar-chart" height="119"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Sales chart -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Table -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="resultados_ajax"></div>

                                <div class="row mb-5">
                                    <div class="col-md-3 hide justify-content-center" id="div-actions-checked">
                                        <div class="btn-group mt-2">
                                            <span class=" mt-2 mr-4">
                                                <strong> <?php echo $lang['global-2'] ?></strong> <strong id="countChecked">: 0</strong>
                                            </span>
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php echo $lang['global-1'] ?>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalCheckboxStatus">
                                                    <i style="color:#20c997" class="ti-reload"></i>
                                                    &nbsp;<?php echo $lang['left21550'] ?>
                                                </a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalDriverCheckbox">
                                                    <i style="color:#ff0000" class="fas fa-car"></i>
                                                    &nbsp;<?php echo $lang['left208'] ?>
                                                </a>
                                                <a class="dropdown-item" onclick="cdp_printMultipleLabel();" target="_blank">
                                                    <i style="color:#343a40" class="ti-printer"></i>
                                                    &nbsp;<?php echo $lang['toollabel'] ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>


                                    <?php
                                    if ($user->cdp_is_Admin()) { ?>
                                        <div class="col-md-12 d-flex  justify-content-end">
                                            <div class=" form-group">
                                                <a href="courier_add.php">
                                                    <button type="button" class="btn btn-primary ">
                                                        <i class="ti-plus" aria-hidden="true"></i>
                                                        <?php echo $lang['global-buttons-1'] ?>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <?php
                                    if ($userData->userlevel == 1) { ?>
                                        <div class="col-md-12 d-flex  justify-content-end">
                                            <div class=" form-group">
                                                <a href="courier_add_client.php">
                                                    <button type="button" class="btn btn-primary ">
                                                        <i class="ti-plus" aria-hidden="true"></i>
                                                        <?php echo $lang['global-buttons-1'] ?>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="row mb-3 ">
                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" class="form-control input-sm float-right" placeholder="<?php echo $lang['left21551'] ?>" onkeyup="cdp_load(1);">
                                            <div class="input-group-append input-sm">
                                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div><!-- /.col -->

                                    <div class="col-sm-12 col-md-3 mb-2">
                                        <div class="input-group">
                                            <select onchange="cdp_load(1);" class="form-control custom-select" id="status_courier" name="status_courier">
                                                <option value="0">--<?php echo $lang['left210'] ?>--</option>
                                                <?php foreach ($statusrow as $row) : ?>
                                                    <option value="<?php echo $row->id; ?>"><?php echo $row->mod_style; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-2">
                                        <div class="input-group">
                                            <select onchange="cdp_load(1);" class="form-control custom-select" id="filterby" name="filterby">
                                                <option value="0"><?php echo $lang['leftorder128'] ?></option>
                                                <option value="1"><?php echo $lang['left1077'] ?></option>
                                                <option value="2"><?php echo $lang['leftorder129'] ?></option>
                                                <option value="3"><?php echo $lang['leftorder130'] ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="outer_divx"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Table -->
                <!-- ============================================================== -->
            </div>

            <?php include('views/modals/modal_update_status_checked.php'); ?>
            <?php include('views/modals/modal_send_email.php'); ?>

            <?php include('views/modals/modal_update_driver.php'); ?>
            <?php include('views/modals/modal_update_driver_checked.php'); ?>
            <?php include('views/modals/modal_verify_payment_packages.php'); ?>
            <?php include('views/modals/modal_cancel_pickup.php'); ?>
            <?php include('views/modals/modal_delete_pickup.php'); ?>
            <?php include('views/modals/modal_charges_list.php'); ?>
            <?php include('views/modals/modal_charges_add.php'); ?>
            <?php include('views/modals/modal_charges_edit.php'); ?>
            <?php include 'views/inc/footer.php'; ?>

        </div>
    </div>


    <?php include('helpers/languages/translate_to_js.php'); ?>

    <script src="dataJs/courier.js"></script>

    <script src="assets/template/assets/extra-libs/chart.js-2.8/Chart.min.js"></script>

    <!-- <script src="assets/template/assets/libs/chart.js/dist/Chart.min.js"></script> -->

    