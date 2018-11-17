<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/11/2018
 * Time: 17:55
 */

namespace Xirion\DependencyInjector\Resolver;

use Xirion\DependencyInjector\DepedencyContainerInterface;
use Xirion\DependencyInjector\Rule\RuleInterface;

class ConstructorResolver extends Resolver
{

    private $_calledMethodsResult = [];

    public function __construct(string $class, DepedencyContainerInterface $container)
    {
        parent::__construct($class, $container);
    }

    public function resolveConstructor(array $optParameters = []) {
        $crParameters = $this->getConstructorParameters();
        if($crParameters) {
            $constructorParameters = [];
            foreach ($crParameters as $parameter) {
                if($parameter->getClass() && isset($this->_container->getClassRule($this->_className)['inherit']) && $this->_container->getClassRule($this->_className)['inherit'] == true) {
                    $this->_container->attachRule($parameter->getClass()->getName(), $this->_container->getClassRule($this->_className));
                }
                $constructorParameters[] = ((!empty($optParameters[$parameter->getName()]))
                    ? $optParameters[$parameter->getName()]
                    : ((!empty($this->_container->getClassRule($this->_className)['constructParameters']) && !empty($this->_container->getClassRule($this->_className)['constructParameters'][$parameter->getName()]))
                        ? $this->_container->getClassRule($this->_className)['constructParameters'][$parameter->getName()]
                        : ($parameter->getClass()
                            ? $this->_container->getClass($parameter->getClass()->getName())
                            : $this->getConstructorParameterBaseValues()[$parameter->getName()])));
            }
            return $constructorParameters;
        } else {
            return [];
        }
    }

    public function getInstance(array $optParameters = []) {
        if($this->_reflectionClass->isInstantiable()) {
            $class = $this->_reflectionClass->newInstanceArgs($this->resolveConstructor($optParameters));
            if(isset($this->_container->getClassRule($this->_className)['injectVariables'])) {
                foreach ($this->_container->getClassRule($this->_className)['injectVariables'] as $name => $variable) {
                    $class->$name = $variable;
                }
            }
            if(isset($this->_container->getClassRule($this->_className)['call'])) {
                foreach ($this->_container->getClassRule($this->_className)['call'] as $method => $parameters) {
                    if($this->hasMethod((!is_int($method)) ? $method : $parameters)) {
                        $resolver = new MethodResolver($this->_className, (!is_int($method)) ? $method : $parameters, $this->_container);
                        $this->_calledMethodsResult[(!is_int($method)) ? $method : $parameters] = $resolver->invoke($class, (!is_string($parameters)) ? $parameters : []);
                    }
                }
            }
            return $class;
        }
    }

}