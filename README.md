# Zaolik

yet another PHP DI Container
inspired by Phalcon

## How To Use

```php
$container = \Zaolik\DIContainer::getInstance();
$databaseConfig = array (
    'host' => 'localhost',
    'user' => 'user',
    'pass' => 'pass',
    'database' => 'test',
);
$memcacheConfig = array (
    'hosts' => 'localhost',
    'port' => 11211,
);

$container->setFlyweight('mysqli', function () use ($databaseConfig) {
    $mysql = new \mysqli($databaseConfig['host'], $databaseConfig['user'], $databaseConfig['pass']);
    $mysql->select_db($config['database']);
    return $mysql; 
})->
setNew('DateTime', function ($time = null) {
    return new \DateTime($time);
});

// new instance
$mysqli1 = $container->getFlyWieght('mysqli');

// flyweight 
$mysqli2 = $container->getFlyWieght('mysqli');
echo $mysqli1 === $mysqli2 . "\n"

// now
echo $container->getNewInstance('DateTime') . "\n";

// yester day
echo $container->getNewInstance('DateTime', '-1 day') . "\n";
```

## License

This library is available under the MIT license. See the LICENSE file for more info.

