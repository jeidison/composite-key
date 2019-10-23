# Laravel Composite Key
==========

## The problem

Eloquent doesn't support composite keys.

## Installation

```bash
$ composer require jeidison/composite-key
```

## Usage

Add Trait of your model

- Relationships

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    use Jeidison\CompositeKey\CompositeKey;
    
    public function anyWhatever()
    {
        return $this->hasMany(Anything::class, ['c1', 'c2'], ['a1', 'a2']);
    }
    
    public function anyWhatever1()
    {
        return $this->hasOne(Anything::class, ['c1', 'c2'], ['a1', 'a2']);
    }
    
    public function anyWhatever2()
    {
        return $this->belongsTo(Anything::class, ['c1', 'c2'], ['a1', 'a2']);
    }
}
```

- Find

```php
    public function index()
    {
        $anyWhatever = Anything::find(1);
        // or 
        $anyWhatever = Anything::find(['c1' => 1, 'c2' => 2]);
    }
```

- Find Or Fail

```php
    public function index()
    {
        $anyWhatever = Anything::findOrFail(1);
        // or 
        $anyWhatever = Anything::findOrFail(['c1' => 1, 'c2' => 2]);
    }
```

- FindMany

```php
    public function index()
    {
        $anyWhatever = Anything::findMany([['c1' => 1, 'c2' => 2]]);
        // or 
        $anyWhatever = Anything::findMany([['c1' => 1, 'c2' => 2], ['a1' => 1, 'a2' => 2]]);
    }
```

- Model Fresh

```php
    public function index()
    {
        $anyWhatever      = Anything::find(1);
        $freshAnyWhatever = $anyWhatever->fresh();
    }
```

- Model Refresh

```php
    public function index()
    {
        $anyWhatever      = Anything::find(1);
        $freshAnyWhatever = $anyWhatever->refresh();
    }
```

## Authors

* [Jeidison Farias](https://github.com/jeidison)

## License

**composite-key** is licensed under the [MIT License](http://opensource.org/licenses/MIT).

