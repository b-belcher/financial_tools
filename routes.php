<?php

function call($controller, $action) {
    require_once('controllers/' . $controller . '_controller.php');

    switch($controller) {
        case 'pages':
            $controller = new PagesController();
            break;
        case 'calculator':
            require_once('models/Calculator.php');
            $model = new Calculate();
            $controller = new CalculatorController($model);
            break;
        case 'simple_mortgage':
            require_once('models/SimpleAmortization.php');
            $model = new SimpleAmortizationModel();
            $controller = new simpleMortgageController($model);
            break;
        case 'advanced_mortgage':
            require_once('models/AdvancedAmortization.php');
            $model = new AdvancedAmortizationModel();
            $controller = new advancedMortgageController($model);
            break;
        case 'refinance':
            require_once('models/Refinance.php');
            $model = new RefinanceModel();
            $controller = new RefinanceController($model);
            break;
        case 'mortgage_vs_loan':
            require_once('models/MortgageVsLoan.php');
            $model = new MortgageVsLoanModel();
            $controller = new mortgageVsLoanController($model);
            break;
    }

    $controller->{$action}();
}

//a static array of which "actions" are allowed for each controller
$controllers = array('pages' => ['home', 'error'],
    'calculator' => ['input', 'add'],
    'simple_mortgage' => ['amortize'],
    'advanced_mortgage' => ['amortize'],
    'refinance' => ['calculate'],
    'mortgage_vs_loan' => ['compare']);

if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
        call($controller, $action);
    } else {
        call('pages', 'error');
    }
} else {
    call('pages', 'error');
}