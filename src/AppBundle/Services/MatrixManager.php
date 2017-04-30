<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 29.04.17
 * Time: 1:11
 */

namespace AppBundle\Services;

use AppBundle\Entity\Variant;
use Symfony\Component\DependencyInjection\Container;


class MatrixManager
{

    /**
     * @var Container
     */
    private $container;

    /**
     * StrategyManager constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Variant[] $variants
     * @return array
     */
    public function getEmptyMatrixByVariants($variants): array
    {

        $emptyMatrix = [];
        foreach ($variants as $row => $variant_row) {
            foreach ($variants as $col => $variant_col) {
                $emptyMatrix[$variant_row->getId()][$variant_col->getId()] = 0;
            }
        }

        return $emptyMatrix;
    }

}