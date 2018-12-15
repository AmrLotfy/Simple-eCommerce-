<?php
    ob_start();
    session_start();
    $pagetitle = 'Categories';
    if(isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') {
        } elseif ($do == 'Add') {

        } elseif ($do == 'Insert') {

        } elseif ($do == 'Edit') {

        } elseif ($do == 'update') {

        } elseif ($do == 'Delete') {

        } elseif ($do == 'Activate') {

        }


        include $tpl . 'footer.php';

    }else {
        header('Location: index.php');
        exit();
    }
    ob_end_flush();
?>












