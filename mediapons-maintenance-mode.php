<?php
/*
    Plugin Name: Media Pons Maintenance Mode
    Description: This plugin
*/
class MpMaintenanceMode {
    function __construct()
    {
        echo 'Maintenance Mode plugin loaded';
    }
}

$mpMaintenanceMode = new MpMaintenanceMode();