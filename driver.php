<?php

use Psr\Container\ContainerInterface;
use Kipchak\Driver\Filecache\Driver;

/**
 * @var $container ContainerInterface
 */

Driver::initialise($container);
Driver::set();