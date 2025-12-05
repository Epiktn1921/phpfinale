<?php require '../core/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Админка</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="logout"><a href="../logout.php">Выйти</a></div>
  <h2>Добавить книгу</h2>
  <form method="POST" action="save_item_to_catalog.php">
    <div class="form-group"><label>Название</label><input name="title" required></div>
    <div class="form-group"><label>Автор</label><input name="author" required></div>
    <div class="form-group"><label>Цена</label><input name="price" type="number" step="0.01" required></div>
    <div class="form-group"><label>Год</label><input name="pubyear" type="number" required></div>
    <button class="btn" type="submit">Добавить</button>
  </form>

  <h2>Заказы</h2>
  <a href="orders.php" class="btn">Просмотреть все</a>
</body>
</html>
