# Laravel Scout Sphinx Driver

## Installation
### Composer
Use the following command to install package via composer
```bash
composer require larva/laravel-scout-sphinxsearch
```

### Configuration

Publish the Scout configuration using the `vendor:publish` Artisan command. 
```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```
This command will publish the scout.php configuration file to your config directory. 
Edit this file to set 'sphinxsearch' as a Scout driver:
```php
'driver' => env('SCOUT_DRIVER', 'sphinxsearch'),
```
And add default Sphinx connection options
```php
    'sphinxsearch' => [
        'host' => env('SPHINX_HOST', 'localhost'),
        'port' => env('SPHINX_PORT', '9306'),
        'socket' => env('SPHINX_SOCKET'),
        'charset' => env('SPHINX_CHARSET'),
    ],
```
Override these variables in your .env file if need.

## Usage
- Add the `Laravel\Scout\Searchable` trait to the model you would like to make searchable. 
- Customize index name and searchable data for the model:
```php
    public function searchableAs()
    {
        return 'posts_index';
    }
    
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
```

The basic search:
```php 
$orders = App\Order::search('Star Trek')->get();
``` 

Please refer to the [Scout documentation](https://laravel.com/docs/master/scout#searching) for additional information.
You can run more complex queries on index using callback, set the where clause, orderBy or paginate, for example:
```php
$oorders = App\Order::search($keyword, function (SphinxQL $query) {
        return $query->groupBy('description');
    })            
    ->where('status', 1)
    ->orderBy('date', 'DESC')
    ->paginate(20);
``` 
> Note: Changes on Sphinx indexes are only allowed for RT (Real Time) indexes. If you have ones and you need to update/delete records please define `public $isRT = true;` model's property. 