<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        file_put_contents(
            "alarm_config.txt",
            "alarm_ring=".$_POST['alarm_ring']
        );
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo file_to_list("alarm_config.txt")["alarm_ring"] == "true";
    }

    function file_to_list($file)
    {
        $file_arrays = file($file);
        $configs = array();

        foreach ($file_arrays as $line) {
            list($config, $value) = explode('=', $line);
            $configs[$config] = $value;
        }

        return $configs;
    }
?>