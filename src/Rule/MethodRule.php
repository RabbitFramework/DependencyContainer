<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/11/2018
 * Time: 18:23
 */

namespace Xirion\DependencyInjector\Rule;

use Xirion\Bags\MixedBag;

class MethodRule extends MixedBag implements RuleInterface
{

    public function __construct(array $rules = ['parameters' => []])
    {
        $this->sets($rules);
    }

}