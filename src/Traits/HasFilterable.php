<?php

namespace Endropie\ApiToolkit\Traits;

use Endropie\ApiToolkit\Http\Filter;

trait HasFilterable
{

    public function scopeFilter($query, Filter $filter = null)
    {
        if (!$filter) $filter = new Filter;

        return $filter->apply($query);
    }

    public function scopePagination($query, $limit = 10)
    {
        return $query->paginate(request()->get('limit', $limit));
    }

    public function scopelimitation($query, $limit = 10)
    {
        return $query->limit(request()->get('limit', $limit))->offset(request()->get('offset', 0))->get();
    }
}
