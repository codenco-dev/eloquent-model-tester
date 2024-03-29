# Helper for Testing structures, relations of your models in Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codenco-dev/eloquent-model-tester.svg?style=flat-square)](https://packagist.org/packages/codenco-dev/eloquent-model-tester)
[![StyleCI](https://github.styleci.io/repos/268515827/shield?branch=master)](https://github.styleci.io/repos/268515827)
[![Build Status](https://travis-ci.com/codenco-dev/eloquent-model-tester.svg?branch=master)](https://travis-ci.com/thomasdominic/eloquent-model-tester)
[![Quality Score](https://scrutinizer-ci.com/g/codenco-dev/eloquent-model-tester/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thomasdominic/eloquent-model-tester)
[![Total Downloads](https://img.shields.io/packagist/dt/codenco-dev/eloquent-model-tester.svg?style=flat-square)](https://packagist.org/packages/codenco-dev/eloquent-model-tester)

This package allows you to test your models about table structures, relations and more

## Installation

You can install the package via composer:

```bash
composer require codenco-dev/eloquent-model-tester --dev
```

## Usage

To use this package, you have to generate factories for your models. (
See [Factories Documentation](https://laravel.com/docs/6.x/database-testing#writing-factories))
You can generate one test file by model or for several. For your model `MyModel` you can use this command for example:

```bash
php artisan make:model MyModel -mf
php artisan make:test Models/MyModelTest
```

To be able to test database, don't forget `RefreshDatabase` use statements.

```
namespace Tests\Feature\Models;

use App\MyModel;
use CodencoDev\EloquentModelTester\HasModelTester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyModelTest extends TestCase
{
    use RefreshDatabase;
    use HasModelTester;

    public function test_have_my_model_model()
    {
        //...
    }
}
```

For more simplicity, you can put the `RefreshDatabase` use statement in `tests/TestCase.php` file

### Test of structure and of fillable / guarded

With this structure

    users
        id - integer
        name - string
        email - string
        password - string
        remember_token - string
        other_field - string
        created_at - timestamp
        updated_at - timestamp

you can test if you have all the fields you need and if they are fillable or guarded.

```php
class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasColumns(['id','name','email','password','remember_token'])
            ->assertHasColumnsInFillable(['name','password'])
            ->assertHasColumnsInGuarded(['remember_token'])
            ->assertHasTimestampsColumns();
    }
}
```

The function `assertHasColumns()` only checks if the values provided are in the schema of the table. If you want to
ensure that no other columns are present, you can use the stricter `assertHasOnlyColumns()` assertion to check that *
only* the column names provided are present in the table.

***N.B. When using this you will need to provide the `created_at` and `updated_at` fields if they are present in the
database table.***

The functions `assertHasColumnsInFillable()` and `assertHasColumnsInGuarded()` only check if the `$fillable`
and `$guarded` arrays contain the values passed. If you want to ensure that no other entries are in the `$fillable`
and `$guarded` arrays, then you can use the stricter `assertHasOnlyColumnsInFillable()`
and `assetHasOnlyColumnsInGuarded()` functions as follows:

```php
class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasOnlyColumns(['id','name','email','password','remember_token', 'created_at', 'updated_at']) // Will fail as missing 'other_field'.
            ->assertHasOnlyColumnsInFillable(['name','password'])
            ->assertHasOnlyColumnsInGuarded(['remember_token']);
    }
}
```

To further confirm that only a set of columns can be filled, you can use the `assertCanOnlyFill()` assertion to confirm
this. This assertion confirms that the fields provided are the only columns that can be filled, by confirming that these
are the only values in the `$fillable` array and they don't appear in the `$guarded` array.

```php
class User extends Model
{
    $fillable = ['name', 'password', 'email'];
    
    $guarded = ['remember_token'];
}

class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasColumns(['id','name','email','password','remember_token'])
            ->assertCanOnlyFill(['name','password']); // Will fail as 'email' is in the fillable array.
    }
}
```

To confirm that a field does not appear in both the `$fillable` and `$guarded` arrays by mistake there is
the `assertNoGuardedAndFillableFields()` assertion that checks that no entry appears in both:

```php
class User extends Model
{
    $fillable = ['name', 'password', 'email'];
    
    $guarded = ['email'];
}

class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertNoGuardedAndFillableFields(); // Will fail as 'email' is in both the fillable & guarded arrays.
    }
}
```

To check for soft delete `deleted_at` column on your model, you can use the `assertHasSoftDeleteTimestampColumns()`
assertion:

    user:
        id - integer
        name - string
        deleted_at - timestamp

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
}

class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasSoftDeleteTimestampColumns();
    }
}
```

### HasOne et BelongsTo

You can test relations of your models. For example, with this structure

    user
        id - integer
        name - string

    phones
        id - integer
        number - string
        user_id - integer

you can use `assertHasHasOneRelation()` and `assertHasBelongsToRelations` methods like this:

```php
class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_category_model()
    {
        $this->modelTestable(User::class)
            ->assertHasHasOneRelation(Phone::class);
    }

}

class PhoneTest extends TestCase
{
    use HasModelTestor;

    public function test_have_customer_model()
    {
        $this->modelTestable(Phone::class)
            ->assertHasBelongsToRelation(User::class);
    }
}
```

### HasMany et BelongsTo

You can test relations of your models. For example, with this structure

    categories
        id - integer
        name - string

    customers
        id - integer
        name - string
        category_id - integer
        type_id - integer

you can use `assertHasHasManyRelations` and `assertHasBelongsToRelations` methods like this

```php
class CategoryTest extends TestCase
{
    use HasModelTestor;

    public function test_have_category_model()
    {
        $this->modelTestable(Category::class)
            ->assertHasHasManyRelation(Customer::class);
    }

}

class CustomerTest extends TestCase
{
    use HasModelTestor;

    public function test_have_customer_model()
    {
        $this->modelTestable(Customer::class)
            ->assertHasBelongsToRelation(Category::class);
    }
}
```

If you don't use Laravel naming convention, you may also override the relation and local keys (for belongsTo relation)
by passing
additional arguments to the `assertHasHasManyRelations` and `assertHasBelongsToRelations` methods

```php
    $this->modelTestable(Customer::class)
            ->assertHasBelongsToRelation(Category::class,'category','category_id');

    $this->modelTestable(Category::class)
            ->assertHasHasManyRelation(Customer::class,'customers');

```

If you have several relations, you can chain methods like this:

```php

    $this->modelTestable(Customer::class)
            ->assertHasBelongsToRelation(Category::class)
            ->assertHasBelongsToRelation(OtherModel::class);

```

### HasManyThrough relations

You can test has many through relations of your models. For example, with this structure

    customers
        id - integer
        name - string

    locations
        id - integer
        customer_id - integer

    orders
        id - integer
        location_id - integer

you can use `assertHasHasManyThroughRelations` method like this

```php
class CustomersTest extends TestCase
{
    use HasModelTestor;

    public function test_have_orders_model()
    {
        $this->modelTestable(Customer::class)
            ->assertHasHasManyThroughRelation(Order::class, Location::class);
    }

}
```

If you don't use Laravel naming convention, you may also override the relation and foreign keys by passing additional
arguments to the `assertHasHasManyThroughRelations` method

```php
    $this->modelTestable(Customer::class)
            ->assertHasHasManyThroughRelation(Order::class, Location::class, 'sales', 'prefix_customer_id', 'prefix_location_id', 'firstPrimaryKey', 'secondPrimaryKey');

```

_Attention_: as there is no **official** inverse of this relationship, it is not possible to use this assertion in the
reverse, i.e., in the `orders` model checking for a `customers` relationship.

### Many to Many relations

You can test your ManyToMany relations with the `ManyToManyRelationsTestable` trait.

    users
        id - integer
        name - string

    roles
        id - integer
        name - string

    role_user
        user_id - integer
        role_id - integer

```php
class UserTest extends TestCase
{
     use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasManyToManyRelation(Role::class);
    }


}

class RoleTest extends TestCase
{
    use HasModelTestor;

    public function test_have_role_model()
    {
        $this->modelTestable(User::class)
            ->assertHasManyToManyRelation(User::class);
    }

}
```

You can override the relation argument too :

```php
    $this->modelTestable(User::class)
            ->assertHasManyToManyRelation(User::class,'users');
```

### Morph Relations

If you have a Morph Relation,

    posts
        id - integer
        title - string
        body - text

    videos
        id - integer
        title - string
        url - string

    comments
        id - integer
        body - text
        commentable_id - integer
        commentable_type - string

you can use `assertHasBelongsToMorphRelations` and `assertHasHasManyMorphRelations` methods like this

```php
class PostTest extends TestCase
{

    use HasModelTestor;

    public function test_have_post_model()
        {
            $this->modelTestable(Post::class)
                ->assertHasHasManyMorphRelation(Comment::class,'comments');
        }
}

class VideoTest extends TestCase
{
    use HasModelTestor;

    public function test_have_video_model()
        {
            $this->modelTestable(Video::class)
                ->assertHasHasManyMorphRelation(Comment::class,'comments');
        }
}

class CommentTest extends TestCase
{

    use HasModelTestor;

    public function test_have_morph_model_model()
    {
        $this->modelTestable(Comment::class)
           ->assertHasBelongsToMorphRelation(Post::class,'commentable')
           ->assertHasBelongsToMorphRelation(Video::class,'commentable');
    }
}
```

#### MorphOne Relations

If you have a Morph One Relation,

    posts
        id - integer
        title - string
        body - text

    users
        id - integer
        title - string

    images
        id - integer
        url - string
        imageable_id - integer
        imageable_type - string

you can use `assertHasBelongsToMorphRelations` and `assertHasMorphOneRelations` methods like this

```php
class PostTest extends TestCase
{

    use HasModelTestor;

    public function test_have_post_model()
        {
            $this->modelTestable(Post::class)
                ->assertHasMorphOneRelation(Image::class);
        }
}

class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
        {
            $this->modelTestable(User::class)
                ->assertHasMorphOneRelation(Image::class, 'avatar');
        }
}

class ImageTest extends TestCase
{

    use HasModelTestor;

    public function test_have_image_model()
    {
        $this->modelTestable(Image::class)
           ->assertHasBelongsToMorphRelation(Post::class,'imageable')
           ->assertHasBelongsToMorphRelation(User::class,'imageable');
    }
}
```

### Pivot and table without Model

You can test if a table contains columns with the `tableTestable` method

```php
class MyPivotTest extends TestCase
{
    public function test_have_table_without_model()
    {
        $this->tableTestable('pivot_table')
            ->assertHasColumns(['first_model_id','second_model_id','other_property']);
    }
}
```

### Model Scopes

You can test if a model has a query scope defined using the `assertHasScope()` assertion:

```php
class User extends Model
{
    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query)
    {
        return $query->where('votes', '>', 100);
    }
}

class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasScope('popular');
    }
}

```

Of course if you're using Dynamic Scopes then `assertHasScope()` takes the arguments to pass to the query scope:

```php
class User extends Model
{
    /**
    * Scope a query to only include users of a given type.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @param  mixed  $type
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}

class UserTest extends TestCase
{
    use HasModelTestor;

    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasScope('ofType', 'admin');
    }
}
```

### Available Assertions

| Assertion                               | Description                                                                         | Notes                                                  |
|-----------------------------------------|-------------------------------------------------------------------------------------|--------------------------------------------------------|
| `assertHasTimestampsColumns()`          | checks if the `created_at` and `updated_at` columns are present in the table.       ||
| `assertHasSoftDeleteTimestampColumns()` | checks if the `deleted_at` column is present in the table.                          ||
| `assertHasColumns()`                    | checks for the presence of the provided column names appear in the table.           ||
| `assertHasOnlyColumns()`                | checks that only the column names provided are the only columns of the table.       ||
| `assertCanOnlyFill()`                   | checks that only the fields provided can be filled and no others.                   ||
| `assertCanFillables()`                  | checks if the fields provided can be filled.                                        | *Deprecated* - Alias of `assertHasColumnsInFillable()` |
| `assertHasColumnsInFillable()`          | checks if the fields provided can be filled.                                        ||
| `assertHasOnlyColumnsInFillable()`      | checks if only the fields provided are those that appear in the $fillable array.    ||
| `assertHasColumnsInGuarded()`           | checks if the fields provide are guarded.                                           ||
| `assertHasOnlyColumnsInGuarded()`       | checks if the only the fields provided are those that appear in the $guarded array. ||
| `assertHasNoGuardedAndFillableFields()` | checks that a column does not appear in both the $fillable and $guarded arrays.     ||
| `assertHasHasOneRelation()`             | checks that the model has the `HasOne` relation.                                    ||
| `assertHasMorphOneRelation()`           | checks that the model has the `MorphOne` relation.                                  ||
| `assertHasHasManyRelation()`            | checks that the model hsa the `HasMany` relation.                                   ||
| `assertHasHasManyThroughRelation()`     | checks that the model has the `HasManyThrough` relation.                            ||
| `assertHasBelongsToRelation()`          | checks that the model has the `BelongsTo` relation.                                 ||
| `assertHasManyToManyRelation()`         | checks that the model has the `ManyToMany` relation.                                ||
| `assertHasHasManyMorphRelation()`       | checks that the model has the `HasManyMorph` relation.                              ||
| `assertHasBelongsToMorphRelation()`     | checks that the model has the `BelongsToMorph` relation.                            ||
| `assertHasScope()`                      | checks that the model has the scope.                                                ||

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dthomas@codenco.fr or contact me on Twitter [DomThomasEs](https://twitter.com/DomThomasEs) instead of using the issue tracker.

## Credits

- [Dominique THOMAS](https://github.com/thomasdominic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
