<?php
/**
 *
 * PHP version 7
 *
 * @category   Console
 * @package    Console
 * @author     Simon Richard <richards@maqprint.fr>
 * @copyright  2016-2017 Maqprint
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://pear.php.net/package/PackageName
 * @since      N.A
 * @deprecated N.A
 */

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';

use puppets\Generate;

$host     = $app['db.options']['host'];
$user     = $app['db.options']['user'];
$password = $app['db.options']['password'];
$database = $app['db.options']['dbname'];

$array = explode( ':', $argv[1] );

$action    = $array[0];
$namespace = $array[1];

$path = __DIR__.'/../models/'.$namespace.'';

$generate = new Generate($host, $user, $password, $database);

if(!is_dir($path)) {
    mkdir("../models/".$namespace."", 0777);
    mkdir("../models/".$namespace."/entities", 0777);
}

switch (strtolower($action)) {
    case 'entity':
        $table = $array[2];
        $generate->generateOneEntity($table, $namespace);
        break;

    case 'model':
        $table = $array[2];
        $generate->generateOneModel($table, $namespace);
        break;

    case 'crud':
        $table = $array[2];

        $generate->generateOneEntity($table, $namespace);
        $generate->generateOneModel($table, $namespace);
        $generate->generateOneController($table);
        $generate->generateOneRoute($table);
        $generate->generateViews($table);
        break;

    case 'project':
        $generate->generateOneProject($namespace, $host, $user, $password, $database);
        break;

    case 'entities':
        $generate->generateAllEntities($namespace);
        break;

    case 'controller':
        $table = $array[2];
        $generate->generateOneController($table);
        break;

    case 'route':
        $table = $array[2];
        $generate->generateOneRoute($table);
        break;

    case 'view':
        $table = $array[2];
        $generate->generateViews($table);
        break;

    default:
        throw new Exception('Commande introuvable');
}
