<?php

namespace Endropie\ApiTool\Http;

use Endropie\ApiTool\Support\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Filter
{
    protected $builder;

    protected $request;

    protected $manager;

    protected $except;

    protected $only;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?: app('request');

        $this->manager = new Filterable($this);
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        return $this->manager->builder($builder);
    }

    public function withTrashed($value = 1)
    {
        $softDelete = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->builder->getModel()));
        if ($value && $softDelete) return $this->builder->withTrashed();

        return $this->builder;
    }

    public function onlyTrashed($value = 1)
    {
        $softDelete = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->builder->getModel()));
        if ($value && $softDelete) return $this->builder->onlyTrashed();

        return $this->builder;
    }

    public function sort($value, $key = null)
    {
        $columns = $this->manager->getColumns();
        $append = $key ? ("." . $key) : "";
        $order = $this->request->has('descending' . $append) ? 'desc' : 'asc';
        $function = (string)  $this->manager->stringable('sort_' . $value)->camel();

        if (method_exists($this, $function)) {
            return $this->$function($order);
        } else if (strlen($value) && in_array($value, $columns)) {
            return $this->builder->orderBy($value, $order);
        }
        return $this->builder;
    }

    public function search($value = '')
    {
        if (!strlen($value)) return $this->builder;
        $columns = $this->manager->getColumns();

        $separator = substr_count($value, '|') > 0 ? '|' : ' ';
        $keywords = gettype($value) == 'array'
            ? array($value)
            : explode($separator, (string) $value);

        return $this->builder->where(function ($query) use ($columns, $keywords) {
            $mode = $this->request->has('searchexpand') ? 'orWher' : 'where';
            foreach ($keywords as $keyword) {
                if (strlen($keyword)) {
                    $query->{$mode}(function ($query) use ($columns, $keyword) {
                        foreach ($columns as $column) {
                            $query->orWhere($column, 'like', '%' . $keyword . '%');
                        }
                    });
                }
            }
        });
    }

    public function __call($method, $parameters)
    {
        if (substr($method, 0, 3) == 'get') {

            $property = (string) $this->manager->stringable(substr($method, 3))->camel();

            if (property_exists($this, $property)) return $this->{$property};
        }

        if (substr($method, 0, 5) == 'manager') {

            $property = (string) $this->manager->stringable(substr($method, 5))->camel();

            if (method_exists($this, $property)) return $this->{$property}(...$parameters);
        }


        return $this->{$method}(...$parameters);
    }
}
