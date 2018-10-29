<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29/10/2018
 * Time: 19:41
 */

namespace Xirion\DependencyInjector;

use Xirion\DependencyInjector\Rule\Rule;
use Xirion\DependencyInjector\Rule\RuleInterface;

class Factory
{

    public static function makeContainer() : ContainerInterface {
        return new Container();
    }

    public static function makeRule() : RuleInterface {
        return new Rule();
    }

}