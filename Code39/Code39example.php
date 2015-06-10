<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL|E_STRICT);
    include 'Code39.php';
    $barcode = new Code39();
    $code = isset($_GET['code']) ? $_GET['code'] :'WIKIPEDIA'; 
    header("Content-type: image/svg+xml");
    echo $barcode->draw($code);
?>
