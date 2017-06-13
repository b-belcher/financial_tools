<?php

class AdvancedAmortizationModel {

    public function amortize($user_data) {
        $monthly_interest_rate = $user_data['interest-rate'] / 12;
        $total_payments = $user_data['mortgage-length'] * 12;

        $monthly_mortgage_cost = ($user_data['loan-amount'] * (($monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments)) / (pow((1 + $monthly_interest_rate), $total_payments) - 1)));

        $principal_remaining = $user_data['loan-amount'];
        $month = 1;

        $home_value = $user_data['purchase-price'];

        $net_spent = 0;

        $date = new DateTime($user_data['first-payment-date']);

        while ($month <= $total_payments && $principal_remaining >= 0) {

            if (!$user_data['extra-payments-start'] || $date < new DateTime($user_data['extra-payments-start'])) {
                $extra_monthly_payment = 0;
            } else {
                $extra_monthly_payment = $user_data['extra-payments'];
            }

            $interest = $principal_remaining * $monthly_interest_rate;
            $amount_to_principal = $monthly_mortgage_cost - $interest;
            $principal_remaining -= ($amount_to_principal + $extra_monthly_payment);
            $home_value = $home_value * (pow(1+($user_data['appreciation']/12),1));
            $equity = $home_value - $principal_remaining;

            if ($principal_remaining <= ($user_data['purchase-price'] * $user_data['pmi-until'])) {
                $pmi = 0;
            } else {
                $pmi = $user_data['pmi'];
            }

            $total_monthly_cost = $interest + $amount_to_principal + $extra_monthly_payment + $pmi + $user_data['taxes'] + $user_data['insurance'];
            $net_spent += $total_monthly_cost;
            $total_net_cost = ($home_value - $principal_remaining) - $net_spent;

            $payments[$month] = [
                'date' => $date->format('m-d-Y'),
                'interest_paid' => $interest,
                'to_principal' => $amount_to_principal,
                'principal_remaining' => $principal_remaining,
                'taxes' => $user_data['taxes'],
                'insurance' => $user_data['insurance'],
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
}
