<?php

namespace App\Traits\Eloquent;

trait Filterable
{
    /**
     * @param $query
     * @param $filters
     * @return mixed
     * custom queryscope in eloquent query
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}

