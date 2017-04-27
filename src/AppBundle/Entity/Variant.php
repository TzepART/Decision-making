<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.17
 * Time: 19:17
 */

namespace AppBundle\Entity;


class Variant
{

    private $name;


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}