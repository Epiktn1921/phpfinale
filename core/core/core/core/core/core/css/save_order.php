<?php
require 'core/init.php';

$action = $_GET['action'] ?? '';

if ($action === 'add') {
    Eshop::addItemToBasket($_GET['item_id']);
    echo json_encode(['ok' => true]);
    exit;
}

if ($action === 'remove') {
    Eshop::removeItemFromBasket($_GET['item_id']);
    header('Location: basket.php');
    exit;
}

if ($action === 'checkout') {
    if ($_POST) {
        $items = [];
        foreach (Basket::get() as $k => $v) {
            if ($k !== 'order-id') $items[$k] = $v;
        }
        $order = new Order($_POST['customer'], $_POST['email'], $_POST['phone'], $_POST['address'], $items);
        Eshop::saveOrder($order);
        header('Location: catalog.php?msg=Заказ+оформлен');
        exit;
    }

  
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="UTF-8">
      <title>Оформление заказа</title>
      <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
      <header><h1>Оформление заказа</h1></header>
      <form method="POST">
        <div class="form-group">
          <label>Имя</label>
          <input name="customer" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input name="email" type="email" required>
        </div>
        <div class="form-group">
          <label>Телефон</label>
          <input name="phone" required>
        </div>
        <div class="form-group">
          <label>Адрес доставки</label>
          <textarea name="address" required></textarea>
        </div>
        <button class="btn" type="submit">Подтвердить заказ</button>
      </form>
    </body>
    </html>
    <?php
    exit;
}

header('Location: catalog.php');
