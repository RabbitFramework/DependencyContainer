<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 10:32
 */

namespace Rabbit\DependencyInjector\Entities;

use Psr\Container\ContainerInterface;
use Rabbit\DependencyInjector\ContainerException;
use Rabbit\DependencyInjector\DependencyInjectorInterface;
use Rabbit\DependencyInjector\Entities\Information\EntityInformationInterface;
use Rabbit\DependencyInjector\Entities\Information\MethodInformation;
use Rabbit\DependencyInjector\Entities\Resolver\MethodResolver;

/**
 * Class MethodEntity
 * @package Rabbit\DependencyInjector\Entities
 */
class MethodEntity implements EntityInterface
{
    /**
     * @var MethodInformation
     */
    public $information;

    /**
     * @var ContainerInterface
     */
    public $container;

    /**
     * @var array
     */
    public $methodRule = [];

    /**
     * @var MethodResolver
     */
    public $resolver;

    /**
     * @var \ReflectionMethod
     */
    private $_reflectionMethod;

    /**
     * @var ClassEntity
     */
    private $_parentEntity;

    /**
     * @var
     */
    private $_parentClass;

    /**
     * MethodEntity constructor.
     * @param \ReflectionMethod $method
     * @param ContainerInterface $container
     * @param ClassEntity $classEntity
     */
    public function __construct(\ReflectionMethod $method, ContainerInterface $container, ClassEntity $classEntity)
    {
        $this->_parentEntity = $classEntity;

        $this->_parentClass = $classEntity->_lastClass;

        $this->_reflectionMethod = $method;

        $this->container = $container;

        $this->information = new MethodInformation($this->_reflectionMethod);

        $this->resolver = new MethodResolver($this->_reflectionMethod, $this);
    }

    /**
     * @param $class
     * @return $this
     */
    public function appendClass($class) {
        $this->_parentClass = $class;
        return $this;
    }

    /**
     * @param array $parameters
     * @param object|null $class
     * @return mixed
     * @throws ContainerException
     */
    public function execute(array $parameters = [], object $class = null) {
        if(!isset($this->_parentClass) && !isset($class)) {
            throw new ContainerException('[Rabbit => DependencyInjector->MethodEntity::execute()] the parent class doesn\'t exists, please invoke first before execute a method');
        }
        return $this->resolver->setClass($this->_parentClass ?? $class)->get($parameters);
    }

    /**
     * @param array $rule
     * @return $this
     */
    public function setRule(array $rule) {
        $this->methodRule = array_replace($this->methodRule, $rule);
        return $this;
    }

    /**
     * @return array
     */
    public function getRule() {
        return $this->methodRule;
    }

    /**
     * @return EntityInformationInterface
     */
    public function getInformation() : EntityInformationInterface {
        return $this->information;
    }
}