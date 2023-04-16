<?php

namespace App\Classes;

use App\Classes\Imposts\Impost;

class CalculatorOfImposts
{
    public function calculate(Budget $budget, string $impost): float
    {
        switch ($impost) {
            case "ICMS":
                return $budget->value * 0.1;
                break;
            case "ISS":
                return $budget->value * 0.06;
                break;
            default:
                return "No match!";
                break;
        }
    }
}
