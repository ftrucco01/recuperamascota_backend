<?php

namespace App\Classes\Strategies;

use Illuminate\Support\Facades\DB;

class  DatatableStrategy
{
    /**
     * Creates and returns a query based on the provided datatable type.
     * 
     * Depending on the datatable type, this method will either:
     * - Return a pre-filtered list of users when the type is 'users'.
     * - Or, return a general database table query for any other provided datatable type.
     *
     * @param string $datatableType The type of datatable to generate a query for.
     *
     * @return \Illuminate\Support\Collection|\Illuminate\Database\Query\Builder
     *         Either a collection of users with associated data, or a query builder instance for the specified datatable.
     * 
     * @throws \InvalidArgumentException If an invalid datatable type is provided.
     */
    public function createQuery($datatableType, $user)
    {
        return match ($datatableType) {
            default => DB::table($datatableType)
        };
    }
}
