<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 10:31
 */

namespace Rabbit\DependencyContainer\Entities\Resolver;

use Rabbit\DependencyContainer\Entities\EntityInterface;

class MethodResolver implements EntityResolverInterface
{

    /**
     * @var \ReflectionMethod
     */
    private $_reflectionMethod;
    /**
     * @var EntityInterface
     */
    private $_entity;

    /**
     * @var object
     */
    private $_class;

    /**
     * MethodResolver constructor.
     * @param \ReflectionMethod $method
     * @param EntityInterface $entity
     */
    public function __construct(\ReflectionMethod $method, EntityInterface $entity)
    {
        $this->_reflectionMethod = $method;
        $this->_entity = $entity;
    }

    /**
     * @param array $optParameters
     * @return array
     */
    public function resolve(array $optParameters = []) : array {
        if($this->_entity->getInformation()->parameters) {
            $meParameters = $this->_entity->getInformation()->parameters;
            $methodParameters = [];
            foreach ($meParameters as $parameter) {
                if($parameter->getClass() && isset($this->_entity->container->getClass($this->_entity->getInformation()->parentClass)->getRule()['inherit']) && $this->_entity->container->get($this->_entity->getInformation()->parentClass)->getRule()['inherit'] === true) {
                    $this->_entity->container->get($parameter->getClass()->getName)->setRule($this->_entity->container->getClass($this->_entity->getInformation()->parentClass)->getRule);
                }
                $methodParameters[] = ((isset($optParameters[$parameter->getName()]))
                    ? $optParameters[$parameter->getName()]
                    : (isset($this->_entity->getRule()['parameters']) && isset($this->_entity->getRule()['parameters'][$parameter->getName()])
                        ? $this->_entity->getRule()['parameters'][$parameter->getName()]
                        : ($parameter->getClass()
                            ? $this->_entity->container->get($parameter->getClass()->getName())
                            : ($parameter->isDefaultValueAvailable()
                                ? $parameter->getDefaultValue()
                                : null))));
            }
            return $methodParameters;
        } else {
            return [];
        }
    }

    public function setClass($class) {
        $this->_class = $class;
        return $this;
    }

    /**
     * @param $class
     * @param array $optParameters
     * @return mixed
     */
    public function get(array $optParameters = []) {
        return $this->_reflectionMethod->invokeArgs($this->_class, $this->resolve($optParameters));
    }

}