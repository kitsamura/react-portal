<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

session_start();

error_reporting(E_ALL);
ini_set("display_errors", 0);

print var_dump($_SESSION);