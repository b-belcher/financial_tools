<?php

class RefinanceModel {

    public function calculate($mortgage_data) {
        $monthly_interest_rate = $mortgage_data['interest-rate'] / 12;
        $total_payments = $mortgage_data['mortgage-length'] * 12;

        $monthly_mortgage_cost = ($mortgage_data['loan-amount'] * (($monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments)) / (pow((1 + $monthly_interest_rate), $total_payments) - 1)));

        $principal_remaining = $mortgage_data['loan-amount'];
        $month = 1;

        $home_value = $mortgage_data['purchase-price'];

        $net_spent = $mortgage_data['down-payment'];

        $date = new DateTime($mortgage_data['first-payment-date']);

        while ($month <= $total_payments && $principal_remaining >= 0) {

            if (!$mortgage_data['extra-payments-start'] || $date < new DateTime($mortgage_data['extra-payments-start'])) {
                $extra_monthly_payment = 0;
            } else {
                $extra_monthly_payment = $mortgage_data['extra-payments'];
            }

            $interest = $principal_remaining * $monthly_interest_rate;
            $amount_to_principal = $monthly_mortgage_cost - $interest;
            $principal_remaining -= ($amount_to_principal + $extra_monthly_payment);
            $home_value = $home_value * (pow(1+($mortgage_data['appreciation']/12),1));
            $equity = $home_value - $principal_remaining;

            if ($principal_remaining <= ($mortgage_data['purchase-price'] * $mortgage_data['pmi-until'])) {
                $pmi = 0;
            } else {
                $pmi = $mortgage_data['pmi'];
            }

            $total_monthly_cost = $interest + $amount_to_principal + $extra_monthly_payment + $pmi + $mortgage_data['taxes'] + $mortgage_data['insurance'];
            $net_spent += $total_monthly_cost;
            $total_net_cost = $equity - $net_spent;

            $payments[$month] = [
                'date' => $date->format('m-d-Y'),
                'interest_paid' => $interest,
                'to_principal' => $amount_to_principal,
                'principal_remaining' => $principal_remaining,
                'taxes' => $mortgage_data['taxes'],
                'insurance' => $mortgage_data['insurance'],
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

        $refinance_date = new DateTime($mortgage_data['refi-date']);
        $monthly_refi_interest_rate = $mortgage_data['refi-interest'] / 12;
        $total_refi_payments = $mortgage_data['refi-duration'] * 12;

        foreach($payments as $month_count => $payment) {
            if($payment['date'] == $refinance_date->format('m-d-Y')) {
                $refi_starting_loan_amount = $payment['principal_remaining'];
                $refi_month_number = $month_count;
                $refi_starting_home_value = $payment['home-value'];
                $refi_net_spent_at_start = $payment['net-spent'];
            };
        }

        $monthly_refi_cost = ($refi_starting_loan_amount * (($monthly_refi_interest_rate * pow((1 + $monthly_refi_interest_rate), $total_refi_payments)) / (pow((1 + $monthly_refi_interest_rate), $total_refi_payments) - 1)));

        $refi_principal_remaining = $refi_starting_loan_amount;
        $refi_month = $refi_month_number;
        $refi_home_value = $refi_starting_home_value;
        $refi_net_spent = $refi_net_spent_at_start + $mortgage_data['refi-onetime-costs'];

        $refi_payment_date = new DateTime($mortgage_data['refi-date']);

        while ($refi_month <= ($total_refi_payments + $refi_month_number) && $refi_principal_remaining >= 0) {

            $refi_interest = $refi_principal_remaining * $monthly_refi_interest_rate;
            $refi_amount_to_principal = $monthly_refi_cost - $refi_interest;
            $refi_principal_remaining -= $refi_amount_to_principal;

            $refi_home_value = $refi_home_value * (pow(1+($mortgage_data['appreciation']/12),1));
            $refi_equity = $refi_home_value - $refi_principal_remaining;

            $refi_total_monthly_cost = $refi_interest + $refi_amount_to_principal + $mortgage_data['taxes'] + $mortgage_data['insurance'];
            $refi_net_spent += $refi_total_monthly_cost;
            $refi_total_net_cost = $refi_equity - $refi_net_spent;

            $refi_payments[$refi_month] = [
                'refi-date' => $refi_payment_date,
                'refi-interest_paid' => $refi_interest,
                'refi-to_principal' => $refi_amount_to_principal,
                'refi-principal_remaining' => $refi_principal_remaining,
                'refi-taxes' => $mortgage_data['taxes'],
                'refi-insurance' => $mortgage_data['insurance'],
                'refi-extra-payments' => 0,
                'refi-pmi' => 0,
                'refi-home-value' => $refi_home_value,
                'refi-total-monthly-cost' => $refi_total_monthly_cost,
                'refi-net-spent' => $refi_net_spent,
                'refi-total-cost' => $refi_total_net_cost,
                'refi-equity' => $refi_equity
            ];

            $refi_payment_date = $refi_payment_date->add(new DateInterval('P1M'));
            $refi_month++;
        }

        for ($i = 1; $i <= $total_payments; $i++) {

            $refi_comparison[$i] = [
                'date' => $payments[$i]['date'],
                'original-mortgage-monthly-cost' => $payments[$i]['total-monthly-cost'],
                'original-mortgage-total-spent' => $payments[$i]['net-spent'],
                'original-mortgage-equity' => $payments[$i]['equity'],
                'original-mortgage-true-cost' => $payments[$i]['total-cost'],
                'refi-mortgage-monthly-cost' => $refi_payments[$i]['refi-total-monthly-cost'],
                'refi-mortgage-total-spent' => $refi_payments[$i]['refi-net-spent'],
                'refi-mortgage-equity' => $refi_payments[$i]['refi-equity'],
                'refi-mortgage-true-cost' => $refi_payments[$i]['refi-total-cost'],
                'difference' => ($refi_payments[$i]['refi-total-cost'] - $payments[$i]['total-cost'])
            ];
        }

        return $refi_comparison;
    }
}