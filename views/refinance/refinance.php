<p>Refinance Calculator. The goal is to help you determine whether or not refinancing an existing mortgage.</p><br>
<p>Similar to the <a href="index.php?controller=advanced_mortgage&action=amortize">Advanced Mortgage Calculator</a>, this was built around the idea that your home equity should be considered alongside your net spend.</p>
<br>
<form method="post" action="">
    <p>
        <label for="purchase-price">Purchase Price:</label>
        <input type="text" id="purchase-price" name="purchase-price" value="<?php if (isset($_POST['purchase-price'])) {echo $_POST['purchase-price']; } ?>">
    </p>
    </p>
    <p>
        <label for="down-payment">Down Payment:</label>
        <input type="text" id="down-payment" name="down-payment" value="<?php if (isset($_POST['down-payment'])) { echo $_POST['down-payment']; } ?>">
    </p>
    <p>
        <label for="loan-amount">Loan Amount:</label>
        <input type="text" id="loan-amount" name="loan-amount" value="<?php if (isset($_POST['loan-amount'])) { echo $_POST['loan-amount']; } ?>">
    </p>
    <p>
        <label for="interest-rate">Interest Rate:</label>
        <input type="text" id="interest-rate" name="interest-rate" value="<?php if (isset($_POST['interest-rate'])) { echo $_POST['interest-rate']; } ?>">
    </p>
    <p>
        <label for="mortgage-length">Mortgage Duration:</label>
        <input type="text" id="mortgage-length" name="mortgage-length" value="<?php if (isset($_POST['mortgage-length'])) { echo $_POST['mortgage-length']; } ?>">
    </p>
    <p>
        <label for="pmi">PMI:</label>
        <input type="text" id="pmi" name="pmi" value="<?php if (isset($_POST['pmi'])) { echo $_POST['pmi']; } ?>">
    </p>
    <p>
        <label for="pmi-until">PMI Until:</label>
        <input type="text" id="pmi-until" name="pmi-until" value="<?php if (isset($_POST['pmi-until'])) { echo $_POST['pmi-until']; } ?>">
    </p>
    <p>
        <label for="extra-payments">Extra Payments:</label>
        <input type="text" id="extra-payments" name="extra-payments" value="<?php if (isset($_POST['extra-payments'])) { echo $_POST['extra-payments']; } ?>">
    </p>
    <p>
        <label for="extra-payments-start">Extra Payment Start:</label>
        <input type="text" id="extra-payments-start" name="extra-payments-start" value="<?php if (isset($_POST['extra-payments-start'])) { echo $_POST['extra-payments-start']; } ?>">
    </p>
    <p>
        <label for="taxes">Taxes:</label>
        <input type="text" id="taxes" name="taxes" value="<?php if (isset($_POST['taxes'])) { echo $_POST['taxes']; } ?>">
    </p>
    <p>
        <label for="insurance">Insurance:</label>
        <input type="text" id="insurance" name="insurance" value="<?php if (isset($_POST['insurance'])) { echo $_POST['insurance']; } ?>">
    </p>
    <p>
        <label for="appreciation-rate">Appreciation Rate:</label>
        <input type="text" id="appreciation-rate" name="appreciation-rate" value="<?php if (isset($_POST['appreciation-rate'])) { echo $_POST['appreciation-rate']; } ?>">
    </p>
    <p>
        <label for="first-payment-date">First Payment Date:</label>
        <input type="text" id="first-payment-date" name="first-payment-date" value="<?php if (isset($_POST['first-payment-date'])) { echo $_POST['first-payment-date']; } ?>">
    </p>
    <br>
    <p>
        <label for="refi-date">Refinance Date:</label>
        <input type="text" id="refi-date" name="refi-date" value="<?php if (isset($_POST['refi-date'])) { echo $_POST['refi-date']; } ?>">
    </p>
    <p>
        <label for="refi-interest">Refinance Interest Rate:</label>
        <input type="text" id="refi-interest" name="refi-interest" value="<?php if (isset($_POST['refi-interest'])) { echo $_POST['refi-interest']; } ?>">
    </p>
    <p>
        <label for="refi-duration">Refinance Duration:</label>
        <input type="text" id="refi-duration" name="refi-duration" value="<?php if (isset($_POST['refi-duration'])) { echo $_POST['refi-duration']; } ?>">
    </p>
    <p>
        <label for="refi-onetime-costs">One-time Refinance Cost:</label>
        <input type="text" id="refi-onetime-costs" name="refi-onetime-costs" value="<?php if (isset($_POST['refi-onetime-costs'])) { echo $_POST['refi-onetime-costs']; } ?>">
    </p>
    <input type="submit" value="Submit" />
</form>
<hr>