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

- Relationships ([Compoships](https://github.com/topclaudy/compoships))

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    use Jeidison\CompositeKey\CompositeKey;
    
    public function modelX()
    {
        return $this->hasMany(ModelX::class, ['c1', 'c2'], ['a1', 'a2']);
    }
    
    public function modelX1()
    {
        return $this->hasOne(ModelX::class, ['c1', 'c2'], ['a1', 'a2']);
    }
    
    public function modelX2()
    {
        return $this->belongsTo(ModelX::class, ['c1', 'c2'], ['a1', 'a2']);
    }
}
```

- Find

```php
    public function index()
    {
        $modelX = ModelX::find(1);
        // or 
        $modelX = ModelX::find(['c1' => 1, 'c2' => 2]);
    }
```

- FindOrFail

```php
    public function index()
    {
        $modelX = ModelX::findOrFail(1);
        // or 
        $modelX = ModelX::findOrFail(['c1' => 1, 'c2' => 2]);
    }
```

- FindMany

```php
    public function index()
    {
        $modelX = ModelX::findMany([['c1' => 1, 'c2' => 2]]);
        // or 
        $modelX = ModelX::findMany([['c1' => 1, 'c2' => 2], ['a1' => 1, 'a2' => 2]]);
    }
```

- Model Fresh

```php
    public function index()
    {
        $modelX      = ModelX::find(1);
        $freshModelX = $modelX->fresh();
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

- Model Delete

```php
    public function index()
    {
        $modelX = ModelX::find(1);
        $modelX->delete();
    }
```

- Model Destroy

```php
    public function index()
    {
        $count = ModelX::destroy(['c1' => 1, 'c2' => 2]);
        // or 
        $count = ModelX::destroy([['c1' => 1, 'c2' => 2], ['a1' => 1, 'a2' => 2]]);
    }
```

- FirstOrCreate

```php
    $modelX = ModelX::firstOrCreate(['name' => 'Test 10']);
    
    $modelX = ModelX::firstOrCreate(
        ['name' => 'Test 10'],
        ['delayed' => 1, 'arrival_time' => '11:30']
    );
```

- FirstOrNew

```php
    $modelX = ModelX::firstOrNew(['name' => 'Test 10']);
    
    $modelX = ModelX::firstOrNew(
        ['name' => 'Test 10'],
        ['delayed' => 1, 'arrival_time' => '11:30']
    );
```

- IncrementOrDecrement

```php
    // Not implemented
```

- Eloquent\Collection::find

```php
    // Not implemented
```

- Eloquent\Collection::fresh

```php
    // Not implemented
```

- Authenticatable::getAuthIdentifierName

```php
    // Not implemented
```

- Authenticatable::getAuthIdentifier

```php
    // Not implemented
```

- Model::getRouteKey

```php
    // Not implemented
```

- Model::getRouteKeyName

```php
    // Not implemented
```

- SerializesAndRestoresModelIdentifiers

```php
    // Not implemented
```

## Authors

* [Jeidison Farias](https://github.com/jeidison)

## License

**composite-key** is licensed under the [MIT License](http://opensource.org/licenses/MIT).

