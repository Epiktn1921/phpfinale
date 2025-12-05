<?php require '../core/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Заказы</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="logout"><a href="../logout.php">Выйти</a></div>
  <h2>Все заказы</h2>
  <?php foreach (Eshop::getOrders() as $order): ?>
    <div class="book-card">
      <p><strong>Заказ №<?= $order->order_id ?></strong> от <?= $order->created ?></p>
      <p>Клиент: <?= $order->customer ?>, <?= $order->email ?></p>
      <p>Телефон: <?= $order->phone ?></p>
      <p>Адрес: <?= $order->address ?></p>
      <h3>Товары:</h3>
      <table>
        <thead><tr><th>Книга</th><th>Автор</th><th>Цена</th><th>Кол-во</th></tr></thead>
        <tbody>
          <?php foreach ($order->items as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['title']) ?></td>
              <td><?= htmlspecialchars($item['author']) ?></td>
              <td><?= $item['price'] ?> ₽</td>
              <td><?= $item['quantity'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</body>
</html>
