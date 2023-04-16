<?php

namespace App\Classes;

class CalculatorOfDiscount
{
    public function discount(Budget $budget): float
    {
        if ($budget->quantityOfItems > 5) {
            return $budget->value * 0.1;
        }

        if ($budget->value > 500) {
            return $budget->value * 0.05;
        }

        return 0;
    }
}
