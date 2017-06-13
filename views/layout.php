<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Mortgage Loan Calculation Tools</title>
        <link rel="stylesheet" href="css/styles.css" type="text/css">
    </head>
    <body>

    <div class="nav-sidebar" style="width:10%">
        <h3>Navigation</h3>
        <ul>
        <li><a href='/mortgagecalc/mvc-main' class="navbar-item">Home</a><br></li>
        <li><a href="?controller=calculator&action=input" class="navbar-item">Add 2 nums!</a><br></li>
        <li><a href="?controller=simple_mortgage&action=amortize" class="navbar-item">Simple Amortization</a><br></li>
        <li><a href="?controller=advanced_mortgage&action=amortize" class="navbar-item">Advanced Amortization</a><br></li>
        <li><a href="?controller=refinance&action=calculate" class="navbar-item">Refinance Calculator</a><br></li>
        <li><a href="?controller=mortgage_vs_loan&action=compare" class="navbar-item">Repayment Calculator - Mortgage vs. Loan</a><br></li>
        </ul>
    </div>

    <div id="content" style="margin-left:11%">

        <?php require_once('routes.php'); ?>

    </div>

    </body>
</html>

