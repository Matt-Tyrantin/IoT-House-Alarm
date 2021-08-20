<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/php/controllers/alarm_controller.php');

    $controller = new \App\Controllers\AlarmController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] == 'PUT') {
            $controller->Put($_POST);
        } else {
            $controller->Post($_POST);
        }
    }
?>