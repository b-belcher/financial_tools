<?php

class simpleMortgageController {

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function amortize() {
        require_once('views/simple_mortgage/amortize.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<p>We should return results here:</p>";
            $user_data = [
                'purchase-price' => $_POST["purchase-price"],
                'down-payment' => $_POST["down-payment"],
                'interest-rate' => $_POST["interest-rate"],
                'mortgage-length' => $_POST["mortgage-length"]
            ];

            $payments = $this->model->amortize($user_data);

            foreach ($payments as $month_count => $payment) {
                echo "<table>";
                echo "<tr>";
                echo "<th>Month</th>";
                echo "<th>Total</th>";
                echo "<th>Interest Paid</th>";
                echo "<th>Principal Paid</th>";
                echo "<th>Principal Remaining</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>" . $month_count . "</td>";
                echo "<td>$" . number_format($payment['total_cost'], 2) . "</td>";
                echo "<td>$" . number_format($payment['interest_paid'], 2) . "</td>";
                echo "<td>$" . number_format($payment['to_principal'], 2) . "</td>";
                echo "<td>$" . number_format($payment['principal_remaining'], 2) . "</td>";
                echo "</tr>";
                echo "</table>";
            }
        }
    }
}