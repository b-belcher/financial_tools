<form method="post" action="">
    <p>
        <label for="first-number">First number:</label>
        <input type="text" id="first-number" name="first-number" value="<?php if (isset($_POST["first-number"])) { echo htmlspecialchars($_POST["first-number"]); } ?>">
    </p>
    </p>
    <p>
        <label for="second-number">Second number:</label>
        <input type="text" id="second-number" name="second-number" value="<?php if (isset($_POST["second-number"])) { echo htmlspecialchars($_POST["second-number"]); } ?>">
    </p>
    <input type="submit" value="Submit" />
</form>
<hr>