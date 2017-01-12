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
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => '',
    'dbname'   => '',
    'user'     => '',
    'password' => '',
    'charset'  => 'utf8mb4',
);

$app['debug'] = true;
