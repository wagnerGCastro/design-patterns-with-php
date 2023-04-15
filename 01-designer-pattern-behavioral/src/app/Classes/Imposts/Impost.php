<?php

namespace App\Classes\Imposts;

use App\Classes\Budget;

interface Impost
{
    public function calculateImpost(Budget $budget): float;
}
