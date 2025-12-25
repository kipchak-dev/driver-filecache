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
        self::set();
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
     * @param string|null $configKey
     * @param string|null $configFileName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get(?string $configKey = null, ?string $configFileName = null): FilesystemAdapter
    {
        return  self::$container->get('drivers.cache.file');
    }

    public static function doctrineSet(string $namespace, string $directory, int $defaultLifetime = 0): void
    {
        self::$container->set('drivers.cache.file.' . $namespace, new FilesystemAdapter($namespace, $defaultLifetime, $directory));
    }

    public static function doctrineGet(string $namespace): FilesystemAdapter
    {
        return self::$container->get('drivers.cache.file.' . $namespace);
    }
}