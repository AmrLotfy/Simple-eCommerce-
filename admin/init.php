<?php
    include "connect.php";
    // Routes
    $tpl = 'includes/templates/'; // templates directory
    $css = 'design/css/'; // css directory
    $js  = 'design/js/'; // js directory
    $lang = 'includes/languages/'; // language directory
    $func = 'includes/functions/'; // language directory


    // include the important Files

    include $func . 'functions.php';
    include $lang .'english.php';
    include $tpl .'header.php';

    //include navbar on all pages expect  the on with nonav variable
    if(!isset($noNav)) {include $tpl .'navbar.php';}


