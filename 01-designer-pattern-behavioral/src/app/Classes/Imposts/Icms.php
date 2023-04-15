<?php

namespace App\Classes\Imposts;

use App\Classes\Budget;

class Icms implements Impost
{
    public function calculateImpost(Budget $budget): float
    {
        return $budget->value * 0.1;
    }
}
