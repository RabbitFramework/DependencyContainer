<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29/11/2018
 * Time: 18:04
 */

namespace Xirion\DependencyInjector;


use Psr\Container\NotFoundExceptionInterface;

class DependencyContainerNotFoundException extends \Exception implements NotFoundExceptionInterface
{

}