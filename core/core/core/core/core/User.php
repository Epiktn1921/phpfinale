<?php
class User {
    public $login, $password, $email;
    public function __construct($login, $password, $email = '') {
        $this->login = trim($login);
        $this->password = $password;
        $this->email = $email;
    }
}
