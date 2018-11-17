<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/11/2018
 * Time: 17:56
 */

namespace Xirion\DependencyInjector\Resolver;

use Psr\Container\ContainerInterface;
use Xirion\DependencyInjector\DepedencyContainerInterface;

class Resolver
{

    /**
     * @var ReflectionClass
     */
    protected $_reflectionClass;

    /**
     * @var DepedencyContainerInterface
     */
    protected $_container;

    /**
     * @var string
     */
    protected $_className;

    /**
     * Resolver constructor.
     * @param string $class
     * @param DepedencyContainerInterface $container
     * @throws \ReflectionException
     */
    public function __construct(string $class, DepedencyContainerInterface $container)
    {
        $this->_reflectionClass = self::getReflectionClass($class);
        $this->_className = $class;
        $this->_container = $container;
    }


    /**
     * @param string $class
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public static function getReflectionClass(string $class) {
        return new \ReflectionClass($class);
    }

    /**
     * @return mixed
     */
    public function getConstructor() {
        if($this->hasConstructor()) {
            return $this->_reflectionClass->getConstructor();
        }
    }

    /**
     * @return int
     */
    public function getConstructorParameterCount() {
        if($this->hasConstructor()) {
            return count($this->getConstructor()->getParameters());
        }
    }

    /**
     * @return mixed
     */
    public function getConstructorParameters() {
        if($this->hasConstructor()) {
            return $this->getConstructor()->getParameters();
        }
    }

    /**
     * @return array
     */
    public function getConstructorParameterBaseValues() {
        if($this->hasConstructor()) {
            $parameters = [];
            foreach ($this->getConstructorParameters() as $parameter) {
                $parameters[$parameter->getName()] = ($parameter->isDefaultValueAvailable()) ? $parameter->getDefaultValue() : null;
            }
            return $parameters;
        }
    }

    /**
     * @return bool
     */
    public function hasConstructor() {
        return !empty($this->_reflectionClass->getConstructor());
    }

    /**
     * @param string $methodName
     * @return mixed
     */
    public function getMethod(string $methodName) {
        return $this->_reflectionClass->getMethod($methodName);
    }

    /**
     * @param string $method
     * @return int
     */
    public function getMethodParameterCount(string $method) {
        if ($this->hasMethod($method)) {
            return count($this->getMethod($method)->getParameters());
        }
    }

    /**
     * @param string $method
     * @return mixed
     */
    public function getMethodParameters(string $method) {
        if($this->hasMethod($method)) {
            return $this->getMethod($method)->getParameters();
        }
    }

    /**
     * @param string $method
     * @return array
     */
    public function getMethodParameterBaseValues(string $method) {
        if($this->hasMethod($method)) {
            $parameters = [];
            foreach ($this->getMethodParameters($method) as $parameter) {
                $parameters[] = $parameter->getDefaultValue();
            }
            return $parameters;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function hasMethod(string $name) {
        return $this->_reflectionClass->getMethod($name);
    }

}