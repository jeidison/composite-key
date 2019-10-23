<?php

namespace Jeidison\CompositeKey;

use Exception;
use Awobaz\Compoships\Compoships;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection as BaseCollection;

trait CompositeKey
{
    use Compoships;

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'array';
    }

    public static function destroy($ids)
    {
        $me = new static();
        $count = 0;
        if (!is_array($me->getKeyName())) {
            if ($ids instanceof BaseCollection) {
                $ids = $ids->all();
            }

            $ids = is_array($ids) ? $ids : func_get_args();
            $key = $me->getKeyName();
            foreach ($me->whereIn($key, $ids)->get() as $model) {
                if ($model->delete()) {
                    $count++;
                }
            }
            return $count;
        } else {
            if (self::isArrayMulti($ids)) {
                $model = self::findMany($ids);
            } else {
                $model = self::find($ids);
            }

            if ($model instanceof BaseCollection) {

                $model->each(function ($item) use (&$count) {
                    if ($item->delete()) {
                        $count++;
                    }
                });

            } else {
                if ($model->delete()) {
                    $count++;
                }
            }

            return $count;
        }
    }

    private static function isArrayMulti($arr) {
        $rv = array_filter($arr,'is_array');
        if(count($rv)>0)
            return true;
        return false;
    }

    public function refresh()
    {
        if (!is_array($this->getKeyName())) {
            return parent::refresh();
        }

        if (!$this->exists) {
            return $this;
        }
        $this->setRawAttributes(
            static::findOrFail($this->getKey())->attributes
        );
        $this->load(collect($this->relations)->except('pivot')->keys()->toArray());
        return $this;
    }

    public static function findOrFail($ids, $columns = ['*'])
    {
        $result = self::find($ids, $columns);
        if (!is_null($result)) {
            return $result;
        }

        throw (new ModelNotFoundException)->setModel(
            __CLASS__, $ids
        );
    }

    public function fresh($with = [])
    {
        if (!$this->exists) {
            return null;
        }

        return static::newQueryWithoutScopes()
            ->with(is_string($with) ? func_get_args() : $with)
            ->where(function ($query) {
                if (!is_array($this->getKeyName())) {
                    $query->where($this->getKeyName(), $this->getKey());
                } else {
                    foreach ($this->getKeyName() as $key) {
                        $query->where($this->getKey());
                    }
                }
            })
            ->first();
    }

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
        $me = new self;
        $query = $me->newQuery();
        if (!is_array($me->getKeyName())) {

            if (is_array($ids) || $ids instanceof Arrayable) {
                return self::findMany($ids, $columns);
            }

            $query->whereKey($ids)->first($columns);
        } else {
            foreach ($me->getKeyName() as $key) {
                $query->where($key, '=', $ids[$key]);
            }
        }

        return $query->first($columns);
    }

    public static function findMany($id, $columns = array('*'))
    {
        $me = new self;
        $query = $me->newQuery();
        if (!is_array($me->getKeyName())) {
            $id = $id instanceof Arrayable ? $id->toArray() : $id;
            if (empty($id)) {
                return $query->model->newCollection();
            }
            return $query->whereKey($id)->get($columns);
        } else {
            foreach ($id as $item) {
                $query->orWhere(function ($q) use($item) {
                    $q->where($item);
                });
            }

            return $query->get($columns);
        }
    }

    protected function getKeyForSaveQuery()
    {
        return $this->getKey();
    }

    public function getKey()
    {
        if (!is_array($this->getKeyName())) {
            return $this->getAttribute($this->getKeyName());
        }

        return array_reduce($this->getKeyName(), function ($result, $item) {
            $result[$item] = $this->getAttribute($item);
            return $result;
        }, array());
    }

}
