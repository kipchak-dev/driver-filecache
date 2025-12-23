<?php

namespace Kipchak\Driver\Filecache;

use Kipchak\Core\Interfaces;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Kipchak\Driver\Config\Driver as Config;

class Driver implements Interfaces\Driver
{
    protected static ContainerInterface $container;

    public static function initialise(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function set(): void
    {
        self::$container->set('drivers.cache.file', function(ContainerInterface $c): FilesystemAdapter {
            $config = Config::get('kipchak.api');
            $namespace = $config['name'] ?? 'KipchakApiCache';

            return new FilesystemAdapter($namespace, 3600);
        });
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get(?string $configKey = null): FilesystemAdapter
    {
        return  self::$container->get('drivers.cache.file');
    }

    public static function create(string $namespace, string $directory, int $defaultLifetime = 0): void
    {
        self::$container->set('drivers.cache.file.' . $namespace, new FilesystemAdapter($namespace, $defaultLifetime, $directory));
    }

    public static function retrieve(string $namespace): FilesystemAdapter
    {
        return self::$container->get('drivers.cache.file.' . $namespace);
    }
}