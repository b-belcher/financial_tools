<form method="post" action="">
    <p>
        <label for="first-number">First number:</label>
        <input type="text" id="first-number" name="first-number" value="<?php if (isset($_POST["first-number"])) { echo htmlspecialchars($_POST["first-number"]); } if (isset($results['number1'])) { echo $results['number1'];} ?>">
    </p>
    </p>
    <p>
        <label for="second-number">Second number:</label>
        <input type="text" id="second-number" name="second-number" value="<?php if (isset($_POST["second-number"])) { echo htmlspecialchars($_POST["second-number"]); } if (isset($results['number2'])) { echo $results['number2'];}?>">
    </p>
    <input type="submit" name="submit" value="Submit" />
    <input type="submit" name="save" value="Save" />
    <input type="submit" name="load" value="Load" />
</form>
<hr>