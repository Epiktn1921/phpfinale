<?php
class Basket {
    private static $data = [];

    public static function init() {
        if (isset($_COOKIE['eshop'])) {
            self::$data = json_decode($_COOKIE['eshop'], true) ?: [];
        } else {
            self::create();
        }
    }

    public static function create() {
        self::$data = ['order-id' => bin2hex(random_bytes(8))];
        self::save();
    }

    public static function add($itemId, $qty = 1) {
        if (!isset(self::$data[$itemId])) self::$data[$itemId] = 0;
        self::$data[$itemId] += $qty;
        self::save();
    }

    public static function remove($itemId) {
        unset(self::$data[$itemId]);
        self::save();
    }

    public static function save() {
        setcookie('eshop', json_encode(self::$data), time() + 86400 * 30, '/');
    }

    public static function get() {
        return self::$data;
    }

    public static function clear() {
        setcookie('eshop', '', time() - 3600, '/');
        self::create();
    }
}
