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
}
?>

    <form method="post" action="amortization_adv.php">
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

    $date = new DateTime($first_payment_date);
    $month_interval = new DateInterval('P1M');

    while ($month <= $total_payments && $principal_remaining >= 0) {

//        $date = date("M-Y", strtotime($first_payment_date . " +" . ($month-1) . " month"));
//
        if ($date < new DateTime($extra_payments_start)) {
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
        $total_net_cost = ($home_value - $principal_remaining) - $net_spent;

        $payments[$month] = [
            'date' => $date->format('m-d-Y'),
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

        $date->add($month_interval);
        $month++;
    }
    ?>

    <table>
    <tr>
        <th>Month</th>
        <th>Principal Paid</th>
        <th>Interest Paid</th>
        <th>PMI</th>
        <th>Extra Payments</th>
        <th>Insurance & Taxes</th>
        <th>Total Monthly Cost</th>
        <th>Principal Remaining</th>
        <th>Net Spent</th>
        <th>Estimated Home Value</th>
        <th>Total Cost</th>
    </tr>

    <?php foreach ($payments as $month_count => $payment) { ?>
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
}

include("inc/footer.php");
?>