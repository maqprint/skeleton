<?php
/**
 *
 * PHP Version 7
 *
 * @category   Puppets
 * @package    Generate
 * @author     Simon Richard <richards@maqprint.fr>
 * @copyright  2016-2017 Maqprint
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @since      N.A
 * @deprecated N.A
 */
namespace puppets;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../config/config.php';

/**
 *
 * PHP Version 7
 *
 * @category   Puppets
 * @package    Generate
 * @author     Simon Richard <richards@maqprint.fr>
 * @copyright  2016-2017 Maqprint
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://pear.php.net/package/PackageName
 * @since      N.A
 * @deprecated N.A
 */
class Generate
{

    /**
     *Function __construct
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     *
     * @return void
     */
    public function __construct($host, $user, $password, $database) {
        $this->host     = $host;
        $this->user     = $user;
        $this->password = $password;
        $this->database = $database;
        $this->charset  = "UTF8";

        $mysqli = new \mysqli($host, $user, $password, $database);

        if ($mysqli->connect_errno) {
            throw new \Exception("Echec lors de la connexion à MySQL : ".$mysqli->connect_error, 1);
        }

            $this->db = $mysqli;
    }


    /**
     * Function getField
     *
     * @param string $table 'nom de la table dans la base de donnée'
     * @return array $res 'tableau contenant les champs de la table
     */
    public function getField($table) {
        $res = $this->db->query("describe `$table`");
        if ($res->num_rows < 1){
            throw new \Exception("Merci de saisir une table existante dans votre base de donnée", 1);
        }

        return $res->fetch_all();
    }


    /**
     * Function getInfos
     *
     * @param string $table 'nom de la table dans la base de donnée'
     * @return array
     */
    public function getInfos($table) {
        $className = $table;
        $className = str_replace('_', ' ', $className);
        $className = ucwords($className);
        $className = str_replace(' ', '', $className);
        $className = ucfirst($className);

        return array(
            'objectName' => $table,
            'className' => $className
        );
    }


