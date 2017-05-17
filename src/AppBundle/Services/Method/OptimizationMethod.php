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
        $solution = '';
        $n = 50;

        //  Шаг 1. Ввести начальную точку х = (х1, ..., хn) и шаг s, принять F : = J(х).
        //  Шаг 2. Принять hi := s, i = 1, n.
        $arrayX = $request->get('beginX');
        $step = $request->get('step');
        $function_type = $request->get('selectFunction');

        $result = $this->getSolution($function_type, $step, $arrayX, $n);

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
     * @param integer $n
     * @return array
     * @internal param $s
     */
    protected function getSolution($function_type, $step, $arrayX, $n)
    {
        $h = $step;
        $f = [
            'current' => 0,
            'next' => 0,
        ];
        $f['current'] = $this->getFunctionResult($function_type,$arrayX);
        $countX = count($arrayX);


        //  Шаг 3. Принять m := 1.
        for ($m = 1; $m <= $n; $m++) {
            $indexX = ($m % $countX);
            //  Шаг 4. Принять xm := xm + hm вычислить F1=J(x).
            $arrayX = $this->increaseStep($arrayX,$h,$indexX);

            $f['next'] = $this->getFunctionResult($function_type,$arrayX);

            if ($f['next'] < $f['current']) {
                //  Шаг 5. Если F1 ≤ F, принять hm := 3hm, F := F1 и перейти к шагу 7; иначе — перейти к шагу 6.
                $h = 3 * $h;
                $f['current'] = $f['next'];
            } else {
                //  Шаг 6. Принять хm := хm - hm, hm := - 0,5hm.
                $arrayX = $this->decreaseStep($arrayX,$h,$indexX);
                $h = -0.5 * $h;
            }
        }
        return ['X' => $arrayX, 'F' => $f];
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

    /**
     * @param array $arrayX
     * @param float $h
     * @param int $index
     * @return array
     */
    protected function increaseStep($arrayX, $h, $index)
    {
        $arrayX[$index] = $arrayX[$index] + $h;
        return $arrayX;
    }

    /**
     * @param array $arrayX
     * @param float $h
     * @param int $index
     * @return array
     */
    protected function decreaseStep($arrayX,$h, $index)
    {
        $arrayX[$index] = $arrayX[$index] - $h;
        return $arrayX;
    }

}