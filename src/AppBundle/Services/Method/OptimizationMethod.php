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
    const EXPERIMENT_TYPE_FUNCTION = 'experiment_function';

    const COUNT_ITERATION = 50;

    /**
     * @param Request $request
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     */

    public function getOptimalSolution(Request $request, DecisionSolutionModel $decisionSolutionModel)
    {
        $result = [];
        $solution = '';

        $arrayX = $request->get('beginX');
        $step = $request->get('step');
        $function_type = $request->get('selectFunction');
        $countIteration = $request->get('countIteration');

        $result['X'] = $this->getSolutionUseGZ1($function_type, $step, $arrayX,$countIteration);
        $result['F'] = $this->getFunctionResult($function_type,$result['X']);

        $solution .= 'X - ('.implode(', ',$result['X']).')</br>';
        $solution .= 'F - ('.$result['F'].')</br>';

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
            OptimizationMethod::EXPERIMENT_TYPE_FUNCTION => 'Экспериментальная функция',
        ];

        return $arFunctions;
    }

    /**
     * @param string $function_type
     * @param float $step
     * @param array $arrayX
     * @param int $countIteration
     * @return array
     * @internal param $s
     * Алгоритм GZ1.
     *    Шаг 1. Ввести начальную точку х = (х1, ..., хn) и шаг s, принять F : = J(х).
     *    Шаг 2. Принять hi := s, i = 1, n.
     *    Шаг 3. Принять m := 1.
     *    Шаг 4. Принять xm := xm+ hm вычислить F1=J(x).
     *    Шаг 5. Если F1 ≤ F, принять hm := 3hm, F := F1 и перейти к шагу 7; иначе — перейти к шагу 6.
     *    Шаг 6. Принять хm := хm - hm, hm := - 0,5hm.
     *    Шаг 7. Принять m := m+1. Если m ≤ n, перейти к шагу 4; иначе — к шагу 3.
     */
    protected function getSolutionUseGZ1($function_type, $step, $arrayX, $countIteration)
    {
        $sz_x = count($arrayX);

        //Шаг 1. Ввести начальную точку х = (х1, ..., хn) и шаг s, принять F : = J(х).
        //Шаг 2. Принять hi := s, i = 0, n(COUNT_ITERATION).
        //Шаг 3. Принять m := 0.

        for ($i = 0; $i <= $sz_x; $i++) {
            $h = array_fill(0,$sz_x, $step);
            for ($n = 0; $n <= $countIteration; $n++) {
                //Шаг 7. Если m ≤ n (COUNT_ITERATION), перейти к шагу 4; иначе — к шагу 3
                for($m = 0; $m < $sz_x; $m++){
                    $oldVal = $this->getFunctionResult($function_type,$arrayX);
                    //  Шаг 4. Принять xm := xm + hm вычислить F1=J(x).
                    $arrayX[$m] = $arrayX[$m] + $h[$m];

                    $newVal = $this->getFunctionResult($function_type,$arrayX);

                    if ($newVal <= $oldVal) {
                        //  Шаг 5. Если F1 ≤ F, принять hm := 3hm, F := F1 и перейти к шагу 7; иначе — перейти к шагу 6.
                        $h[$m] = 3 * $h[$m];
                    } else {
                        //  Шаг 6. Принять хm := хm - hm, hm := - 0,5hm.
                        $arrayX[$m] = $arrayX[$m] - $h[$m];
                        $h[$m] = -0.5 * $h[$m];
                    }
                }

            }
        }
        return $arrayX;
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
            case self::EXPERIMENT_TYPE_FUNCTION:
                return $this->experimentFunction($arrayX);
            default:
                return false;
        }
    }


    /**
     * F1(x) = (x1-x2)2+(x1+x2-10)2/9
     * @param array $arrayX
     * @return float
     */
    protected function simpleFunction($arrayX){
        $result = 0;
        $result = ($arrayX[0] - $arrayX[1])**2+(($arrayX[0] + $arrayX[1]-10)**2)/9;
        return $result;
    }

    /**
     * F2 = 100(x12-x2)2+(1-x1)2
     * @param array $arrayX
     * @return float
     */
    protected function rozenborkFunction($arrayX){
        $result = 0;
        $result = 100*($arrayX[0]**2 - $arrayX[1])**2+(1 - $arrayX[0])**2;
        return $result;
    }

    /**
     * F3 = [(x1-3)/100]2-(x2-x1)+exp[20(x2-x1)];
     * @param array $arrayX
     * @return float
     */
    protected function asymmetricValleyFunction($arrayX){
        $result = 0;
        $result = (($arrayX[0]-3)/100)**2-($arrayX[1]-$arrayX[0])+exp(20*($arrayX[1]-$arrayX[0]));
        return $result;
    }

    /**
     * F4 = (x1+10x22)2+5(x3-x4)2+(x2-2x3)4+10(x1-x4)4;
     * @param array $arrayX
     * @return float
     */
    protected function powelFunction($arrayX){
        $result = 0;
        $result = ($arrayX[0] + $arrayX[1]**2)**2+5*($arrayX[2]-$arrayX[3])**2+($arrayX[1]-2*$arrayX[2])**4+10*($arrayX[0]-$arrayX[3])**4;
        return $result;
    }

    /**
     * @param array $arrayX
     * @return float
     */
    protected function experimentFunction($arrayX)
    {
        $a = [0, 0.428, 1, 1.61, 2.09, 3.48, 5.25];
        $b = [7.391, 11.18, 16.44, 16.20, 22.2, 24.02, 31.32];

        $result = 0;
        for($i = 0;$i<7;$i++){
            $result += ((($arrayX[0]**2+$arrayX[1]**2*$a[$i]+$arrayX[2]*$a[$i]**2)/(1+$arrayX[3]**2*$a[$i])-$b[$i])/$b[$i])**2;
        }
        $result = 10**4*$result;

        return $result;
        
    }

}