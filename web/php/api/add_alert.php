<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/php/controllers/alert_controller.php');

    $controller = new \App\Controllers\AlertController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] == 'DELETE') {
            $controller->Delete();
        } else {
            $controller->Post($_POST);
        }
        
    }
?>