<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29/11/2018
 * Time: 18:04
 */

namespace Rabbit\DependencyInjector;

use Psr\Container\NotFoundExceptionInterface;

class ContainerNotFoundException extends \Exception implements NotFoundExceptionInterface
{

}