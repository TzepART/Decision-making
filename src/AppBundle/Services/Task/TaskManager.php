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
                            $variantVal += 1;
                        }elseif ($matrixBO[$col_variant_id][$row_variant_id] < $matrixBO[$row_variant_id][$col_variant_id]){
                            $variantVal += 0;
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


}
