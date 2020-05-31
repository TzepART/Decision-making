<?php

namespace AppBundle\Services\Task;


use AppBundle\Entity\Criteria;
use AppBundle\Entity\Task;
use AppBundle\Entity\Variant;
use AppBundle\Model\MatrixModel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class TaskManager
{

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var Container $container
     * */
    protected $container;

    /**
     * @param Container $container
     * @param EntityManager $em
     */
    public function __construct(Container $container, EntityManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Преобразует Task в массив матриц БО
     * @param Task $task
     * @return Variant[]
     */
    public function getTournamentMechanismSolutionByTask(Task $task)
    {
        $arrResultVariants = [];
        $midArrResultVariants = [];

        /** @var Criteria $criterion */
        foreach($task->getCriteria() as $index => $criterion) {
            /** @var MatrixModel $matrixBO */
            $matrixBO = $criterion->getMatrix()->toArray();
            $arVariantSumm = [];
            foreach ($matrixBO as $row_variant_id => $row){
                $variantVal = 0;
                foreach ($row as $col_variant_id => $value){
                    if($row_variant_id != $col_variant_id){
                        if($matrixBO[$col_variant_id][$row_variant_id] == $matrixBO[$row_variant_id][$col_variant_id]){
                            $variantVal += 0.5;
                        }elseif ($matrixBO[$col_variant_id][$row_variant_id] > $matrixBO[$row_variant_id][$col_variant_id]){
                            $variantVal += 0;
                        }elseif ($matrixBO[$col_variant_id][$row_variant_id] < $matrixBO[$row_variant_id][$col_variant_id]){
                            $variantVal += 1;
                        }
                    }
                }
                $arVariantSumm[$row_variant_id] = $variantVal*$criterion->getSignificance();
            }
            //сохраним отсортированный массив с привязкой к критерию
            arsort($arVariantSumm);
            $midArrResultVariants[$criterion->getId()] = $arVariantSumm;
        }

        foreach ($midArrResultVariants as $criteriaId => $midArrResultVariant) {
            foreach ($midArrResultVariant as $variantId => $value) {
                if(isset($arrResultVariants[$variantId])){
                    $arrResultVariants[$variantId] += $value;
                }else{
                    $arrResultVariants[$variantId] = $value;
                }
            }
        }

        //сохраним отсортированный массив с привязкой к критерию
        arsort($arrResultVariants);

        return $arrResultVariants;
    }

    /**
     * @param Task $task
     * @return array
     */
    public function getSolutionByDominationMethod(Task $task)
    {
        $arrResultVariants = [];

        /** @var Criteria $criterion */
        foreach($task->getCriteria() as $index => $criterion) {
            /** @var MatrixModel $matrixBO */
            $matrixBO = $criterion->getMatrix();
            $arMatrixBO = $matrixBO->toArray();
            $arVariantDomination = [];
            foreach ($arMatrixBO as $row_variant_id => $row){
                $dominationResult = true;
                foreach ($row as $col_variant_id => $value){
                    if($row_variant_id != $col_variant_id){
                        if(($arMatrixBO[$col_variant_id][$row_variant_id] > $arMatrixBO[$row_variant_id][$col_variant_id]) ||
                            $arMatrixBO[$row_variant_id][$col_variant_id] == 0
                        ){
                            $dominationResult = false;
                        }
                    }
                }
                if($dominationResult){
                    $arVariantDomination[$row_variant_id] = true;
                }else{
                    $arVariantDomination[$row_variant_id] = false;
                }
            }
            $arrResultVariants[$criterion->getId()] = $arVariantDomination;
        }

        $arrSolution = $this->getSolutions($task, $arrResultVariants);

        return $arrSolution;
    }

    /**
     * @param Task $task
     * @return array
     */
    public function getSolutionByBlockedMethod(Task $task)
    {
        $arrResultVariants = [];

        /** @var Criteria $criterion */
        foreach($task->getCriteria() as $index => $criterion) {
            /** @var MatrixModel $matrixBO */
            $matrixBO = $criterion->getMatrix();
            $arMatrixBO = $matrixBO->toArray();
            $arVariantBlocked = [];
            foreach ($arMatrixBO as $row_variant_id => $row){
                $blockedResult = true;
                foreach ($row as $col_variant_id => $value){
                    if($row_variant_id != $col_variant_id){
                        if($arMatrixBO[$col_variant_id][$row_variant_id] == 1){
                            $blockedResult = false;
                        }
                    }
                }
                if($blockedResult){
                    $arVariantBlocked[$row_variant_id] = true;
                }else{
                    $arVariantBlocked[$row_variant_id] = false;
                }
            }
            $arrResultVariants[$criterion->getId()] = $arVariantBlocked;
        }

        $arrSolution = $this->getSolutions($task, $arrResultVariants);

        return $arrSolution;
    }

    /**
     * @param Task $task
     * @param $arrResultVariants
     * @return array
     */
    protected function getSolutions(Task $task, $arrResultVariants)
    {
        $arrSolution = [];
        $arrSolution['resultsArray'] = $arrResultVariants;
        $variantIds = $task->getVariantIds();
        foreach ($arrResultVariants as $index => $arrResultCriteria) {
            foreach ($arrResultCriteria as $variantId => $result) {
                if (!$result) {
                    if (($key = array_search($variantId, $variantIds)) !== false) {
                        unset($variantIds[$key]);
                    }
                }
            }
        }
        $arrSolution['arSolutions'] = $variantIds;
        return $arrSolution;
    }

}
