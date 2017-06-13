<p>A very simple mortgage amortization calculator that breaks down monthly mortgage payments and forecasts the total loan principal remaining.</p>
<br>
<form method="post" action="">
    <p>
        <label for="purchase-price">Purchase Price:</label>
        <input type="text" id="purchase-price" name="purchase-price" value="<?php if (isset($_POST["purchase-price"])) { echo $_POST["purchase-price"]; } ?>">
    </p>
    </p>
    <p>
        <label for="down-payment">Down Payment:</label>
        <input type="text" id="down-payment" name="down-payment" value="<?php if (isset($_POST["down-payment"])) { echo $_POST["down-payment"]; } ?>">
    </p>
    <p>
        <label for="interest-rate">Interest Rate:</label>
        <input type="text" id="interest-rate" name="interest-rate" value="<?php if (isset($_POST["interest-rate"])) { echo $_POST["interest-rate"]; } ?>">
    </p>
    <p>
        <label for="mortgage-length">Mortgage Duration:</label>
        <input type="text" id="mortgage-length" name="mortgage-length" value="<?php if (isset($_POST["mortgage-length"])) { echo $_POST["mortgage-length"]; } ?>">
    </p>
    <input type="submit" value="Submit" />
</form>
<hr>