<?php require 'core/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Корзина</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <h1>Ваша корзина</h1>
    <a href="catalog.php">← Назад в каталог</a>
    <?php if (Eshop::isAdmin()): ?>
      | <a href="admin/">Админка</a> | <a href="logout.php">Выйти</a>
    <?php endif; ?>
  </header>

  <?php
  $items = Eshop::getItemsFromBasket();
  if (empty($items)): ?>
    <p>Корзина пуста.</p>
  <?php else: ?>
    <div class="cart">
      <?php $total = 0; ?>
      <?php foreach ($items as $item): ?>
        <div class="cart-item">
          <div>
            <strong><?= $item->title ?></strong> — <?= $item->author ?><br>
            <?= $item->quantity ?> × <?= $item->price ?> ₽
          </div>
          <a href="save_order.php?action=remove&item_id=<?= $item->id ?>">Удалить</a>
        </div>
        <?php $total += $item->price * $item->quantity; ?>
      <?php endforeach; ?>
      <div class="total">Итого: <?= $total ?> ₽</div>
      <a href="javascript:void(0)" class="btn" onclick="checkout()">Оформить заказ</a>
    </div>

    <script>
      function checkout() {
        if (confirm('Оформить заказ?')) {
          window.location = 'save_order.php?action=checkout';
        }
      }
    </script>
  <?php endif; ?>
</body>
</html>
