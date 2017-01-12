<?php
/**
 *
 * PHP Version 7
 *
 * @category   Config
 * @package    Config
 * @author     Simon Richard <richard@maqprint.fr>
 * @copyright  2016-2017 Maqprint
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @since      N.A
 * @deprecated N.A
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();


// Pour la gestion des sessions
$app->register(new Silex\Provider\SessionServiceProvider());
$app['session']->start();
// Pour la connection à la base de donnée qui se trouve dans app/config
$app->register(new Silex\Provider\DoctrineServiceProvider());

$app['db.builder'] = $app->factory(function () use ($app) {
    return new \Doctrine\DBAL\Query\QueryBuilder($app['db']);
});

// chemin pour les vues
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

/*Utilisation de asset raccourics pour les css / js */
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'css' => array('version' => 'css2', 'base_path' => '/public/')
    ),
));

/* Debug bar */
if($app['debug'] == true){
    $app->register(new Provider\ServiceControllerServiceProvider());
    $app->register(new Provider\HttpFragmentServiceProvider());

    $app->register(new Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}
