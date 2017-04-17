<?php
include __DIR__.'/autoloader.php';

echo App\AppKernel::getInstance()->getTwig()->render('main.html.twig');