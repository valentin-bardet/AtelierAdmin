<?php
session_start();
ini_set("display_errors", 1);

require_once 'vendor/autoload.php';
require_once 'src/mf/utils/AbstractClassLoader.php';
require_once 'src/mf/utils/ClassLoader.php';

$config = parse_ini_file("conf/config.ini");

/* une instance de connexion  */
$db = new \Illuminate\Database\Capsule\Manager();

$db->addConnection( $config ); /* configuration avec nos paramètres */
$db->setAsGlobal();            /* rendre la connexion visible dans tout le projet */
$db->bootEloquent();           /* établir la connexion */
use \appClient\model\Categorie;
use \appClient\model\Produits;

$loader = new \mf\utils\ClassLoader('src');
$loader->register();
mf\view\AbstractView::addStyleSheet('html/style.css');
$router = new \mf\router\Router();

////////////////////////////application client///////////////////////////////////

$router->addRoute('categorie',
    '/categorie/',
    '\appClient\control\clientController',
    'viewCategorie',
    \appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('produits','/produits/','\appClient\control\clientController','viewProduit',
    \appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('login','/login/','\appAdmin\control\AdminController','viewLogin',
    \appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('checklogin',
    '/checklogin/',
    '\appAdmin\control\AdminController',
    'checkLogin',
    \appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('homeProducteur',
    '/homeProducteur/',
    '\appAdmin\control\AdminController',
    'viewProducteurHome',
    \appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('logout',
    '/logout/',
    '\appAdmin\control\AdminController',
    'log_out',
    \appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('commandes','/commandes/','\appAdmin\control\AdminController','viewCommandes',
    \appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('user','/user/','\appClient\control\clientController','viewUser',\appAdmin\auth\AdminAuthentification::ACCESS_LEVEL_NONE);
$router->setDefaultRoute('/categorie/');
$router->run();
