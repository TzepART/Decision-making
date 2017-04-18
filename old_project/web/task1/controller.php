<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 08.02.17
 * Time: 0:01
 */
use Controllers\FactoryStrategies;

include __DIR__.'/../autoloader.php';


$matrix = isset($_POST['matrix']) ? $_POST['matrix'] : null;
$strategyName = isset($_POST['strategy']) ? $_POST['strategy'] : null;
$coefficient = isset($_POST['coefficient']) ? $_POST['coefficient'] : 0;


/**
 * @param $strategyName
 * @param $matrix
 * @param $coefficient
 */
function getSolution($strategyName, $matrix, $coefficient)
{
    $strategy = FactoryStrategies::getStrategy($strategyName);

    /** @var \Controllers\AbstractStrategy $strategy */
    if ($strategy != null) {
        $result = $strategy->getOptimalSolution($matrix, $coefficient);
        $solution = '';
        $solution .= $strategyName . '<br>';
        $solution .= 'solution ' . $result['solution'] . '<br>';
        $solution .= 'value ' . $result['value'] . '<br>';
        echo \App\AppKernel::getInstance()->getTwig()->render('solution.html.twig', ['solution' => $solution]);
    }
}

if(!empty($matrix) && !empty($strategyName)){

    getSolution($strategyName, $matrix, $coefficient);
}





