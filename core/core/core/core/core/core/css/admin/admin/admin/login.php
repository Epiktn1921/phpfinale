<?php
require 'core/init.php';
if ($_POST) {
    $user = new User($_POST['login'], $_POST['password']);
    if (Eshop::userCheck($user)) {
        Eshop::logIn();
        header('Location: admin/');
    } else {
        header('Location: enter.php?error=1');
    }
    exit;
}
