<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/11/2018
 * Time: 17:55
 */

namespace Xirion\DependencyInjector\Resolver;

use Xirion\DependencyInjector\DepedencyContainerInterface;

class MethodResolver extends Resolver
{

    private $_methodName;

    private $_methodResult;

    public function __construct(string $class, string $methodName, DepedencyContainerInterface $container)
    {
        parent::__construct($class, $container);
        $this->_methodName = $methodName;
    }

    public function resolveParameters(array $optParameters = []) {
        $meParameters = $this->getMethodParameters($this->_methodName);
        if($meParameters) {
            $methodParameters = [];
            foreach ($meParameters as $parameter) {
                if($parameter->getClass() && $this->_container->getClassRule($this->_className)['inherit'] === true) {
                    $this->_container->attachRule($parameter->getClass()->getName(), $this->_container->getClassRule($this->_className));
                }
                $methodParameters[] = ((!empty($optParameters[$parameter->getName()]))
                     ? $optParameters[$parameter->getName()]
                     : ((!empty($this->_container->getMethodRule($this->_className, $this->_methodName)['parameters']) && !empty($this->_container->getMethodRule($this->_className, $this->_methodName)['parameters'][$parameter->getName()]))
                         ? $this->_container->getMethodRule($this->_className, $this->_methodName)['parameters'][$parameter->getName()]
                         : ($parameter->getClass()
                             ? $this->_container->getClass($parameter->getClass()->getName())
                             : $this->getMethodParameterBaseValues($this->_methodName)[$parameter->getName])));
            }
            return $methodParameters;
        } else {
            return [];
        }
    }

    public function invoke($class, array $optParameters = []) {
        $this->_methodResult = $this->getMethod($this->_methodName)->invokeArgs($class, $this->resolveParameters($optParameters));
        return $this->_methodResult;
    }

    public function getResult() {
        return $this->_methodResult;
    }

}