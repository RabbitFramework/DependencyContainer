<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/11/2018
 * Time: 18:23
 */

namespace Xirion\DependencyInjector\Rule;

use Xirion\Bags\MixedBag;

class InstanceRule extends MixedBag implements RuleInterface
{

    /**
     * InstanceRule constructor.
     * @param array $rules
     */
    public function __construct(array $rules = ['singleton' => false,  'call' => [], 'inherit' => false])
    {
        $this->sets($rules);
    }

    /**
     * @param bool $singleton
     * @return $this
     */
    public function setSingleton(bool $singleton = false) {
        $this->singleton = $singleton;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSingleton(): bool {
        return $this->singleton === true;
    }

    /**
     * @param array $methods
     * @return $this
     */
    public function setCallMethods(array $methods = []) {
        $this->call = $methods;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCallMethod() {
        return $this->call;
    }

}