    /**
     * Function generateOneController
     *
     * @param string $table "nom de la table dans la base de donnée : tables"
     *
     * @return void
     */
    public function generateOneController($table) {
        $res        = $this->getField($table);
        $arrayInfo  = $this->getInfos($table);
        $colId      = $res[0][0];
        $objectName = $arrayInfo['objectName'];
        $className  = $arrayInfo['className'];

        $controller  = "<?php\n";
        $controller .= "/**\n";
        $controller .= " *\n";
        $controller .= " * PHP Version 7\n";
        $controller .= " *\n";
        $controller .= " * @category   N.A\n";
        $controller .= " * @package    N.A\n";
        $controller .= " * @author     Simon Richard <richards@maqprint.fr>\n";
        $controller .= " * @copyright  2016-2017 Maqprint\n";
        $controller .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $controller .= " * @link       http://pear.php.net/package/PackageName\n";
        $controller .= " * @see        N.A\n";
        $controller .= " * @since      N.A\n";
        $controller .= " * @deprecated N.A\n";
        $controller .= " */\n";
        $controller .= "namespace controllers;\n\n";
        $controller .= "use Silex\Application;\n";
        $controller .= "use Symfony\Component\HttpFoundation\Request;\n";
        $controller .= "use Symfony\Component\HttpFoundation\Response;\n";
        $controller .= "use models\\".$className.";\n\n";
        $controller .= "/**\n";
        $controller .= " *\n";
        $controller .= " * PHP Version 7\n";
        $controller .= " *\n";
        $controller .= " * @category   N.A\n";
        $controller .= " * @package    N.A\n";
        $controller .= " * @author     Simon Richard <richards@maqprint.fr>\n";
        $controller .= " * @copyright  2016-2017 Maqprint\n";
        $controller .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $controller .= " * @link       http://pear.php.net/package/PackageName\n";
        $controller .= " * @see        N.A\n";
        $controller .= " * @since      N.A\n";
        $controller .= " * @deprecated N.A\n";
        $controller .= " */\n";
        $controller .= "class ".$className."Controller extends HomeController\n{\n";

        $controller .= "    /**\n";
        $controller .= "     *Function index\n";
        $controller .= "     *\n";
        $controller .= "     * @param Application \$app '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function index(Application \$app) {\n";
        $controller .= "        \$array_".$objectName." = \$app['models.".$objectName."']->getAll();\n";
        $controller .= "        return \$app['twig']->render('$objectName/index.html.twig', array('array_".$objectName."' => \$array_".$objectName."));\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function show\n";
        $controller .= "     *\n";
        $controller .= "     * @param integer     \$".$colId." '\n";
        $controller .= "     * @param Application \$app    '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function show(\$".$colId.", Application \$app) {\n";
        $controller .= "         \$".$objectName." = \$app['models.".$objectName."']->getById(\$".$colId.");\n";
        $controller .= "        return \$app['twig']->render('$objectName/show.html.twig', array('$objectName' => $".$objectName."));\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function new\n";
        $controller .= "     *\n";
        $controller .= "     * @param Application \$app '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function new(Application \$app) {\n";
        $controller .= "        return \$app['twig']->render('$objectName/new.html.twig');\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function create\n";
        $controller .= "     *\n";
        $controller .= "     * @param Request     \$request '\n";
        $controller .= "     * @param Application \$app     '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function create(Request \$request, Application \$app) {\n";
        $controller .= "        \$params = \$request->request->all();\n";
        $controller .= "        \$app['models.".$objectName."']->insert(\$params);\n";
        $controller .= "         return \$app->redirect('/".$objectName."');\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function edit\n";
        $controller .= "     *\n";
        $controller .= "     * @param integer     \$".$colId." '\n";
        $controller .= "     * @param Application \$app    '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function edit($".$colId.", Application \$app) {\n";
        $controller .= "        \$".$objectName." = \$app['models.".$objectName."']->getById(\$".$colId.");\n";
        $controller .= "        return \$app['twig']->render('$objectName/edit.html.twig', array('$objectName' => $".$objectName."));\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function update\n";
        $controller .= "     *\n";
        $controller .= "     * @param integer     \$".$colId."  '\n";
        $controller .= "     * @param Request     \$request '\n";
        $controller .= "     * @param Application \$app     '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function update($".$colId.", Request \$request, Application \$app) {\n";
        $controller .= "        \$params = \$request->request->all();\n";
        $controller .= "        \$app['models.".$objectName."']->update(\$params, $".$colId.");\n";
        $controller .= "         return \$app->redirect('/".$objectName."');\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function delete\n";
        $controller .= "     *\n";
        $controller .= "     * @param integer     \$".$colId." '\n";
        $controller .= "     * @param Application \$app    '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function delete($".$colId.", Application \$app) {\n";
        $controller .= "        \$app['models.".$objectName."']->delete($".$colId.");\n";
        $controller .= "        return \$app->redirect('/".$objectName."');\n";
        $controller .= "    }\n";
        $controller .= "}\n";

        $filename = "../controllers/".$className."Controller.php";
        if (file_exists($filename)) {
            echo "*\n";
            echo "* Le fichier $filename existe déjà.\n";
            echo "*\n";
            echo "* Voulez vous le regénérer ?(y/n) : ";
            $input = fgets(STDIN);
            $input = substr($input, 0, -1);
            if($input == "n" || $input == "N"){
                echo "* Le fichier n'a pas été regénéré ! \n";
                echo "*\n";
                echo "*****************************************************\n";
                return false;
            }
        }

        if($file = fopen('../controllers/'.$className."Controller.php", "w+")) {
            if(!fwrite($file, $controller)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Le fichier controllers\\".$className."Controller.php à été créé avec succès\n";
        echo "*\n";
        echo "*\n";

    }


    /**
     * function generateAllEntities
     *
     * @param string $namespace 'nom de la table dans la base de donnée'
     *
     * @return void
     */
    public function generateAllEntities($namespace) {
        $resutlt = $this->db->query("show tables");
        $res     = $resutlt->fetch_all();
        foreach ($res as $row) {
            $this->generateOneEntity($row[0], $namespace);
        }
    }


    /**
     * Function generateOneEntity
     *
     * @param string $table     'nom de la table dans la base de donnée'
     * @param string $namespace 'nom de la table dans la base de donnée'
     * @return false
     */
    public function generateOneEntity($table, $namespace) {
        $res       = $this->getField($table);
        $arrayInfo = $this->getInfos($table);

        $objectName = $arrayInfo['objectName'];
        $className  = $arrayInfo['className'];

        $class  = "<?php\n";
        $class .= "/**\n";
        $class .= " *\n";
        $class .= " * PHP Version 7\n";
        $class .= " *\n";
        $class .= " * @category   N.A\n";
        $class .= " * @package    N.A\n";
        $class .= " * @author     Simon Richard <richards@maqprint.fr>\n";
        $class .= " * @copyright  2016-2017 Maqprint\n";
        $class .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $class .= " * @link       http://pear.php.net/package/PackageName\n";
        $class .= " * @see        N.A\n";
        $class .= " * @since      N.A\n";
        $class .= " * @deprecated N.A\n";
        $class .= " */\n";
        $class .= "namespace $namespace\\entities;\n\n";
        $class .= "use Doctrine\DBAL\Connection;\n\n";

        $class .= "/**\n";
        $class .= " *\n";
        $class .= " * PHP Version 7\n";
        $class .= " *\n";
        $class .= " * @category   N.A\n";
        $class .= " * @package    N.A\n";
        $class .= " * @author     Simon Richard <richards@maqprint.fr>\n";
        $class .= " * @copyright  2016-2017 Maqprint\n";
        $class .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $class .= " * @link       http://pear.php.net/package/PackageName\n";
        $class .= " * @see        N.A\n";
        $class .= " * @since      N.A\n";
        $class .= " * @deprecated N.A\n";
        $class .= " */\n";

        $class .= "class ".$className."\n{\n\n";
        foreach ($res as $row) {
            $class .= "    public $".$row[0].";\n";
        }

        $class .= "\n";
        $class .= "    /**\n";
        $class .= "    *Function __construct\n";
        $class .= "    *\n";
        $class .= "    * @param Connection \$db 'db\n";
        $class .= "    * @return void\n";
        $class .= "    */\n";
        $class .= "    public function __construct(Connection \$db) {\n";
        $class .= "        \$this->db = \$db;\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    *Function getById\n";
        $class .= "    *\n";
        $class .= "    * @param integer \$".$res[0][0]." 'id de la table\n";
        $class .= "    * @return function buildDomainObject\n";
        $class .= "    */\n";
        $class .= "    public function getById(\$".$res[0][0].") {\n";
        $class .= "        \$sql = 'SELECT * FROM $table WHERE ".$res[0][0]." =?';\n";
        $class .= "        \$row = \$this->db->fetchAssoc(\$sql,(array( \$".$res[0][0].")));\n";
        $class .= "        if(\$row){\n";
        $class .= "            return \$this->buildDomainObject(\$row);\n";
        $class .= "        }\n";
        $class .= "        else {\n";
        $class .= "            throw new \Exception('No data matching id ' .  \$".$res[0][0].");\n";
        $class .= "        }\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    *Function insert\n";
        $class .= "    *\n";
        $class .= "    * @param integer \$".$objectName." '\n";
        $class .= "    * @return void\n";
        $class .= "    */\n";
        $class .= "    public function insert($".$objectName.") {\n";
        $class .= "        \$this->db->insert('".$table."', \$".$objectName.");\n";
        $class .= "        return \$this->db->lastInsertId();\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    *Function update\n";
        $class .= "    *\n";
        $class .= "    * @param $className $".$objectName." '\n";
        $class .= "    * @param integer $".$res[0][0]." '\n";
        $class .= "    * @return function buildDomainObject\n";
        $class .= "    */\n";
        $class .= "    public function update($".$objectName.", $".$res[0][0].") {\n";
        $class .= "        \$this->db->update('".$table."', $".$objectName.", array('".$res[0][0]."' => \$".$res[0][0]."));\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    *Function delete\n";
        $class .= "    *\n";
        $class .= "    * @param integer \$".$res[0][0]." 'id de la table\n";
        $class .= "    * @return void\n";
        $class .= "    */\n";
        $class .= "    public function delete(\$".$res[0][0].") {\n";
        $class .= "        \$this->db->delete('".$table."', array('".$res[0][0]."' => \$".$res[0][0]."));\n";
        $class .= "    }\n";

        $class .= "    /**\n";
        $class .= "    *Function buildDomainObject\n";
        $class .= "    *\n";
        $class .= "    * @param array \$row 'id de la table\n";
        $class .= "    * @return function buildDomainObject\n";
        $class .= "    */\n";
        $class .= "    protected function buildDomainObject(array \$row) {\n";
        $class .= "        \$".$objectName." = new \\stdClass;\n\n";

        foreach ($res as $row) {
            $class .= "        \$".$objectName."->".$row[0]." = \$row['$row[0]'];\n";
        }

        $class .= "        return \$".$objectName.";\n";
        $class .= "    }\n";

        $class .= "}\n";

        $filename = "../models/".$namespace."/entities/".$className.".php";
        if (file_exists($filename)) {
            echo "*\n";
            echo "* /!\ Le fichier $filename existe déjà.\n";
            echo "*\n";
            echo "* Voulez vous le regénérer ?(y/n) : ";
            $input = fgets(STDIN);
            $input = substr($input, 0, -1);
            if($input == "n" || $input == "N"){
                echo "* Le fichier n'a pas été regénéré ! \n";
                echo "*\n";
                echo "*****************************************************\n";
                return false;
            }
        } else {
            $register  = "\n";
            $register .= "\$app['models.".$objectName."'] = function (\$app) {\n";
            $register .= "   return new \\$namespace\\".$className."(\$app['db']);\n";
            $register .= "};\n";

            if($file = fopen("../config/common.php", "a")) {
                if(!fwrite($file, $register)) {
                    return false;
                };
                if(!fclose($file)) {
                    return false;
                };
            }
        }

        if($file = fopen(__DIR__."/../".$namespace."/entities/".$className.".php", "w+")) {
            if(!fwrite($file, $class)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Le fichier models\\entities\\$className.php a été créé avec succès  \n";
        echo "*\n";
        echo "*\n";
    }


    /**
     * Function generateOneModel
     *
     * @param string $table     'nom de la table dans la base de donnée'
     * @param string $namespace 'le nom du namespace'
     * @return void
     */
    public function generateOneModel($table, $namespace) {
        $res       = $this->getField($table);
        $arrayInfo = $this->getInfos($table);

        $colId      = $res[0][0];
        $objectName = $arrayInfo['objectName'];
        $className  = $arrayInfo['className'];

        $model  = "<?php\n";
        $model .= "/**\n";
        $model .= " *\n";
        $model .= " * PHP Version 7\n";
        $model .= " *\n";
        $model .= " * @category   N.A\n";
        $model .= " * @package    N.A\n";
        $model .= " * @author     Simon Richard <richards@maqprint.fr>\n";
        $model .= " * @copyright  2016-2017 Maqprint\n";
        $model .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $model .= " * @link       http://pear.php.net/package/PackageName\n";
        $model .= " * @see        N.A\n";
        $model .= " * @since      N.A\n";
        $model .= " * @deprecated N.A\n";
        $model .= " */\n";
        $model .= "namespace $namespace;\n\n";
        $model .= "use Doctrine\DBAL\Connection;\n\n";

        $model .= "/**\n";
        $model .= " *\n";
        $model .= " * PHP Version 7\n";
        $model .= " *\n";
        $model .= " * @category   N.A\n";
        $model .= " * @package    N.A\n";
        $model .= " * @author     Simon Richard <richards@maqprint.fr>\n";
        $model .= " * @copyright  2016-2017 Maqprint\n";
        $model .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $model .= " * @link       http://pear.php.net/package/PackageName\n";
        $model .= " * @see        N.A\n";
        $model .= " * @since      N.A\n";
        $model .= " * @deprecated N.A\n";
        $model .= " */\n";
        $model .= "class ".$className." extends entities\\".$className."\n{\n\n";

        $model .= "    /**\n";
        $model .= "    *Fonction getAll\n";
        $model .= "    *\n";
        $model .= "    * @return array $className \$".$objectName."\n";
        $model .= "    */\n";
        $model .= "    public function getAll() {\n";
        $model .= "        \$result = \$this->db->fetchAll('SELECT * FROM $table');\n";
        $model .= "        foreach (\$result as \$row) {\n";
        $model .= "            \$".$colId." = \$row['".$colId."'];\n";
        $model .= "            \$array_".$objectName."[\$".$colId."] = \$this->getById(\$".$colId.");\n";
        $model .= "        }\n\n";
        $model .= "        return \$array_".$objectName.";\n";
        $model .= "    }\n";
        $model .= "}\n";

        $filename = "../models/".$namespace."/".$className.".php";
        if (file_exists($filename)) {
            echo "*\n";
            echo "* /!\ Le fichier $filename existe déjà.\n";
            echo "*\n";
            echo "* Voulez vous le regénérer ?(y/n) : ";
            $input = fgets(STDIN);
            $input = substr($input, 0, -1);
            if($input == "n" || $input == "N"){
                echo "* Le fichier n'a pas été regénéré ! \n";
                echo "*\n";
                echo "*****************************************************\n";
                return false;
            }
        }

        if($file = fopen("../models/".$namespace."/".$className.".php", "w+")) {
            if(!fwrite($file, $model)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }


        echo "*\n";
        echo "* Le fichier models\\$className.php a été créé avec succès  \n";
        echo "*\n";
        echo "*\n";
    }


     /**
      *Function generateProject
      *
      * @return void
      */
     public function generateOneProject($namespace, $host, $user, $password, $database) {
         $resutlt = $this->db->query("show tables");
         $res     = $resutlt->fetch_all();
         foreach ($res as $row) {
             $this->generateOneEntity($row[0], $namespace);
             $this->generateOneModel($row[0], $namespace);
             $this->generateOneController($row[0]);
             $this->generateOneRoute($row[0]);
             $this->generateViews($row[0]);
         }

     }

    /**
     * Function generateOneRoute
     *
     * @param string $table 'nom de la table dans la base de donnée : tables'
     * @return false
     */
    public function generateOneRoute($table) {
        $res        = $this->getField($table);
        $colId      = $res[0][0];
        $arrayInfo  = $this->getInfos($table);
        $objectName = $arrayInfo['objectName'];
        $className  = $arrayInfo['className'];

        $arg    = "{".$colId."}";
        $route  = "<?php \n";
        $route .= "\$app->get('/".$objectName."','controllers\\".$className."Controller::index')
        ->bind('".$objectName."_index');\n";
        $route .= "\$app->get('/".$objectName."/new','controllers\\".$className."Controller::new')
        ->bind('".$objectName."_new');\n";
        $route .= "\$app->get('/".$objectName."/$arg','controllers\\".$className."Controller::show')
        ->bind('".$objectName."_show');\n";
        $route .= "\$app->get('/".$objectName."/edit/$arg','controllers\\".$className."Controller::edit')
        ->bind('".$objectName."_edit');\n";
        $route .= "\$app->post('/".$objectName."/update/$arg','controllers\\".$className."Controller::update')
        ->bind('".$objectName."_update');\n";
        $route .= "\$app->post('/".$objectName."/create','controllers\\".$className."Controller::create')
        ->bind('".$objectName."_create');\n";
        $route .= "\$app->post('/".$objectName."/delete/$arg','controllers\\".$className."Controller::delete')
        ->bind('".$objectName."_delete');\n";

        $filename = "../routes/".$objectName.".php";
        if (file_exists($filename)) {
            echo "*\n";
            echo "* Le fichier $filename existe déjà.\n";
            echo "*\n";
            echo "* Voulez vous le regénérer ?(y/n) : ";
            $input = fgets(STDIN);
            $input = substr($input, 0, -1);
            if($input == "n" || $input == "N"){
                echo "* Le fichier n'a pas été regénéré ! \n";
                echo "*\n";
                echo "*****************************************************\n";
                return false;
            }
        }

        if($file = fopen('../routes/'.$objectName.'.php', "w+")) {
            if(!fwrite($file, $route)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        if($file = fopen('../routes/routes.php', "a")) {
            if(!fwrite($file, "include __DIR__.'/".$objectName.".php'; \n")) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Les fichiers routes\\".$objectName.".php a été créé avec succès\n";
        echo "*\n";
        echo "*\n";
    }

    /**
     * Function generateViews
     *
     * @param string $table '
     * @return void
     */
    public function generateViews($table) {
        $res        = $this->getField($table);
        $colId      = $res[0][0];
        $arrayInfo  = $this->getInfos($table);
        $objectName = $arrayInfo['objectName'];
        $className  = $arrayInfo['className'];

        if (is_dir("../views/".$objectName."") == false) {
            mkdir("../views/".$objectName."", 0775);
        }

        $index  = "";
        $index .= "{% extends \"layout.html.twig\" %}\n";

        $index .= "{% block title %}Index{% endblock %}\n";

        $index .= "{% block content %}\n";
        $index .= "<div class=\"row\">\n";
        $index .= "    <div class=\"col-xs-12\">\n";
        $index .= "        <div class=\"box\">\n";
        $index .= "            <div class=\"box-header\">\n";
        $index .= "                  <h3 class=\"box-title\">Striped Full Width Table</h3>\n";
        $index .= "            </div>\n";
        $index .= "                <!-- /.box-header -->\n";
        $index .= "            <div class=\"box-body no-padding\">\n";
        $index .= "                <table class=\"table table-striped\">\n";
        $index .= "                    <tbody>\n";
        $index .= "                        <tr>\n";
        foreach ($res as $row) {
            $index .= "                               <th>".$row[0]."</th>\n";
        }

        $index .= "                        </tr>\n";
        $index .= "                        {% for $objectName in array_".$objectName." %}\n";
        $index .= "                            <tr>\n";
        foreach ($res as $row) {
            $index .= "                               <td>\n";
            $index .= "                                   <a href=\"{{ path('".$objectName."_show',
                {'".$colId."' : $objectName.$colId})}}\">{{ $objectName.$row[0] }}</a>\n";
            $index .= "                               </td>\n";
        }

        $index .= "                            </tr>\n";
        $index .= "                        {% endfor %}\n";
        $index .= "                    </tbody>\n";
        $index .= "                </table>\n";
        $index .= "            </div>\n";
        $index .= "            <!-- /.box-body -->\n";
        $index .= "        </div>\n";
        $index .= "        <a href=\"{{path('".$objectName."_new')}}\" class=\"btn btn-primary btn-block margin-bottom\">Nouveau ".$objectName."</a>";
        $index .= "    </div>\n";
        $index .= "</div>\n";
        $index .= "{% endblock %}\n";

        if($file = fopen('../views/'.$objectName."/index.html.twig", "w+")) {
            if(!fwrite($file, $index)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        $show  = "";
        $show .= "{% extends \"layout.html.twig\" %}\n";
        $show .= "{% block title %}Index{% endblock %}\n";
        $show .= "{% block content %}\n";
        $show .= "<div class=\"row\">\n";
        $show .= "    <div class=\"col-xs-12\">\n";
        $show .= "        <div class=\"box\">\n";
        $show .= "            <div class=\"box-header\">\n";
        $show .= "                  <h3 class=\"box-title\">Striped Full Width Table</h3>\n";
        $show .= "            </div>\n";
        $show .= "                <!-- /.box-header -->\n";
        $show .= "            <div class=\"box-body no-padding\">\n";
        $show .= "                <table class=\"table table-striped\">\n";
        $show .= "                    <tbody>\n";
        $show .= "                        <tr>\n";
        foreach ($res as $row) {
            $show .= "                               <th>".$row[0]."</th>\n";
        }

        $show .= "                        </tr>\n";
        $show .= "                            <tr>\n";
        foreach ($res as $row) {
            $show .= "                               <td>\n";
            $show .= "                                   {{ $objectName.$row[0] }}\n";
            $show .= "                               </td>\n";
        }

        $show .= "                            </tr>\n";
        $show .= "                    </tbody>\n";
        $show .= "                </table>\n";
        $show .= "            </div>\n";
        $show .= "            <!-- /.box-body -->\n";
        $show .= "        </div>\n";
        $show .= "        <a href=\"{{path('".$objectName."_edit',
            {'".$res[0][0]."' : ".$objectName.".".$res[0][0]." })}}\"
            class=\"btn btn-primary btn-block margin-bottom\">Editer $objectName</a>";
        $show .= "        <form method=\"post\" action=\"{{path('".$objectName."_delete',
            {'".$res[0][0]."' : ".$objectName.".".$res[0][0]." })}}\">\n";
        $show .= "                    <button type=\"submit\"
        class=\"btn btn-danger btn-block btn-flat\">Supprimer $objectName </button>";
        $show .= "        </form>";
        $show .= "    </div>\n";
        $show .= "</div>\n";
        $show .= "{% endblock %}\n";

        if($file = fopen('../views/'.$objectName."/show.html.twig", "w+")) {
            if(!fwrite($file, $show)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        $new  = "";
        $new .= "{% extends \"layout.html.twig\" %}\n";
        $new .= "{% block title %}Index{% endblock %}\n";
        $new .= "{% block content %}\n";
        $new .= "<div class=\"row\">\n";
        $new .= "    <div class=\"col-xs-12\">\n";
        $new .= "        <form method=\"post\" action=\"{{path('".$objectName."_create')}}\">\n";

        foreach ($res as $row) {
            $new .= "            <div class=\"form-group has-feedback\">\n";
            $new .= "                <input type=\"text\" name=\"$row[0]\" class=\"form-control\" placeholder=\"$row[0]\">\n";
            $new .= "            </div>\n";
        }

        $new .= "            <div class=\"row\">\n";
        $new .= "                <div class=\"col-xs-5\">\n";
        $new .= "                    <button type=\"submit\" class=\"btn btn-primary btn-block btn-flat\">Valider</button>\n";
        $new .= "                </div>\n";
        $new .= "            </div>\n";
        $new .= "        </form>\n";
        $new .= "    </div>\n";
        $new .= "</div>\n";
        $new .= "{% endblock %}\n";

        if($file = fopen('../views/'.$objectName."/new.html.twig", "w+")) {
            if(!fwrite($file, $new)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        $edit  = "";
        $edit .= "{% extends \"layout.html.twig\" %}\n";
        $edit .= "{% block title %}Index{% endblock %}\n";
        $edit .= "{% block content %}\n";
        $edit .= "<div class=\"row\">\n";
        $edit .= "    <div class=\"col-xs-12\">\n";
        $edit .= "        <form method=\"post\" action=\"{{path('".$objectName."_update', {'".$res[0][0]."' : ".$objectName.".".$res[0][0]." })}}\">\n";

        foreach ($res as $row) {
            $edit .= "            <div class=\"form-group has-feedback\">\n";
            $edit .= "                <input value=\"{{".$objectName.".".$row[0]." }}\" type=\"text\" name=\"$row[0]\" class=\"form-control\" placeholder=\"$row[0]\">\n";
            $edit .= "            </div>\n";
        }

        $edit .= "            <div class=\"row\">\n";
        $edit .= "                <div class=\"col-xs-5\">\n";
        $edit .= "                    <button type=\"submit\" class=\"btn btn-primary btn-block btn-flat\">Valider</button>\n";
        $edit .= "                </div>\n";
        $edit .= "            </div>\n";
        $edit .= "        </form>\n";
        $edit .= "    </div>\n";
        $edit .= "</div>\n";
        $edit .= "{% endblock %}\n";

        if($file = fopen('../views/'.$objectName."/edit.html.twig", "w+")) {
            if(!fwrite($file, $edit)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Les fichiers views\\".$objectName."\***.html.twig ont été créé avec succès\n";
        echo "*\n";
        echo "*\n";
    }
}
