<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 10:31
 */

namespace Rabbit\DependencyInjector\Entities\Resolver;

use \ReflectionClass;
use Rabbit\DependencyInjector\Entities\EntityInterface;

class ClassResolver
{

    /**
     * @var ReflectionClass
     */
    private $_reflectionClass;
    /**
     * @var EntityInterface
     */
    private $_entity;

    /**
     * ClassResolver constructor.
     * @param ReflectionClass $class
     * @param EntityInterface $entity
     */
    public function __construct(ReflectionClass $class, EntityInterface $entity) {
        $this->_reflectionClass = $class;
        $this->_entity = $entity;
    }

    /**
     * @param array $optParameters
     * @return array
     */
    public function resolveConstructor(array $optParameters = []) {
        if($this->_entity->getInformation()->hasConstructor()) {
            $crParameters = $this->_entity->getInformation()->constructor->parameters;
            $constructorParameters = [];
            foreach ($crParameters as $key => $parameter) {
                if(isset($this->_entity->getRule()['inherit']) && $this->_entity->getRule()['inherit'] === true) {
                    $this->_entity->container->getClass($parameter->getClass()->getName())->setRule($this->_entity->getRule());
                }
                $constructorParameters[] = ((isset($optParameters[$parameter->getName()]))
                    ? $optParameters[$parameter->getName()]
                    : (isset($this->_entity->getRule()['constructParameters']) && isset($this->_entity->getClassRule()['constructParameters'][$parameter->getName()])
                        ? $this->_entity->getRule()['constructParameters'][$parameter->getName()]
                        : ($parameter->getClass()
                            ? $this->_entity->container->getClass($parameter->getClass()->getName())->getInstance()
                            : ($parameter->isDefaultValueAvailable()
                                ? $parameter->getDefaultValue()
                                : null))));
            }
            return $constructorParameters;
        } else {
            return [];
        }
    }

    /**
     * @param array $parameters
     * @return object
     */
    public function getInstance(array $parameters = []) {
        if($this->_entity->getInformation()->isInstantiable()) {
            $class = $this->_reflectionClass->newInstanceArgs($this->resolveConstructor($parameters));
            if(isset($this->_entity->getRule()['call'])) {
                foreach ($this->_entity->getRule()['call'] as $method => $parameter) {
                    if($this->_entity->getInformation()->hasMethod(!is_int($method) ? $method : $parameter)) {
                        $this->_entity->getMethod((!is_int($method) ? $method : $parameter))->appendClass($class)->execute(is_array($parameter) ? $parameter : []);
                    }
                }
            }
            return $class;
        }
    }

}