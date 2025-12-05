<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Вход</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header><h1>Админка — Вход</h1></header>
  <?php if (isset($_GET['error'])): ?>
    <p style="color:red">Неверный логин или пароль</p>
  <?php endif; ?>
  <form method="POST" action="login.php">
    <div class="form-group"><label>Логин</label><input name="login" required></div>
    <div class="form-group"><label>Пароль</label><input name="password" type="password" required></div>
    <button class="btn" type="submit">Войти</button>
  </form>
</body>
</html>
