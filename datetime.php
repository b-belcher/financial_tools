<?php
$title = "Brett's Homepage";
include("inc/nav.php");
?>

    <p>Welcome! Placeholder content. In the meantime, check out the <a href="amortization.php">Amortization Calculator</a>!</p>

<?php

$test = new DateTime('jan 12 2012');
$test->add(new DateInterval('P1M'));
echo $test->format('m-d-Y');

?>

<?php
include("inc/footer.php");
?>