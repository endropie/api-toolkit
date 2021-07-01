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

    public function scopelimitation($query, Filter $limit)
    {
        return $query->limit($this->request->get('limit', 20));
    }
}
