<?php

use Shrew\Mazzy\Core\App;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


//==============================================================================
// Initialisation 
//==============================================================================

// Autoloader PSR-4 
require __DIR__ . "/vendor/autoload";

// Racine de l'application
define("APP_ROOT", __DIR__);

// Chargement du *front-controller*
$app = new App;


//==============================================================================
// Configuration 
//==============================================================================

// Logger
//------------------------------------------------------------------------------
// (documentation)[https://github.com/Seldaek/monolog]

$logger = new Logger("mazzy");
$logger->pushHandler(new StreamHandler(Logger::WARNING));
$app->setLogger($logger);

// Application 
//------------------------------------------------------------------------------

$app->set("error.reporting", E_ALL);

// Multilinguisme
//------------------------------------------------------------------------------

$app->set("locale.charset", "UTF-8");
$app->set("locale.default", "fr_FR");
$app->set("locale.allowed", array("en_US"));
$app->set("locale.directory", APP_ROOT . "/locales");

// Template
//------------------------------------------------------------------------------

$app->set("view.public", APP_WWW);
$app->set("view.directory", APP_ROOT . "/templates");
$app->set("view.assets", "/assets");
$app->set("view.extension", ".php");
$app->set("view.default", "default");
$app->set("view.main", "blog");

// Bases de données
//------------------------------------------------------------------------------

$app->setDatabase("main", array(
    "dsn" => "sqlite:" . APP_ROOT . "/var/main.sqlite",
    "user" => null,
    "password" => null,
    "options" => null
));


//==============================================================================
// Routage 
//==============================================================================

/**
 * Exemple de traitements intermediaies avec fonction anonyme
 */
$app->route->hook("*", function($req, $res, $settings) {
    $res->setHeader("X-Env", $req->getEnv());
    $settings->set("foo", "bar");
});

// Definition d'un espace de noms pour les controleurs suivants
$app->route->pushNamespace("Shrew\Blog\Handler");

/**
 * Page d'accueil
 * Route avec classe et methodes
 */
$app->route->get("/", "Home", "indexAction");


//==============================================================================
// Exécution 
//==============================================================================

$app->run();

/**/