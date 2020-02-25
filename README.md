# Helper for Testing structures, relations of your models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thomasdominic/models-testor.svg?style=flat-square)](https://packagist.org/packages/thomasdominic/models-testor)
[![Build Status](https://img.shields.io/travis/thomasdominic/models-testor/master.svg?style=flat-square)](https://travis-ci.org/thomasdominic/models-testor)
[![Quality Score](https://img.shields.io/scrutinizer/g/thomasdominic/models-testor.svg?style=flat-square)](https://scrutinizer-ci.com/g/thomasdominic/models-testor)
[![Total Downloads](https://img.shields.io/packagist/dt/thomasdominic/models-testor.svg?style=flat-square)](https://packagist.org/packages/thomasdominic/models-testor)

This package allows you to test your models about table structures, relations and more

## Installation

You can install the package via composer:

```bash
composer require thomasdominic/models-testor
```

## Usage

To use this package, you have to generate factories for your models. (See [Factories Documentation](https://laravel.com/docs/6.x/database-testing#writing-factories)) 
You can generate one test file by model or by several. For your model `MyModel` you can use this command for example: 

```bash
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
    use ModelsTestor;
    
    public function test_have_user_model()
    {
        $this->setModelTestable(User::class)
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
    
    use ModelsTestor;
    
    public function test_have_category_model()
    {
        $this->setModelTestable(Category::class)
            ->assertHasHasManyRelations([
                [
                    'relation_class' => Customer::class,
                    'relation_name'  => 'customers',
                ],
            ]);
    }

}

class CustomerTest extends TestCase
{
    use ModelsTestor;

    public function test_have_customer_model()
    {
        $this->setModelTestable(Customer::class)
            ->assertHasBelongsToRelations([
                [
                    'relation_class'       => Category::class,
                    'relation_name'        => 'category',
                    'relation_foreign_key' => 'category_id',
                ],
            ]);
    }
}
```

If you have several relations, you can do this: 

``` php

    $this->setModelTestable(Customer::class)
            ->assertHasBelongsToRelations([
                [
                    'model_class'          => Customer::class,
                    'relation_class'       => Category::class,
                    'relation_name'        => 'category',
                    'relation_foreign_key' => 'category_id',
                ],
                [
                    'model_class'          => Customer::class,
                    'relation_class'       => OtherModel::class,
                    'relation_name'        => 'other_model',
                    'relation_foreign_key' => 'other_model_id',
                ]
            ]);
    
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
         use ModelsTestor;
         
        public function test_have_user_model()
        {
            $this->setModelTestable(User::class)
                ->assertHasManyToManyRelations([
                [
                    'relation_class' => Role::class,
                    'relation_name'  => 'roles',
                ]
            ]);
        }


    }

    class RoleTest extends TestCase
    {
        use ModelsTestor;

        public function test_have_role_model()
        {
            $this->setModelTestable(User::class)
                ->assertHasManyToManyRelations([
                [
                    'relation_class' => User::class,
                    'relation_name'  => 'users',
                ]
            ]);
        }

    }
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
        
        use ModelsTestor;
                    
        public function test_have_post_model()
            {
                $this->setModelTestable(Post::class)
                    ->assertHasHasManyMorphRelations([
                            [
                                'morph_model_class'     => Comment::class,
                                'morph_relation'        => 'comments',
                            ],
                        ]
                    );
            }
    }
    
    class VideoTest extends TestCase
        {
            use ModelsTestor;
            
            public function test_have_video_model()
                {
                    $this->setModelTestable(Video::class)
                        ->assertHasHasManyMorphRelations([
                                [
                                    'morph_model_class'     => Comment::class,
                                    'morph_relation'        => 'comments',
                                ],
                            ]
                        );
                }
        }

    class CommentTest extends TestCase
        {
            
            use ModelsTestor;
            
            public function test_have_morph_model_model()
            {
                $this->setModelTestable(Comment::class)
                   ->assertHasBelongsToMorphRelations([
                         [
                                'morphable_model_class' => Post::class,
                                'morph_relation'        => 'commentable',
                            ],
                            [
                                'morphable_model_class' => Video::class,
                                'morph_relation'        => 'commentable',
                            ],
                        ]
                    );
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