<?php require 'core/init.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Книжный магазин</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <h1>📚 Книжный уголок</h1>
    <a href="basket.php">Корзина</a>
    <?php if (Eshop::isAdmin()): ?>
      | <a href="admin/">Админка</a> | <a href="logout.php">Выйти</a>
    <?php endif; ?>
  </header>

  <div class="books">
    <?php foreach (Eshop::getItemsFromCatalog() as $book): ?>
      <div class="book-card">
        <div class="book-title"><?= $book->title ?></div>
        <div class="book-author"><?= $book->author ?> (<?= $book->pubyear ?>)</div>
        <div class="book-price"><?= $book->price ?> ₽</div>
        <a href="javascript:void(0)" class="btn" onclick="addToCart(<?= $book->id ?>)">В корзину</a>
      </div>
    <?php endforeach; ?>
  </div>

  <script>
    function addToCart(id) {
      fetch('save_order.php?action=add&item_id=' + id)
        .then(() => location.reload());
    }
  </script>
</body>
</html>
