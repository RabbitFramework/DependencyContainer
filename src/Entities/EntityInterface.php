<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 17:52
 */

namespace Rabbit\DependencyInjector\Entities;

use Rabbit\DependencyInjector\Entities\Information\EntityInformationInterface;

interface EntityInterface
{

    public function getInformation() : EntityInformationInterface;

    public function setRule(array $rule);

    public function getRule();

}