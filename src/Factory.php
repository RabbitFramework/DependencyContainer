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

    /**
     * @return RuleInterface
     * @throws \Xirion\Bags\Exceptions\BagException
     * @throws \Xirion\Bags\Exceptions\BagNotFoundException
     */
    public static function makeRule(bool $singleton = false, bool $inherit = true, bool $autoResolve = true, array $constructParameters = [], array $call = []) : RuleInterface {
        return new Rule($singleton, $inherit, $autoResolve, $constructParameters, $call);
    }

}