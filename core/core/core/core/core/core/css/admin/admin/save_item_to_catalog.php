<?php
require '../core/init.php';
if ($_POST) {
    $book = new Book($_POST['title'], $_POST['author'], $_POST['price'], $_POST['pubyear']);
    Eshop::addItemToCatalog($book);
    header('Location: index.php?msg=Книга+добавлена');
    exit;
}
