<?php
/**
 *
 * PHP Version 7
 *
 * @category   Controllers
 * @package    Controllers
 * @author     Simon Richard <richards@maqprint.fr>
 * @copyright  2016-2017 Maqprint
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @since      N.A
 * @deprecated N.A
 */
namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * PHP Version 7
 *
 * @category   Controllers
 * @package    Controllers
 * @author     Simon Richard <richards@maqprint.fr>
 * @copyright  2016-2017 Maqprint
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://pear.php.net/package/PackageName
 * @since      N.A
 * @deprecated N.A
 */
class HomeController
{

    public $app;

    /**
     *Fonction index
     *
     * @param Application $app "variable app"

     * @return void
     */
    public function index(Application $app) {
        return $app['twig']->render('layout.html.twig');
    }
}
