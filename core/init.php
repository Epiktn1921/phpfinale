<?php

session_start();

require_once __DIR__ . '/Eshop.php';
require_once __DIR__ . '/Basket.php';

Eshop::init(require '../config.php');
Basket::init();

if (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false && !Eshop::isAdmin()) {
    header('Location: /enter.php');
    exit;
}
