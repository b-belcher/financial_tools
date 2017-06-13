<?php

class mortgageVsLoanController
{

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function compare()
    {
        require_once('views/mortgage_vs_loan/compare.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<p>Results:</p>";
            $input_data = [
                'purchase-price' => $_POST['purchase-price'],
                'down-payment' => $_POST["down-payment"],
                'loan-amount' => $_POST['loan-amount'],
                'interest-rate' => $_POST["interest-rate"],
                'mortgage-length' => $_POST["mortgage-length"],
                'pmi' => $_POST['pmi'],
                'pmi-until' => $_POST['pmi-until'],
                'taxes' => $_POST["taxes"],
                'insurance' => $_POST["insurance"],
                'appreciation' => $_POST["appreciation-rate"],
                'first-payment-date' => $_POST["first-payment-date"],
                'loan2-amount' => $_POST['loan2-amount'],
                'loan2-interest' => $_POST['loan2-interest'],
                'loan2-duration' => $_POST['loan2-duration'],
                'loan2-start' => $_POST['loan2-start'],
                'extra-payments' => $_POST["extra-payments"],
                'extra-payments-start' => $_POST["extra-payments-start"],
            ];

            $results = $this->model->compare($input_data);

            echo "<table>";
            echo "<tr>";
            echo "<th>Month</th>";
            echo "<th>Pay down Mortgage - Mortgage Monthly Cost</th>";
            echo "<th>Pay down Mortgage - Loan Monthly Cost</th>";
            echo "<th>Pay down Mortgage - Total Monthly Cost</th>";
            echo "<th>Pay down Mortgage - Net Total Spent</th>";
            echo "<th>Pay down Mortgage - True Cost</th>";
            echo "<th>Pay down Loan - Mortgage Monthly Cost</th>";
            echo "<th>Pay down Loan - Loan Monthly Cost</th>";
            echo "<th>Pay down Loan - Totatl Monthly Cost</th>";
            echo "<th>Pay down Loan - Net Total Spent</th>";
            echo "<th>Pay down Loan - True Cost</th>";
            echo "<th>Difference</th>";
            echo "</tr>";
            
            foreach ($results as $result) {
                echo "<tr>";
                echo "<td>" . $result['date'] . "</td>";
                echo "<td>$" . number_format($result['extra_to_mortgage_mortgage_monthly_cost'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_mortgage_loan_monthly_cost'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_mortgage_total_monthly_cost'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_mortgage_net_spend'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_mortgage_total'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_loan_mortgage_monthly_cost'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_loan_loan_monthly_cost'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_loan_total_monthly_cost'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_loan_net_spend'], 2) . "</td>";
                echo "<td>$" . number_format($result['extra_to_loan_total'], 2) . "</td>";
                echo "<td>$" . number_format(($result['extra_to_mortgage_total'] - $result['extra_to_loan_total']), 2) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            }
        }
}