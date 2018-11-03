<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/10/2018
 * Time: 12:11
 */

namespace Xirion\DependencyInjector\Resolver;


use Xirion\DependencyInjector\ContainerInterface;

/**
 * Class Resolver
 * @package Xirion\DependencyInjector\Resolver
 */
class Resolver implements ResolverInterface
{

    /**
     * @var \ReflectionClass
     */
    private $_reflectedClass;
    /**
     * @var ContainerInterface
     */
    private $_container;

    /**
     * Resolver constructor.
     * @param string $class
     * @param ContainerInterface $container
     * @throws \ReflectionException
     */
    public function __construct(string $class, ContainerInterface $container)
    {
        $this->_reflectedClass = new \ReflectionClass($class);
        $this->_container = $container;
    }

    /**
     *
     */
    public function getNewInstance() {

    }

    /**
     * Methods to resolve
     */
    public function resolveConstructor() {
        if($this->_reflectedClass->isInstantiable()) {
            $constructor = $this->_reflectedClass->getConstructor();
            if($constructor) {
                $parameters = $constructor->getParameters();
                $constructorParameters = [];
                foreach ($parameters as $parameter) {
                    if($parameter->getClass()) { // check if the current eached parameter is a class
                        if($this->_container->hasRule($this->_reflectedClass->getName())) { // Check if the class has the rule
                            $rule = $this->_container->getRule($this->_reflectedClass->getName()); // If has, get the rule
                            if($rule->isAutoResolve()) { // Check if auto resolve rule is true
                                $constructorParameters[] = ($this->_container->hasClass($parameter->getClass()->getName())) ? $this->_container->getClass($parameter->getClass()->getName()) : $this->_container->getClass($parameter->getClass()->getName()); // if true, automatically resolve the parameters for classes
                            } else {
                                echo $parameter->getName();
                                if($rule->hasConstructParameter($parameter->getName())) { // else check if the constructParameter rule has the parameter included
                                    $constructorParameters[] =$rule->getConstructParameter($parameter->name); // if true then put the parameter instead of automatic resolve
                                }
                            }
                        } else {
                            $constructorParameters[] = $this->_container->register($parameter->getClass()->getName()); // else if the class doesn't have rule, automatically resolve the parameter
                        }
                    } else { // else the parameter is a string or a number or a array or etc...
                        if($this->_container->hasRule($this->_reflectedClass->getName())) {
                            $rule = $this->_container->getRule($this->_reflectedClass->getName());
                            if($rule->hasConstructParameter($parameter->getName())) {
                                $constructorParameters[] = $rule->getConstructParameter($parameter->getName());
                            }
                        }
                    }
                }
                return $this->_reflectedClass->newInstanceArgs($constructorParameters);
            } else {
                return $this->_reflectedClass->newInstance();
            }
        }
    }

    /**
     * @param string $methodName
     * @return mixed|void
     */
    public function resolveMethod(string $methodName) {

    }

    /**
     * Methods to check if has
     */
    public function hasMethod(string $methodName) : bool {
        return $this->_reflectedClass->hasMethod($methodName);
    }

}