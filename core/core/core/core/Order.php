<?php
class Order {
    public $customer, $email, $phone, $address, $items;
    public function __construct($customer, $email, $phone, $address, $items = []) {
        $this->customer = htmlspecialchars(trim($customer));
        $this->email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $this->phone = preg_replace('/[^0-9+]/', '', $phone);
        $this->address = htmlspecialchars(trim($address));
        $this->items = $items;
    }
}
