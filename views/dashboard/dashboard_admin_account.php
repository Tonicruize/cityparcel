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

$db = new Conexion;

$month = date('m');
$year = date('Y');

$sql = "SELECT * FROM cdb_add_order where status_courier!=21 and order_payment_method >1  and month(order_date)='$month' AND year(order_date)='$year' order by order_id desc";



$db->cdp_query($sql);
$data = $db->cdp_registros();




$count = 0;
$sumador_pendiente = 0;
$sumador_total = 0;
$sumador_pagado = 0;

foreach ($data as $row) {



    $db->cdp_query('SELECT  IFNULL(sum(total), 0)  as total  FROM cdb_charges_order WHERE order_id=:order_id');

    $db->bind(':order_id', $row->order_id);

    $db->cdp_execute();

    $sum_payment = $db->cdp_registro();

    $pendiente = $row->total_order - $sum_payment->total;

    $sumador_pendiente += $pendiente;
    $sumador_total += $row->total_order;
    $sumador_pagado += $sum_payment->total;


    $count++;
}

?>
<!DOCTYPE html>
<html dir="<?php echo $direction_layout; ?>" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Courier DEPRIXA-Integral Web System" />
    <meta name="author" content="Jaomweb">
    <title><?php echo $lang['left-menu-sidebar-28'] ?>| <?php echo $core->site_name ?></title>

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
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"><?php echo $lang['left-menu-sidebar-28'] ?></h4>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">


                <!-- ============================================================== -->
                <!-- Earnings -->
                <!-- ============================================================== -->
                <div class="row">

                    <div class="col-sm-12 col-md-12 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title"><?php echo $lang['dash-general-31'] ?></h4>
                                    </div>
                                </div>

                                <div>
                                    <canvas id="myChart" height="120px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col -->
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $lang['dash-general-244'] ?></h4>
                                <div class="row m-t-20 m-b-20">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <ul class="list-style-none">

                                            <li class="m-t-10">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span class="text-muted"><?php echo $lang['dash-general-3102'] ?></span>
                                                        <h4 class="m-b-0">
                                                            <span class="font-16">
                                                                <?php echo $core->currency; ?>
                                                                <?php

                                                                echo cdb_money_format($sumador_total);
                                                                ?>
                                                            </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="progress m-t-10">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo cdb_money_format($sumador_total) / 100; ?>%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </li>

                                            <li class="m-t-10">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span class="text-muted"><?php echo $lang['dash-general-32'] ?></span>
                                                        <h4 class="m-b-0">
                                                            <span class="font-16">

                                                                <?php echo $core->currency; ?>
                                                                <?php
                                                                echo cdb_money_format($sumador_pagado);
                                                                ?>
                                                            </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="progress m-t-10">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo cdb_money_format($sumador_pagado) / 100; ?>%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </li>
                                            <li class="m-t-10">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span class="text-muted"><?php echo $lang['dash-general-33'] ?></span>
                                                        <h4 class="m-b-0">
                                                            <span class="font-16">

                                                                <?php echo $core->currency; ?>
                                                                <?php
                                                                echo cdb_money_format($sumador_pendiente);

                                                                ?>
                                                            </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="progress m-t-10">
                                                    <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo cdb_money_format($sumador_pendiente) / 100; ?>%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Projects of the month -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Table -->
                <!-- ============================================================== -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title"><?php echo $lang['dash-general-30'] ?></h4>
                                        <h5 class="card-subtitle"><?php echo $lang['dash-general-244'] ?></h5>
                                    </div>

                                </div>
                                <div class="outer_div">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Table -->
                <!-- ============================================================== -->

            </div>
            <?php include 'views/inc/footer.php'; ?>

        </div>
    </div>
    <?php include('helpers/languages/translate_to_js.php'); ?>


    <script src="dataJs/dashboard_account.js"> </script>
    <script src="assets/template/assets/extra-libs/chart.js-2.8/Chart.min.js"></script>