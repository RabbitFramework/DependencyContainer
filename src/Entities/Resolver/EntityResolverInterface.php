<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/11/2018
 * Time: 13:46
 */

namespace Rabbit\DependencyInjector\Entities\Resolver;


interface EntityResolverInterface
{

    public function resolve(array $parameters = []) : array;

    public function get(array $parameters = []);

}