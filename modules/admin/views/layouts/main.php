<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->render('inc/head'); ?>
</head>

<body class="fix-header fix-sidebar">
<!-- Preloader - style you can find in spinners.css -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- Main wrapper  -->
<div id="main-wrapper">
    <!-- header header  -->

    <!-- End header header -->
    <?= $this->render('inc/header'); ?>
    <!-- Left Sidebar  -->
    <?= $this->render('inc/leftmenu' /*, compact('models')*/); ?>
    <!-- End Left Sidebar  -->

    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Bread crumb -->
        <?= $this->render('inc/breadcrumps'); ?>
        <!-- End Bread crumb -->
        <!-- Container fluid  -->
        <div class="container-fluid" style="min-height: 76vh;">
            <!-- Start Page Content -->

            <?= $content ?>

            <!-- End PAge Content -->
        </div>
        <!-- End Container fluid  -->
        <!-- footer -->
        <footer class="footer"> © <?= date('Y'); ?> All rights reserved. </footer>
        <!-- End footer -->
    </div>
    <!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->
<?= $this->render('inc/js'); ?>
</body>

</html>