<?php

class Calculate {

    public function add($num1, $num2) {
        $result = $num1 + $num2;
        return $result;
    }

    public function save($num1, $num2) {
        $db = Db::getInstance();
        $sql = $db->prepare("INSERT INTO `calculator` (number1, number2) VALUES (?, ?)");
        $sql->execute([$num1, $num2]);
    }

    public function load() {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM `calculator` WHERE id=(SELECT MAX(id) FROM `calculator`)");
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

}