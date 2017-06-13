<?php

class MortgageVsLoanModel {

    public function compare($input_data) {

        $mortgage_payments = $this->calculateMortgageTotal($input_data);
        $mortgage_payments_extra = $this->calculateMortgageWithExtraPayments($input_data);
        $loan_payments = $this->calculateLoanTotal($input_data);
        $loan_payments_extra = $this->calculateLoanWithExtraPayments($input_data);

        $total_payments = $input_data['mortgage-length'] * 12;

        for ($i = 1; $i <= $total_payments; $i++) {

            $loan_payment_comparison[$i] = [
                'date' => $mortgage_payments[$i]['date'],
                'extra_to_mortgage_mortgage_monthly_cost' => $mortgage_payments_extra[$i]['total-monthly-cost'],
                'extra_to_mortgage_loan_monthly_cost' => $loan_payments[$i]['total-monthly-cost'],
                'extra_to_mortgage_total_monthly_cost' => $mortgage_payments_extra[$i]['total-monthly-cost'] + $loan_payments[$i]['total-monthly-cost'],
                'extra_to_mortgage_net_spend' => ($mortgage_payments_extra[$i]['net-spent'] ?: end($mortgage_payments_extra)['net-spent']) + ($loan_payments[$i]['net-spent'] ?: end($loan_payments)['net-spent']),
                'extra_to_mortgage_total' => ($mortgage_payments_extra[$i]['total-cost'] ?: end($mortgage_payments_extra)['total-cost']) + ($loan_payments[$i]['net-spent'] ?: end($loan_payments)['net-spent']),
                'extra_to_loan_mortgage_monthly_cost' => $mortgage_payments[$i]['total-monthly-cost'],
                'extra_to_loan_loan_monthly_cost' => $loan_payments_extra[$i]['total-monthly-cost'],
                'extra_to_loan_total_monthly_cost' => $mortgage_payments[$i]['total-monthly-cost'] + $loan_payments_extra[$i]['total-monthly-cost'],
                'extra_to_loan_net_spend' => $mortgage_payments[$i]['net-spent'] + ($loan_payments_extra[$i]['net-spent'] ?: end($loan_payments_extra)['net-spent']),
                'extra_to_loan_total' => $mortgage_payments[$i]['total-cost'] + ($loan_payments_extra[$i]['net-spent'] ?: end($loan_payments_extra)['net-spent']),
            ];
        }

        return $loan_payment_comparison;
    }

    private function calculateMortgageTotal($input_data) {
        $monthly_interest_rate = $input_data['interest-rate'] / 12;
        $total_payments = $input_data['mortgage-length'] * 12;

        $monthly_mortgage_cost = ($input_data['loan-amount'] * (($monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments)) / (pow((1 + $monthly_interest_rate), $total_payments) - 1)));

        $principal_remaining = $input_data['loan-amount'];
        $month = 1;

        $home_value = $input_data['purchase-price'];

        $net_spent = $input_data['down-payment'];

        $date = new DateTime($input_data['first-payment-date']);

        while ($month <= $total_payments && $principal_remaining >= 0) {

            $interest = $principal_remaining * $monthly_interest_rate;
            $amount_to_principal = $monthly_mortgage_cost - $interest;
            $principal_remaining -= $amount_to_principal;
            $home_value = $home_value * (pow(1+($input_data['appreciation']/12),1));
            $equity = $home_value - $principal_remaining;

            if ($principal_remaining <= ($input_data['purchase-price'] * $input_data['pmi-until'])) {
                $pmi = 0;
            } else {
                $pmi = $input_data['pmi'];
            }

            $total_monthly_cost = $interest + $amount_to_principal + $pmi + $input_data['taxes'] + $input_data['insurance'];
            $net_spent += $total_monthly_cost;
            $total_net_cost = $equity - $net_spent;

            $payments[$month] = [
                'date' => $date->format('m-d-Y'),
                'interest_paid' => $interest,
                'to_principal' => $amount_to_principal,
                'principal_remaining' => $principal_remaining,
                'taxes' => $input_data['taxes'],
                'insurance' => $input_data['insurance'],
                'pmi' => $pmi,
                'home-value' => $home_value,
                'total-monthly-cost' => $total_monthly_cost,
                'net-spent' => $net_spent,
                'total-cost' => $total_net_cost,
                'equity' => $equity
            ];

            $date->add(new DateInterval('P1M'));
            $month++;
        }
        return $payments;
    }
    
    private function calculateMortgageWithExtraPayments($input_data) {
        $monthly_interest_rate = $input_data['interest-rate'] / 12;
        $total_payments = $input_data['mortgage-length'] * 12;

        $monthly_mortgage_cost = ($input_data['loan-amount'] * (($monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments)) / (pow((1 + $monthly_interest_rate), $total_payments) - 1)));

        $principal_remaining = $input_data['loan-amount'];
        $month = 1;

        $home_value = $input_data['purchase-price'];

        $net_spent = $input_data['down-payment'];

        $date = new DateTime($input_data['first-payment-date']);

        while ($month <= $total_payments && $principal_remaining >= 0) {

            if (!$input_data['extra-payments-start'] || $date < new DateTime($input_data['extra-payments-start'])) {
                $extra_monthly_payment = 0;
            } else {
                $extra_monthly_payment = $input_data['extra-payments'];
            }

            $interest = $principal_remaining * $monthly_interest_rate;
            $amount_to_principal = $monthly_mortgage_cost - $interest;
            $principal_remaining -= ($amount_to_principal + $extra_monthly_payment);
            $home_value = $home_value * (pow(1+($input_data['appreciation']/12),1));
            $equity = $home_value - $principal_remaining;

            if ($principal_remaining <= ($input_data['purchase-price'] * $input_data['pmi-until'])) {
                $pmi = 0;
            } else {
                $pmi = $input_data['pmi'];
            }

            $total_monthly_cost = $interest + $amount_to_principal + $extra_monthly_payment + $pmi + $input_data['taxes'] + $input_data['insurance'];
            $net_spent += $total_monthly_cost;
            $total_net_cost = $equity - $net_spent;

            $payments[$month] = [
                'date' => $date->format('m-d-Y'),
                'interest_paid' => $interest,
                'to_principal' => $amount_to_principal,
                'principal_remaining' => $principal_remaining,
                'taxes' => $input_data['taxes'],
                'insurance' => $input_data['insurance'],
                'extra-payments' => $extra_monthly_payment,
                'pmi' => $pmi,
                'home-value' => $home_value,
                'total-monthly-cost' => $total_monthly_cost,
                'net-spent' => $net_spent,
                'total-cost' => $total_net_cost,
                'equity' => $equity
            ];

            $date->add(new DateInterval('P1M'));
            $month++;
        }
        return $payments;
    }

    private function calculateLoanTotal($input_data) {
        $monthly_interest_rate = $input_data['loan2-interest'] / 12;
        $total_payments = $input_data['loan2-duration'] * 12;

        $monthly_loan_cost = ($input_data['loan2-amount'] * (($monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments)) / (pow((1 + $monthly_interest_rate), $total_payments) - 1)));

        $principal_remaining = $input_data['loan2-amount'];
        $month = 1;
        $month_test = round(abs(strtotime($input_data['loan2-start']) - strtotime($input_data['first-payment-date'])) / ((60*60*24*365.25)/12)) + 1;
