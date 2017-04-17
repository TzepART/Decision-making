<?php
include __DIR__.'/../autoloader.php';

echo App\AppKernel::getInstance()->getTwig()->render('task2.html.twig');