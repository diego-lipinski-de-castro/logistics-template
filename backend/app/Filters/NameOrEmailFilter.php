<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class NameOrEmailFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = mb_strtolower($value, 'UTF8');

        $query
            ->whereRaw("LOWER(name) LIKE ?", ["%{$value}%"])
            ->orWhereRaw("LOWER(email) LIKE ?", ["%{$value}%"]);
    }
}
