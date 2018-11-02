<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/10/2018
 * Time: 12:18
 */

namespace Xirion\DependencyInjector\Rule;

/**
 * Interface RuleInterface
 * @package Xirion\DependencyInjector\Rule
 */
interface RuleInterface
{

    /**
     * @param bool $singleton
     * @return self
     */
    public function setSingleton(bool $singleton = false);

    /**
     * @return bool
     */
    public function isSingleton() : bool;

    /**
     * @param bool $inherit
     * @return self
     */
    public function setInherit(bool $inherit = true);

    /**
     * @return bool
     */
    public function isInherit() : bool;

    /**
     * @param bool $resolve
     * @return self
     */
    public function autoResolve(bool $resolve = true);

    /**
     * @return bool
     */
    public function isAutoResolve() : bool;

    /**
     * @param string $parameterName
     * @param $value
     * @return self
     */
    public function addConstructParameter(string $parameterName, $value);

    /**
     * @param string $parameterName
     * @return self
     */
    public function removeConstructParameter(string $parameterName);

    /**
     * @param string $parameterName
     * @return mixed
     */
    public function getConstructParameter(string $parameterName);

    /**
     * @param string $parameterName
     * @return bool
     */
    public function hasConstructParameter(string $parameterName) : bool;

    /**
     * @param string $methodName
     * @return self
     */
    public function addCallMethod(string $methodName);

    /**
     * @param string $methodName
     * @return self
     */
    public function removeCallMethod(string $methodName);

    /**
     * @param string $methodName
     * @return bool
     */
    public function hasCallMethod(string $methodName) : bool;

}