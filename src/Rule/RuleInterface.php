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
     * @return mixed
     */
    public function setSingleton(bool $singleton = false);

    /**
     * @return bool
     */
    public function isSingleton() : bool;

    /**
     * @param bool $inherit
     * @return mixed
     */
    public function setInherit(bool $inherit = true);

    /**
     * @return bool
     */
    public function isInherit() : bool;

    /**
     * @param bool $default
     * @return mixed
     */
    public function useDefaultConstructValue(bool $default = true);

    /**
     * @return bool
     */
    public function isUsingDefaultConstructValue() : bool;

    /**
     * @param bool $resolve
     * @return mixed
     */
    public function autoResolve(bool $resolve = true);

    /**
     * @return bool
     */
    public function isAutoResolve() : bool;

    /**
     * @param string $parameterName
     * @param $value
     * @return mixed
     */
    public function addConstructParameter(string $parameterName, $value);

    /**
     * @param string $parameterName
     * @return mixed
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
     * @return mixed
     */
    public function addCallMethod(string $methodName);

    /**
     * @param string $methodName
     * @return mixed
     */
    public function removeCallMethod(string $methodName);

    /**
     * @param string $methodName
     * @return bool
     */
    public function hasCallMethod(string $methodName) : bool;

}