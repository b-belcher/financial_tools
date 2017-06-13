<?php
$title = "Advanced Amortization Calculator";
include("inc/nav.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $purchase_price = $_POST["purchase-price"];
    $down_payment = $_POST["down-payment"];
    $loan_amount = $_POST["loan-amount"];
    $interest_rate = $_POST["interest-rate"];
    $mortgage_length = $_POST["mortgage-length"];
    $pmi = $_POST["pmi"];
    $pmi_until = $_POST["pmi-until"];
    $extra_payments = $_POST["extra-payments"];
    $extra_payments_start = $_POST["extra-payments-start"];
    $taxes = $_POST["taxes"];
    $insurance = $_POST["insurance"];
    $appreciation = $_POST["appreciation-rate"];
    $first_payment_date = $_POST["first-payment-date"];
    $refi_date = $_POST["refi-date"];
    $refi_interest_rate = $_POST["refi-interest"];
    $refi_duration = $_POST["refi-duration"];
    $refi_onetime_costs = $_POST["refi-onetime-costs"];
}
?>

    <form method="post" action="refinance.php">
        <p>
            <label for="purchase-price">Purchase Price:</label>
            <input type="text" id="purchase-price" name="purchase-price" value="<?php if (isset($purchase_price)) { echo $purchase_price; } ?>">
        </p>
        </p>
        <p>
            <label for="down-payment">Down Payment:</label>
            <input type="text" id="down-payment" name="down-payment" value="<?php if (isset($down_payment)) { echo $down_payment; } ?>">
        </p>
        <p>
            <label for="loan-amount">Loan Amount:</label>
            <input type="text" id="loan-amount" name="loan-amount" value="<?php if (isset($loan_amount)) { echo $loan_amount; } ?>">
        </p>
        <p>
            <label for="interest-rate">Interest Rate:</label>
            <input type="text" id="interest-rate" name="interest-rate" value="<?php if (isset($interest_rate)) { echo $interest_rate; } ?>">
        </p>
        <p>
            <label for="mortgage-length">Mortgage Duration:</label>
            <input type="text" id="mortgage-length" name="mortgage-length" value="<?php if (isset($mortgage_length)) { echo $mortgage_length; } ?>">
        </p>
        <p>
            <label for="pmi">PMI:</label>
            <input type="text" id="pmi" name="pmi" value="<?php if (isset($pmi)) { echo $pmi; } ?>">
        </p>
        <p>
            <label for="pmi-until">PMI Until:</label>
            <input type="text" id="pmi-until" name="pmi-until" value="<?php if (isset($pmi_until)) { echo $pmi_until; } ?>">
        </p>
        <p>
            <label for="extra-payments">Extra Payments:</label>
            <input type="text" id="extra-payments" name="extra-payments" value="<?php if (isset($extra_payments)) { echo $extra_payments; } ?>">
        </p>
        <p>
            <label for="extra-payments-start">Extra Payment Start:</label>
            <input type="text" id="extra-payments-start" name="extra-payments-start" value="<?php if (isset($extra_payments_start)) { echo $extra_payments_start; } ?>">
        </p>
        <p>
            <label for="taxes">Taxes:</label>
            <input type="text" id="taxes" name="taxes" value="<?php if (isset($taxes)) { echo $taxes; } ?>">
        </p>
        <p>
            <label for="insurance">Insurance:</label>
            <input type="text" id="insurance" name="insurance" value="<?php if (isset($insurance)) { echo $insurance; } ?>">
        </p>
        <p>
            <label for="appreciation-rate">Appreciation Rate:</label>
            <input type="text" id="appreciation-rate" name="appreciation-rate" value="<?php if (isset($appreciation)) { echo $appreciation; } ?>">
        </p>
        <p>
            <label for="first-payment-date">First Payment Date:</label>
            <input type="text" id="first-payment-date" name="first-payment-date" value="<?php if (isset($first_payment_date)) { echo $first_payment_date; } ?>">
        </p>
        <br>
        <p>
            <label for="refi-date">Refinance Date:</label>
            <input type="text" id="refi-date" name="refi-date" value="<?php if (isset($refi_date)) { echo $refi_date; } ?>">
        </p>
        <p>
            <label for="refi-interest">Refinance Interest Rate:</label>
            <input type="text" id="refi-interest" name="refi-interest" value="<?php if (isset($refi_interest_rate)) { echo $refi_interest_rate; } ?>">
        </p>
        <p>
            <label for="refi-duration">Refinance Duration:</label>
            <input type="text" id="refi-duration" name="refi-duration" value="<?php if (isset($refi_duration)) { echo $refi_duration; } ?>">
        </p>
        <p>
            <label for="refi-onetime-costs">One-time Refinance Cost:</label>
            <input type="text" id="refi-onetime-costs" name="refi-onetime-costs" value="<?php if (isset($refi_onetime_costs)) { echo $refi_onetime_costs; } ?>">
        </p>
        <input type="submit" value="Submit" />
    </form>
    <hr>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $monthly_interest_rate = $interest_rate / 12;
    $total_payments = $mortgage_length * 12;

    $monthly_mortgage_cost = ($loan_amount * (($monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments)) / (pow((1 + $monthly_interest_rate), $total_payments) - 1)));

    $principal_remaining = $loan_amount;
    $month = 1;

    $home_value = $purchase_price;

    $net_spent = 0;

    while ($month <= $total_payments && $principal_remaining >= 0) {

        $date = date("M-Y", strtotime($first_payment_date . " +" . ($month-1) . " month"));

        if (strtotime($date) < strtotime($extra_payments_start)) {
            $extra_monthly_payment = 0;
        } else {
            $extra_monthly_payment = $extra_payments;
        }

        $interest = $principal_remaining * $monthly_interest_rate;
        $amount_to_principal = $monthly_mortgage_cost - $interest;
        $principal_remaining -= ($amount_to_principal + $extra_monthly_payment);
        $home_value = $home_value * (pow(1+($appreciation/12),1));
        $equity = $home_value - $principal_remaining;

        if ($principal_remaining <= ($purchase_price * $pmi_until)) {
            $pmi = 0;
        }

        $total_monthly_cost = $interest + $amount_to_principal + $extra_monthly_payment + $pmi + $taxes + $insurance;
        $net_spent += $total_monthly_cost;
        $total_net_cost = ($home_value - $principal_remaining) - $net_spent - $down_payment;

        $payments[$month] = [
            'date' => $date,
            'interest_paid' => $interest,
            'to_principal' => $amount_to_principal,
            'principal_remaining' => $principal_remaining,
            'taxes' => $taxes,
            'insurance' => $insurance,
            'extra-payments' => $extra_monthly_payment,
            'pmi' => $pmi,
            'home-value' => $home_value,
            'total-monthly-cost' => $total_monthly_cost,
            'net-spent' => $net_spent,
            'total-cost' => $total_net_cost,
            'equity' => $equity
        ];

        $month++;
    }

    $refinance_date = date("M-Y", strtotime($refi_date));
    $monthly_refi_interest_rate = $refi_interest_rate / 12;
    $total_refi_payments = $refi_duration * 12;

    foreach($payments as $month_count => $payment) {
        if($payment['date'] == $refinance_date) {
            $refi_starting_loan_amount = $payment['principal_remaining'];
            $refi_month_number = $month_count;
            $refi_starting_home_value = $payment['home-value'];
            $refi_net_spent_at_start = $payment ['net-spent'];
        };
    }

    $monthly_refi_cost = ($refi_starting_loan_amount * (($monthly_refi_interest_rate * pow((1 + $monthly_refi_interest_rate), $total_refi_payments)) / (pow((1 + $monthly_refi_interest_rate), $total_refi_payments) - 1)));

    $refi_principal_remaining = $refi_starting_loan_amount;
    $refi_month = $refi_month_number;
    $refi_home_value = $refi_starting_home_value;
    $refi_net_spent = $refi_net_spent_at_start;

    while ($refi_month <= ($total_refi_payments + $refi_month_number) && $refi_principal_remaining >= 0) {

        $refi_payment_date = date("M-Y", strtotime($refi_date . " +" . ($refi_month-$refi_month_number) . " month"));

        $refi_interest = $refi_principal_remaining * $monthly_refi_interest_rate;
        $refi_amount_to_principal = $monthly_refi_cost - $refi_interest;
        $refi_principal_remaining -= $refi_amount_to_principal;

        $refi_home_value = $refi_home_value * (pow(1+($appreciation/12),1));
        $refi_equity = $refi_home_value - $refi_principal_remaining;

        $refi_total_monthly_cost = $refi_interest + $refi_amount_to_principal + $taxes + $insurance;
        $refi_net_spent += $refi_total_monthly_cost;
        $refi_total_net_cost = ($refi_home_value - $refi_principal_remaining) - $refi_net_spent - $refi_onetime_costs;

        $refi_payments[$refi_month] = [
            'date' => $refi_payment_date,
            'interest_paid' => $refi_interest,
            'to_principal' => $refi_amount_to_principal,
            'principal_remaining' => $refi_principal_remaining,
            'taxes' => $taxes,
            'insurance' => $insurance,
            'extra-payments' => 0,
            'pmi' => 0,
            'home-value' => $refi_home_value,
            'total-monthly-cost' => $refi_total_monthly_cost,
            'net-spent' => $refi_net_spent,
            'total-cost' => $refi_total_net_cost,
            'equity' => $refi_equity
        ];

        $refi_month++;
    }

    echo "<table>";
    echo "<tr>";
    echo "<th>Month</th>";
    echo "<th>Original Total Cost</th>";
    echo "<th>Refi Total Cost</th>";
    echo "<th>Difference</th>";
    echo "</tr>";

    for ($i = 1; $i <= $total_payments; $i++) {
        ?>
        <tr>
            <td><?php echo $payments[$i]['date']; ?></td>
            <td><?php echo "$" . number_format($payments[$i]['total-cost'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payments[$i]['total-cost'], 2); ?></td>
            <td><?php echo "$" . number_format(($refi_payments[$i]['total-cost'] - $payments[$i]['total-cost']), 2); ?></td>
        </tr>
        <?php
    }

    echo "</table>";
    echo "<hr>";

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
        ?>
            <tr>
                <td><?php echo $payment['date']; ?></td>
                <td><?php echo "$" . number_format($payment['to_principal'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['interest_paid'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['pmi'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['extra-payments'], 2); ?></td>
                <td><?php echo "$" . number_format(($payment['insurance'] + $payment['taxes']), 2); ?></td>
                <td><?php echo "$" . number_format($payment['total-monthly-cost'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['principal_remaining'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['net-spent'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['home-value'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['total-cost'], 2); ?></td>
            </tr>
        <?php
    }
    echo "</table>";

    echo "<hr>";

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

    foreach ($refi_payments as $month_count => $refi_payment) {
        ?>
        <tr>
            <td><?php echo $refi_payment['date']; ?></td>
            <td><?php echo "$" . number_format($refi_payment['to_principal'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['interest_paid'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['pmi'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['extra-payments'], 2); ?></td>
            <td><?php echo "$" . number_format(($refi_payment['insurance'] + $refi_payment['taxes']), 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['total-monthly-cost'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['principal_remaining'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['net-spent'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['home-value'], 2); ?></td>
            <td><?php echo "$" . number_format($refi_payment['total-cost'], 2); ?></td>
        </tr>
        <?php
    }
    echo "</table>";
}

include("inc/footer.php");
?>