//        $mortgage_payment_start_date = new DateTime($input_data['first-payment-date']);
//        $loan_payment_start_date = new DateTime($input_data['loan2-start']);
//        $month_diff = $mortgage_payment_start_date->diff($loan_payment_start_date);
//        $test_var = ($month_diff->format('%y') * 12) + $month_diff->format('%m');
        $net_spent = 0;

//        $loan_start_date = date("M-Y", strtotime($input_data['loan2-start']));

//        while ($month < $month_diff->format('%m')) {
        while ($month < $month_test->format('%m')) {
            $payments[$month] = [
                'date' => "n/a",
                'interest_paid' => .001,
                'to_principal' => .001,
                'extra-payments' => .001,
                'principal_remaining' => .001,
                'total-monthly-cost' => .001,
                'net-spent' => .001
            ];
            $month++;
        }

        $month = 1;
        $date = new DateTime($input_data['loan2-start']);

        while ($month_test <= ($total_payments + $month_test) && $principal_remaining >= 0) {

            $interest = $principal_remaining * $monthly_interest_rate;
            $amount_to_principal = $monthly_loan_cost - $interest;
            $principal_remaining -= $amount_to_principal;

            $net_spent += $monthly_loan_cost;

            $payments[$month_test] = [
                'date' => $date->format('m-d-Y'),
                'interest_paid' => $interest,
                'to_principal' => $amount_to_principal,
                'principal_remaining' => $principal_remaining,
                'total-monthly-cost' => $monthly_loan_cost,
                'net-spent' => $net_spent
            ];

            $date->add(new DateInterval('P1M'));
//            $month_test++;
            $month++;
        }
        return $payments;
    }

    private function calculateLoanWithExtraPayments($input_data) {
        $monthly_interest_rate = $input_data['loan2-interest'] / 12;
        $total_payments = $input_data['loan2-duration'] * 12;

        $monthly_loan_cost = ($input_data['loan2-amount'] * (($monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments)) / (pow((1 + $monthly_interest_rate), $total_payments) - 1)));

        $principal_remaining = $input_data['loan2-amount'];
        $month = 1;
        $month_test = round(abs(strtotime($input_data['loan2-start']) - strtotime($input_data['first-payment-date'])) / ((60*60*24*365.25)/12)) + 1;
        $net_spent = 0;

//        $loan_start_date = date("M-Y", strtotime($input_data['loan2-start']));

        while ($month < $month_test) {
            $payments[$month] = [
                'date' => "n/a",
                'interest_paid' => .001,
                'to_principal' => .001,
                'extra-payments' => .001,
                'principal_remaining' => .001,
                'total-monthly-cost' => .001,
                'net-spent' => .001
            ];
            $month++;
        }

        $month = 1;
        $date = new DateTime($input_data['loan2-start']);

        while ($month_test < ($total_payments + $month_test) && $principal_remaining >= 0) {

            if (!$input_data['extra-payments-start'] || $date < new DateTime($input_data['extra-payments-start'])) {
                $extra_monthly_payment = 0;
            } else {
                $extra_monthly_payment = $input_data['extra-payments'];
            }

            $interest = $principal_remaining * $monthly_interest_rate;
            $amount_to_principal = $monthly_loan_cost - $interest;
            $principal_remaining -= ($amount_to_principal + $extra_monthly_payment);

            $total_monthly_cost = $interest + $amount_to_principal + $extra_monthly_payment;
            $net_spent += $total_monthly_cost;

            $payments[$month_test] = [
                'date' => $date->format('m-d-Y'),
                'interest_paid' => $interest,
                'to_principal' => $amount_to_principal,
                'extra-payments' => $extra_monthly_payment,
                'principal_remaining' => $principal_remaining,
                'total-monthly-cost' => $total_monthly_cost,
                'net-spent' => $net_spent
            ];

            $date->add(new DateInterval('P1M'));
            $month_test++;
            $month++;
        }
        return $payments;
    }
}