<?php
include('phpqrcode/qrlib.php');
$text=urlencode($_GET["link"]);
QRcode::png($text);