<?php

// OrderFilter.php

namespace App\Filters;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    protected $filters = [
        'type' => TypeFilter::class
    ];
}
