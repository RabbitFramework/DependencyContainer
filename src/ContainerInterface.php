<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/10/2018
 * Time: 12:16
 */

namespace Xirion\DependencyInjector;


use Xirion\DependencyInjector\Rule\RuleInterface;

/**
 * Interface ContainerInterface
 * @package Xirion\DependencyInjector
 */
interface ContainerInterface
{

    /**
     * @param string $className
     * @param RuleInterface $rule
     * @return mixed
     */
    public function attachRule(string $className, RuleInterface $rule);

    /**
     * @param string $className
     * @return mixed
     */
    public function detachRule(string $className);

    /**
     * @param string $class
     * @return mixed
     */
    public function register(string $class);

    /**
     * @param string $className
     * @return bool
     */
    public function hasRule(string $className) : bool;

    /**
     * @param string $className
     * @return bool
     */
    public function hasClass(string $className) : bool;

    /**
     * @param string $name
     * @return mixed
     */
    public function getRule(string $name);

    /**
     * @param string $className
     * @return mixed
     */
    public function getClass(string $className);

}