<?php
@session_start();
require_once("SessionFingerprint.php");

header('Content-Type: application/json');

echo json_encode(SessionFingerprint::getInfo());
?>
