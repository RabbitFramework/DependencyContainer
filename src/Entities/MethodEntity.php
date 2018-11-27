<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 10:32
 */

namespace Xirion\DependencyInjector\Entities;

use \Xirion\DependencyInjector\DependencyContainerInterface;
use Xirion\DependencyInjector\Entities\Information\EntityInformationInterface;
use Xirion\DependencyInjector\Entities\Information\MethodInformation;
use Xirion\DependencyInjector\Entities\Resolver\MethodResolver;

class MethodEntity implements EntityInterface
{
    public $information;

    public $container;

    public $methodRule = [];

    public $resolver;

    private $_reflectionMethod;

    private $_parentEntity;

    private $_parentClass;

    public function __construct(\ReflectionMethod $method, DependencyContainerInterface $container, ClassEntity $classEntity)
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
            throw new EntityException('MethodEntity => execute; the parent class doesn\'t exists, please invoke first before execute a method');
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