<?php

    function lang ($phrase){
        static $lang = array (

            // navabr Page

            'HOME'      => 'Home',
            'Sections'  => 'Categories',
            'EDIT_PRO'  => 'Edit Profile',
            'SEET'      => 'Settings',
            'LOG_OUT'   => 'Log Out',
            'ITEMS'     => 'items',
            'MEMBERS'   => 'Members',
            'COMMENTS'  => 'Comments',
            'STATISTICS' => 'Statistics',
            'LOGS'      => 'Logs',

        );
        return $lang[$phrase];
    }