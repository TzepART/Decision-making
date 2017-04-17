<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 22:59
 */
use Composer\Autoload\ClassLoader;
use App\AppKernel;


error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addClassMap([
    'Controllers\FactoryStrategies'=> __DIR__ . '/../src/Controllers/FactoryStrategies.php',
    'Controllers\HurwitzStrategy'=> __DIR__ . '/../src/Controllers/HurwitzStrategy.php',
    'Controllers\BayasLaplasStrategy'=> __DIR__ . '/../src/Controllers/BayasLaplasStrategy.php',
    'Controllers\MinimaxStrategy'=> __DIR__ . '/../src/Controllers/MinimaxStrategy.php',
    'Controllers\SevidgStrategy'=> __DIR__ . '/../src/Controllers/SevidgStrategy.php',
    'Controllers\AbstractStrategy'=> __DIR__ . '/../src/Controllers/AbstractStrategy.php',
    'App\AppKernel'=> __DIR__ . '/../src/AppKernel.php',
]);
$loader->register();
$loader->setUseIncludePath(true);


$twigLoader = new Twig_Loader_Filesystem(__DIR__ . '/../src/Templates');
$twig = new Twig_Environment($twigLoader);

AppKernel::getInstance()->setTwig($twig);