<?php

class RefinanceController
{

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function calculate()
    {
        require_once('views/refinance/refinance.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<p>We should return results here:</p>";
            $mortgage_data = [
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
                'first-payment-date' => $_POST["first-payment-date"],
                'refi-date'=> $_POST['refi-date'],
                'refi-interest' => $_POST['refi-interest'],
                'refi-duration' => $_POST['refi-duration'],
                'refi-onetime-costs' => $_POST['refi-onetime-costs']
            ];

            $payments = $this->model->calculate($mortgage_data);

            echo "<table>";
            echo "<tr>";
            echo "<th>Month</th>";
            echo "<th>Original Monthly Costs</th>";
            echo "<th>Original Net Spent</th>";
            echo "<th>Original Home Equity</th>";
            echo "<th>Original True Cost</th>";
            echo "<th>Refinance Monthly Costs</th>";
            echo "<th>Refinance Net Spent</th>";
            echo "<th>Refinance Home Equity</th>";
            echo "<th>Refinance True Cost</th>";
            echo "<th>Difference</th>";
            echo "</tr>";

            foreach ($payments as $payment) {
                echo "<tr>";
                echo "<td>" . $payment['date'] . "</td>";
                echo "<td>$" . number_format($payment['original-mortgage-monthly-cost'], 2) . "</td>";
                echo "<td>$" . number_format($payment['original-mortgage-total-spent'], 2) . "</td>";
                echo "<td>$" . number_format($payment['original-mortgage-equity'], 2) . "</td>";
                echo "<td>$" . number_format($payment['original-mortgage-true-cost'], 2) . "</td>";

                if ($payment['refi-mortgage-monthly-cost'] != 0) {
                    echo "<td>$" . number_format($payment['refi-mortgage-monthly-cost'], 2) . "</td>";
                    echo "<td>$" . number_format($payment['refi-mortgage-total-spent'], 2) . "</td>";
                    echo "<td>$" . number_format($payment['refi-mortgage-equity'], 2) . "</td>";
                    echo "<td>$" . number_format($payment['refi-mortgage-true-cost'], 2) . "</td>";
                    echo "<td>$" . number_format($payment['difference'], 2) . "</td>";
                } else {
                    echo "<td>n/a</td>";
                    echo "<td>n/a</td>";
                    echo "<td>n/a</td>";
                    echo "<td>n/a</td>";
                    echo "<td>n/a</td>";
                }

                echo "</tr>";
            }

            echo "</table>";
        }
    }
}