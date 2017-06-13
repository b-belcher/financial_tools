<?php

class CalculatorController {

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function input() {
        require_once('views/calculator/input.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $to_return = $this->model->add($_POST["first-number"],$_POST["second-number"]);
            echo $to_return;
        }
    }

}