<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 10:32
 */

namespace Rabbit\DependencyInjector\Entities;

use Psr\Container\ContainerInterface;
use Rabbit\DependencyInjector\DependencyContainerException;
use Rabbit\DependencyInjector\DependencyContainerInterface;
use Rabbit\DependencyInjector\Entities\Information\EntityInformationInterface;
use Rabbit\DependencyInjector\Entities\Information\MethodInformation;
use Rabbit\DependencyInjector\Entities\Resolver\MethodResolver;

class MethodEntity implements EntityInterface
{
    public $information;

    public $container;

    public $methodRule = [];

    public $resolver;

    private $_reflectionMethod;

    private $_parentEntity;

    private $_parentClass;

    public function __construct(\ReflectionMethod $method, ContainerInterface $container, ClassEntity $classEntity)
    {
        $this->_parentEntity = $classEntity;

        $this->_parentClass = $classEntity->_lastClass;

        $this->_reflectionMethod = $method;

        $this->container = $container;

        $this->information = new MethodInformation($this->_reflectionMethod);

        $this->resolver = new MethodResolver($this->_reflectionMethod, $this);
    }

    public function appendClass($class) {
        $this->_parentClass = $class;
        return $this;
    }

    public function execute(array $parameters = [], object $class = null) {
        if(!isset($this->_parentClass) && !isset($class)) {
            throw new DependencyContainerException('[Rabbit => DependencyContainer->MethodEntity::execute()] the parent class doesn\'t exists, please invoke first before execute a method');
        }
        return $this->resolver->invoke($this->_parentClass ?? $class, $parameters);
    }

    public function setRule(array $rule) {
        $this->methodRule = array_replace($this->methodRule, $rule);
        return $this;
    }

    public function getRule() {
        return $this->methodRule;
    }

    public function getInformation() : EntityInformationInterface {
        return $this->information;
    }
}