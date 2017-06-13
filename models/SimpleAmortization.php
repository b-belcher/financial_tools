<?php

class SimpleAmortizationModel {

    public function amortize($user_data)
    {
        $initial_principal = $user_data['purchase-price'] - $user_data['down-payment'];
        $monthly_interest_rate = $user_data['interest-rate'] / 12;
        $total_payments = $user_data['mortgage-length'] * 12;

        $mort_calc_formula_top = $monthly_interest_rate * pow((1 + $monthly_interest_rate), $total_payments);
        $mort_calc_formula_bottom = pow((1 + $monthly_interest_rate), $total_payments) - 1;

        $monthly_mortgage_cost = ($initial_principal * ($mort_calc_formula_top / $mort_calc_formula_bottom));

        $principal = $initial_principal;
        $month = 1;

        while ($month <= $total_payments) {
            $interest = $principal * $monthly_interest_rate;
            $amount_to_principal = $monthly_mortgage_cost - $interest;
            $principal = $principal - $amount_to_principal;

            $payments[$month] = [
                'total_cost' => $monthly_mortgage_cost,
                'interest_paid' => $interest,
                'to_principal' => $amount_to_principal,
                'principal_remaining' => $principal,
            ];

            $month++;
        }

        return $payments;
    }
}
