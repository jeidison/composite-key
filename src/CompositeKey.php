<?php

namespace Jeidison\CompositeKey;

use Exception;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Query\Builder;

trait CompositeKey
{
    use Compoships;

    protected function setKeysForSaveQuery(Builder $query)
    {
        foreach ($this->getKeyName() as $key) {
            if (isset($this->$key)) {
                $query->where($key, '=', $this->original[$key] ?? $this->$key);
            } else {
                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
            }
        }
        return $query;
    }

    public static function find($ids, $columns = ['*'])
    {
        $me    = new self;
        $query = $me->newQuery();
        if (!is_array($ids) && !is_array($me->getKeyName())) {
            $query->where($me->getKeyName(), '=', $ids);
        } else {
            foreach ($me->getKeyName() as $key) {
                $query->where($key, '=', $ids[$key]);
            }
        }
        return $query->first($columns);
    }

    protected function getKeyForSaveQuery()
    {
        return $this->getKey();
    }

    public function getKey()
    {
        return array_reduce($this->getKeyName(), function ($result, $item) {
            $result[$item] = $this->getAttribute($item);
            return $result;
        }, array());
    }

}
