<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 15.05.17
 * Time: 23:21
 */

namespace AppBundle\Services\Method;



use AppBundle\Model\MethodModel\DecisionSolutionModel;
use Symfony\Component\HttpFoundation\Request;

class OptimizationMethod extends AbstractMethod
{
    const METHOD_NAME = 'optimization';
    const SIMPLE_TYPE_FUNCTION = 'simlple_function';
    const ROZENBORK_TYPE_FUNCTION = 'rozenbork_function';
    const ASYMMETRIC_VALLEY_TYPE_FUNCTION = 'asymmetric_valley_function';
    const POWELL_TYPE_FUNCTION = 'powell_function';

    const COUNT_ITERATION = 200;
//    const ASYMMETRIC_VALLEY_TYPE_FUNCTION = 'asymmetric_valley_function';

    /**
     * @param Request $request
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     * Алгоритм GZ1.
     *    Шаг 1. Ввести начальную точку х = (х1, ..., хn) и шаг s, принять F : = J(х).
     *    Шаг 2. Принять hi := s, i = 1, n.
     *    Шаг 3. Принять m := 1.
     *    Шаг 4. Принять xm := xm+ hm вычислить F1=J(x).
     *    Шаг 5. Если F1 ≤ F, принять hm := 3hm, F := F1 и перейти к шагу 7; иначе — перейти к шагу 6.
     *    Шаг 6. Принять хm := хm - hm, hm := - 0,5hm.
     *    Шаг 7. Принять m := m+1. Если m ≤ n, перейти к шагу 4; иначе — к шагу 3.
     */
    public function getOptimalSolution(Request $request, DecisionSolutionModel $decisionSolutionModel)
    {
        $result = [];
        $solution = '';

        //Шаг 1. Ввести начальную точку х = (х1, ..., хn) и шаг s, принять F : = J(х).
        //Шаг 2. Принять hi := s, i = 0, n(COUNT_ITERATION).
        //Шаг 3. Принять m := 0.
        $arrayX = $request->get('beginX');
        $step = $request->get('step');
        $function_type = $request->get('selectFunction');

        foreach ($arrayX as $indexX => $value) {
            $result['X'][$indexX] = $this->getSolutionByX($function_type, $step, $arrayX, $indexX);
        }

        $result['F'] = $this->getFunctionResult($function_type,$result['X']);

        echo "<pre>";
        var_dump($result);
        echo "</pre>";
        die();

        if($solution ==! ''){
            $decisionSolutionModel->setSolution($solution);
        }else{
            $decisionSolutionModel->setError('Нет вариантов, удовлетворяющего, условию метода');
        }

        return $decisionSolutionModel;
    }

    public function getArrayFunctionNames()
    {
        $arFunctions = [
            OptimizationMethod::SIMPLE_TYPE_FUNCTION => 'Квадратичная функция простой структуры',
            OptimizationMethod::ROZENBORK_TYPE_FUNCTION => 'Функция Розенброка',
            OptimizationMethod::ASYMMETRIC_VALLEY_TYPE_FUNCTION => 'Ассиметричная долина',
            OptimizationMethod::POWELL_TYPE_FUNCTION => 'Функция Пауэлла',
        ];

        return $arFunctions;
    }

    /**
     * @param string $function_type
     * @param float $step
     * @param array $arrayX
     * @param integer $indexX
     * @return array
     * @internal param $s
     */
    protected function getSolutionByX($function_type, $step, $arrayX, $indexX)
    {
        $f = [
            'current' => 0,
            'next' => 0,
        ];
        $f['current'] = $this->getFunctionResult($function_type,$arrayX);

        //Шаг 7. Если m ≤ n (COUNT_ITERATION), перейти к шагу 4; иначе — к шагу 3
        for ($m = 0; $m <= self::COUNT_ITERATION; $m++) {
            //  Шаг 4. Принять xm := xm + hm вычислить F1=J(x).
            $arrayX[$indexX] = $arrayX[$indexX] + $step;

            $f['next'] = $this->getFunctionResult($function_type,$arrayX);

            if ($f['next'] <= $f['current']) {
                //  Шаг 5. Если F1 ≤ F, принять hm := 3hm, F := F1 и перейти к шагу 7; иначе — перейти к шагу 6.
                $step = 3 * $step;
                $f['current'] = $f['next'];
            } else {
                //  Шаг 6. Принять хm := хm - hm, hm := - 0,5hm.
                $arrayX[$indexX] = $arrayX[$indexX] - $step;
                $step = -0.5 * $step;
            }
            echo "<pre>";
            var_dump($arrayX);
            echo "</pre>";
        }

        return $arrayX[$indexX];
    }

    protected function getFunctionResult($typeFunction, $arrayX){
        switch ($typeFunction){
            case self::SIMPLE_TYPE_FUNCTION:
                return $this->simpleFunction($arrayX);
            case self::ROZENBORK_TYPE_FUNCTION:
                return $this->rozenborkFunction($arrayX);
            case self::ASYMMETRIC_VALLEY_TYPE_FUNCTION:
                return $this->asymmetricValleyFunction($arrayX);
            case self::POWELL_TYPE_FUNCTION:
                return $this->powelFunction($arrayX);
            default:
                return false;
        }
    }


    /**
     * F1(x) = (x1-x2)2+(x1+x2-10)2/9
     * @param array $arrayX
     * @return int
     */
    protected function simpleFunction($arrayX){
        $result = 0;
        $result = ($arrayX[0] - $arrayX[1])**2+(($arrayX[0] + $arrayX[1]-10)**2)/9;
        return $result;
    }

    /**
     * F2 = 100(x12-x2)2+(1-x1)2
     * @param array $arrayX
     * @return int
     */
    protected function rozenborkFunction($arrayX){
        $result = 0;
        $result = 100*($arrayX[0]**2 - $arrayX[1])**2+(1 - $arrayX[0])**2;
        return $result;
    }

    /**
     * F3 = [(x1-3)/100]2-(x2-x1)+exp[20(x2-x1)];
     * @param array $arrayX
     * @return int
     */
    protected function asymmetricValleyFunction($arrayX){
        $result = 0;
        $result = (($arrayX[0]-3)/100)**2-($arrayX[1]-$arrayX[0]+exp(20*($arrayX[1]-$arrayX[0])));
        return $result;
    }

    /**
     * F4 = (x1+10x22)2+5(x3-x4)2+(x2-2x3)4+10(x1-x4)4;
     * @param array $arrayX
     * @return int
     */
    protected function powelFunction($arrayX){
        $result = 0;
        $result = ($arrayX[0] + $arrayX[1]**2)**2+5*($arrayX[2]-$arrayX[3])**2+($arrayX[1]-2*$arrayX[2])**4+10*($arrayX[0]-$arrayX[3])**4;
        return $result;
    }

}