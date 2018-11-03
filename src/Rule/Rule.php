<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/10/2018
 * Time: 16:59
 */

namespace Xirion\DependencyInjector\Rule;

use Xirion\Bags\MixedBag;

/**
 * Class Rule
 *
 * Only singleton parameter work for the moment
 *
 * @since 1.0.0
 * @package Xirion\DependencyInjector\Rule
 */
class Rule extends MixedBag implements RuleInterface
{

    /**
     * Rule constructor.
     * @param bool $singleton
     * @param bool $inherit
     * @param bool $autoResolve
     * @param array $constructParameters
     * @param array $call
     * @throws \Xirion\Bags\Exceptions\BagException
     * @throws \Xirion\Bags\Exceptions\BagNotFoundException
     */
    public function __construct(bool $singleton = false, bool $inherit = true, bool $autoResolve = true, array $constructParameters = [], array $call = [])
    {
        $this->sets([
            'singleton',
            'inherit',
            'autoResolve',
            'constructParameters',
            'call'
        ], [
            $singleton,
            $inherit,
            $autoResolve,
            $constructParameters,
            $call
        ]);
    }

    /**
     * Methods to set
     */
    public function setSingleton(bool $singleton = false) {
        $this->singleton = $singleton;
        return $this;
    }

    /**
     * @param bool $inherit
     * @return $this
     */
    public function setInherit(bool $inherit = true) {
        $this->inherit = $inherit;
        return $this;
    }

    /**
     * @param bool $resolve
     * @return $this
     */
    public function autoResolve(bool $resolve = true)
    {
        $this->autoResolve = $resolve;
        return $this;
    }

    /**
     * @param string $methodName
     * @return $this
     */
    public function addCallMethod(string $methodName) {
        if(!$this->hasCallMethod($methodName)) {
            $this->call[] = $methodName;
            return $this;
        }
    }

    /**
     * @param string $methodName
     * @return $this
     */
    public function removeCallMethod(string $methodName) {
        if($this->hasCallMethod($methodName)) {
            unset($this->call[array_keys($this->call, $methodName)]);
            return $this;
        }
    }

    /**
     * @param string $parameterName
     * @param $value
     */
    public function addConstructParameter(string $parameterName, $value)
    {
        if(!$this->hasConstructParameter($parameterName)) {
            $this->constructParameters[$parameterName] = $value;
            return $this;
        }
    }

    /**
     * @param string $parameterName
     */
    public function removeConstructParameter(string $parameterName) {
        if($this->hasConstructParameter($parameterName)) {
            unset($this->constructParameters[$parameterName]);
            return $this;
        }
    }

    /**
     * @param string $parameterName
     * @return mixed
     */
    public function getConstructParameter(string $parameterName)
    {
        if($this->hasConstructParameter($parameterName)) {
            return $this->constructParameters[$parameterName];
        }
    }

    /**
     * @param string $parameterName
     * @return bool
     */
    public function hasConstructParameter(string $parameterName) : bool {
        return isset($this->constructParameters[$parameterName]);
    }

    /**
     * Methods to check if has
     */
    public function hasCallMethod(string $methodName) : bool {
        return isset($this->call[array_keys($this->call, $methodName)]);
    }

    /**
     * @return bool
     */
    public function isInherit() : bool {
        return $this->inherit === true;
    }

    /**
     * @return bool
     */
    public function isSingleton(): bool {
        return $this->singleton === true;
    }

    /**
     * @return bool
     */
    public function isAutoResolve(): bool {
        return $this->autoResolve === true;
    }

}