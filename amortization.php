<?php
$title = "Basic Amortization Calculator";
include("inc/nav.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $purchase_price = $_POST["purchase-price"];
    $down_payment = $_POST["down-payment"];
    $interest_rate = $_POST["interest-rate"];
    $mortgage_length = $_POST["mortgage-length"];
}
?>

    <form method="post" action="amortization.php">
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
            <label for="interest-rate">Interest Rate:</label>
            <input type="text" id="interest-rate" name="interest-rate" value="<?php if (isset($interest_rate)) { echo $interest_rate; } ?>">
        </p>
        <p>
            <label for="mortgage-length">Mortgage Duration:</label>
            <input type="text" id="mortgage-length" name="mortgage-length" value="<?php if (isset($mortgage_length)) { echo $mortgage_length; } ?>">
        </p>
        <input type="submit" value="Submit" />
    </form>
<hr>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $initial_principal = $purchase_price - $down_payment;
    $monthly_interest_rate = $interest_rate / 12;
    $total_payments = $mortgage_length * 12;

    $mort_calc_formula_top = $monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments);
    $mort_calc_formula_bottom = pow((1 + $monthly_interest_rate), $total_payments) - 1;

    $monthly_mortgage_cost = ($initial_principal * ($mort_calc_formula_top / $mort_calc_formula_bottom));

    $principal = $initial_principal;
    $month = 1;

    while ($month <= $total_payments) {
        $interest = $principal * $monthly_interest_rate;
        $amount_to_principal = $monthly_mortgage_cost - $interest;
        $principal = $principal - $amount_to_principal;

        $payments[$month] = [
            'interest_paid' => $interest,
            'to_principal' => $amount_to_principal,
            'principal_remaining' => $principal,
        ];

        $month++;
    }
    ?>

    <table>
        <tr>
            <th>Month</th>
            <th>Total</th>
            <th>Interest Paid</th>
            <th>Principal Paid</th>
            <th>Principal Remaining</th>
        </tr>

    <?php foreach ($payments as $month_count => $payment) { ?>
            <tr>
                <td><?php echo $month_count; ?></td>
                <td><?php echo "$" . number_format($monthly_mortgage_cost, 2); ?></td>
                <td><?php echo "$" . number_format($payment['interest_paid'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['to_principal'], 2); ?></td>
                <td><?php echo "$" . number_format($payment['principal_remaining'], 2); ?></td>
            </tr>
<?php
    }

    echo "</table>";
}

include("inc/footer.php");
?>