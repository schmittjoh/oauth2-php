<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

require_once __DIR__ . '/../vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
    'FOS'     => array(__DIR__.'/../src', __DIR__),
    'Symfony' => __DIR__.'/../vendor',
));

$loader->register();

