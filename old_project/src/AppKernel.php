<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 07.03.17
 * Time: 3:09
 */

namespace App;


class AppKernel
{
    /**
     * @var AppKernel
     */
    protected static $instance;

    /**
     * @var \Twig_Environment|null
     */
    private $twig = null;


    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return null|\Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @param null|\Twig_Environment $twig
     */
    public function setTwig($twig)
    {
        $this->twig = $twig;
    }




    protected function __construct()
    {
    }


    private function __clone()
    {
    }


    private function __wakeup()
    {
    }

}