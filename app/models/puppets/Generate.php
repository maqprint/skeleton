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
        $colsquery = $this->db->query("describe ".$this->database.".`$table`;");
        if ($colsquery->num_rows > 0) {
            while($column = $colsquery->fetch_assoc()) {
                $columns[] = $column;
            }

            return $columns;
        }

        throw new \Exception("cannot find ".$this->database.".`$table` into the specified database.", 1);
        return false;
    }


    /**
     * Function getInfos
     *
     * @param string $table 'nom de la table dans la base de donnée'
     * @return array
     */
    public function getInfos($table) {
        $class_name = $table;
        $class_name = str_replace('_', ' ', $class_name);
        $class_name = ucwords($class_name);
        $class_name = str_replace(' ', '', $class_name);
        $class_name = ucfirst($class_name);

        return array(
            'objectName' => $table,
            'className' => $class_name
        );
    }


    /**
     * Function generateOneController
     *
     * @param string $table "nom de la table dans la base de donnée : tables"
     *
     * @return void
     */
    public function generateOneController($table, $namespace) {
        $res         = $this->getField($table);
        $arrayInfo   = $this->getInfos($table);
        $colId       = $res[0]['Field'];
        $object_name = $arrayInfo['objectName'];
        $class_name  = $arrayInfo['className'];

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
        $controller .= "use ".$namespace."\\".$class_name.";\n\n";
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
        $controller .= "class ".$class_name."Controller extends HomeController\n{\n";

        $controller .= "    /**\n";
        $controller .= "     *Function index\n";
        $controller .= "     *\n";
        $controller .= "     * @param Application \$app '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function index(Application \$app) {\n";
        $controller .= "        \$array_".$object_name." = ".ucfirst($object_name)."::getAll();\n";
        $controller .= "        return \$app['twig']->render('$object_name/index.html.twig', array('array_".$object_name."' => \$array_".$object_name."));\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function show\n";
        $controller .= "     *\n";
        $controller .= "     * @param integer     \$".$colId." '\n";
        $controller .= "     * @param Application \$app    '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function show(\$".$colId.", Application \$app) {\n";
        $controller .= "         \$".$object_name." = new ".ucfirst($object_name)."(); \n";
        $controller .= "         \$".$object_name." = \$".$object_name."->getById(\$".$colId.");\n";
        $controller .= "        return \$app['twig']->render('$object_name/show.html.twig', array('$object_name' => $".$object_name."));\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function add\n";
        $controller .= "     *\n";
        $controller .= "     * @param Application \$app '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function add(Application \$app) {\n";
        $controller .= "        return \$app['twig']->render('$object_name/new.html.twig');\n";
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
        $controller .= "        $".$object_name." = new ".ucfirst($object_name)."();\n";
        foreach ($res as $row) {
            $controller .= "        \$".$object_name."->".$row[0]." = \$params['$row[0]'];\n";
        }

        $controller .= "        $".$object_name."->save();\n";
        $controller .= "         \$redirect = \$app['url_generator']->generate('".$object_name."_index');\n";
        $controller .= "         return \$app->redirect(\$redirect);\n";
        $controller .= "    }\n\n";

        $controller .= "    /**\n";
        $controller .= "     *Function edit\n";
        $controller .= "     *\n";
        $controller .= "     * @param integer     \$".$colId." '\n";
        $controller .= "     * @param Application \$app    '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function edit($".$colId.", Application \$app) {\n";
        $controller .= "        \$".$object_name." = new ".ucfirst($object_name)."();\n";
        $controller .= "        \$".$object_name." = \$".$object_name."->getById(\$".$colId.");\n";
        $controller .= "        return \$app['twig']->render('$object_name/edit.html.twig', array('$object_name' => $".$object_name."));\n";
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
        $controller .= "       \$params = \$request->request->all();\n";
        $controller .= "        $".$object_name." = new ".ucfirst($object_name)."();\n";
        foreach ($res as $row) {
            $controller .= "        \$".$object_name."->".$row[0]." = \$params['$row[0]'];\n";
        }

        $controller .= "       \$".$object_name."->save();\n";
        $controller .= "         \$redirect = \$app['url_generator']->generate('".$object_name."_index');\n";
        $controller .= "         return \$app->redirect(\$redirect);\n";
        $controller .= "    }\n\n";
        $controller .= "    /**\n";
        $controller .= "     *Function delete\n";
        $controller .= "     *\n";
        $controller .= "     * @param integer     \$".$colId." '\n";
        $controller .= "     * @param Application \$app    '\n";
        $controller .= "     * @return \$app\n";
        $controller .= "     */\n";
        $controller .= "    public function delete($".$colId.", Application \$app) {\n";
        $controller .= "        \$".$object_name."     = new ".ucfirst($object_name)."();\n";
        $controller .= "        \$".$object_name."->id = $".$colId.";\n";
        $controller .= "        \$".$object_name."->delete();\n";
        $controller .= "         \$redirect = \$app['url_generator']->generate('".$object_name."_index');\n";
        $controller .= "         return \$app->redirect(\$redirect);\n";
        $controller .= "    }\n";
        $controller .= "}\n";

        $filename = "../controllers/".$class_name."Controller.php";
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

        if($file = fopen('../controllers/'.$class_name."Controller.php", "w+")) {
            if(!fwrite($file, $controller)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Le fichier controllers\\".$class_name."Controller.php à été créé avec succès\n";
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

        echo "* Voulez vous tout regénérer [All] ? (y/n) : ";
        $input = fgets(STDIN);
        $input = substr($input, 0, -1);
        if($input == "y" || $input == "Y") {
            foreach ($res as $row) {
                $all = true;
                $this->generateOneEntity($row[0], $namespace, $all);
            }
        } else {
            foreach ($res as $row) {
                $this->generateOneEntity($row[0], $namespace);
            }
        }
    }


    /**
     * Function generateOneEntity
     *
     * @param string  $table     'nom de la table dans la base de donnée'
     * @param string  $namespace 'nom du namespace'
     * @param boolean $all       'boolean de regénération total'
     * @return false
     **/
    public function generateOneEntity($table, $namespace, $all = false) {
        $flag_date_columns = false;
        $columns           = $this->getField($table);
        $array_info        = $this->getInfos($table);
        $object_name       = $array_info["objectName"];
        $class_name        = $array_info["className"];

        $columns_count = count($columns);
        for ($c = 0; $c < $columns_count; $c ++) {
            if ($columns[$c]["Key"] == "PRI") {
                $id = $columns[$c]["Field"];
                break;
            }
        }

        $class  = "<?php\n";
        $class .= "/**\n";
        $class .= " * PHP Version 7\n";
        $class .= " *\n";
        $class .= " * @file       $class_name.php\n";
        $class .= " * @category   Entities\n";
        $class .= " * @package    ".ucfirst($namespace)."\n";
        $class .= " *\n";
        $class .= " * @author     Skeleton MAQPRINT <devs@maqprint.fr>\n";
        $class .= " * @copyright  2016-2017 Maqprint\n";
        $class .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $class .= " * @link       https://www.maqprint.fr\n";
        $class .= " *\n";
        $class .= " * @since      N.A\n";
        $class .= " * @deprecated N.A\n";
        $class .= " **/\n";
        $class .= "namespace $namespace\\entities;\n\n";
        $class .= "use Doctrine\DBAL\Connection;\n\n";

        $class .= "/**\n";
        $class .= " * @class      $class_name\n";
        $class .= " * @brief      $class_name manipulation class.\n";
        $class .= " * @details    Provides different methods for $class_name manipulation.\n";
        $class .= " *\n";
        $class .= " * @category   Entities\n";
        $class .= " * @package    ".ucfirst($namespace)."\n";
        $class .= " *\n";
        $class .= " * @author     Skeleton MAQPRINT <devs@maqprint.fr>\n";
        $class .= " * @copyright  2016-2017 Maqprint\n";
        $class .= " * @license    http://www.php.net/license/3_01.txt  PHP License 3.01\n";
        $class .= " * @link       https://www.maqprint.fr\n";
        $class .= " *\n";
        $class .= " * @see        N.A\n";
        $class .= " * @since      N.A\n";
        $class .= " * @deprecated N.A\n";
        $class .= " **/\n";

        $class .= "class ".$class_name."\n{\n\n";
        for ($c = 0; $c < $columns_count; $c ++) {
            $class .= "    public $".$columns[$c]["Field"].";\n";
        }

        $class .= "\n";
        $class .= "    protected static \$db;\n";
        $class .= "    protected static \$database = '$this->database';\n\n";

        $class .= "    /**\n";
        $class .= "    * @function __construct()\n";
        $class .= "    * @brief    Create a new $class_name object.\n";
        $class .= "    * @details  Create a new $class_name object.\n";
        $class .= "    *\n";
        $class .= "    * @param int \$$id 'id of $class_name object'\n";
        $class .= "    *\n";
        $class .= "    * @return <boolean>\n";
        $class .= "    *\n";
        $class .= "    * @access public\n";
        $class .= "    **/\n";
        $class .= "    public function __construct(\$$id = null) {\n";
        $class .= "        self::\$db = \$this->db();\n";
        $class .= "        self::\$database = \$this->database();\n\n";

        for ($c = 0; $c < $columns_count; $c ++) {
            if ($columns[$c]["Key"] == "PRI") {
                $class .= "        \$this->".$columns[$c]["Field"]." = \$$id;\n";
            } elseif (strtolower($columns[$c]["Null"]) == "no") {
                preg_match_all("/^([a-z]+)\(?([0-9]+)?\)?$/i", strtolower($columns[$c]["Type"]), $result);

                switch($result[1][0]) {
                    default:
                    case "char":
                    case "varchar":
                    case "tinytext":
                    case "text":
                    case "mediumtext":
                    case "longtext":
                    case "binary":
                    case "varbinary":
                    case "tinyblob":
                    case "mediumblob":
                    case "blob":
                    case "longblobv":
                    case "enum":
                    case "geometry":
                    case "point":
                    case "linestring":
                    case "polygon":
                    case "multipoint":
                    case "multilinestring":
                    case "multipolygon":
                    case "geometrycollection":
                    case "json":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = '".$columns[$c]["Default"]."';\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;

                    case "tinyint":
                    case "smallint":
                    case "mediumint":
                    case "int":
                    case "bigint":
                    case "decimal":
                    case "float":
                    case "double":
                    case "real":
                    case "bit":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = ".$columns[$c]["Default"].";\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;

                    case "boolean":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = ".$columns[$c]["Default"].";\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;

                    case "year":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = '".$columns[$c]["Default"]."';\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;

                    case "date":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = '".$columns[$c]["Default"]."';\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;

                    case "time":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = '".$columns[$c]["Default"]."';\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;

                    case "datetime":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = '".$columns[$c]["Default"]."';\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;

                    case "timestamp":
                        if ($columns[$c]["Default"] != "") {
                            $class .= "        \$this->".$columns[$c]["Field"]." = '".$columns[$c]["Default"]."';\n";
                        } else {
                            $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
                        }
                        break;
                }
            } else {
                $class .= "        \$this->".$columns[$c]["Field"]." = null;\n";
            }
        }

        $class .= "\n";
        $class .= "        if(\$this->$id !== null) {\n";
        $class .= "            \$row = self::\$db->fetchAssoc(\"SELECT * FROM \".self::\$database.\".`$table` WHERE id=:id\", array(\"$id\" => \$this->$id));\n";
        $class .= "            if (count(\$row) > 0) {\n";
        for ($c = 0; $c < $columns_count; $c ++) {
            $class .= "                \$this->".$columns[$c]["Field"]." = \$row[\"".$columns[$c]["Field"]."\"];\n";
        }

        $class .= "            } else {\n";
        $class .= "                throw new \Exception(\"`$table` loading failed : there no ".$this->database.".`$table` of id : \$this->$id.\");\n";
        $class .= "            }\n";
        $class .= "        }\n\n";
        $class .= "        return \$this;\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    * @function db()\n";
        $class .= "    * @brief    Get the Silex® \$app['db'] object.\n";
        $class .= "    * @details  Get the Silex® \$app['db'] object.\n";
        $class .= "    *\n";
        $class .= "    * @return <boolean>\n";
        $class .= "    *\n";
        $class .= "    * @static\n";
        $class .= "    **/\n";
        $class .= "    protected static function db() {\n";
        $class .= "        global \$app;\n\n";
        $class .= "        if(self::\$db === null) {\n";
        $class .= "            self::\$db = \$app[\"db\"];\n";
        $class .= "        }\n\n";
        $class .= "        return self::\$db;\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    * @function database()\n";
        $class .= "    * @brief    Get the name of database used.\n";
        $class .= "    * @details   Get the name of database used.\n";
        $class .= "    *\n";
        $class .= "    * @return <boolean>\n";
        $class .= "    *\n";
        $class .= "    * @static\n";
        $class .= "    **/\n";
        $class .= "    protected static function database() {\n";
        $class .= "        return self::\$database;\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    * @function save()\n";
        $class .= "    * @brief    save an $table object.\n";
        $class .= "    * @details  save an $table object.\n";
        $class .= "    *\n";
        $class .= "    * @return <boolean>\n";
        $class .= "    *\n";
        $class .= "    * @access public\n";
        $class .= "    **/\n";
        $class .= "    public function save() {\n";
        $class .= "        $".$object_name."_data = array(\n";
        for ($c = 0; $c < $columns_count; $c ++) {
            switch(strtolower($columns[$c]["Field"])) {
                default:
                    $class .= "           \"".$columns[$c]["Field"]."\" => \$this->".$columns[$c]["Field"].",\n";
                    break;

                case "date_add":
                case "date_edit":
                    $flag_date_columns = true;
                    $class .= "           \"".$columns[$c]["Field"]."\" => date(\"Y-m-d H:i:s\"),\n";
                    break;
            }
        }

        $class .= "        );\n\n";

        $class .= "        if(\$this->id) { // si c'est un update\n";
        if ($flag_date_columns === true) {
            $class .= "            unset($".$object_name."_data[\"date_add\"]);\n\n";
        }
        $class .= "            self::\$db->update(self::\$database.\".`$table`\", \$".$object_name."_data, array(\"$id\" => \$this->id));\n";
        $class .= "        } else {\n";
        $class .= "            self::\$db->insert(self::\$database.\".`$table`\", \$".$object_name."_data);\n";
        $class .= "            \$this->id = self::\$db->lastInsertId();\n\n";
        $class .= "            return \$this->id;\n";
        $class .= "        }\n";
        $class .= "    }\n\n";

        $class .= "    /**\n";
        $class .= "    * @function delete()\n";
        $class .= "    * @brief    delete an $table object.\n";
        $class .= "    * @details  delete an $table object.\n";
        $class .= "    *\n";
        $class .= "    * @param integer $id the id of $table object to delete\n";
        $class .= "    *\n";
        $class .= "    * @return <boolean>\n";
        $class .= "    *\n";
        $class .= "    * @static\n";
        $class .= "    **/\n";
        $class .= "    public static function delete(\$id = null) {\n";
        $class .= "        self::\$db->delete(self::\$database.\".`$table`\", array(\"$id\" => \$id));\n";
        $class .= "\n\n";
        $class .= "        return true;\n";
        $class .= "    }\n";
        $class .= "}\n";

        if ($all != true) {
            $filename = "../models/".$namespace."/entities/".$class_name.".php";
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
        }

        if($file = fopen(__DIR__."/../".$namespace."/entities/".$class_name.".php", "w+")) {
            if(!fwrite($file, $class)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Le fichier models\\entities\\$class_name.php a été créé avec succès  \n";
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

        $colId  = $res[0]['Field'];
        $object_name = $arrayInfo['objectName'];
        $class_name  = $arrayInfo['className'];

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
        $model .= "class ".$class_name." extends entities\\".$class_name."\n{\n\n";

        $model .= "    /**\n";
        $model .= "    *Fonction __construct\n";
        $model .= "    *\n";
        $model .= "    * @return this\n";
        $model .= "    */\n";
        $model .= "    public function __construct(\$id) {\n";
        $model .= "        parent::__construct(\$id);\n\n";
        $model .= "        return \$this;\n";
        $model .= "    }\n\n";

        $model .= "    /**\n";
        $model .= "    *Fonction getAll\n";
        $model .= "    *\n";
        $model .= "    * @return array $class_name \$".$object_name."\n";
        $model .= "    */\n";
        $model .= "    public static function getAll() {\n";
        $model .= "        \$result = self::db()->fetchAll('SELECT * FROM $table');\n";
        $model .= "        foreach (\$result as \$row) {\n";
        $model .= "            \$".$colId." = \$row['".$colId."'];\n";
        $model .= "            $".$object_name." = new ".ucfirst($object_name)."();\n";
        $model .= "            \$array_".$object_name."[\$".$colId."] = $".$object_name."->getById(\$".$colId.");\n";
        $model .= "        }\n\n";
        $model .= "        return \$array_".$object_name.";\n";
        $model .= "    }\n";
        $model .= "}\n";

        $filename = "../models/".$namespace."/".$class_name.".php";
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

        if($file = fopen("../models/".$namespace."/".$class_name.".php", "w+")) {
            if(!fwrite($file, $model)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }


        echo "*\n";
        echo "* Le fichier models\\$class_name.php a été créé avec succès  \n";
        echo "*\n";
        echo "*\n";
    }


    /**
     *Function generateProject
     *
     *@param string $namespace 'nom du namespace'
     *@param string $host      'nom du serveur de la base de donnée'
     *@param string $user      'nom de l'utilisateur'
     *@param string $password  'mot de passe de connexion à la base de donnée'
     *@param string $database  'nom de la base de donnée'
     *
     * @return void
     */
    public function generateOneProject($namespace, $host, $user, $password, $database) {
        $resutlt = $this->db->query("show tables");
        $res     = $resutlt->fetch_all();
        foreach ($res as $row) {
            $this->generateOneEntity($row[0], $namespace);
            $this->generateOneModel($row[0], $namespace);
            $this->generateOneController($row[0], $namespace);
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
        $res         = $this->getField($table);
        $colId       = $res[0]['Field'];
        $arrayInfo   = $this->getInfos($table);
        $object_name = $arrayInfo['objectName'];
        $class_name  = $arrayInfo['className'];

        $arg    = "{".$colId."}";
        $route  = "<?php \n";
        $route .= "\$app->get('/".$object_name."','controllers\\".$class_name."Controller::index')
        ->bind('".$object_name."_index');\n";
        $route .= "\$app->get('/".$object_name."/add','controllers\\".$class_name."Controller::add')
        ->bind('".$object_name."_add');\n";
        $route .= "\$app->get('/".$object_name."/$arg','controllers\\".$class_name."Controller::show')
        ->bind('".$object_name."_show');\n";
        $route .= "\$app->get('/".$object_name."/edit/$arg','controllers\\".$class_name."Controller::edit')
        ->bind('".$object_name."_edit');\n";
        $route .= "\$app->post('/".$object_name."/update/$arg','controllers\\".$class_name."Controller::update')
        ->bind('".$object_name."_update');\n";
        $route .= "\$app->post('/".$object_name."/create','controllers\\".$class_name."Controller::create')
        ->bind('".$object_name."_create');\n";
        $route .= "\$app->post('/".$object_name."/delete/$arg','controllers\\".$class_name."Controller::delete')
        ->bind('".$object_name."_delete');\n";

        $filename = "../routes/".$object_name.".php";
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

        if($file = fopen('../routes/'.$object_name.'.php', "w+")) {
            if(!fwrite($file, $route)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        if($file = fopen('../routes/routes.php', "a")) {
            if(!fwrite($file, "include __DIR__.'/".$object_name.".php'; \n")) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Les fichiers routes\\".$object_name.".php a été créé avec succès\n";
        echo "*\n";
        echo "*\n";
    }

    /**
     * Function generateViews
     *
     * @param string $table 'nom de la table dans la base de donnée'
     * @return void
     */
    public function generateViews($table) {
        $res         = $this->getField($table);
        $colId       = $res[0]['Field'];
        $arrayInfo   = $this->getInfos($table);
        $object_name = $arrayInfo['objectName'];
        $class_name  = $arrayInfo['className'];

        if (is_dir("../views/".$object_name."") == false) {
            mkdir("../views/".$object_name."", 0775);
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
        $index .= "                        {% for $object_name in array_".$object_name." %}\n";
        $index .= "                            <tr>\n";
        foreach ($res as $row) {
            $index .= "                               <td>\n";
            $index .= "                                   <a href=\"{{ path('".$object_name."_show',
                {'".$colId."' : $object_name.$colId})}}\">{{ $object_name.$row[0] }}</a>\n";
            $index .= "                               </td>\n";
        }

        $index .= "                            </tr>\n";
        $index .= "                        {% endfor %}\n";
        $index .= "                    </tbody>\n";
        $index .= "                </table>\n";
        $index .= "            </div>\n";
        $index .= "            <!-- /.box-body -->\n";
        $index .= "        </div>\n";
        $index .= "        <a href=\"{{path('".$object_name."_add')}}\" class=\"btn btn-primary btn-block margin-bottom\">Nouveau ".$object_name."</a>";
        $index .= "    </div>\n";
        $index .= "</div>\n";
        $index .= "{% endblock %}\n";

        if($file = fopen('../views/'.$object_name."/index.html.twig", "w+")) {
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
            $show .= "                                   {{ $object_name.$row[0] }}\n";
            $show .= "                               </td>\n";
        }

        $show .= "                            </tr>\n";
        $show .= "                    </tbody>\n";
        $show .= "                </table>\n";
        $show .= "            </div>\n";
        $show .= "            <!-- /.box-body -->\n";
        $show .= "        </div>\n";
        $show .= "        <a href=\"{{path('".$object_name."_edit',
            {'".$res[0]['Field']."' : ".$object_name.".".$res[0]['Field']." })}}\"
            class=\"btn btn-primary btn-block margin-bottom\">Editer $object_name</a>";
        $show .= "        <form method=\"post\" action=\"{{path('".$object_name."_delete',
            {'".$res[0]['Field']."' : ".$object_name.".".$res[0]['Field']." })}}\">\n";
        $show .= "                    <button type=\"submit\"
        class=\"btn btn-danger btn-block btn-flat\">Supprimer $object_name </button>";
        $show .= "        </form>";
        $show .= "    </div>\n";
        $show .= "</div>\n";
        $show .= "{% endblock %}\n";

        if($file = fopen('../views/'.$object_name."/show.html.twig", "w+")) {
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
        $new .= "        <form method=\"post\" action=\"{{path('".$object_name."_create')}}\">\n";

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

        if($file = fopen('../views/'.$object_name."/new.html.twig", "w+")) {
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
        $edit .= "        <form method=\"post\" action=\"{{path('".$object_name."_update', {'".$res[0]['Field']."' : ".$object_name.".".$res[0]['Field']." })}}\">\n";

        foreach ($res as $row) {
            $edit .= "            <div class=\"form-group has-feedback\">\n";
            $edit .= "                <input value=\"{{".$object_name.".".$row[0]." }}\" type=\"text\" name=\"$row[0]\" class=\"form-control\" placeholder=\"$row[0]\">\n";
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

        if($file = fopen('../views/'.$object_name."/edit.html.twig", "w+")) {
            if(!fwrite($file, $edit)) {
                return false;
            };
            if(!fclose($file)) {
                return false;
            };
        }

        echo "*\n";
        echo "* Les fichiers views\\".$object_name."\***.html.twig ont été créé avec succès\n";
        echo "*\n";
        echo "*\n";
    }
}
