<?php
class Eshop {
    private static $pdo;

    public static function init(array $config) {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        self::$pdo = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function userCheck(User $user): bool {
        $stmt = self::$pdo->prepare("CALL spGetAdmin(?)");
        $stmt->execute([$user->login]);
        $row = $stmt->fetch();
        return $row && password_verify($user->password, $row['password']);
    }

    public static function userAdd(User $user) {
        $hash = password_hash($user->password, PASSWORD_DEFAULT);
        self::$pdo->prepare("CALL spSaveAdmin(?, ?, ?)")
            ->execute([$user->login, $hash, $user->email]);
    }

    public static function isAdmin(): bool {
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }

    public static function logIn() {
        $_SESSION['admin'] = true;
    }

    public static function logOut() {
        unset($_SESSION['admin']);
        session_destroy();
    }


    public static function addItemToCatalog(Book $book) {
        self::$pdo->prepare("CALL spAddItemToCatalog(?, ?, ?, ?)")
            ->execute([$book->title, $book->author, $book->price, $book->pubyear]);
    }

    public static function getItemsFromCatalog() {
        $stmt = self::$pdo->prepare("CALL spGetCatalog()");
        $stmt->execute();
        $books = [];
        while ($row = $stmt->fetch()) {
            $books[] = new Book($row['title'], $row['author'], $row['price'], $row['pubyear'], $row['id']);
        }
        return $books;
    }


    public static function addItemToBasket($itemId, $qty = 1) {
        Basket::add($itemId, $qty);
    }

    public static function removeItemFromBasket($itemId) {
        Basket::remove($itemId);
    }

    public static function getItemsFromBasket() {
        $items = Basket::get();
        unset($items['order-id']);
        if (empty($items)) return [];

        $ids = implode(',', array_keys($items));
        $stmt = self::$pdo->prepare("CALL spGetItemsForBasket(?)");
        $stmt->execute([$ids]);

        $books = [];
        while ($row = $stmt->fetch()) {
            $book = new Book($row['title'], $row['author'], $row['price'], $row['pubyear'], $row['id']);
            $book->quantity = $items[$row['id']];
            $books[] = $book;
        }
        return $books;
    }


    public static function saveOrder(Order $order) {
        $basket = Basket::get();
        $orderId = $basket['order-id'] ?? bin2hex(random_bytes(8));

        self::$pdo->prepare("CALL spSaveOrder(?, ?, ?, ?, ?)")
            ->execute([$orderId, $order->customer, $order->email, $order->phone, $order->address]);

        foreach ($order->items as $itemId => $qty) {
            self::$pdo->prepare("CALL spSaveOrderedItems(?, ?, ?)")
                ->execute([$orderId, $itemId, $qty]);
        }

        Basket::clear();
    }

    public static function getOrders() {
        $stmt = self::$pdo->prepare("CALL spGetOrders()");
        $stmt->execute();
        $orders = [];
        while ($row = $stmt->fetch()) {
            $order = new Order($row['customer'], $row['email'], $row['phone'], $row['address']);
            $order->id = $row['id'];
            $order->order_id = $row['order_id'];
            $order->created = $row['created'];

            $stmt2 = self::$pdo->prepare("CALL spGetOrderedItems(?)");
            $stmt2->execute([$row['order_id']]);
            $items = [];
            while ($item = $stmt2->fetch()) {
                $items[] = [
                    'title' => $item['title'],
                    'author' => $item['author'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ];
            }
            $order->items = $items;
            $orders[] = $order;
        }
        return $orders;
    }
}
