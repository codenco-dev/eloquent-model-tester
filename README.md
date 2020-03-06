# Helper for Testing structures, relations of your models in Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thomasdominic/eloquent-model-testor.svg?style=flat-square)](https://packagist.org/packages/thomasdominic/eloquent-model-testor)
[![StyleCI](https://github.styleci.io/repos/245467771/shield?branch=master)](https://github.styleci.io/repos/245467771)
[![Build Status](https://travis-ci.com/thomasdominic/eloquent-model-testor.svg?branch=master)](https://travis-ci.com/thomasdominic/eloquent-model-testor)
[![Quality Score](https://scrutinizer-ci.com/g/thomasdominic/eloquent-model-testor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thomasdominic/eloquent-model-testor)
[![Total Downloads](https://img.shields.io/packagist/dt/thomasdominic/eloquent-model-testor.svg?style=flat-square)](https://packagist.org/packages/thomasdominic/models-testor)

This package allows you to test your models about table structures, relations and more

## Installation

You can install the package via composer:

```bash
composer require thomasdominic/eloquent-model-testor
```

## Usage

To use this package, you have to generate factories for your models. (See [Factories Documentation](https://laravel.com/docs/6.x/database-testing#writing-factories)) 
You can generate one test file by model or for several. For your model `MyModel` you can use this command for example: 

```bash
php artisan make:model MyModel -mf
php artisan make:test Models/MyModelTest
```

### Test of structure and of fillable

With this structure

    users
        id - integer
        name - string
        other_field - string 
   

you can test if you have all the fields you need and if they are fillable.

``` php
class UserTest extends TestCase
{
    use HasModelTestor;
    
    public function test_have_user_model()
    {
        $this->modelTestable(User::class)
            ->assertHasColumns(['id','name','other_field'])
            ->assertCanFillables(['name','other_field']);
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

you can use `assertHasHasManyRelations` and `assertHasBelongsToRelations` methods  like this        

``` php
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

If you don't use Laravel naming convention, you may also override the relation and local keys (for belongsTo relation) by passing 
additional arguments to the `assertHasHasManyRelations` and `assertHasBelongsToRelations` methods

``` php
    $this->modelTestable(Customer::class)
            ->assertHasBelongsToRelation(Category::class,'category','category_id');

    $this->modelTestable(Category::class)
            ->assertHasHasManyRelation(Customer::class,'customers');

```

If you have several relations, you can chain methods like this: 

``` php

    $this->modelTestable(Customer::class)
            ->assertHasBelongsToRelation(Category::class)
            ->assertHasBelongsToRelation(OtherModel::class);
    
```

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

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dthomas@codenco.io instead of using the issue tracker.

## Credits

- [Dominic THOMAS](https://github.com/thomasdominic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).