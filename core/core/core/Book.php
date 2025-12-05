<?php
class Book {
    public $id, $title, $author, $price, $pubyear;
    public function __construct($title, $author, $price, $pubyear, $id = null) {
        $this->id = $id;
        $this->title = htmlspecialchars(trim($title));
        $this->author = htmlspecialchars(trim($author));
        $this->price = (float)$price;
        $this->pubyear = (int)$pubyear;
    }
}
