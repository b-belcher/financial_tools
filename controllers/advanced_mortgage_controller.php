<?php

class advancedMortgageController
{

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function amortize()
    {
        require_once('views/advanced_mortgage/amortize.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<p>We should return results here:</p>";
            $user_data = [
                'purchase-price' => $_POST['purchase-price'],
                'down-payment' => $_POST["down-payment"],
                'loan-amount' => $_POST['loan-amount'],
                'interest-rate' => $_POST["interest-rate"],
                'mortgage-length' => $_POST["mortgage-length"],
                'pmi' => $_POST['pmi'],
                'pmi-until' => $_POST['pmi-until'],
                'extra-payments' => $_POST["extra-payments"],
                'extra-payments-start' => $_POST["extra-payments-start"],
                'taxes' => $_POST["taxes"],
                'insurance' => $_POST["insurance"],
                'appreciation' => $_POST["appreciation-rate"],
                'first-payment-date' => $_POST["first-payment-date"]
            ];

            $payments = $this->model->amortize($user_data);

            echo "<table>";
            echo "<tr>";
            echo "<th>Month</th>";
            echo "<th>Principal Paid</th>";
            echo "<th>Interest Paid</th>";
            echo "<th>PMI</th>";
            echo "<th>Extra Payments</th>";
            echo "<th>Insurance & Taxes</th>";
            echo "<th>Total Monthly Cost</th>";
            echo "<th>Principal Remaining</th>";
            echo "<th>Net Spent</th>";
            echo "<th>Estimated Home Value</th>";
            echo "<th>Total Cost</th>";
            echo "</tr>";

            foreach ($payments as $month_count => $payment) {
                echo "<tr>";
                echo "<td>" . $payment['date'] . "</td>";
                echo "<td>$" . number_format($payment['to_principal'], 2) . "</td>";
                echo "<td>$" . number_format($payment['interest_paid'], 2) . "</td>";
                echo "<td>$" . number_format($payment['pmi'], 2) . "</td>";
                echo "<td>$" . number_format($payment['extra-payments'], 2) . "</td>";
                echo "<td>$" . number_format(($payment['insurance'] + $payment['taxes']), 2) . "</td>";
                echo "<td>$" . number_format($payment['total-monthly-cost'], 2) . "</td>";
                echo "<td>$" . number_format($payment['principal_remaining'], 2) . "</td>";
                echo "<td>$" . number_format($payment['net-spent'], 2) . "</td>";
                echo "<td>$" . number_format($payment['home-value'], 2) . "</td>";
                echo "<td>$" . number_format($payment['total-cost'], 2) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    }
}