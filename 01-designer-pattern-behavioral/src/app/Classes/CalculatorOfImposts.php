<?php

namespace App\Classes;

use App\Classes\Imposts\Impost;

class CalculatorOfImposts
{
    public function calculate(Budget $budget, Impost $impost): float
    {
       return  $impost->calculateImpost($budget);
    }
}
