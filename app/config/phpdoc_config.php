<?php
$dir = __DIR__ . '/../../app';

$iterator = Symfony\Component\Finder\Finder::create()
->files()
->name('*.php')
->exclude('cache')
->exclude('views')
->in($dir);

$options = [
'theme'                => 'default',
'title'                => 'Silex Project Documentation',
'build_dir'            => __DIR__ . '/../../public/documentation/build',
'cache_dir'            => __DIR__ . '/../../public/documentation/cache',
];

$sami = new Sami\Sami($iterator, $options);

return $sami;
