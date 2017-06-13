<?php

class CalculatorController {

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function input() {

        if (isset($_POST['load'])) {
            $results = $this->model->load();
//            var_dump($results);
        }

        require_once('views/calculator/input.php');

        if (isset($_POST['save'])) {
            $this->model->save($_POST["first-number"],$_POST["second-number"]);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            $to_return = $this->model->add($_POST["first-number"],$_POST["second-number"]);
            echo $to_return;
        }
    }

}